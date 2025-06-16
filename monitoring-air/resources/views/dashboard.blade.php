@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Dashboard Monitoring Kualitas Air</h1>
    
    <!-- Notifikasi -->
    <div id="alertContainer"></div>

    <!-- Card untuk tips -->
    <div class="row">
        <div class="col-md-12">
            <div id="tips-card" class="card border-info mb-3">
                <div class="card-header">💡 Tips Budidaya Udang Vaname</div>
                <div class="card-body">
                    <h5 id="tips-title" class="card-title">Semua parameter dalam kondisi aman! 🎉</h5>
                    <p id="tips-content" class="card-text">Pastikan Anda tetap memantau kondisi air secara berkala.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card untuk nilai realtime -->
    <div class="row">
        <div class="col-md-3">
            <div id="card-ph" class="card border-primary mb-3">
                <div class="card-header">pH</div>
                <div class="card-body text-primary">
                    <h5 id="ph-value" class="card-title">-</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div id="card-suhu" class="card border-success mb-3">
                <div class="card-header">Suhu (°C)</div>
                <div class="card-body text-success">
                    <h5 id="suhu-value" class="card-title">-</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div id="card-salinitas" class="card border-info mb-3">
                <div class="card-header">Salinitas (ppt)</div>
                <div class="card-body text-info">
                    <h5 id="salinitas-value" class="card-title">-</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div id="card-kekeruhan" class="card border-warning mb-3">
                <div class="card-header">Kekeruhan (NTU)</div>
                <div class="card-body text-warning">
                    <h5 id="kekeruhan-value" class="card-title">-</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik untuk parameter kualitas air -->
    <div class="row mt-5">
        <div class="col-md-6">
            <h5>Grafik pH</h5>
            <canvas id="phChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Grafik Suhu</h5>
            <canvas id="suhuChart"></canvas>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <h5>Grafik Salinitas</h5>
            <canvas id="salinitasChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5>Grafik Kekeruhan</h5>
            <canvas id="kekeruhanChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Data dari database Laravel
    const sensorData = @json($sensorData);

    // Fungsi pemetaan data
    const getLabels = data => data.map(d => new Date(d.created_at).toLocaleTimeString());
    const getDataArray = (data, key) => data.map(d => parseFloat(d[key]));

    // Inisialisasi grafik
    let charts = {
        ph: null,
        suhu: null,
        salinitas: null,
        kekeruhan: null
    };

    // Fungsi untuk membuat grafik
    function createChart(id, label, data, borderColor, bgColor) {
        const ctx = document.getElementById(id).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: getLabels(sensorData),
                datasets: [{
                    label,
                    data,
                    borderColor,
                    backgroundColor: bgColor,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true }
                },
                scales: { y: { beginAtZero: false } }
            }
        });
    }

    // Inisialisasi semua grafik
    function initCharts() {
        charts.ph = createChart('phChart', 'pH', getDataArray(sensorData, 'ph'), '#4CAF50', 'rgba(76, 175, 80, 0.2)');
        charts.suhu = createChart('suhuChart', 'Suhu (°C)', getDataArray(sensorData, 'suhu'), '#FF9800', 'rgba(255, 152, 0, 0.2)');
        charts.salinitas = createChart('salinitasChart', 'Salinitas (ppt)', getDataArray(sensorData, 'salinitas'), '#2196F3', 'rgba(33, 150, 243, 0.2)');
        charts.kekeruhan = createChart('kekeruhanChart', 'Kekeruhan (NTU)', getDataArray(sensorData, 'kekeruhan'), '#9C27B0', 'rgba(156, 39, 176, 0.2)');
    }

    // Fungsi untuk memperbarui nilai kartu
    function updateCards(latestData) {
        const params = ['ph', 'suhu', 'salinitas', 'kekeruhan'];
        params.forEach(param => {
            document.getElementById(`${param}-value`).textContent = latestData[param];

            const element = document.getElementById(`card-${param}`);
            element.classList.remove('border-primary', 'border-warning', 'border-danger', 'border-success');

            let condition = false;
            if (param === 'ph') condition = latestData.ph < 7.5 || latestData.ph > 8.5;
            if (param === 'suhu') condition = latestData.suhu < 28 || latestData.suhu > 32;
            if (param === 'salinitas') condition = latestData.salinitas < 15 || latestData.salinitas > 30;
            if (param === 'kekeruhan') condition = latestData.kekeruhan < 15 || latestData.kekeruhan > 30;

            element.classList.add(condition ? 'border-danger' : 'border-success');
        });
    }

    // Fungsi untuk memperbarui tips
    function updateTips(latestData) {
        let issues = [];
        let tipsTitle = "✅ Semua parameter dalam kondisi aman!";
        let tipsContent = "Pastikan Anda tetap memantau kondisi air secara berkala.";
        let borderClass = "border-info";

        const conditions = {
            "pH terlalu rendah. Tambahkan kapur dolomit.": latestData.ph < 7.5,
            "pH terlalu tinggi. Lakukan penggantian air bertahap.": latestData.ph > 8.5,
            "Suhu terlalu tinggi. Tambahkan aerasi dan hindari pakan berlebih.": latestData.suhu > 32,
            "Suhu terlalu rendah. Gunakan pemanas air.": latestData.suhu < 28,
            "Salinitas terlalu rendah. Tambahkan garam laut perlahan.": latestData.salinitas < 15,
            "Salinitas terlalu tinggi. Tambahkan air tawar bertahap.": latestData.salinitas > 30,
            "Kekeruhan tinggi. Periksa sisa pakan dan kurangi kepadatan tebar.": latestData.kekeruhan > 30
        };

        for (const [msg, condition] of Object.entries(conditions)) {
            if (condition) issues.push(msg);
        }

        if (issues.length) {
            tipsTitle = `⚠️ Ada ${issues.length} parameter tidak baik!`;
            borderClass = issues.length >= 3 ? "border-danger" : "border-warning";
            tipsContent = issues.join(" ");
        }

        document.getElementById("tips-title").textContent = tipsTitle;
        document.getElementById("tips-content").textContent = tipsContent;
        document.getElementById("tips-card").className = `card mb-3 ${borderClass}`;
    }

    // Fungsi untuk mengecek kualitas air dan menampilkan notifikasi
    function checkWaterQuality() {
        $.ajax({
            url: "{{ url('/dashboard/check-water') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                let alertContainer = $("#alertContainer");
                alertContainer.empty();

                if (response.status === "warning") {
                    response.messages.forEach(msg => {
                        alertContainer.append(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${msg}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    });
                } else {
                    alertContainer.append(`
                        <div class="alert alert-success" role="alert">
                            ✅ Semua parameter dalam kondisi normal.
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error("Gagal mengambil data:", error);
            }
        });
    }

    // Fungsi untuk memperbarui data grafik
    function updateCharts() {
        fetch('/api/sensor-data')
            .then(response => response.json())
            .then(sensorData => {
                if (sensorData.length === 0) return;

                const latestData = sensorData[0];
                updateCards(latestData);
                updateTips(latestData);

                const labels = getLabels(sensorData);
                Object.keys(charts).forEach(key => {
                    charts[key].data.labels = labels;
                    charts[key].data.datasets[0].data = getDataArray(sensorData, key);
                    charts[key].update();
                });
            })
            .catch(error => console.error('Error fetching sensor data:', error));
    }

    // Inisialisasi dan update data berkala
    document.addEventListener("DOMContentLoaded", () => {
        initCharts();
        setInterval(updateCharts, 7000);
    });
</script>

@endsection
