<?php

namespace App\Services\Python;

/**
 * Interface for Python service calls.
 */
interface PythonServiceInterface
{
    /**
     * Execute a Python script with given arguments.
     *
     * @param  string  $scriptName
     * @param  array   $args
     * @return string|null
     */
    public function runScript(string $scriptName, array $args = []): ?string;
}
