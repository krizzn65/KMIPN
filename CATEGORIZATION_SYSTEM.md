# Water Quality Categorization System

## Overview

This document describes the redesigned water quality monitoring system focused on three main parameters with simplified status categorization for vaname shrimp ponds.

## Parameters & Thresholds

### 1. pH Level

-   **Normal**: 7.5 – 8.5 (Safe for vaname shrimp)
-   **Warning**: 7.0 – 7.4 (Slightly acidic, needs monitoring)
-   **Danger**: <7.0 or >8.5 (Critical levels requiring immediate action)

### 2. Temperature (Suhu)

-   **Normal**: 28 – 32°C (Optimal range for vaname shrimp)
-   **Warning**: 26 – 27°C or 33 – 34°C (Borderline temperatures)
-   **Danger**: <26°C or >34°C (Extreme temperatures harmful to shrimp)

### 3. Turbidity (Kekeruhan)

-   **Normal**: 25 – 400 NTU (Acceptable water clarity)
-   **Warning**: 401 – 600 NTU (Increased turbidity, needs attention)
-   **Danger**: >600 NTU (Critical turbidity requiring treatment)

## Status Colors & Indicators

### Color Coding

-   **Normal**: Green (#27AE60)
-   **Warning**: Yellow/Orange (#F39C12)
-   **Danger**: Red (#E74C3C)

### Icons

-   **pH**: Flask icon (fas fa-flask)
-   **Temperature**: Thermometer icon (fas fa-thermometer-half)
-   **Turbidity**: Eye icon (fas fa-eye / fas fa-eye-slash for danger)

## Data Output Format

Each parameter measurement returns:

```json
{
    "parameter": "pH",
    "value": 8.0,
    "status": "Normal",
    "color": "success",
    "icon": "fas fa-flask",
    "message": "pH level optimal untuk udang vaname (8.0)"
}
```

## Overall Water Quality Status

The system calculates an overall status based on individual parameter statuses:

-   **Danger**: Any parameter in Danger status
-   **Warning**: Any parameter in Warning status (but no Danger)
-   **Normal**: All parameters in Normal status

## Database Schema Updates

The sensors table now includes status columns:

-   `ph_status` (VARCHAR 10)
-   `suhu_status` (VARCHAR 10)
-   `kekeruhan_status` (VARCHAR 10)
-   `overall_status` (VARCHAR 10)

## API Endpoints

### Dashboard Data

-   `GET /api/sensor-data` - Returns latest sensor data with status information
-   `GET /dashboard/check-water` - Checks water quality and returns status

### History Data

-   `GET /history/data` - Returns all historical data with status information
-   `GET /history/filter` - Filters historical data by parameter and status

## Filtering Options

### Time Range

-   Today
-   Last 7 Days
-   Last 30 Days
-   Custom Range

### Parameter Status

-   All Statuses
-   Normal only
-   Warning only
-   Danger only

## UI Features

### Dashboard

-   Color-coded parameter cards with real-time status
-   Overall water quality status panel
-   Trend charts for 24-hour monitoring
-   Real-time notifications system

### History Page

-   Filterable data grid with status badges
-   Parameter-specific status indicators
-   Export functionality (CSV)
-   Paginated results

## Technical Implementation

### Backend

-   **QualityHelper**: Central class for status determination
-   **Threshold-based logic**: Simple range checking instead of fuzzy logic
-   **Real-time updates**: Automatic status calculation on data ingestion

### Frontend

-   **Responsive design**: Works on desktop and mobile
-   **Live updates**: Real-time data refresh every 5 seconds
-   **Visual feedback**: Color transitions and status animations

## Benefits

1. **Simplified Monitoring**: Focus on three critical parameters only
2. **Clear Status Indicators**: Immediate visual understanding of water quality
3. **Actionable Insights**: Specific guidance for each status level
4. **Historical Analysis**: Easy filtering and export of historical data
5. **Mobile Compatibility**: Responsive design for field monitoring

## Usage Examples

### Normal Operation

All parameters within optimal ranges → Green indicators, "Normal" status

### Warning Scenario

pH at 7.2 (Warning) + Temperature at 33°C (Warning) → Yellow indicators, "Warning" status

### Critical Situation

Turbidity at 650 NTU (Danger) → Red indicators, "Danger" status with urgent notifications
