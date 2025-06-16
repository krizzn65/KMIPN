@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Riwayat Kualitas Air</h2>

    <!-- Toggle Filter for Mobile and export excel -->
    <div class="row justify-content-between mb-3">
    <div class="col-6 col-md-2 mb-2">
        <button class="btn btn-outline-primary w-100 d-md-none" onclick="toggleFilter()">🔍 Filter</button>
    </div>
    <div class="col-6 col-md-2 mb-2 text-end">
        <button class="btn btn-success w-100" onclick="exportTableToExcel()">⬇️ Export Excel</button>
    </div>
    </div>


    <!-- Filter Data -->
    <div id="filterSection" class="row mb-3">
        <div class="col-md-3 mb-2">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" id="start_date" class="form-control">
        </div>
        <div class="col-md-3 mb-2">
            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" id="end_date" class="form-control">
        </div>
        <div class="col-md-2 mb-2">
            <label for="ph">pH:</label>
            <select id="ph" class="form-control">
                <option value="all">Semua</option>
                <option value="acid">Asam (pH &lt; 7,5)</option>
                <option value="neutral">Netral (7,5 ≤ pH ≤ 8,5)</option>
                <option value="base">Basa (pH &gt; 8,5)</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="suhu">Suhu (°C):</label>
            <select id="suhu" class="form-control">
                <option value="all">Semua</option>
                <option value="cold">Dingin (T &lt; 28°C)</option>
                <option value="optimal_suhu">Optimal (28 ≤ T ≤ 32°C)</option>
                <option value="hot">Panas (T &gt; 32°C)</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="kekeruhan">Kekeruhan (NTU):</label>
            <select id="kekeruhan" class="form-control">
                <option value="all">Semua</option>
                <option value="clear">Jernih (NTU &lt; 15)</option>
                <option value="optimal_kekeruhan">Optimal (15 ≤ NTU ≤ 30)</option>
                <option value="turbid">Keruh (NTU &gt; 30)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end mb-2">
            <button class="btn btn-primary w-100" onclick="loadHistoryData()">Filter</button>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped table-hover text-center" id="historyTable">
           <thead id="dynamicTableHead">
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>pH</th>
                    <th>Suhu (°C)</th>
                    <th>Kekeruhan (NTU)</th>
                    <th>Kualitas Air</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="historyBody">
                <!-- Data akan dimuat oleh AJAX -->
            </tbody>
        </table>
    </div>
</div>

{{-- Dynamic Theme Styling --}}
<style>
    [data-bs-theme="dark"] thead#dynamicTableHead {
        background-color: #007F73;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    [data-bs-theme="light"] thead#dynamicTableHead {
        background-color: #00A9A4;
        color: #fff;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .text-danger {
        color: #dc3545 !important;
    }
    .text-warning {
        color: #ffc107 !important;
    }
    .text-success {
        color: #28a745 !important;
    }

</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function toggleFilter() {
    $("#filterSection").toggle();
}

function getPHColor(ph) {
    if (ph < 7.5) return "text-danger";  // Asam
    if (ph >= 7.5 && ph <= 8.5) return "text-success";  // Netral
    return "text-primary";  // Basa
}

function getSuhuColor(suhu) {
    if (suhu < 26) return "text-primary";  // Dingin
    if (suhu >= 26 && suhu <= 34) return "text-success";  // Optimal
    return "text-danger";  // Panas
}

function getKekeruhanColor(kekeruhan) {
    if (kekeruhan < 3) return "text-danger";  // Jernih
    if (kekeruhan >= 3 && kekeruhan <= 43) return "text-success";  // Optimal
    return "text-primary";  // Keruh
}

function getWaterQualityColor(value) {
    if (value < 30) return "text-danger"; // Buruk (merah)
    if (value >= 30 && value < 70) return "text-warning"; // Cukup (kuning/orange)
    return "text-success"; // Baik (hijau)
}

function getKualitasLabel(value) {
    if (value <= 40) return "Buruk";
    if (value <= 70) return "Sedang";
    return "Baik";
}

    function loadHistoryData() {
    let startDate = $("#start_date").val();
    let endDate = $("#end_date").val();
    let ph = $("#ph").val();
    let suhu = $("#suhu").val();
    let kekeruhan = $("#kekeruhan").val();

    $.ajax({
        url: "{{ route('history.filter') }}",
        type: "GET",
        data: {
            start_date: startDate,
            end_date: endDate,
            ph: ph,
            suhu: suhu,
            kekeruhan: kekeruhan
        },
        dataType: "json",
        success: function(response) {
            let tableBody = $("#historyBody");
            tableBody.empty(); 
            
            if (response.length === 0) {
                tableBody.append('<tr><td colspan="4">🔍 Data tidak ditemukan</td></tr>');
            } else {
               response.forEach(function(data) {
                let createdAt = new Date(data.created_at).toLocaleString("id-ID", { timeZone: "Asia/Jakarta" });

                // Nilai kualitas
                let kualitasValue = data.kualitas;
                let kualitasLabel = getKualitasLabel(kualitasValue);
                let kualitasClass = getKualitasColor(kualitasValue);
                let statusClass = getKualitasColor(kualitasValue);  // status pakai warna yang sama

                let row = `
                    <tr>
                        <td>${createdAt}</td>
                        <td class="${getPHColor(data.ph)} fw-bold">${data.ph}</td>
                        <td class="${getSuhuColor(data.suhu)} fw-bold">${data.suhu}°C</td>
                        <td class="${getKekeruhanColor(data.kekeruhan)} fw-bold">${data.kekeruhan} NTU</td>
                        <td class="fw-bold ${kualitasClass}">${kualitasValue}%</td>
                        <td class="fw-bold ${statusClass}">${kualitasLabel}</td>
                    </tr>
                `;
                tableBody.append(row);
            });

            }
        },
        error: function(xhr, status, error) {
            console.error("Gagal mengambil data:", error);
        }
    });
}

    function getStatusKualitasAir(kualitas) {
        if (kualitas < 40) {
            return "Buruk";
        } else if (kualitas >= 40 && kualitas <= 70) {
            return "Sedang";
        } else if (kualitas > 70) {
            return "Baik";
        } else {
            return "-";
        }
    }



   async function exportTableToExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet("Riwayat");

    // Header
    worksheet.columns = [
        { header: "Tanggal & Waktu", key: "tanggal", width: 25 },
        { header: "pH", key: "ph", width: 10 },
        { header: "Suhu (°C)", key: "suhu", width: 15 },
        { header: "Kekeruhan (NTU)", key: "kekeruhan", width: 20 },
        { header: "Kualitas Air", key: "kualitas_air", width: 20 },
        { header: "Status", key: "status", width: 20 }
    ];

    // Ambil data dari tabel HTML
    const rows = document.querySelectorAll("#historyTable tbody tr");
    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        if (cells.length === 6) {
            const ph = parseFloat(cells[1].innerText);
            const suhu = parseFloat(cells[2].innerText);
            const kekeruhan = parseFloat(cells[3].innerText);
            const kualitasText = cells[4].innerText.replace('%', '').trim();
            const kualitasValue = parseFloat(kualitasText);

            const newRow = worksheet.addRow({
                tanggal: cells[0].innerText,
                ph: ph,
                suhu: suhu,
                kekeruhan: kekeruhan,
                kualitas_air: cells[4].innerText,
                status: cells[5].innerText
            });

            // Warna berdasarkan nilai pH
            const phCell = newRow.getCell("ph");
            if (ph < 7.5) phCell.fill = redFill();
            else if (ph <= 8.5) phCell.fill = greenFill();
            else phCell.fill = blueFill();

            // Warna berdasarkan suhu
            const suhuCell = newRow.getCell("suhu");
            if (suhu < 26) suhuCell.fill = blueFill();
            else if (suhu <= 34) suhuCell.fill = greenFill();
            else suhuCell.fill = redFill();

            // Warna berdasarkan kekeruhan
            const kekeruhanCell = newRow.getCell("kekeruhan");
            if (kekeruhan < 3) kekeruhanCell.fill = redFill();
            else if (kekeruhan <= 43) kekeruhanCell.fill = greenFill();
            else kekeruhanCell.fill = blueFill();

            // Warna berdasarkan kualitas air
            const kualitasCell = newRow.getCell("kualitas_air");
            const statusCell = newRow.getCell("status");
            if (kualitasValue <= 40) {
                kualitasCell.fill = redFill();
                statusCell.fill = redFill();
            } else if (kualitasValue <= 70) {
                kualitasCell.fill = orangeFill();
                statusCell.fill = orangeFill();
            } else {
                kualitasCell.fill = greenFill();
                statusCell.fill = greenFill();
            }

            // Styling umum
            newRow.eachCell(cell => {
                cell.font = { bold: true };
                cell.alignment = { vertical: 'middle', horizontal: 'center' };
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });
        }
    });

    // Export file
    const buffer = await workbook.xlsx.writeBuffer();
    saveAs(new Blob([buffer]), "riwayat_kualitas_air.xlsx");
}

// Fungsi bantu untuk warna cell
function redFill() {
    return {
        type: "pattern",
        pattern: "solid",
        fgColor: { argb: "FFFFC7CE" } // light red
    };
}

function greenFill() {
    return {
        type: "pattern",
        pattern: "solid",
        fgColor: { argb: "FFC6EFCE" } // light green
    };
}

function blueFill() {
    return {
        type: "pattern",
        pattern: "solid",
        fgColor: { argb: "FFBDD7EE" } // light blue
    };
}

function orangeFill() {
    return {
        type: "pattern",
        pattern: "solid",
        fgColor: { argb: "FFFFEB9C" } // light orange/yellow
    };
}

function getKualitasColor(kualitas) {
    if (kualitas <= 40) return "text-danger";      // Buruk
    if (kualitas <= 70) return "text-warning";     // Sedang
    return "text-success";                         // Baik
}

function getStatusColor(kualitas) {
    if (kualitas <= 40) return "text-danger";      // Buruk
    if (kualitas <= 70) return "text-warning";     // Sedang
    return "text-success";                         // Baik
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
    


    $(document).ready(function() {
        loadHistoryData(); // Load pertama kali
        setInterval(loadHistoryData, 1000); // Update berkala
    });
</script>
@endsection
