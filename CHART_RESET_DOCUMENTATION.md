# Automatic Chart Reset at Midnight

## Overview

This implementation provides automatic reset of chart data at midnight (00:00) every day. The system clears previous day's data and starts recording new values for the new day.

## Components

### 1. Laravel Command (`app/Console/Commands/ResetDailyChartData.php`)

-   **Purpose**: Clears cached chart data and resets daily tracking
-   **Command**: `php artisan chart:reset-daily`
-   **Functionality**:
    -   Clears cached chart data (`daily_chart_data`, `hourly_chart_data`)
    -   Stores the reset date in cache for tracking
    -   Logs the reset action for monitoring

### 2. Scheduled Task (`routes/console.php`)

-   **Schedule**: Runs daily at midnight (00:00)
-   **Configuration**: `Schedule::command('chart:reset-daily')->dailyAt('00:00');`
-   **Next Run**: Check with `php artisan schedule:list`

### 3. JavaScript Frontend Logic (`resources/views/dashboard.blade.php`)

-   **Date Tracking**: Uses localStorage to track the last reset date
-   **Midnight Detection**: Checks every minute for date changes
-   **Automatic Reset**: When midnight is detected and date changes:
    -   Clears all hourly data
    -   Reinitializes data structure for new day
    -   Updates chart with empty data
    -   Shows visual notification

## How It Works

### Backend Process

1. **Scheduled Command**: The Laravel scheduler runs `chart:reset-daily` at midnight
2. **Data Clearing**: The command clears any cached chart data
3. **Tracking**: Stores the reset timestamp for future reference

### Frontend Process

1. **Date Monitoring**: JavaScript checks current date every minute
2. **Midnight Detection**: When hour is 00:00 and date changes from stored date
3. **Data Reset**: Clears hourly data and reinitializes structure
4. **Visual Feedback**: Shows notification that chart was reset

## Testing

### Manual Testing

1. Run the command: `php artisan chart:reset-daily`
2. Check schedule: `php artisan schedule:list`
3. Test JavaScript: Open `test_midnight_reset.html` in browser

### Automated Testing

The system includes:

-   Command execution testing
-   Schedule configuration verification
-   JavaScript logic validation

## Configuration

### Time Zone

The system uses the server's timezone for scheduling. Ensure your server timezone is correctly set for your location.

### Cache Settings

The reset command uses Laravel's cache system. Configure your cache driver in `.env`:

```
CACHE_DRIVER=file
```

### Logging

Reset actions are logged to Laravel's log file (`storage/logs/laravel.log`).

## Troubleshooting

### Command Not Running

1. Check scheduler: `php artisan schedule:list`
2. Verify command exists: `php artisan list | findstr chart`
3. Test manually: `php artisan chart:reset-daily`

### Frontend Not Resetting

1. Check browser console for errors
2. Verify localStorage is enabled
3. Test with `test_midnight_reset.html`

### Time Zone Issues

1. Check server timezone: `date`
2. Verify Laravel timezone in `config/app.php`

## Maintenance

### Regular Checks

-   Monitor logs for reset actions
-   Verify scheduler is running (using cron or supervisor)
-   Test functionality periodically

### Updates

When modifying chart functionality, ensure:

-   The reset command clears any new cache keys
-   JavaScript logic handles new data structures
-   Scheduling remains configured correctly

## Dependencies

-   Laravel Scheduler (requires cron job setup)
-   Browser localStorage support
-   Chart.js library (for frontend charts)
