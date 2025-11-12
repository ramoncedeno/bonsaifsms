### `SmsTransactionController`
- **Purpose**: Handles the sending of individual SMS messages via an external API and logs the transactions.
- **Key Methods**:
    - `sendSMS($phone, $message)`: Validates input, normalizes the message, sends the SMS via the BonsaiF API, logs the transaction, and returns the API response.
    - `normalizeMessage($message)`: Converts the message to ASCII.
    - `showForm()`: Displays the `send_sms` view for manual SMS sending.

### `SmsSenderController`
- **Purpose**: Manages the sending of SMS messages, potentially interacting with external SMS APIs.
- **Key Methods**:
    - `send(Request $request)`: Handles the logic for sending an SMS message based on request data.

### `HealthCheckController`
- **Purpose**: Provides an API endpoint (`/healthcheck`) to perform a series of comprehensive health checks on various application services (database, cache, filesystem, environment, app key, routes, controllers, Livewire components, Excel library, permissions package, mail service, Bonsaif API, Resend API). It returns a JSON response with the status of each check, indicating the overall health of the application.
- **Key Methods**:
    - `checkAccessibleRoutes()`: Performs checks on various application routes and services.

### `SmsImportController`
- **Purpose**: Handles the web interface for SMS import operations. While the core import logic resides in `App\Imports\SmsImport`, this controller manages the view and initial request handling for file uploads.
- **Key Methods**:
    - `showImportForm()`: Displays the view where users can upload Excel/CSV files for SMS import.

### `DashboardController`
- **Purpose**: Manages the display of the application's main dashboard, often showing summary information or key metrics.
- **Key Methods**:
    - `__invoke()`: Displays the dashboard view.

### `UserController`
- **Purpose**: Manages user-related operations, such as displaying user lists, creating new users, or updating existing user profiles.
- **Key Methods**:
    - `index()`: Displays a list of users.
    - `create()`: Shows the form for creating a new user.
    - `store(Request $request)`: Stores a newly created user in the database.
    - `show(User $user)`: Displays a specific user's profile.
    - `edit(User $user)`: Shows the form for editing a user's profile.
    - `update(Request $request, User $user)`: Updates a specific user's profile in the database.
    - `destroy(User $user)`: Deletes a user from the database.