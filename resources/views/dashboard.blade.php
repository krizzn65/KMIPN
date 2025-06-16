@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold text-primary">📊 Dashboard Monitoring Kualitas Air</h1>

    <!-- Notifikasi -->
    <div id="alertContainer"></div>

    <!-- Tips Card -->
    <div class="row mb-4">
        <div class="col">
            <div id="tips-card" class="card shadow-sm border-info rounded-4">
                <div class="card-header bg-info text-white fw-semibold rounded-top-4">💡 Tips Budidaya Udang Vaname</div>
                <div class="card-body">
                    <h5 id="tips-title" class="card-title fw-bold">Semua parameter dalam kondisi aman! 🎉</h5>
                    <p id="tips-content" class="card-text">Pastikan Anda tetap memantau kondisi air secara berkala.</p>

                    <!-- Tambahan untuk status kualitas air -->
                    <p id="quality-status" class="card-text fw-semibold text-primary mt-2"></p>

                    <!-- Tambahan untuk tips berdasarkan kualitas air -->
                    <ul id="quality-tips" class="card-text text-secondary mt-2 ps-3"></ul>
                </div>
            </div>
        </div>
    </div>


    <!-- Nilai Realtime Cards -->
    <div class="row row-cols-2 row-cols-md-4 g-3">
        <div class="col">
            <div id="card-ph" class="card border-primary shadow-sm rounded-4 h-100">
                <div class="card-header bg-primary text-white fw-semibold">pH</div>
                <div class="card-body text-primary text-center">
                    <h5 id="ph-value" class="card-title display-6">-</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="card-suhu" class="card border-success shadow-sm rounded-4 h-100">
                <div class="card-header bg-success text-white fw-semibold">Suhu (°C)</div>
                <div class="card-body text-success text-center">
                    <h5 id="suhu-value" class="card-title display-6">-</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="card-kekeruhan" class="card border-warning shadow-sm rounded-4 h-100">
                <div class="card-header bg-warning text-dark fw-semibold">Kekeruhan (NTU)</div>
                <div class="card-body text-warning text-center">
                    <h5 id="kekeruhan-value" class="card-title display-6">-</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div id="card-quality" class="card border-info shadow-sm rounded-4 h-100">
                <div class="card-header bg-info text-white fw-semibold">Kualitas Air (%)</div>
                <div class="card-body text-info text-center">
                    <h5 id="quality-value" class="card-title display-6">-</h5>
                </div>
            </div>
        </div>
    </div>




    <!-- Grafik Parameter -->
    <div class="row mt-5 g-4">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header text-black fw-semibold bg-light">📈 Grafik pH</div>
                <div class="card-body">
                    <canvas id="phChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header text-black fw-semibold bg-light">📈 Grafik Suhu</div>
                <div class="card-body">
                    <canvas id="suhuChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header text-black fw-semibold bg-light">📈 Grafik Kekeruhan</div>
                <div class="card-body">
                    <canvas id="kekeruhanChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    let sensorData = @json($sensorData);
    let latestQuality = null; 
    const getLabels = data => data.map(d => new Date(d.created_at).toLocaleTimeString());
    const getDataArray = (data, key) => data.map(d => parseFloat(d[key]));

    let charts = {
        ph: null,
        suhu: null,
        kekeruhan: null
    };

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
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    }

    function initCharts() {
        charts.ph = createChart('phChart', 'pH', getDataArray(sensorData, 'ph'), '#007bff', 'rgba(0, 123, 255, 0.1)');
        charts.suhu = createChart('suhuChart', 'Suhu (°C)', getDataArray(sensorData, 'suhu'), '#28a745', 'rgba(40, 167, 69, 0.1)');
        charts.kekeruhan = createChart('kekeruhanChart', 'Kekeruhan (NTU)', getDataArray(sensorData, 'kekeruhan'), '#ffc107', 'rgba(255, 193, 7, 0.1)');
    }

 

    function updateCards(latestData) {
        const params = ['ph', 'suhu', 'kekeruhan'];

        // Hanya update jika data quality tersedia dan valid
if ('quality' in latestData && latestData.quality !== null) {
    document.getElementById('quality-value').textContent = latestData.quality;
    latestQuality = latestData.quality; // simpan untuk backup
} else if (latestQuality !== null) {
    // fallback pakai data sebelumnya
    document.getElementById('quality-value').textContent = latestQuality;
}


        params.forEach(param => {
            const value = latestData[param] !== undefined ? latestData[param] : "-";
            document.getElementById(`${param}-value`).textContent = value;

            const element = document.getElementById(`card-${param}`);
            element.classList.remove('border-primary', 'border-success', 'border-warning', 'border-danger');

            let isDanger = false;
            if (param === 'ph') isDanger = latestData.ph < 7.5 || latestData.ph > 8.5;
            if (param === 'suhu') isDanger = latestData.suhu < 26 || latestData.suhu > 34;
            if (param === 'kekeruhan') isDanger = latestData.kekeruhan < 5 || latestData.kekeruhan > 43;

            element.classList.add(isDanger ? 'border-danger' : 'border-success');
        });
    }


    function updateTips(latestData) {
    let issues = [];
    let tipsTitle = "✅ Semua parameter dalam kondisi aman!";
    let tipsContent = "Pastikan Anda tetap memantau kondisi air secara berkala.";
    let borderClass = "border-info";

    const conditions = {
        "pH terlalu rendah. Tambahkan kapur dolomit.": latestData.ph < 7.5,
        "pH terlalu tinggi. Lakukan penggantian air bertahap.": latestData.ph > 8.5,
        "Suhu terlalu tinggi. Tambahkan aerasi dan hindari pakan berlebih.": latestData.suhu > 34,
        "Suhu terlalu rendah. Gunakan pemanas air.": latestData.suhu < 26,
        "Kekeruhan tinggi. Periksa sisa pakan dan kurangi kepadatan tebar.": latestData.kekeruhan > 43
    };

    for (const [msg, condition] of Object.entries(conditions)) {
        if (condition) issues.push(msg);
    }

    // Menentukan status kualitas air
    let qualityText = "";
    let qualityTips = [];
    let currentQuality = latestData.quality;

    if (currentQuality !== undefined && currentQuality !== null) {
    if (currentQuality >= 70) {
        qualityText = "🌊 Kualitas air saat ini **baik**. Lanjutkan perawatan seperti biasa.";
    } else if (currentQuality >= 50) {
        qualityText = "⚠️ Kualitas air saat ini **cukup**. Perlu perhatian lebih.";
        qualityTips.push("Periksa warna air secara visual — hindari warna terlalu gelap atau terlalu hijau pekat.");
        qualityTips.push("Kurangi pemberian pakan jika air terlihat kotor atau berbau.");
        qualityTips.push("Tambahkan air bersih secara bertahap, terutama saat cuaca panas.");
    } else {
        qualityText = "🚨 Kualitas air saat ini **kurang baik**. Perlu tindakan cepat!";
        qualityTips.push("Kuras sebagian air tambak dan isi dengan air baru dari sumber bersih.");
        qualityTips.push("Periksa bau air — jika amis busuk, segera lakukan penggantian air bertahap.");
        qualityTips.push("Jika tersedia, tebarkan probiotik atau gunakan bahan alami seperti daun ketapang untuk menetralisir kondisi.");
    }

    // Tambahan: notifikasi tren perubahan
    if (latestQuality !== null && currentQuality !== latestQuality) {
        const diff = currentQuality - latestQuality;
        const trend = diff > 0 ? "meningkat" : "menurun";
        qualityText += ` Kualitas air ${trend} dari sebelumnya (${latestQuality} → ${currentQuality}).`;
    }

    latestQuality = currentQuality;
    }


    // Update judul dan konten tips
    if (issues.length > 0) {
        tipsTitle = `⚠️ ${issues.length} parameter tidak normal!`;
        tipsContent = issues.join(" ");
        borderClass = "border-danger";
    }

    // Render ke HTML
    document.getElementById("tips-title").textContent = tipsTitle;
    document.getElementById("tips-content").textContent = tipsContent;
    document.getElementById("tips-card").className = `card shadow-sm ${borderClass} rounded-4`;

    // Tampilkan status kualitas air
    const qualityStatus = document.getElementById("quality-status");
    qualityStatus.innerHTML = qualityText;

    // Tampilkan tips tambahan jika kualitas air kurang baik
    const qualityTipsList = document.getElementById("quality-tips");
    qualityTipsList.innerHTML = ""; // kosongkan dulu
    qualityTips.forEach(tip => {
        const li = document.createElement("li");
        li.textContent = tip;
        qualityTipsList.appendChild(li);
    });
    }


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
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                ${msg}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    });
                } else {
                    alertContainer.append(`
                        <div class="alert alert-success shadow-sm" role="alert">
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

    function updateCharts() {
        fetch('/api/sensor-data')
            .then(response => response.json())
            .then(data => {
                if (!data.length) return;

                sensorData = data; // <- UPDATE sensorData agar grafik ikut update
                const latest = data[0];

                updateCards(latest);
                updateTips(latest);

                const labels = getLabels(sensorData);
                charts.ph.data.labels = labels;
                charts.suhu.data.labels = labels;
                charts.kekeruhan.data.labels = labels;

                charts.ph.data.datasets[0].data = getDataArray(sensorData, 'ph');
                charts.suhu.data.datasets[0].data = getDataArray(sensorData, 'suhu');
                charts.kekeruhan.data.datasets[0].data = getDataArray(sensorData, 'kekeruhan');

                charts.ph.update();
                charts.suhu.update();
                charts.kekeruhan.update();
            })
            .catch(console.error);
    }

    function applyThemeToCharts() {
        const isDark = document.documentElement.classList.contains('dark');

        const textColor = isDark ? '#ffffff' : '#B4B4B8';
        const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';

        Object.values(charts).forEach(chart => {
            if (!chart) return;
            chart.options.scales.x.ticks.color = textColor;
            chart.options.scales.y.ticks.color = textColor;
            chart.options.scales.x.grid.color = gridColor;
            chart.options.scales.y.grid.color = gridColor;
            chart.options.plugins.legend.labels.color = textColor;
            chart.update();
        });
    }

    let qualityLog = JSON.parse(localStorage.getItem("qualityLog")) || [];
const MAX_LOG = 100;

function fetchWaterQuality() {
    fetch('/check-water')
        .then(response => response.json())
        .then(latestData => {
            if ('quality' in latestData) {
                const quality = parseFloat(latestData.quality);
                if (!isNaN(quality)) {
                    document.getElementById('quality-value').textContent = quality;

                    const status = quality >= 70 ? "Baik" :
                                   quality >= 50 ? "Cukup" : "Kurang Baik";

                    if (qualityLog.length >= MAX_LOG) qualityLog.shift();

                    qualityLog.push({
                        waktu: new Date().toLocaleString(),
                        quality,
                        status
                    });

                    localStorage.setItem("qualityLog", JSON.stringify(qualityLog));
                } else {
                    document.getElementById('quality-value').textContent = "-";
                }
            } else {
                console.warn("Response tidak memiliki field 'quality'", latestData);
                document.getElementById('quality-value').textContent = "-";
            }
        })
        .catch(error => {
            console.error('Gagal mengambil data kualitas air:', error);
            document.getElementById('quality-value').textContent = "-";
        });
}



    // Jalankan saat halaman selesai dimuat
    window.addEventListener('DOMContentLoaded', fetchWaterQuality);

    document.addEventListener("DOMContentLoaded", () => {
        initCharts();
        applyThemeToCharts(); 
        setInterval(updateCharts, 1000);
        checkWaterQuality();
    });
</script>

@endsection
