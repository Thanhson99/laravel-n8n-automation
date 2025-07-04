import './bootstrap';
import '../scss/app.scss';

// Import coins.index
if (window.location.pathname.includes('/coins')) {
    import('./pages/coins/index.js');
}
