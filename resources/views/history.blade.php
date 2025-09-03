@extends('layouts.app')

@section('content')
<div class="history-content">
    <!-- Header -->
    <div class="history-header">
        <h1 class="history-title">Historical Records</h1>
        <p class="history-subtitle">Review past water quality measurements and trends over time</p>
    </div>
    
    <!-- Filter Controls -->
    <div class="filter-controls">
        <button class="filter-btn active">Today</button>
        <button class="filter-btn">Last 7 Days</button>
        <button class="filter-btn">Last 30 Days</button>
        <button class="filter-btn">Custom Range</button>
    </div>
    
    <!-- Historical Records Grid -->
    <div class="history-grid">
        <!-- Sample Record 1 -->
        <div class="card history-card">
            <div class="card-header">
                <span class="card-date">September 2, 2025</span>
                <span class="card-time">14:30:45</span>
            </div>
            
            <div class="parameter-grid">
                <div class="parameter-item">
                    <div class="parameter-value ph-value">7.2</div>
                    <div class="parameter-label">pH Level</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value temp-value">28.5°C</div>
                    <div class="parameter-label">Temperature</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value turbidity-value">15.2 NTU</div>
                    <div class="parameter-label">Turbidity</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value">0.5 mS/cm</div>
                    <div class="parameter-label">Conductivity</div>
                </div>
            </div>
            
            <div class="quality-score">
                <div class="quality-value">85%</div>
                <div class="quality-label">Water Quality Score</div>
            </div>
            
            <div class="status-badge status-success">
                <i class="fas fa-check-circle"></i>
                Good Quality
            </div>
        </div>
        
        <!-- Sample Record 2 -->
        <div class="card history-card">
            <div class="card-header">
                <span class="card-date">September 2, 2025</span>
                <span class="card-time">13:15:22</span>
            </div>
            
            <div class="parameter-grid">
                <div class="parameter-item">
                    <div class="parameter-value ph-value">7.1</div>
                    <div class="parameter-label">pH Level</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value temp-value">27.8°C</div>
                    <div class="parameter-label">Temperature</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value turbidity-value">12.8 NTU</div>
                    <div class="parameter-label">Turbidity</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value">0.4 mS/cm</div>
                    <div class="parameter-label">Conductivity</div>
                </div>
            </div>
            
            <div class="quality-score">
                <div class="quality-value">88%</div>
                <div class="quality-label">Water Quality Score</div>
            </div>
            
            <div class="status-badge status-success">
                <i class="fas fa-check-circle"></i>
                Excellent Quality
            </div>
        </div>
        
        <!-- Sample Record 3 -->
        <div class="card history-card">
            <div class="card-header">
                <span class="card-date">September 2, 2025</span>
                <span class="card-time">12:00:18</span>
            </div>
            
            <div class="parameter-grid">
                <div class="parameter-item">
                    <div class="parameter-value ph-value">6.9</div>
                    <div class="parameter-label">pH Level</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value temp-value">26.5°C</div>
                    <div class="parameter-label">Temperature</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value turbidity-value">18.5 NTU</div>
                    <div class="parameter-label">Turbidity</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value">0.6 mS/cm</div>
                    <div class="parameter-label">Conductivity</div>
                </div>
            </div>
            
            <div class="quality-score">
                <div class="quality-value">72%</div>
                <div class="quality-label">Water Quality Score</div>
            </div>
            
            <div class="status-badge status-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Moderate Quality
            </div>
        </div>
        
        <!-- Sample Record 4 -->
        <div class="card history-card">
            <div class="card-header">
                <span class="card-date">September 1, 2025</span>
                <span class="card-time">16:45:33</span>
            </div>
            
            <div class="parameter-grid">
                <div class="parameter-item">
                    <div class="parameter-value ph-value">7.4</div>
                    <div class="parameter-label">pH Level</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value temp-value">29.2°C</div>
                    <div class="parameter-label">Temperature</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value turbidity-value">9.8 NTU</div>
                    <div class="parameter-label">Turbidity</div>
                </div>
                
                <div class="parameter-item">
                    <div class="parameter-value">0.3 mS/cm</div>
                    <div class="parameter-label">Conductivity</div>
                </div>
            </div>
            
            <div class="quality-score">
                <div class="quality-value">92%</div>
                <div class="quality-label">Water Quality Score</div>
            </div>
            
            <div class="status-badge status-success">
                <i class="fas fa-check-circle"></i>
                Excellent Quality
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="pagination">
        <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
    </div>
    
    <!-- Footer -->
    <div class="dashboard-footer">
        <p>&copy; 2025 AquaMonitor Water Quality System. Historical data analysis and monitoring.</p>
    </div>
</div>

<style>
    .history-content {
        padding: var(--space-xl) 0;
    }
    
    .history-header {
        text-align: center;
        margin-bottom: var(--space-3xl);
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
    
    .filter-controls {
        display: flex;
        justify-content: center;
        gap: var(--space-md);
        margin-bottom: var(--space-2xl);
        flex-wrap: wrap;
    }
    
    .filter-btn {
        background: var(--surface-color);
        border: 2px solid var(--border-color);
        padding: var(--space-sm) var(--space-lg);
        border-radius: var(--border-radius-md);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: var(--font-weight-medium);
    }
    
    .filter-btn:hover,
    .filter-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .history-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: var(--space-lg);
        margin-bottom: var(--space-3xl);
    }
    
    .history-card {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .history-card:hover {
        transform: translateY(-4px);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-md);
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
    
    .parameter-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-md);
        margin-bottom: var(--space-lg);
    }
    
    .parameter-item {
        text-align: center;
        padding: var(--space-md);
        background: rgba(236, 240, 241, 0.3);
        border-radius: var(--border-radius-md);
    }
    
    .parameter-value {
        font-size: 1.2rem;
        font-weight: var(--font-weight-semibold);
        margin-bottom: var(--space-xs);
    }
    
    .parameter-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .quality-score {
        text-align: center;
        padding: var(--space-md);
        background: rgba(39, 174, 96, 0.1);
        border-radius: var(--border-radius-md);
        margin-bottom: var(--space-md);
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
        background: var(--surface-color);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .page-btn:hover,
    .page-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .no-records {
        text-align: center;
        color: var(--text-secondary);
        padding: var(--space-3xl);
        font-style: italic;
    }
    
    /* Color variations for parameter values */
    .ph-value { color: #3498DB; }
    .temp-value { color: #27AE60; }
    .turbidity-value { color: #F39C12; }
    
    @media (max-width: 768px) {
        .history-grid {
            grid-template-columns: 1fr;
            gap: var(--space-md);
        }
        
        .history-title {
            font-size: 2rem;
        }
        
        .parameter-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-controls {
            flex-direction: column;
            align-items: center;
        }
        
        .filter-btn {
            width: 200px;
        }
    }
</style>

<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const pageBtns = document.querySelectorAll('.page-btn');
        
        // Filter button click handler
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Simulate loading new data
                simulateFilterChange(this.textContent.trim());
            });
        });
        
        // Pagination button click handler
        pageBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.querySelector('i')) { // Don't change for arrow buttons
                    pageBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
        
        function simulateFilterChange(filter) {
            console.log('Filter changed to:', filter);
            // In a real application, this would fetch new data from the server
        }
    });
</script>
@endsection
