<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['kategori'] === 'undangan') {

    $id_keluar = $_POST['id_keluar']; // <--- PENENTU MODE !!!

    // =================== MODE TAMBAH ===================
    if (empty($id_keluar)) {
    
        // Data induk
        $kode_surat     = $_POST['kode_surat'];
        $tahun = date("Y");

        // cari nomor terakhir berdasarkan kode_surat dan tahun
        $q = mysqli_query($config, "
            SELECT MAX(nomor_surat) AS last 
            FROM tb_keluar 
            WHERE kode_surat = '$kode_surat'
            AND YEAR(tanggal) = '$tahun'
        ");

        $d = mysqli_fetch_assoc($q);
        $nomor_surat = ($d['last']) ? $d['last'] + 1 : 1;

        $tentang        = $_POST['tentang'];
        $tanggal        = $_POST['tanggal'];
        $tujuan         = $_POST['tujuan'];
        $kategori       = $_POST['kategori'];
        $ttd            = $_POST['ttd'];

        // Data tambahan
        $lampiran       = $_POST['lampiran'];
        $tgl_acara      = $_POST['tanggal1'];
        $waktu          = $_POST['waktu'];
        $tempat         = $_POST['tempat'];

        $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';

        
        // Siapkan query untuk menambahkan data ke tb_keluar
        $stmt = $config->prepare("
                INSERT INTO tb_keluar 
                    (kode_surat, nomor_surat, tanggal, id_perihal, kategori, tujuan, ttd, status_verifikasi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");    

        $stmt->bind_param(
            "sisissss",
            $kode_surat, 
            $nomor_surat, 
            $tanggal, 
            $tentang, 
            $kategori,
            $tujuan, 
            $ttd, 
            $status_verifikasi
        );

        // Eksekusi query dan periksa apakah berhasil
        if ($stmt->execute()) {
            
            // Ambil ID surat induk
            $id_keluar = $stmt->insert_id;

            $stmt2 = $config->prepare("
                INSERT INTO tb_undangan (id_keluar, lampiran, tgl_acara, waktu, tempat)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt2->bind_param("issss", $id_keluar, $lampiran, $tgl_acara, $waktu, $tempat);
            $stmt2->execute();

            // Redirect setelah berhasil menyimpan
            echo "<script>
                window.location.href = 'suratkeluar.php?success_undangan=1';
            </script>";
            exit;

        } else {
            $errorMsg = "Gagal menyimpan data. Silakan coba lagi.";
        }

        $stmt->close();

    }
    
    // =================== MODE UPDATE ===================
    else {

        $kode_surat  = $_POST['kode_surat'];
        $tentang     = $_POST['tentang'];
        $tanggal     = $_POST['tanggal'];
        $tujuan      = $_POST['tujuan'];
        $kategori    = $_POST['kategori'];
        $ttd         = $_POST['ttd'];

        // data tambahan
        $lampiran  = $_POST['lampiran'];
        $tgl_acara = $_POST['tanggal1'];
        $waktu     = $_POST['waktu'];
        $tempat    = $_POST['tempat'];

        $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';

        // ================= UPDATE tb_keluar =================
        $stmt = $config->prepare("
            UPDATE tb_keluar SET
                kode_surat = ?, 
                tanggal = ?, 
                id_perihal = ?, 
                kategori = ?,
                tujuan = ?, 
                ttd = ?, 
                status_verifikasi = ?
            WHERE id_keluar = ?
        ");

        $stmt->bind_param(
            "ssissssi",
            $kode_surat,
            $tanggal,
            $tentang,
            $kategori,
            $tujuan,
            $ttd,
            $status_verifikasi,
            $id_keluar
        );

        $stmt->execute();

        // ================= UPDATE tb_undangan =================
        $stmt2 = $config->prepare("
            UPDATE tb_undangan SET
                lampiran = ?, 
                tgl_acara = ?, 
                waktu = ?, 
                tempat = ?
            WHERE id_keluar = ?
        ");
        $stmt2->bind_param(
            "ssssi",
            $lampiran,
            $tgl_acara,
            $waktu,
            $tempat,
            $id_keluar
        );

        $stmt2->execute();

        echo "<script>window.location.href='suratkeluar.php?update_undangan=1';</script>";
        exit;
    }
}

$sql_instansi = "SELECT nama_instansi FROM tb_instansi ORDER BY nama_instansi ASC";
$result_instansi = $config->query($sql_instansi);


?>

<!-- Modal untuk Surat Pemberitahuan -->
<div class="modal fade" id="undangModal" tabindex="-1" role="dialog" aria-labelledby="undangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undangModalLabel">Detail Surat Undangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" class="form-control" name="nomor_surat" id="nomor-surat" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kode-surat">Pilih Kode Surat</label>
                        <select class="form-control" name="kode_surat" id="kode-surat" required onchange="updateNomorSurat()">
                            <option value="" disabled selected>Pilih Kode Surat</option>
                            <?php if ($result_kode->num_rows > 0): ?>
                                <?php while ($row = $result_kode->fetch_assoc()): ?>
                                    <option value="<?php echo $row['kode_surat']; ?>">
                                        <?php echo $row['pokok_kode']; ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">Tidak ada kode surat</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Tentang Perihal Undangan</label>
                        <select class="form-control" name="tentang" id="tentang" required>
                            <option value="" disabled selected>Pilih Tentang</option>
                            <?php
                            // Ambil data dari tb_perihal untuk kategori 'sk'
                            $sql_perihal = "SELECT id_perihal, tentang FROM tb_perihal WHERE kategori = 'undangan'";
                            $result_perihal = $config->query($sql_perihal);
                            if ($result_perihal->num_rows > 0) {
                                while ($row = $result_perihal->fetch_assoc()) {
                                    echo '<option value="' . $row['id_perihal'] . '">' . $row['tentang'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Buat</label>
                        <input class="form-control" name="tanggal" id="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Dikirim Kepada</label>
                        <input list="list-instansi" type="text" class="form-control" name="tujuan" id="tujuan" placeholder="Masukkan Tujuan" required>
                    
                        <datalist id="list-instansi">
                            <?php while ($row = $result_instansi->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['nama_instansi']); ?>"></option>
                            <?php endwhile; ?>
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="lampiran">Lampiran Surat</label>
                        <input type="text" class="form-control" name="lampiran" id="lampiran" placeholder="Masukkan Jumlah Lampiran" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal1">Tanggal Acara</label>
                        <input class="form-control" name="tanggal1" id="tanggal1" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="waktu">Waktu Acara</label>
                        <input type="text" class="form-control" name="waktu" id="waktu" placeholder="contoh : 09.00 s/d Selesai" required>
                    </div>
                    <div class="form-group">
                        <label for="tempat">Tempat Acara</label>
                        <input type="text" class="form-control" name="tempat" id="tempat" placeholder="Masukkan Tempat Pelaksanaan Acara" required>
                    </div>
                    <div class="form-group">
                        <label for="ttd">Pilih Tanda Tangan</label>
                        <select name="ttd" id="ttd" class="form-control">
                            <option value="Tanpa Tanda Tangan">Tanpa Tanda Tangan</option>
                            <option value="Tanda Tangan Saja">Tanda Tangan Saja</option>
                            <option value="Tanda Tangan dan Cap">Tanda Tangan dan Cap</option>
                        </select>
                    </div>
                    <input type="hidden" name="kategori" value="undangan">
                    <div class="modal-footer">
                        <input type="hidden" name="id_keluar" id="id_keluar">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>