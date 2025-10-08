@extends('layouts.app')

@section('content')
    <div class="history-content">
        <!-- Header -->
        <div class="history-header">
            <h1 class="history-title">Historical Records</h1>
            <p class="history-subtitle">Review past water quality measurements and parameter statuses</p>
        </div>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <div class="filter-group">
                <label class="filter-label">Time Range:</label>
                <select class="filter-select" id="time-range">
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Parameter:</label>
                <select class="filter-select" id="parameter-filter">
                    <option value="all">All Parameters</option>
                    <option value="ph">pH Level</option>
                    <option value="suhu">Temperature</option>
                    <option value="kekeruhan">Turbidity (%)</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Status:</label>
                <select class="filter-select" id="status-filter">
                    <option value="all">All Statuses</option>
                    <option value="Normal">Normal</option>
                    <option value="Warning">Warning</option>
                    <option value="Danger">Danger</option>
                </select>
            </div>

            <button class="btn btn-primary" id="apply-filters">
                <i class="fas fa-filter"></i> Apply Filters
            </button>

            <button class="btn btn-outline" id="export-data">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>

        <!-- Loading Indicator -->
        <div class="loading-container" id="loading-container">
            <div class="loading-spinner"></div>
            <p>Loading historical data...</p>
        </div>

        <!-- Historical Records Grid -->
        <div class="history-grid" id="history-grid"></div>

        <!-- No Data Message -->
        <div class="no-records" id="no-records" style="display: none;">
            <i class="fas fa-database" style="font-size: 3rem; margin-bottom: var(--space-md);"></i>
            <h3>No Records Found</h3>
            <p>No historical data matches your current filters.</p>
        </div>

        <!-- Pagination -->
        <div class="pagination" id="pagination"></div>

        <!-- Footer -->
        <div class="dashboard-footer">
            <p>&copy; 2025 AquaMonitor Water Quality System. Historical data analysis and monitoring.</p>
        </div>
    </div>

    <style>
/* === Button Styles === */
.btn.btn-primary {
  background-color: #1e88e5;
  color: white;
  border: none;
}

.btn.btn-primary:hover {
  background-color: #1976d2;
}

.btn.btn-outline {
  background-color: transparent;
  border: 1px solid var(--border-color);
  color: var(--text-primary);
}

.btn.btn-outline:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

/* === History Section === */
.history-content {
  padding: var(--space-xl) 0;
}

.history-header {
  text-align: center;
  margin-bottom: var(--space-2xl);
  animation: fadeIn 0.8s ease-out;
}

.history-title {
  font-size: 2.5rem;
  font-weight: var(--font-weight-semibold);
  color: var(--primary-color);
  margin-bottom: var(--space-sm);
}

.history-subtitle {
  color: var(--text-secondary);
  font-size: 1.1rem;
  max-width: 600px;
  margin: 0 auto var(--space-lg);
}

/* === Filter Controls === */
.filter-controls {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--space-md);
  margin-bottom: var(--space-2xl);
  align-items: end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: var(--space-xs);
}

.filter-label {
  font-size: 0.9rem;
  font-weight: var(--font-weight-medium);
  color: var(--text-primary);
}

.filter-select {
  padding: var(--space-sm) var(--space-md);
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  background: var(--card-color);
  font-size: 0.9rem;
  transition: all 0.3s ease;
  color: var(--text-primary);
}

.filter-select:focus {
  outline: none;
  border-color: var(--accent-color);
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

/* === Loading Container === */
.loading-container {
  text-align: center;
  padding: var(--space-3xl);
  color: var(--text-secondary);
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--border-color);
  border-top: 3px solid var(--accent-color);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto var(--space-md);
}

/* === History Grid === */
.history-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: var(--space-lg);
  margin-bottom: var(--space-3xl);
}

.history-card {
  position: relative;
  transition: all 0.3s ease;
}

.history-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--space-md);
  padding-bottom: var(--space-sm);
  border-bottom: 1px solid var(--border-color);
}

.card-date {
  font-size: 0.9rem;
  color: var(--text-secondary);
  font-weight: var(--font-weight-medium);
}

.card-time {
  font-size: 0.85rem;
  color: var(--text-secondary);
  opacity: 0.8;
}

/* === Parameter Grid === */
.parameter-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--space-md);
  margin-bottom: var(--space-lg);
}

.parameter-item {
  text-align: center;
  padding: var(--space-md);
  background: var(--card-color);
  border-radius: var(--border-radius-md);
  position: relative;
  border: 1px solid var(--border-color);
}

.parameter-value {
  font-size: 1.2rem;
  font-weight: var(--font-weight-semibold);
  margin-bottom: var(--space-xs);
}

.parameter-label {
  font-size: 0.85rem;
  color: var(--text-secondary);
  margin-bottom: var(--space-xs);
}

.parameter-status {
  font-size: 0.75rem;
  padding: 2px 8px;
  border-radius: 12px;
  display: inline-block;
  font-weight: var(--font-weight-medium);
}

/* === Status Colors === */
.status-normal {
  background: rgba(39, 174, 96, 0.1);
  color: var(--success-color);
}

.status-warning {
  background: rgba(243, 156, 18, 0.1);
  color: var(--warning-color);
}

.status-danger {
  background: rgba(231, 76, 60, 0.1);
  color: var(--danger-color);
}

/* === Quality Score === */
.quality-score {
  text-align: center;
  padding: var(--space-md);
  background: var(--card-color);
  border-radius: var(--border-radius-md);
  margin-bottom: var(--space-md);
  border: 1px solid var(--border-color);
}

.quality-value {
  font-size: 1.5rem;
  font-weight: var(--font-weight-semibold);
  color: var(--success-color);
}

.quality-label {
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.overall-status {
  text-align: center;
  padding: var(--space-sm) var(--space-md);
  border-radius: var(--border-radius-md);
  font-weight: var(--font-weight-medium);
  margin-top: var(--space-md);
}

/* === Color Variations for Parameter Values === */
.ph-value {
  color: #3498db;
}

.temp-value {
  color: #27ae60;
}

.turbidity-value {
  color: #f39c12;
}

/* === No Records === */
.no-records {
  text-align: center;
  color: var(--text-secondary);
  padding: var(--space-3xl);
  font-style: italic;
}

/* === Pagination === */
.pagination {
  display: flex;
  justify-content: center;
  gap: var(--space-sm);
  margin-top: var(--space-2xl);
  margin-bottom: var(--space-xl);
}

.page-btn {
  padding: var(--space-sm) var(--space-md);
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  background: var(--card-color);
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  color: var(--text-primary);
}

.page-btn:hover,
.page-btn.active {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* === Responsive Design === */
@media (max-width: 768px) {
  .history-grid {
    grid-template-columns: 1fr;
    gap: var(--space-md);
  }

  .history-title {
    font-size: 2rem;
  }

  .filter-controls {
    grid-template-columns: 1fr;
    gap: var(--space-md);
  }

  .parameter-grid {
    grid-template-columns: 1fr;
  }

  .card-header {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--space-xs);
  }
}
</style>


    {{-- ================= JS ================= --}}
    <script>
        let currentPage = 1;
        const itemsPerPage = 6;
        let allData = [];
        let filteredData = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadHistoryData();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('apply-filters').addEventListener('click', applyFilters);
            document.getElementById('export-data').addEventListener('click', exportToCSV);
        }

        // Load history data
        async function loadHistoryData() {
            showLoading(true);

            try {
                const response = await fetch('/history/data');
                const data = await response.json();
                allData = data;
                applyFilters();
            } catch (error) {
                console.error('Error loading history data:', error);
                showError('Failed to load historical data');
            } finally {
                showLoading(false);
            }
        }

        // Apply filters
        function applyFilters() {
            const timeRange = document.getElementById('time-range').value;
            const status = document.getElementById('status-filter').value;

            filteredData = allData.filter(item => {
                // Time range filter
                if (timeRange !== 'all') {
                    const itemDate = new Date(item.created_at);
                    const now = new Date();
                    const timeDiff = now - itemDate;

                    switch (timeRange) {
                        case 'today':
                            if (!isToday(itemDate)) return false;
                            break;
                        case '7days':
                            if (timeDiff > 7 * 24 * 60 * 60 * 1000) return false;
                            break;
                        case '30days':
                            if (timeDiff > 30 * 24 * 60 * 60 * 1000) return false;
                            break;
                    }
                }

                // Status filter
                if (status !== 'all' && item.status_kualitas_air !== status) {
                    return false;
                }

                return true;
            });

            currentPage = 1;
            renderHistoryData();
            renderPagination();
        }

        function isToday(date) {
            const today = new Date();
            return date.getDate() === today.getDate() &&
                date.getMonth() === today.getMonth() &&
                date.getFullYear() === today.getFullYear();
        }

        // Render history cards
        function renderHistoryData() {
            const container = document.getElementById('history-grid');
            const noRecords = document.getElementById('no-records');

            if (filteredData.length === 0) {
                container.innerHTML = '';
                noRecords.style.display = 'block';
                return;
            }

            noRecords.style.display = 'none';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            container.innerHTML = pageData.map(item => `
                <div class="card history-card">
                    <div class="card-header">
                        <span class="card-date">${item.created_at.split(',')[0]}</span>
                        <span class="card-time">${item.created_at.split(',')[1]}</span>
                    </div>

                    <div class="parameter-grid">
                        <div class="parameter-item">
                            <div class="parameter-value ph-value">${item.ph?.toFixed(1) || '--'}</div>
                            <div class="parameter-label">pH Level</div>
                            <span class="parameter-status status-${item.parameter_statuses?.ph?.status?.toLowerCase() || 'normal'}">
                                ${item.parameter_statuses?.ph?.status || 'Normal'}
                            </span>
                        </div>

                        <div class="parameter-item">
                            <div class="parameter-value temp-value">${item.suhu?.toFixed(1) || '--'}°C</div>
                            <div class="parameter-label">Temperature</div>
                            <span class="parameter-status status-${item.parameter_statuses?.suhu?.status?.toLowerCase() || 'normal'}">
                                ${item.parameter_statuses?.suhu?.status || 'Normal'}
                            </span>
                        </div>

                        <div class="parameter-item">
                            <div class="parameter-value turbidity-value">${item.kekeruhan?.toFixed(1) || '--'}%</div>
                            <div class="parameter-label">Turbidity (%)</div>
                            <span class="parameter-status status-${item.parameter_statuses?.kekeruhan?.status?.toLowerCase() || 'normal'}">
                                ${item.parameter_statuses?.kekeruhan?.status || 'Normal'}
                            </span>
                        </div>
                    </div>

                    <div class="quality-score">
                        <div class="quality-value">${item.kualitas ? Math.round(item.kualitas) + '%' : '--%'}</div>
                        <div class="quality-label">Water Quality Score</div>
                    </div>

                    <div class="overall-status status-${item.status_kualitas_air?.toLowerCase() || 'normal'}">
                        <i class="fas ${getStatusIcon(item.status_kualitas_air)}"></i>
                        ${item.status_kualitas_air || 'Normal'} Quality
                    </div>
                </div>
            `).join('');
        }

        function getStatusIcon(status) {
            switch (status?.toLowerCase()) {
                case 'danger': return 'fa-exclamation-triangle';
                case 'warning': return 'fa-exclamation-circle';
                case 'normal': return 'fa-check-circle';
                default: return 'fa-question-circle';
            }
        }

        // Pagination
        function renderPagination() {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = `
                <button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>`;

            for (let i = 1; i <= totalPages; i++) {
                html += `
                    <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                        ${i}
                    </button>`;
            }

            html += `
                <button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>`;

            pagination.innerHTML = html;
        }

        function changePage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderHistoryData();
            renderPagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Export to CSV
        function exportToCSV() {
            if (filteredData.length === 0) return alert('No data to export');

            const headers = ['Date', 'Time', 'pH', 'pH Status', 'Temperature', 'Temp Status', 'Turbidity (%)', 'Turbidity Status', 'Quality Score', 'Overall Status'];
            const csvData = [
                headers,
                ...filteredData.map(item => {
                    const [date, time] = item.created_at.split(', ');
                    return [
                        date, time,
                        item.ph?.toFixed(1) || '',
                        item.parameter_statuses?.ph?.status || '',
                        item.suhu?.toFixed(1) || '',
                        item.parameter_statuses?.suhu?.status || '',
                        item.kekeruhan?.toFixed(1) || '',
                        item.parameter_statuses?.kekeruhan?.status || '',
                        item.kualitas ? Math.round(item.kualitas) + '%' : '',
                        item.status_kualitas_air || ''
                    ];
                })
            ];

            const csvContent = csvData.map(row => row.map(f => `"${f}"`).join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `aquamonitor-history-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function showLoading(show) {
            document.getElementById('loading-container').style.display = show ? 'block' : 'none';
            document.getElementById('history-grid').style.display = show ? 'none' : 'grid';
        }

        function showError(message) {
            document.getElementById('history-grid').innerHTML = `
                <div class="card" style="grid-column: 1 / -1; text-align: center; color: var(--danger-color);">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: var(--space-md);"></i>
                    <h3>Error Loading Data</h3>
                    <p>${message}</p>
                    <button class="btn btn-primary" onclick="loadHistoryData()" style="margin-top: var(--space-md);">
                        <i class="fas fa-refresh"></i> Try Again
                    </button>
                </div>`;
        }

        window.changePage = changePage;
    </script>
@endsection
