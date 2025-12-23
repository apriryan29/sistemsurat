<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}


require_once 'include/functions.php';
require_once 'include/config.php';

?>

<!-- Memanggil header -->
	<?php 
		include'include/header.php';
	?>

<!-- Konten Utama menu Dashboard -->
<main role="main" class="main-content">
    <h2>Laporan Rekap Dokumen</h2>
	<!-- Tabel data Surat Masuk -->
		<div class="card shadow mb-4">
			<div class="card-body">
				<p class="mb-2"><strong>Cetak Laporan</strong></p>
				<div class="form-group">
				<input type="text" name="datetimes" id="date-input1" class="form-control datetimes" placeholder="Pilih Tanggal"/>
				</div>
				<button type="button" id="printBtn" class="btn btn-primary" name="cetak-laporan">Cetak Laporan</button>
                <button id="loadingBtn" class="btn btn-primary" type="button" disabled style="display: none;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading... </button>
			</div> <!-- /.card-body -->
		</div> <!-- /.card -->
        <!-- Tabel data Dokumen -->
        <div class="row justify-content-center">
            <div class="col-12">
                <h2 class="h5 page-title text-muted">Data Dokumen</h2>
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="searchInput" 
                                        class="form-control" 
                                        placeholder="Cari berkas disini..." 
                                        onkeyup="filterTable()">
                                </div>
                                <!-- table -->
                                <table class="table datatables" id="dataTable-1">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Dokumen</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Loker</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($config, "SELECT * FROM tb_dokumen");
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>" . htmlspecialchars($row['instansi']) . "</td>
                                                    <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                                    <td>" . htmlspecialchars($row['kategori']) . "</td>
                                                    <td>" . htmlspecialchars($row['id_loker']) . "</td>
                                                </tr>";
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
								<!-- tabel diakhiri -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Tabel -->
    </div> <!-- .container-fluid -->
</main>
<!-- Konten Utama menu Dashboard Selesai-->

<!-- memanggil footer -->
	<?php 
		include'include/footer.php';
	?>


<script>
$(function () {
    $('.datetimes').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY',
            separator: ' Sampai '
        },
        opens: 'right',
        drops: 'down'
    });

    $('.datetimes').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' Sampai ' + picker.endDate.format('DD-MM-YYYY'));
    });

    $('.datetimes').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $(document).ready(function() {
        $('#printBtn').on('click', function() {

            // Sembunyikan tombol cetak dan tampilkan tombol loading
            $(this).hide(); // Sembunyikan tombol cetak
            var loadingBtn = $('#loadingBtn');
            loadingBtn.show(); // Tampilkan tombol loading

            var dateRange = $('#date-input1').val(); // Mengambil rentang tanggal dari input
            if (!dateRange) {
                alert('Pilih Tanggal Laporan terlebih dahulu.');
                loadingBtn.hide(); // Sembunyikan loading jika tidak ada tanggal
                $('#printBtn').show(); // Tampilkan kembali tombol cetak
                return;
            }

            // Mengambil tanggal mulai dan akhir
            var dates = dateRange.split(' Sampai ');
            var startDate = moment(dates[0], 'DD-MM-YYYY');
            var endDate = moment(dates[1], 'DD-MM-YYYY');

            // Mengambil data dari setiap baris tabel yang sesuai dengan rentang tanggal
            var dataRows = [];
            $('#dataTable-1 tbody tr').each(function() {
                var rowDate = moment($(this).find('td').eq(2).text(), 'YYYY-MM-DD'); // Tanggal dari kolom ke-5
                if (rowDate.isBetween(startDate, endDate, null, '[]')) { // Memeriksa apakah tanggal dalam rentang
                    var row = {
                        instansi: $(this).find('td').eq(1).text(), // Instansi
                        kategori: $(this).find('td').eq(3).text(), // Kategori
                        tanggal: $(this).find('td').eq(2).text(), // Tanggal
                    };
                    dataRows.push(row);
                }
            });

            // Menampilkan data di jendela baru
            if (dataRows.length === 0) {
                alert('Tidak ada data yang sesuai dengan rentang tanggal yang dipilih.');
                loadingBtn.hide(); // Sembunyikan loading jika tidak ada data
                $('#printBtn').show(); // Tampilkan kembali tombol cetak
                return;
            }
            

            // Tampilkan loading spinner selama beberapa detik sebelum membuka jendela baru
           setTimeout(function () {

                fetch('kop.php')
                    .then(response => response.text())
                    .then(kopSurat => {

                        var printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                            <html>
                            <head>
                                <title>Laporan Dokumen Masuk</title>
                                <style>
                        body {
                            font-family: "Times New Roman", serif;
                            font-size: 12pt;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        th, td {
                            border: 1px solid #000;
                            padding: 6px;
                        }

                        th {
                            text-align: center;
                        }

                        @media print {
                            @page {
                                size: 215.9mm 330.2mm;
                                margin: 20mm;
                                margin-top: 5mm;
                            }

                            body {
                                margin: 0;
                            }

                            tr {
                                page-break-inside: avoid;
                            }

                            .page-break {
                                page-break-before: always;
                            }
                        }
                    </style>
                            </head>
                            <body>
                        `);

                        // ===== KOP SURAT =====
                        printWindow.document.write(kopSurat);

                        // ===== JUDUL LAPORAN =====
                        printWindow.document.write(`
                            <h3 style="text-align:center;">LAPORAN DOKUMEN</h3>
                            <p>Rentang Tanggal: ${dateRange}</p>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Instansi</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `);

                        dataRows.forEach(function (item) {
                            printWindow.document.write(`
                                <tr>
                                    <td>${item.instansi}</td>
                                    <td>${item.kategori}</td>
                                    <td>${item.tanggal}</td>
                                </tr>
                            `);
                        });

                        printWindow.document.write(`
                                </tbody>
                            </table>
                            </body>
                            </html>
                        `);

                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
                        printWindow.close();

                        loadingBtn.hide();
                        $('#printBtn').show();
                    });

            }, 1000);
        });
    });
});
</script>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dataTable-1');
    const trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // Mulai dari 1 untuk menghindari header
        const tds = trs[i].getElementsByTagName('td');
        let match = false;

        // Cek semua kolom yang relevan
        for (let j = 1; j < tds.length - 1; j++) { // -1 untuk menghindari kolom Aksi
            if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
                break;
            }
        }

        trs[i].style.display = match ? '' : 'none'; // Tampilkan atau sembunyikan baris
    }
}
</script>