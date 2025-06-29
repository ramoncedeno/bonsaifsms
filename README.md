Requeriments

- Laravel v11.3.3
- Composer version 2.8.2 2024-10-29 16:12:11
- PHP version 8.2.12 (Laravel herd)
- node v20.11.0
- npm 10.2.4

***
Complements
- Laravel telescope: https://laravel.com/docs/11.x/telescope
- laravel breeze/livewire: https://laravel.com/docs/11.x/starter-kits
- Laravel auditing: https://laravel-auditing.com/
- Laravel Api routes: https://laravel.com/docs/11.x/routing
- laravel excel
    - Import
    - WithHeadingRow
    - WithChunkReading
    - WithBatchInserts
    - ShouldQueue


***
Sms Transaction Controller - Data Flow
```mermaid
flowchart TD
    A["User"] -->|Sends GET request| B["Endpoint /send-sms/{phone}/{message}"]
    B --> C["Controller: BonsaifSMSController"]
    C --> D["Validate phone and message"]
    D -->|Validation successful| E["Normalize message"]
    D -->|Validation failed| F["Return validation error"]
    E --> G["Send request to BonsaiF API"]
    G --> H["BonsaiF API"]
    H --> I["BonsaiF response"]
    I --> J["Log transaction in database"]
    J --> K["Return response to user"]
    F --> K["Return validation error"]
    K --> A["User"]
```
***
Class SmsImport - Data Flow
```mermaid
flowchart TD
    subgraph "Excel Input"
        ExcelRow["Excel Row Data\n- subject\n- sponsor\n- identification_id\n- phone\n- message"]
    end

    subgraph "SendAttempt Model"
        CreateSendAttempt["SendAttempt::create()\nInitial Record\n- status: 'pending'"]
        UpdateSendAttempt["SendAttempt::update()\nUpdate Statuses:\n- 'sent'\n- 'processed'\n- 'error'"]
    end

    subgraph "SMS Controller"
        SendSMS["smsController->sendSMS()\nSend SMS Attempt"]
    end

    ExcelRow --> CreateSendAttempt
    CreateSendAttempt --> SendSMS
    SendSMS --> |Success with ID| UpdateSendAttempt
    SendSMS --> |No ID| UpdateSendAttempt
    SendSMS --> |Exception| UpdateSendAttempt

    subgraph "Additional Data Storage"
        AdditionalData["Additional Data\n- response_id\n- aditional_data (JSON)\n- error_message"]
    end

    UpdateSendAttempt --> AdditionalData

    subgraph "Logging"
        ErrorLog["Log::error()\nLog Exceptions"]
    end

    SendSMS --> |Exception| ErrorLog

```
