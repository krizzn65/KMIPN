@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"> Riwayat Kualitas Air</h2>

    <!-- Filter Data -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" id="start_date" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" id="end_date" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="ph">pH:</label>
            <select id="ph" class="form-control">
                <option value="all">Semua</option>
                <option value="acid">Asam (pH < 7,5)</option>
                <option value="neutral">Netral (7,5 ≤ pH ≤ 8,5)</option>
                <option value="base">Basa (pH > 8,5)</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="suhu">Suhu (°C):</label>
            <select id="suhu" class="form-control">
                <option value="all">Semua</option>
                <option value="cold">Dingin (T < 28°C)</option>
                <option value="optimal_suhu">Optimal (28 ≤ T ≤ 32°C)</option>
                <option value="hot">Panas (T > 32°C)</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="salinitas">Salinitas (ppt):</label>
            <select id="salinitas" class="form-control">
                <option value="all">Semua</option>
                <option value="low">Rendah (PPT < 15)</option>
                <option value="optimal_salinitas">Optimal (15 ≤ PPT ≤ 30)</option>
                <option value="high">Tinggi (PPT > 30)</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="kekeruhan">Kekeruhan (NTU):</label>
            <select id="kekeruhan" class="form-control">
                <option value="all">Semua</option>
                <option value="clear">Jernih (NTU < 15)</option>
                <option value="optimal_kekeruhan">Optimal (15 ≤ NTU ≤ 30)</option>
                <option value="turbid">Keruh (NTU > 30)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100" onclick="loadHistoryData()">Filter</button>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped table-hover text-center" id="historyTable">
            <thead class="table-dark sticky-top">
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>pH</th>
                    <th>Suhu (°C)</th>
                    <th>Salinitas (ppt)</th>
                    <th>Kekeruhan (NTU)</th>
                </tr>
            </thead>
            <tbody id="historyBody">
                <!-- Data akan dimuat oleh AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function getPHColor(ph) {
    if (ph < 7.5) return "text-danger";  // Asam
    if (ph >= 7.5 && ph <= 8.5) return "text-success";  // Netral
    return "text-primary";  // Basa
}

function getSuhuColor(suhu) {
    if (suhu < 28) return "text-primary";  // Dingin
    if (suhu >= 28 && suhu <= 32) return "text-success";  // Optimal
    return "text-danger";  // Panas
}

function getSalinitasColor(salinitas) {
    if (salinitas < 15) return "text-danger";  // Rendah
    if (salinitas >= 15 && salinitas <= 30) return "text-success";  // Optimal
    return "text-primary";  // Tinggi
}

function getKekeruhanColor(kekeruhan) {
    if (kekeruhan < 15) return "text-danger";  // Jernih
    if (kekeruhan >= 15 && kekeruhan <= 30) return "text-success";  // Optimal
    return "text-primary";  // Keruh
}

function loadHistoryData() {
    let startDate = $("#start_date").val();
    let endDate = $("#end_date").val();
    let ph = $("#ph").val();
    let suhu = $("#suhu").val();
    let salinitas = $("#salinitas").val();
    let kekeruhan = $("#kekeruhan").val();

    $.ajax({
        url: "{{ route('history.filter') }}",
        type: "GET",
        data: {
            start_date: startDate,
            end_date: endDate,
            ph: ph,
            suhu: suhu,
            salinitas: salinitas,
            kekeruhan: kekeruhan
        },
        dataType: "json",
        success: function(response) {
            let tableBody = $("#historyBody");
            tableBody.empty(); // Kosongkan isi tabel sebelum menambahkan data baru
            
            if (response.length === 0) {
                tableBody.append('<tr><td colspan="5">🔍 Data tidak ditemukan</td></tr>');
            } else {
                response.forEach(function(data) {
                    let createdAt = new Date(data.created_at).toLocaleString("id-ID", { timeZone: "Asia/Jakarta" });

                    let row = `
                        <tr>
                            <td>${createdAt}</td>
                            <td class="${getPHColor(data.ph)} fw-bold">${data.ph}</td>
                            <td class="${getSuhuColor(data.suhu)} fw-bold">${data.suhu}°C</td>
                            <td class="${getSalinitasColor(data.salinitas)} fw-bold">${data.salinitas} ppt</td>
                            <td class="${getKekeruhanColor(data.kekeruhan)} fw-bold">${data.kekeruhan} NTU</td>
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

$(document).ready(function() {
    loadHistoryData(); // Load data pertama kali
});

$(document).ready(function() {
    loadHistoryData();
    setInterval(loadHistoryData, 7000);
});
</script>
@endsection
