@extends('layouts.app')

@section('content')
<div class="dashboard-content">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">AquaMonitor Dashboard</h1>
        <p class="dashboard-subtitle">Real-time water quality monitoring and analysis</p>
        
        <div class="real-time-indicator">
            <i class="fas fa-clock text-primary"></i>
            <span id="current-time">{{ now()->format('H:i:s') }}</span>
            <span class="status-badge status-success">Live</span>
        </div>
    </div>
    
    <!-- Status Cards Grid -->
    <div class="status-grid">
        <!-- pH Card -->
        <div class="card status-card status-ph">
            <span class="status-badge status-success">Normal</span>
            <div class="status-icon">
                <i class="fas fa-flask"></i>
            </div>
            <div class="status-value" id="ph-value">7.2</div>
            <div class="status-label">pH Level</div>
            <div class="status-range">Optimal: 6.5 - 8.5</div>
        </div>
        
        <!-- Temperature Card -->
        <div class="card status-card status-suhu">
            <span class="status-badge status-success">Normal</span>
            <div class="status-icon">
                <i class="fas fa-thermometer-half"></i>
            </div>
            <div class="status-value" id="suhu-value">28.5°C</div>
            <div class="status-label">Water Temperature</div>
            <div class="status-range">Optimal: 26°C - 30°C</div>
        </div>
        
        <!-- Turbidity Card -->
        <div class="card status-card status-kekeruhan">
            <span class="status-badge status-warning">Warning</span>
            <div class="status-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="status-value" id="kekeruhan-value">15.2 NTU</div>
            <div class="status-label">Turbidity</div>
            <div class="status-range">Optimal: < 20 NTU</div>
        </div>
        
        <!-- Quality Card -->
        <div class="card status-card status-quality">
            <span class="status-badge status-success">Good</span>
            <div class="status-icon">
                <i class="fas fa-tint"></i>
            </div>
            <div class="status-value" id="quality-value">85%</div>
            <div class="status-label">Water Quality</div>
            <div class="status-range">Overall score</div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="chart-section">
        <h3 class="chart-section-title">Parameter Trends (24 Hours)</h3>
        <div class="chart-container">
            <canvas id="trendChart" width="400" height="200"></canvas>
        </div>
    </div>
    
    <!-- Notifications Section -->
    <div class="chart-section">
        <h3 class="chart-section-title">Recent Notifications</h3>
        <div class="notifications-container">
            <div class="notification-item notification-warning">
                <i class="fas fa-exclamation-triangle notification-icon"></i>
                <strong>Turbidity Increased</strong>
                <p>Turbidity level reached 15.2 NTU. Consider cleaning procedures.</p>
                <small>2 minutes ago</small>
            </div>
            
            <div class="notification-item notification-success">
                <i class="fas fa-check-circle notification-icon"></i>
                <strong>All Parameters Normal</strong>
                <p>All parameters within optimal range for the last 6 hours.</p>
                <small>1 hour ago</small>
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
        margin-bottom: var(--space-3xl);
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
        margin: 0 auto;
    }
    
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-3xl);
    }
    
    .status-card {
        text-align: center;
        padding: var(--space-xl);
        position: relative;
        transition: all 0.3s ease;
    }
    
    .status-card:hover {
        transform: translateY(-4px);
    }
    
    .status-icon {
        font-size: 2.5rem;
        margin-bottom: var(--space-lg);
        opacity: 0.9;
    }
    
    .status-value {
        font-size: 2.5rem;
        font-weight: var(--font-weight-semibold);
        margin-bottom: var(--space-sm);
    }
    
    .status-label {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: var(--space-sm);
    }
    
    .status-range {
        font-size: 0.9rem;
        color: var(--text-secondary);
        opacity: 0.8;
    }
    
    .status-badge {
        position: absolute;
        top: var(--space-lg);
        right: var(--space-lg);
        font-size: 0.8rem;
        padding: var(--space-xs) var(--space-md);
        border-radius: 20px;
        font-weight: var(--font-weight-medium);
    }
    
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
        background: var(--surface-color);
        border-radius: var(--border-radius-lg);
        padding: var(--space-xl);
        box-shadow: var(--shadow-md);
        margin-bottom: var(--space-lg);
    }
    
    .notifications-container {
        max-width: 400px;
        margin: 0 auto;
    }
    
    .notification-item {
        padding: var(--space-lg);
        border-radius: var(--border-radius-md);
        margin-bottom: var(--space-md);
        border-left: 4px solid;
        background: var(--surface-color);
        box-shadow: var(--shadow-sm);
    }
    
    .notification-warning {
        border-left-color: var(--warning-color);
        background: rgba(243, 156, 18, 0.05);
    }
    
    .notification-success {
        border-left-color: var(--success-color);
        background: rgba(39, 174, 96, 0.05);
    }
    
    .notification-icon {
        font-size: 1.2rem;
        margin-right: var(--space-sm);
    }
    
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
        background: var(--surface-color);
        padding: var(--space-sm) var(--space-md);
        border-radius: 20px;
        font-size: 0.9rem;
        margin-bottom: var(--space-lg);
        box-shadow: var(--shadow-sm);
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
    }
    
    /* Color variations for status cards */
    .status-ph .status-icon { color: #3498DB; }
    .status-ph .status-value { color: #3498DB; }
    
    .status-suhu .status-icon { color: #27AE60; }
    .status-suhu .status-value { color: #27AE60; }
    
    .status-kekeruhan .status-icon { color: #F39C12; }
    .status-kekeruhan .status-value { color: #F39C12; }
    
    .status-quality .status-icon { color: #9B59B6; }
    .status-quality .status-value { color: #9B59B6; }
</style>

<script>
    // Simulated sensor data
    let sensorData = {
        ph: 7.2,
        suhu: 28.5,
        kekeruhan: 15.2,
        quality: 85
    };
    
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
    
    // Update status badges based on values
    function updateStatusBadges() {
        const phBadge = document.querySelector('.status-ph .status-badge');
        const suhuBadge = document.querySelector('.status-suhu .status-badge');
        const kekeruhanBadge = document.querySelector('.status-kekeruhan .status-badge');
        const qualityBadge = document.querySelector('.status-quality .status-badge');
        
        // pH status
        if (sensorData.ph < 6.5 || sensorData.ph > 8.5) {
            phBadge.className = 'status-badge status-danger';
            phBadge.textContent = 'Critical';
        } else {
            phBadge.className = 'status-badge status-success';
            phBadge.textContent = 'Normal';
        }
        
        // Temperature status
        if (sensorData.suhu < 26 || sensorData.suhu > 30) {
            suhuBadge.className = 'status-badge status-danger';
            suhuBadge.textContent = 'Critical';
        } else {
            suhuBadge.className = 'status-badge status-success';
            suhuBadge.textContent = 'Normal';
        }
        
        // Turbidity status
        if (sensorData.kekeruhan > 20) {
            kekeruhanBadge.className = 'status-badge status-danger';
            kekeruhanBadge.textContent = 'Critical';
        } else if (sensorData.kekeruhan > 15) {
            kekeruhanBadge.className = 'status-badge status-warning';
            kekeruhanBadge.textContent = 'Warning';
        } else {
            kekeruhanBadge.className = 'status-badge status-success';
            kekeruhanBadge.textContent = 'Normal';
        }
        
        // Quality status
        if (sensorData.quality >= 80) {
            qualityBadge.className = 'status-badge status-success';
            qualityBadge.textContent = 'Excellent';
        } else if (sensorData.quality >= 60) {
            qualityBadge.className = 'status-badge status-warning';
            qualityBadge.textContent = 'Good';
        } else {
            qualityBadge.className = 'status-badge status-danger';
            qualityBadge.textContent = 'Poor';
        }
    }
    
    // Simulate data updates
    function simulateDataUpdate() {
        // Random variations
        sensorData.ph += (Math.random() - 0.5) * 0.1;
        sensorData.suhu += (Math.random() - 0.5) * 0.5;
        sensorData.kekeruhan += (Math.random() - 0.5) * 1;
        sensorData.quality = Math.max(0, Math.min(100, sensorData.quality + (Math.random() - 0.5) * 2));
        
        // Keep values in reasonable ranges
        sensorData.ph = Math.max(6, Math.min(9, sensorData.ph));
        sensorData.suhu = Math.max(25, Math.min(35, sensorData.suhu));
        sensorData.kekeruhan = Math.max(0, Math.min(50, sensorData.kekeruhan));
        
        // Update display
        document.getElementById('ph-value').textContent = sensorData.ph.toFixed(1);
        document.getElementById('suhu-value').textContent = sensorData.suhu.toFixed(1) + '°C';
        document.getElementById('kekeruhan-value').textContent = sensorData.kekeruhan.toFixed(1) + ' NTU';
        document.getElementById('quality-value').textContent = Math.round(sensorData.quality) + '%';
        
        updateStatusBadges();
    }
    
    // Initialize everything
    document.addEventListener('DOMContentLoaded', function() {
        updateTime();
        setInterval(updateTime, 1000);
        
        updateStatusBadges();
        
        // Simulate real-time updates every 3 seconds
        setInterval(simulateDataUpdate, 3000);
        
        // Initialize trend chart (placeholder)
        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [{
                    label: 'pH Level',
                    data: [7.0, 7.2, 7.1, 7.3, 7.2, 7.1],
                    borderColor: '#3498DB',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
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
    });
</script>
@endsection