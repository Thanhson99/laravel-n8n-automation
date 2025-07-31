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
            // coins-favorites → ./pages/coins/coin-favorites-table.js → Handles favorites table display and logic
            case 'coins-favorites':
                await import('./pages/coins/coin-favorites-table.js');
                break;

            // keywords → ./pages/coins/keywords-create.js → Handles keyword creation form with dynamic tag input
            case 'keywords':
                await import('./pages/coins/keywords-create.js');
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
