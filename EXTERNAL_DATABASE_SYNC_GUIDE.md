# External Database Synchronization Guide

## Current Situation

-   **External Database**: Accessible via phpMyAdmin at `https://db.devonjago.site`
-   **Database Name**: `n8n_db`
-   **Table**: `sensors`
-   **Direct MySQL Connection**: Not available (connection refused)
-   **Local Database**: Fully functional with test data

## Manual Synchronization Steps

### Option 1: Export/Import via phpMyAdmin

#### Step 1: Export Data from External Database

1. Open phpMyAdmin: https://db.devonjago.site
2. Login with credentials:
    - Username: `n8nuser`
    - Password: `n8npassword`
3. Select database `n8n_db`
4. Select table `sensors`
5. Click "Export" tab
6. Choose format: **SQL** or **CSV**
7. Select "Export method: Custom"
8. Check "Add DROP TABLE / VIEW / PROCEDURE statement"
9. Check "IF NOT EXISTS (less efficient as indexes will be generated during table creation)"
10. Click "Go" to download export file

#### Step 2: Import into Local Database

1. Open local phpMyAdmin (usually http://localhost/phpmyadmin)
2. Select database `n8n_db`
3. Click "Import" tab
4. Choose the exported file
5. Set format to match export format (SQL/CSV)
6. Click "Go" to import

#### Step 3: Update Status Information

```bash
php artisan sensors:update-statuses
```

### Option 2: CSV Export/Import

#### Export as CSV:

1. In phpMyAdmin export, choose format: **CSV**
2. Options:
    - Columns separated with: `,` (comma)
    - Columns enclosed with: `"` (double quote)
    - Columns escaped with: `\` (backslash)
    - Lines terminated with: `AUTO`
    - Column names: ☑ Yes

#### Import CSV:

1. Use Laravel artisan command:

```bash
php artisan db:import-csv path/to/exported_file.csv
```

## Automated Solutions (To be implemented)

### 1. API-based Synchronization

If the external database exposes API endpoints, we can create:

-   REST API client to fetch data
-   Webhook integration
-   Scheduled synchronization

### 2. Database Replication

If remote access can be enabled:

-   Configure MySQL for remote connections
-   Set up master-slave replication
-   Use Laravel's multiple database connections

### 3. File-based Synchronization

-   Regular export/import scripts
-   Cloud storage integration (Dropbox, Google Drive)
-   FTP/SFTP automated transfers

## Current Local Database Status

✅ Database structure is correct and up-to-date
✅ Migration issues fixed (kualitas column handling)
✅ Test data available with proper status information
✅ Quality assessment system working
✅ API endpoints available for data ingestion

## Troubleshooting

### Connection Issues:

-   External MySQL: Connection refused (firewall/configuration)
-   phpMyAdmin: Accessible ✅

### Data Consistency:

-   Ensure both databases have same table structure
-   Run migrations on both databases if needed
-   Verify data types and constraints match

## Next Steps

1. Manual data synchronization using phpMyAdmin export/import
2. Consider requesting remote MySQL access from hosting provider
3. Implement automated synchronization if API becomes available
4. Regular data validation and consistency checks
