<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

// Memeriksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['kategori'] === 'pemberitahuan') {
    
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
    $isi            = $_POST['isi'];

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

        // Insert detail ke tb_pemberitahuan
        $stmt2 = $config->prepare("
            INSERT INTO tb_pemberitahuan (id_keluar, lampiran, isi)
            VALUES (?, ?, ?)
        ");
        $stmt2->bind_param("iss", $id_keluar, $lampiran, $isi);
        $stmt2->execute();
        

        // Redirect setelah berhasil menyimpan
        echo "<script>
            window.location.href = 'suratkeluar.php?success_pemberitahuan=1';
        </script>";
        exit;
        
    } else {
        $errorMsg = "Gagal menyimpan data. Silakan coba lagi.";
    }

    $stmt->close();
}
?>

<!-- Modal untuk Surat Pemberitahuan -->
<div class="modal fade" id="pemberitahuanModal" tabindex="-1" role="dialog" aria-labelledby="pemberitahuanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pemberitahuanModalLabel">Detail Surat Pemberitahuan</h5>
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
                        <select class="form-control" name="kode_surat" id="kode-surat" required>
                            <option value="" disabled selected>Pilih Kode Surat</option>
                            <?php if ($result_kode->num_rows > 0): ?>
                                <?php while ($row = $result_kode->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['kode_surat']); ?>">
                                        <?php echo htmlspecialchars($row['pokok_kode']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="">Tidak ada kode surat</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Tentang Perihal Pemberitahuan</label>
                        <select class="form-control" name="tentang" id="tentang" required>
                            <option value="" disabled selected>Pilih Tentang</option>
                            <?php
                            // Ambil data dari tb_perihal untuk kategori 'pemberitahuan'
                            $sql_perihal = "SELECT id_perihal, tentang FROM tb_perihal WHERE kategori = 'pemberitahuan'";
                            $result_perihal = $config->query($sql_perihal);
                            if ($result_perihal->num_rows > 0) {
                                while ($row = $result_perihal->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['id_perihal']) . '">' 
                                    . htmlspecialchars($row['tentang']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Dibuat</label>
                        <input class="form-control" name="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Dikirim Kepada</label>
                        <input type="text" class="form-control" name="tujuan" placeholder="Masukkan Tujuan" required>
                    </div>
                    <div class="form-group">
                        <label for="lampiran">Lampiran Surat</label>
                        <input type="text" class="form-control" name="lampiran" placeholder="Masukkan Jumlah Lampiran" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Surat</label>
                        <textarea class="form-control" name="isi" placeholder="Masukkan Isi Surat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ttd">Pilih Tanda Tangan</label>
                        <select name="ttd" id="ttd" class="form-control">
                            <option value="Tanpa Tanda Tangan">Tanpa Tanda Tangan</option>
                            <option value="Tanda Tangan Saja">Tanda Tangan Saja</option>
                            <option value="Tanda Tangan dan Cap">Tanda Tangan dan Cap</option>
                        </select>
                    </div>
                    <input type="hidden" name="kategori" value="pemberitahuan">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
