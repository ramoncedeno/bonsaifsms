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
- **Purpose**: Provides an endpoint to check the operational status and health of the application.
- **Key Methods**:
    - `__invoke()`: Returns a simple status response, indicating the application is running.

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