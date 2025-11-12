# Views Documentation

This document provides an overview of the application's views (Blade templates) and their associated Livewire components.

## Overview of Views

-   `dashboard.blade.php`: The main dashboard view.
    -   Integrates Livewire components for dynamic content:
        -   `<livewire:health-check-summary />`: Displays the overall health status of the application, including checks for various services.
        -   `<livewire:user-sms-consumption />`: Shows the logged-in user's SMS usage statistics (available, sent, remaining).
        -   `<livewire:sms-summary />`: Provides a summary of SMS send attempts, categorized by status (e.g., Successful, Failed).

-   `sms_attemptview.blade.php`: Displays details of SMS send attempts.
    -   Powered by the `<livewire:sms-attempt-view />` component.
    -   Features a paginated table to list SMS send attempts.
    -   Includes filtering options to view "My Records" (SMS sent by the current user) or "All Records".
    -   Provides a form for importing new SMS records from Excel/CSV files.
    -   The table is styled for modern readability, with features like text wrapping for messages.
    -   After an import is initiated, the page reloads to reflect the updated data once the queue processing is complete.

-   `import_sms.blade.php`: (Note: The import functionality has been integrated into `sms_attemptview.blade.php` for a more streamlined user experience.)
