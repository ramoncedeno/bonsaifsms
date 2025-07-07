### `User` Model
- **Purpose**: Manages user authentication and authorization. Integrates with `Spatie\Permission` for roles and permissions.
- **Table**: `users`
- **Fillable Fields**: `name`, `email`, `password`
- **Hidden Fields**: `password`, `remember_token`
- **Casts**: `email_verified_at` (datetime), `password` (hashed)
- **Auditing**: Implements `OwenIt\Auditing\Contracts\Auditable` for tracking changes.

### `sms_transaction` Model
- **Purpose**: Stores records of individual SMS transactions sent through the application.
- **Table**: `sms_transactions`
- **Fillable Fields**: `phone`, `message`, `response`
- **Auditing**: Implements `OwenIt\Auditing\Contracts\Auditable` for tracking changes.

### `SendAttempt` Model
- **Purpose**: Records details of each SMS send attempt, particularly for bulk imports.
- **Table**: `send_attempts`
- **Fillable Fields**: `subject`, `sponsor`, `identification_id`, `phone`, `message`, `status`, `response_id`, `aditional_data`, `created_at`