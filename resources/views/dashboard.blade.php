@extends('layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">AquaMonitor Dashboard</h1>
            <p class="dashboard-subtitle">Real-time water quality monitoring for vaname shrimp ponds</p>

            <div class="real-time-indicator">
                <i class="fas fa-clock text-primary"></i>
                <span id="current-time">{{ now()->format('H:i:s') }}</span>
                <span class="status-badge status-success" id="connection-status">Live</span>
            </div>
        </div>

        <!-- Overall Status Card -->
        <div class="overall-status-container" id="overall-status-container">
            <div class="card overall-status-card">
                <div class="overall-status-content">
                    <div class="overall-status-icon" id="overall-status-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="overall-status-info">
                        <h3 id="overall-status-title">Water Quality Status</h3>
                        <p id="overall-status-message">Monitoring parameters in real-time</p>
                    </div>
                    <div class="overall-quality-score">
                        <span id="overall-quality-value">--%</span>
                        <small>Overall Score</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Cards Grid -->
        <div class="status-grid">
            <!-- pH Card -->
            <div class="card status-card status-ph" id="ph-card">
                <div class="status-header">
                    <div class="status-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <span class="status-badge" id="ph-status-badge">Normal</span>
                </div>
                <div class="status-value" id="ph-value">--</div>
                <div class="status-label">pH Level</div>
                <div class="status-range">
                    <span class="range-label">Normal:</span> 6.8-8.5
                </div>
                <div class="status-message" id="ph-message">Monitoring pH level</div>
            </div>

            <!-- Temperature Card -->
            <div class="card status-card status-suhu" id="suhu-card">
                <div class="status-header">
                    <div class="status-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <span class="status-badge" id="suhu-status-badge">Normal</span>
                </div>
                <div class="status-value" id="suhu-value">--°C</div>
                <div class="status-label">Water Temperature</div>
                <div class="status-range">
                    <span class="range-label">Normal:</span> 24-29°C
                </div>
                <div class="status-message" id="suhu-message">Monitoring temperature</div>
            </div>

            <!-- Turbidity Card -->
            <div class="card status-card status-kekeruhan" id="kekeruhan-card">
                <div class="status-header">
                    <div class="status-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <span class="status-badge" id="kekeruhan-status-badge">Normal</span>
                </div>
                <div class="status-value" id="kekeruhan-value">-- NTU</div>
                <div class="status-label">Turbidity</div>
                <div class="status-range">
                    <span class="range-label">Normal:</span> 5-30 NTU
                </div>
                <div class="status-message" id="kekeruhan-message">Monitoring turbidity</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="chart-section">
            <h3 class="chart-section-title">Parameter Trends (Last 24 Hours)</h3>
            <div class="chart-container">
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="chart-section">
            <h3 class="chart-section-title">Recent Notifications</h3>
            <div class="notifications-container" id="notifications-container">
                <div class="notification-item notification-info">
                    <i class="fas fa-info-circle notification-icon"></i>
                    <div class="notification-content">
                        <strong>System Initialized</strong>
                        <p>Monitoring system started. Waiting for sensor data...</p>
                        <small>Just now</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="dashboard-footer">
            <p>&copy; 2025 AquaMonitor Water Quality System. Monitoring the health of your water.</p>
        </div>
    </div>

    <style>
        .dashboard-content {
            padding: var(--space-xl) 0;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
            animation: fadeIn 0.8s ease-out;
        }

        .dashboard-title {
            font-size: 2.5rem;
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--space-sm);
        }

        .dashboard-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto var(--space-lg);
        }

        /* Overall Status */
        .overall-status-container {
            margin-bottom: var(--space-2xl);
        }

        .overall-status-card {
            background: linear-gradient(135deg, var(--highlight-color), #34495E);
            color: white;
            border: none;
        }

        .overall-status-content {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }

        .overall-status-icon {
            font-size: 3rem;
            opacity: 0.9;
        }

        .overall-status-info {
            flex: 1;
        }

        .overall-status-info h3 {
            color: white;
            margin-bottom: var(--space-xs);
            font-size: 1.5rem;
        }

        .overall-status-info p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 0.95rem;
        }

        .overall-quality-score {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: var(--space-md);
            border-radius: var(--border-radius-lg);
            backdrop-filter: blur(10px);
        }

        .overall-quality-score span {
            font-size: 2rem;
            font-weight: var(--font-weight-semibold);
            display: block;
            margin-bottom: var(--space-xs);
        }

        .overall-quality-score small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        /* Status Grid */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-lg);
            margin-bottom: var(--space-3xl);
        }

        .status-card {
            padding: var(--space-xl);
            position: relative;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .status-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-lg);
        }

        .status-icon {
            font-size: 2rem;
            opacity: 0.8;
        }

        .status-value {
            font-size: 2.5rem;
            font-weight: var(--font-weight-semibold);
            margin-bottom: var(--space-sm);
            text-align: center;
        }

        .status-label {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: var(--space-sm);
            text-align: center;
            font-weight: var(--font-weight-medium);
        }

        .status-range {
            font-size: 0.9rem;
            color: var(--text-secondary);
            opacity: 0.8;
            text-align: center;
            margin-bottom: var(--space-md);
        }

        .range-label {
            font-weight: var(--font-weight-medium);
        }

        .status-message {
            font-size: 0.85rem;
            text-align: center;
            padding: var(--space-sm);
            border-radius: var(--border-radius-md);
            background: var(--card-color);
            border: 1px solid var(--border-color);
        }

        /* Status-specific styling */
        .status-ph.status-normal {
            border-color: var(--success-color);
        }

        .status-ph.status-warning {
            border-color: var(--warning-color);
        }

        .status-ph.status-danger {
            border-color: var(--danger-color);
        }

        .status-suhu.status-normal {
            border-color: var(--success-color);
        }

        .status-suhu.status-warning {
            border-color: var(--warning-color);
        }

        .status-suhu.status-danger {
            border-color: var(--danger-color);
        }

        .status-kekeruhan.status-normal {
            border-color: var(--success-color);
        }

        .status-kekeruhan.status-warning {
            border-color: var(--warning-color);
        }

        .status-kekeruhan.status-danger {
            border-color: var(--danger-color);
        }

        /* Color variations */
        .status-ph .status-icon {
            color: #3498DB;
        }

        .status-ph .status-value {
            color: #3498DB;
        }

        .status-suhu .status-icon {
            color: #27AE60;
        }

        .status-suhu .status-value {
            color: #27AE60;
        }

        .status-kekeruhan .status-icon {
            color: #F39C12;
        }

        .status-kekeruhan .status-value {
            color: #F39C12;
        }

        /* Chart Section */
        .chart-section {
            margin-bottom: var(--space-3xl);
        }

        .chart-section-title {
            font-size: 1.5rem;
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--space-xl);
            text-align: center;
        }

        .chart-container {
            background: var(--card-color);
            border-radius: var(--border-radius-lg);
            padding: var(--space-xl);
            box-shadow: var(--shadow-md);
            margin-bottom: var(--space-lg);
            border: 1px solid var(--border-color);
        }

        /* Notifications */
        .notifications-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: var(--space-md);
            padding: var(--space-lg);
            border-radius: var(--border-radius-lg);
            margin-bottom: var(--space-md);
            background: var(--card-color);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid;
            border: 1px solid var(--border-color);
        }

        .notification-content {
            flex: 1;
        }

        .notification-content strong {
            display: block;
            margin-bottom: var(--space-xs);
            font-size: 0.95rem;
        }

        .notification-content p {
            margin: 0 0 var(--space-xs) 0;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .notification-content small {
            color: var(--text-secondary);
            opacity: 0.7;
            font-size: 0.8rem;
        }

        .notification-icon {
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .notification-info {
            border-left-color: var(--accent-color);
            background: rgba(52, 152, 219, 0.05);
        }

        .notification-warning {
            border-left-color: var(--warning-color);
            background: rgba(243, 156, 18, 0.05);
        }

        .notification-danger {
            border-left-color: var(--danger-color);
            background: rgba(231, 76, 60, 0.05);
        }

        .notification-success {
            border-left-color: var(--success-color);
            background: rgba(39, 174, 96, 0.05);
        }

        /* Footer */
        .dashboard-footer {
            text-align: center;
            margin-top: var(--space-3xl);
            padding-top: var(--space-xl);
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        .real-time-indicator {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            background: var(--card-color);
            padding: var(--space-sm) var(--space-md);
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: var(--space-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .status-grid {
                grid-template-columns: 1fr;
                gap: var(--space-md);
            }

            .status-card {
                padding: var(--space-lg);
            }

            .status-value {
                font-size: 2rem;
            }

            .dashboard-title {
                font-size: 2rem;
            }

            .chart-container {
                padding: var(--space-lg);
            }

            .overall-status-content {
                flex-direction: column;
                text-align: center;
                gap: var(--space-md);
            }

            .overall-status-icon {
                font-size: 2.5rem;
            }
        }
    </style>

    <script>
        // Global state
        let currentData = {
            ph: null,
            suhu: null,
            kekeruhan: null,
            kualitas: null,
            parameter_statuses: null,
            overall_status: null
        };

        let notifications = [];
        let chart = null;

        // Update real-time clock
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }

        // Fetch sensor data from API
        async function fetchSensorData() {
            try {
                const response = await fetch('/api/sensor-data');
                const data = await response.json();

                if (data && data.length > 0) {
                    const latest = data[0];
                    updateDashboard(latest);
                    updateTrendChart(latest);
                } else {
                    // No data available
                    updateConnectionStatus('no_data');
                }
            } catch (error) {
                console.error('Error fetching sensor data:', error);
                updateConnectionStatus('error');
            }
        }

        // Update dashboard with new data
        function updateDashboard(data) {
            currentData = data;

            // Update parameter values
            document.getElementById('ph-value').textContent = data.ph?.toFixed(1) || '--';
            document.getElementById('suhu-value').textContent = data.suhu?.toFixed(1) + '°C' || '--°C';
            document.getElementById('kekeruhan-value').textContent = data.kekeruhan?.toFixed(1) + ' NTU' || '-- NTU';
            document.getElementById('overall-quality-value').textContent = data.kualitas ? Math.round(data.kualitas) + '%' :
                '--%';

            // Update status badges and styling
            updateParameterStatus('ph', data.parameter_statuses?.ph);
            updateParameterStatus('suhu', data.parameter_statuses?.suhu);
            updateParameterStatus('kekeruhan', data.parameter_statuses?.kekeruhan);
            updateParameterStatus('kualitas', data.parameter_statuses?.kualitas);

            // Update overall status
            updateOverallStatus(data.overall_status);

            updateConnectionStatus('success');
        }

        // Update individual parameter status
        function updateParameterStatus(parameter, status) {
            if (!status) return;

            const card = document.getElementById(`${parameter}-card`);
            const badge = document.getElementById(`${parameter}-status-badge`);
            const message = document.getElementById(`${parameter}-message`);

            // Update classes
            card.className = `card status-card status-${parameter} status-${status.status.toLowerCase()}`;

            // Update badge
            badge.className = `status-badge status-${status.color}`;
            badge.textContent = status.status;

            // Update message
            message.textContent = status.message;
        }

        // Update overall status
        function updateOverallStatus(status) {
            if (!status) return;

            const container = document.getElementById('overall-status-container');
            const icon = document.getElementById('overall-status-icon');
            const title = document.getElementById('overall-status-title');
            const message = document.getElementById('overall-status-message');

            container.className = `overall-status-container status-${status.color}`;
            icon.innerHTML = `<i class="${status.icon}"></i>`;
            title.textContent = `Water Quality: ${status.status}`;
            message.textContent = status.message;
        }

        // Update connection status
        function updateConnectionStatus(status) {
            const indicator = document.getElementById('connection-status');
            indicator.className = `status-badge status-${status}`;
            indicator.textContent = status === 'success' ? 'Live' : 'Error';
        }

        // Add notification
        function addNotification(type, title, message) {
            const notificationsContainer = document.getElementById('notifications-container');
            const notification = {
                type,
                title,
                message,
                timestamp: new Date()
            };

            notifications.unshift(notification);
            if (notifications.length > 5) notifications.pop();

            // Update UI
            notificationsContainer.innerHTML = notifications.map(notif => `
            <div class="notification-item notification-${notif.type}">
                <i class="fas fa-${getNotificationIcon(notif.type)} notification-icon"></i>
                <div class="notification-content">
                    <strong>${notif.title}</strong>
                    <p>${notif.message}</p>
                    <small>${formatTimeAgo(notif.timestamp)}</small>
                </div>
            </div>
        `).join('');
        }

        // Get notification icon based on type
        function getNotificationIcon(type) {
            const icons = {
                info: 'info-circle',
                warning: 'exclamation-triangle',
                danger: 'exclamation-circle',
                success: 'check-circle'
            };
            return icons[type] || 'info-circle';
        }

        // Format time ago
        function formatTimeAgo(timestamp) {
            const now = new Date();
            const diff = now - new Date(timestamp);
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);

            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
            if (hours < 24) return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
            return new Date(timestamp).toLocaleTimeString();
        }

        // Check water quality periodically
        async function checkWaterQuality() {
            try {
                const response = await fetch('/dashboard/check-water');
                const result = await response.json();

                if (result.status === 'warning' || result.status === 'danger') {
                    result.messages.forEach(message => {
                        addNotification(result.status, 'Water Quality Alert', message);
                    });
                }
            } catch (error) {
                console.error('Error checking water quality:', error);
            }
        }

        // Initialize trend chart with real data
        async function initTrendChart() {
            try {
                const response = await fetch('/api/chart-data');
                const chartData = await response.json();

                const ctx = document.getElementById('trendChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels || [],
                        datasets: [{
                                label: 'pH Level',
                                data: chartData.datasets?.ph || [],
                                borderColor: '#3498DB',
                                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Temperature (°C)',
                                data: chartData.datasets?.suhu || [],
                                borderColor: '#27AE60',
                                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Turbidity (NTU)',
                                data: chartData.datasets?.kekeruhan || [],
                                borderColor: '#F39C12',
                                backgroundColor: 'rgba(243, 156, 18, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading chart data:', error);
                // Initialize empty chart if data loading fails
                const ctx = document.getElementById('trendChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                                label: 'pH Level',
                                data: [],
                                borderColor: '#3498DB',
                                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Temperature (°C)',
                                data: [],
                                borderColor: '#27AE60',
                                backgroundColor: 'rgba(39, 174, 96, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Turbidity (NTU)',
                                data: [],
                                borderColor: '#F39C12',
                                backgroundColor: 'rgba(243, 156, 18, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
        }

        // Update trend chart with new data
        function updateTrendChart(data) {
            if (!chart || !data.created_at) return;

            const timestamp = new Date(data.created_at).toLocaleTimeString();

            // Add new data points
            chart.data.labels.push(timestamp);
            chart.data.datasets[0].data.push(data.ph);
            chart.data.datasets[1].data.push(data.suhu);
            chart.data.datasets[2].data.push(data.kekeruhan);

            // Keep only last 24 data points
            const maxPoints = 24;
            if (chart.data.labels.length > maxPoints) {
                chart.data.labels.shift();
                chart.data.datasets.forEach(dataset => dataset.data.shift());
            }

            chart.update('quiet');
        }

        // Global QualityHelper for frontend (simplified version)
        const QualityHelper = {
            getOverallStatus: function(parameterStatuses) {
                if (!parameterStatuses) return {
                    status: 'Unknown',
                    color: 'secondary',
                    icon: 'fas fa-question-circle',
                    message: 'No data available'
                };

                const statusCounts = {
                    Danger: 0,
                    Warning: 0,
                    Normal: 0
                };
                Object.values(parameterStatuses).forEach(status => statusCounts[status.status]++);

                if (statusCounts.Danger > 0) {
                    return {
                        status: 'Danger',
                        color: 'danger',
                        icon: 'fas fa-exclamation-triangle',
                        message: 'Kualitas air kritis! Perlu tindakan segera'
                    };
                }
                if (statusCounts.Warning > 0) {
                    return {
                        status: 'Warning',
                        color: 'warning',
                        icon: 'fas fa-exclamation-circle',
                        message: 'Kualitas air perlu perhatian'
                    };
                }
                return {
                    status: 'Normal',
                    color: 'success',
                    icon: 'fas fa-check-circle',
                    message: 'Kualitas air dalam kondisi baik'
                };
            }
        };

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 4000);

            // Fetch data immediately and then every 4000 seconds
            fetchSensorData();
            setInterval(fetchSensorData, 4000);

            // Check water quality every 30 seconds
            setInterval(checkWaterQuality, 30000);

            // Initialize trend chart
            initTrendChart();

            // Add initial notification
            addNotification('info', 'System Initialized', 'Monitoring system started. Waiting for sensor data...');
        });
    </script>
@endsection
