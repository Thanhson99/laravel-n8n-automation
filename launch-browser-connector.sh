#!/usr/bin/env bash
# macOS only — opens a separate automation instance with CDP and prints WS endpoints
# Env (optional):
#   BROWSER=chromium|chrome|brave   # default: auto-pick (prefers chromium)
#   PROFILE="Default"
#   PORTS="9222 9333 9444 9555"
#   AUTO_INSTALL=1                  # install chromium via brew if missing
#   USE_SYSTEM_PROFILE=0|1
#   AUTO_CLOSE=0                    # 1 = try to close running browsers automatically
#   AUTO_CLOSE_FORCE=0              # 1 = force kill running browsers if graceful quit fails
set -euo pipefail

# ========= config dirs =========
CONFIG_DIR="${HOME}/.puppetbind"
CONFIG_FILE="${CONFIG_DIR}/config.json"
WSE_FILE="${CONFIG_DIR}/ws_endpoint"
PROFILES_DIR="${CONFIG_DIR}/profiles"
mkdir -p "${CONFIG_DIR}" "${PROFILES_DIR}"

# ========= env defaults =========
BROWSER="${BROWSER:-}"
PROFILE="${PROFILE:-Default}"
PORTS="${PORTS:-9222 9333 9444 9555 9666}"
AUTO_INSTALL="${AUTO_INSTALL:-1}"
USE_SYSTEM_PROFILE="${USE_SYSTEM_PROFILE:-0}"
REMOTE_HOST="127.0.0.1"

# New envs for auto-close behavior
AUTO_CLOSE="${AUTO_CLOSE:-0}"
AUTO_CLOSE_FORCE="${AUTO_CLOSE_FORCE:-0}"

# ========= helpers =========
cmd_exists(){ command -v "$1" >/dev/null 2>&1; }

json_get() { # json_get KEY < FILE
  python3 - <<'PY' "$1" < "$2"
import json,sys
k=sys.argv[1]
try:
  print(json.load(sys.stdin).get(k,""))
except Exception:
  pass
PY
}

json_set() { # json_set KEY VALUE FILE
  python3 - "$1" "$2" "$3" <<'PY'
import json,sys,os
k,v,f=sys.argv[1:4]
d={}
if os.path.exists(f):
  try:
    with open(f) as fh: d=json.load(fh)
  except: d={}
d[k]=v
with open(f,"w") as fh: json.dump(d,fh,indent=2)
PY
}

os_detect() {
  case "$(uname -s)" in
    Darwin) echo "mac" ;;
    *)      echo "other" ;;
  esac
}

free_port(){
  local p="$1"
  if lsof -i :"$p" -sTCP:LISTEN >/dev/null 2>&1; then return 1; else return 0; fi
}

wait_ws(){ # robust wait until /json/version contains webSocketDebuggerUrl
  local port="$1"
  echo -n "Waiting for DevTools endpoint on :$port"
  for _ in $(seq 1 80); do
    body="$(curl -fsS "http://${REMOTE_HOST}:${port}/json/version" 2>/dev/null || true)"
    if [[ -n "$body" ]] && echo "$body" | grep -q '"webSocketDebuggerUrl"'; then
      echo
      return 0
    fi
    echo -n "."
    sleep 0.25
  done
  echo
  return 1
}

parse_ws(){ # robust parse of webSocketDebuggerUrl from /json/version or /json/list
  local port="$1"
  local body=""
  local ws=""

  # try /json/version first with retries
  for i in $(seq 1 20); do
    body="$(curl -sS "http://${REMOTE_HOST}:${port}/json/version" 2>/dev/null || true)"
    if [[ -n "$body" ]]; then
      ws="$(echo "$body" | python3 - <<'PY' 2>/dev/null || true
import sys, json
try:
  j=json.load(sys.stdin)
  print(j.get("webSocketDebuggerUrl","") or "")
except Exception:
  print("")
PY
)"
      if [[ -n "$ws" ]]; then
        echo "$ws"
        return 0
      fi
    fi
    sleep 0.15
  done

  # fallback: try /json/list (may contain pages or the browser entry)
  for i in $(seq 1 20); do
    body="$(curl -sS "http://${REMOTE_HOST}:${port}/json/list" 2>/dev/null || true)"
    if [[ -n "$body" ]]; then
      ws="$(echo "$body" | python3 - <<'PY' 2>/dev/null || true
import sys,json
try:
  arr=json.load(sys.stdin)
  if isinstance(arr, list) and len(arr)>0:
    v=arr[0].get("webSocketDebuggerUrl","")
    if v:
      print(v)
    else:
      print("")
  else:
    print("")
except Exception:
  print("")
PY
)"
      if [[ -n "$ws" ]]; then
        echo "$ws"
        return 0
      fi
    fi
    sleep 0.15
  done

  # last-ditch: get webSocketDebuggerUrl directly via /json/version without JSON parse (grep)
  body="$(curl -sS "http://${REMOTE_HOST}:${port}/json/version" 2>/dev/null || true)"
  if [[ -n "$body" ]]; then
    ws="$(echo "$body" | grep -o '"webSocketDebuggerUrl"[[:space:]]*:[[:space:]]*"[^"]*"' | sed -E 's/.*"([^"]+)".*/\1/' || true)"
    if [[ -n "$ws" ]]; then
      echo "$ws"
      return 0
    fi
  fi

  # if we reach here, return empty
  echo ""
  return 1
}

# ========= platform guard =========
if [[ "$(os_detect)" != "mac" ]]; then
  echo "This script supports macOS only."
  exit 1
fi

# ========= resolve browser exe & system user-data-dir =========
resolve_mac(){
  local want="$1"  # chromium|chrome|brave|""
  local exe="" userdir="" picked=""

  if [[ "$want" == "chromium" || "$want" == "" ]]; then
    if [[ -e "/Applications/Chromium.app/Contents/MacOS/Chromium" ]]; then
      exe="/Applications/Chromium.app/Contents/MacOS/Chromium"
      userdir="${HOME}/Library/Application Support/Chromium"
      picked="chromium"
    fi
  fi

  if [[ -z "$picked" && ( "$want" == "chrome" || "$want" == "" ) ]]; then
    if [[ -e "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" ]]; then
      exe="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
      userdir="${HOME}/Library/Application Support/Google/Chrome"
      picked="chrome"
    fi
  fi

  if [[ -z "$picked" && ( "$want" == "brave" || "$want" == "" ) ]]; then
    if [[ -e "/Applications/Brave Browser.app/Contents/MacOS/Brave Browser" ]]; then
      exe="/Applications/Brave Browser.app/Contents/MacOS/Brave Browser"
      userdir="${HOME}/Library/Application Support/BraveSoftware/Brave-Browser"
      picked="brave"
    fi
  fi

  echo "$picked|$exe|$userdir"
}

ensure_chromium_mac(){
  # Returns 0 if chromium executable exists, otherwise 1
  if [[ -e "/Applications/Chromium.app/Contents/MacOS/Chromium" ]]; then
    return 0
  fi
  if [[ "$AUTO_INSTALL" != "1" ]]; then
    return 1
  fi
  if ! cmd_exists brew; then
    echo "Homebrew not found. Install it first:"
    echo '/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"'
    return 1
  fi

  echo "Installing Chromium via Homebrew..."
  if brew install --cask chromium; then
    [[ -e "/Applications/Chromium.app/Contents/MacOS/Chromium" ]]
    return $?
  fi

  echo "Normal install failed; retrying with --no-quarantine (workaround)..."
  if brew install --cask --no-quarantine chromium 2>/dev/null || brew reinstall --cask --no-quarantine chromium 2>/dev/null; then
    [[ -e "/Applications/Chromium.app/Contents/MacOS/Chromium" ]]
    return $?
  fi

  return 1
}

# Check for running browsers and optionally close them
ensure_no_running_browsers(){
  # This function checks for running Chrome/Chromium/Brave processes.
  # If found and AUTO_CLOSE==0, it prints a message and exits with code 2.
  # If AUTO_CLOSE==1, it will try graceful quit and wait a few seconds.
  local procs
  procs="$(pgrep -f 'Google Chrome|Chromium|Brave Browser' || true)"
  if [[ -z "$procs" ]]; then
    return 0
  fi

  echo "Detected running browser processes (Chrome/Chromium/Brave)."
  if [[ "${AUTO_CLOSE}" != "1" ]]; then
    echo "Please fully quit your browser(s) before running this script, or re-run with AUTO_CLOSE=1 to let the script close them."
    return 2
  fi

  echo "AUTO_CLOSE=1, attempting graceful quit of browsers..."
  # Try AppleScript quit (graceful)
  osascript -e 'tell application "Google Chrome" to quit' >/dev/null 2>&1 || true
  osascript -e 'tell application "Brave Browser" to quit' >/dev/null 2>&1 || true
  sleep 1

  # wait for processes to disappear (up to 8 seconds)
  for i in $(seq 1 16); do
    if ! pgrep -f 'Google Chrome|Chromium|Brave Browser' >/dev/null 2>&1; then
      echo "Browsers closed."
      return 0
    fi
    sleep 0.5
  done

  if [[ "${AUTO_CLOSE_FORCE}" == "1" ]]; then
    echo "Force-killing remaining browser processes (AUTO_CLOSE_FORCE=1)."
    pkill -f 'Google Chrome' || true
    pkill -f 'Brave Browser' || true
    sleep 0.5
    if ! pgrep -f 'Google Chrome|Chromium|Brave Browser' >/dev/null 2>&1; then
      echo "Force-kill succeeded."
      return 0
    fi
  fi

  echo "Unable to close browser processes. Please close them manually and re-run."
  return 3
}

# Try to launch the browser robustly on macOS:
# 1) Try running binary directly (fast).
# 2) If that fails (quarantine/Gatekeeper), try `open -n -a "Name" --args ...`.
launch_browser(){
  local exe="$1"
  local short_name="$2"   # e.g. "chromium"|"chrome"|"brave"
  local port="$3"
  local user_dir="$4"
  local profile="$5"

  # common args to start a clean, automation-friendly instance
  ARGS=( "--remote-debugging-address=${REMOTE_HOST}"
         "--remote-debugging-port=${port}"
         "--user-data-dir=${user_dir}"
         "--profile-directory=${profile}"
         "--no-first-run" "--no-default-browser-check"
         "--disable-extensions" "--disable-background-networking"
         "--disable-component-update" "--disable-default-apps"
         "--disable-popup-blocking" "--disable-sync" )

  echo "Attempting to run executable directly: ${exe}"
  set +e
  "${exe}" "${ARGS[@]}" >/dev/null 2>&1 &
  pid=$!
  sleep 0.8

  # If port is listening already, success
  if lsof -i :"${port}" -sTCP:LISTEN >/dev/null 2>&1; then
    set -e
    echo "Launched via executable (pid ${pid}) and port ${port} is listening."
    return 0
  fi

  # If executable started but port not listening, likely forwarded args to existing Chrome instance.
  if ps -p "${pid}" >/dev/null 2>&1; then
    echo "Process ${pid} started but port ${port} not listening — likely forwarded to existing Chrome instance."
  else
    echo "Executable exited quickly; trying macOS open fallback."
  fi

  # map short id to macOS app bundle name
  case "${short_name}" in
    chromium) appname="Chromium" ;;
    chrome)   appname="Google Chrome" ;;
    brave)    appname="Brave Browser" ;;
    *)        appname="${short_name}" ;;
  esac

  echo "Trying fallback: open -n -a \"${appname}\" --args ..."
  open -n -a "${appname}" --args "${ARGS[@]}" >/dev/null 2>&1 &
  sleep 0.8

  # wait for port up (robust loop)
  for _ in $(seq 1 40); do
    if lsof -i :"${port}" -sTCP:LISTEN >/dev/null 2>&1; then
      set -e
      echo "Launched via open -n -a ${appname}; port ${port} is listening."
      return 0
    fi
    sleep 0.25
  done

  set -e
  echo "Both direct and open fallback failed to bind port ${port}."
  return 1
}

# ========== choose browser ==========
pick="${BROWSER:-}"
if [[ "$pick" == "chromium" ]]; then
  ensure_chromium_mac || true
fi

resolved="$(resolve_mac "$pick")"
picked_browser="${resolved%%|*}"
rest="${resolved#*|}"
EXE="${rest%%|*}"
SYSTEM_USER_DIR="${rest#*|}"

# If still nothing and user wanted/accepts chromium, try install & resolve again
if [[ -z "$picked_browser" && ( -z "$pick" || "$pick" == "chromium" ) ]]; then
  if ensure_chromium_mac; then
    resolved="$(resolve_mac "chromium")"
    picked_browser="${resolved%%|*}"
    rest="${resolved#*|}"
    EXE="${rest%%|*}"
    SYSTEM_USER_DIR="${rest#*|}"
  fi
fi

if [[ -z "$picked_browser" ]]; then
  echo "No supported browser found (Chromium/Chrome/Brave)."
  echo "Tip: AUTO_INSTALL=1 BROWSER=chromium $0"
  exit 1
fi

echo "Using browser: $picked_browser"
echo "Executable: $EXE"

if [[ "${USE_SYSTEM_PROFILE}" == "1" ]]; then
  USER_DIR="${SYSTEM_USER_DIR}"
  echo "User data dir (system): ${USER_DIR}"
else
  USER_DIR="${PROFILES_DIR}/${picked_browser}"
  echo "User data dir (automation): ${USER_DIR}"
fi
mkdir -p "${USER_DIR}/${PROFILE}"

# If browsers are running, decide how to proceed
ensure_no_running_browsers || exit $?

# Pick a free port
PORT_CHOSEN=""
for p in $PORTS; do
  if free_port "$p"; then PORT_CHOSEN="$p"; break; fi
done
if [[ -z "$PORT_CHOSEN" ]]; then
  echo "No free port found in: $PORTS"
  exit 1
fi
echo "Binding on port: $PORT_CHOSEN"

# Launch separate instance (does NOT kill your existing browser unless AUTO_CLOSE used)
echo "Launching a separate instance..."
set +e
launch_browser "${EXE}" "${picked_browser}" "${PORT_CHOSEN}" "${USER_DIR}" "${PROFILE}"
LAUNCH_RC=$?
set -e

if [[ "${LAUNCH_RC}" -ne 0 ]]; then
  echo "Failed to launch browser instance."
  exit 1
fi

# Wait for endpoint and parse it safely
if ! wait_ws "${PORT_CHOSEN}"; then
  echo "DevTools endpoint not available on :${PORT_CHOSEN}"
  echo "If you used USE_SYSTEM_PROFILE=1, ensure the system browser is fully closed so flags are honored."
  # Try removing quarantine attribute as a last-ditch (ask user first)
  case "${picked_browser}" in
    chromium) bundle_name="Chromium.app" ;;
    chrome)   bundle_name="Google Chrome.app" ;;
    brave)    bundle_name="Brave Browser.app" ;;
    *)        bundle_name="${picked_browser}.app" ;;
  esac

  if [[ -e "/Applications/${bundle_name}" ]]; then
    echo "Trying to clear macOS quarantine attribute (may require permissions)..."
    if xattr -p com.apple.quarantine "/Applications/${bundle_name}" >/dev/null 2>&1; then
      xattr -dr com.apple.quarantine "/Applications/${bundle_name}" || true
      echo "Cleared quarantine attributes. Please re-run the script."
    fi
  fi

  exit 1
fi

WS_LOCAL="$(parse_ws "${PORT_CHOSEN}")"
if [[ -z "${WS_LOCAL}" ]]; then
  echo "Could not parse webSocketDebuggerUrl."
  exit 1
fi

echo "${WS_LOCAL}" > "${WSE_FILE}"
WS_DOCKER="$(echo "${WS_LOCAL}" | sed -E 's#//(127\.0\.0\.1|localhost):#//host.docker.internal:#')"

# Persist config for future runs
json_set browser "${picked_browser}" "${CONFIG_FILE}"
json_set os "mac" "${CONFIG_FILE}"
json_set port "${PORT_CHOSEN}" "${CONFIG_FILE}"
json_set exe "${EXE}" "${CONFIG_FILE}"
json_set user_data_dir "${USER_DIR}" "${CONFIG_FILE}"
json_set profile "${PROFILE}" "${CONFIG_FILE}"
json_set ws_local "${WS_LOCAL}" "${CONFIG_FILE}"

echo "✅ Bound successfully."
echo "browserWSEndpoint (localhost): ${WS_LOCAL}"
echo "browserWSEndpoint (Docker host): ${WS_DOCKER}"
echo
cat <<'NOTE'
Use in Puppeteer (n8n container):
---------------------------------
const puppeteer = require('puppeteer-core');
const browser = await puppeteer.connect({
  browserWSEndpoint: 'PUT-DOCKER-ENDPOINT-HERE' // e.g. ws://host.docker.internal:9222/devtools/browser/<id>
});
const ctx = await browser.createIncognitoBrowserContext();
const page = await ctx.newPage();
await page.goto('https://example.com', { waitUntil: 'domcontentloaded' });
NOTE
