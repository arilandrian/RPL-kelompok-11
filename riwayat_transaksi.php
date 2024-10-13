<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Sesuaikan dengan file CSS yang ada -->
</head>

<body>
    <div class="admin-container">
        <h2>Riwayat Transaksi</h2>
        <div class="filter-buttons">
            <button onclick="loadRiwayatTransaksi('today')" class="<?php echo ($filter === 'today') ? 'active' : ''; ?>">Hari Ini</button>
            <button onclick="loadRiwayatTransaksi('month')" class="<?php echo ($filter === 'month') ? 'active' : ''; ?>">Bulan Ini</button>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>No Meja</th>
                <th>Daftar Masakan</th>
                <th>Total Harga</th>
                <th>Waktu Pesan</th>
            </tr>
        </table>
</body>

</html>