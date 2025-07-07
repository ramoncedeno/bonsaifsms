### `SmsTransactionController`
- **Purpose**: Handles the sending of individual SMS messages via an external API and logs the transactions.
- **Key Methods**:
    - `sendSMS($phone, $message)`: Validates input, normalizes the message, sends the SMS via the BonsaiF API, logs the transaction, and returns the API response.
    - `normalizeMessage($message)`: Converts the message to ASCII.
    - `showForm()`: Displays the `send_sms` view for manual SMS sending.

### `SmsImportController`
- **Purpose**: Manages the import of SMS data from Excel files, processing them for bulk sending.
- **Key Methods**:
    - (Intended for import logic, likely using `Maatwebsite\Excel` and `App\Imports\SmsImport`).