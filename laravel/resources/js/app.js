import './bootstrap';
import '../scss/app.scss';

/**
 * Dynamic JavaScript Loader
 *
 * Load JavaScript modules dynamically based on the current page.
 * The page is determined via <body data-page="..."> attribute.
 * This helps reduce global JS scope and improves performance.
 */

document.addEventListener('DOMContentLoaded', async () => {
    const page = document.body.dataset.page;

    if (!page) return;

    try {
        switch (page) {
            // coins-favorites → ./pages/coins/favorites/coin-favorites-table.js
            // Handles favorites table display and logic
            case 'coins-favorites':
                await import('./pages/coins/favorites/coin-favorites-table.js');
                break;

            // Coin-alert-settings → ./pages/coins/alert-settings/coin-alert-settings-table.js
            // Manages coin alert settings table: edit, toggle on/off
            case 'coin-alert-settings':
                await import('./pages/coins/alert-settings/coin-alert-settings-table.js');
                break;

            // keywords → ./pages/coins/keywords/keywords-create.js
            // Handles keyword creation form with dynamic tag input
            case 'keywords':
                await import('./pages/coins/keywords/keywords-create.js');
                break;
            
            // error-404 → ./errors/error-404.js
            // Handles custom 404 page
            case 'error-404':
                await import('./errors/error-404.js');
                break;

            // Add more pages below using the same pattern
            // example-page → ./pages/example/example-page.js → Description
            // case 'example-page':
            //     await import('./pages/example/example-page.js');
            //     break;
        }
    } catch (err) {
        console.error(`Failed to load JS module for page "${page}":`, err);
    }
});
