Requeriments

- Laravel v11.3.3
- Composer version 2.8.2 2024-10-29 16:12:11
- PHP version 8.2.12 (C:\xampp\php\php.exe)
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
Sms Transaction Controller Data Flow
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
