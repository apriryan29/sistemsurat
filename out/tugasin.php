<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['kategori'] === 'tugas individu') {

    //data induk
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

    //data tambahan
    $keperluan = $_POST['keperluan'];
    $waktu = $_POST['waktu'];
    $petugas = $_POST['petugas'];
    $jabatan = $_POST['jabatan'];
    $keterangan = $_POST['keterangan'];

    $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';


    //memasukan data ke tb_keluar
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

    //eksekusi data
    if ($stmt->execute()){
        //ambil id keluar
        $id_keluar = $stmt->insert_id;

        //masukan detail ke tb_tugas
        $stmt2 = $config->prepare(
            "INSERT INTO tb_tugas (id_keluar, keperluan, waktu, petugas, jabatan, keterangan)
            VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt2->bind_param(
            "isssss", $id_keluar, $keperluan, $waktu, $petugas, $jabatan, $keterangan);
        $stmt2->execute();


        //eksekusi
        echo "<script>
            window.location.href = 'suratkeluar.php?success_tugasin=1';
        </script>";
        exit;
    }
    else {
        $errorMsg = "Gagal menyimpan data. Silakan coba lagi.";
    }

    $stmt->close();
}
?>

<!-- Modal untuk Surat Pemberitahuan -->
<!-- Modal for Surat Pemberitahuan -->
<div class="modal fade" id="tugasinModal" tabindex="-1" role="dialog" aria-labelledby="tugasinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tugainsModalLabel">Detail Surat Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
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
                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" class="form-control" name="nomor_surat" id="nomor-surat" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Tentang Perihal</label>
                        <select class="form-control" name="tentang" id="tentang" required>
                            <option value="" disabled selected>Pilih Tentang</option>
                            <?php
                            // Ambil data dari tb_perihal untuk kategori 'pemberitahuan'
                            $sql_perihal = "SELECT id_perihal, tentang FROM tb_perihal WHERE kategori = 'tugas'";
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
                        <label for="tanggal">Tanggal Pelaksanaan</label>
                        <input class="form-control" name="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="keperluan">Keperluan Tugas</label>
                        <textarea class="form-control" name="keperluan" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Tempat Tujuan Tugas</label>
                        <input class="form-control" name="tujuan" id="tujuan" required>
                    </div>
                    <div class="form-group">
                        <label for="waktu">Waktu Pelaksanaan Tugas</label>
                        <input class="form-control" name="waktu" required>
                        <p><i>contoh penulisan waktu (Pukul 09.00 s/d Selesai)</i></p>
                    </div>
                    <div class="form-group">
                        <label for="petugas">Pejabat yang ditugaskan</label>
                        <input class="form-control" name="petugas" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input class="form-control" name="jabatan" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input class="form-control" name="keterangan" required>
                        <p><i>jika tidak ada keterangan berikan tanda (-)</i></p>
                    </div>
                    <div class="form-group">
                        <label for="ttd">Pilih Tanda Tangan</label>
                        <select name="ttd" id="ttd" class="form-control">
                            <option value="Tanpa Tanda Tangan">Tanpa Tanda Tangan</option>
                            <option value="Tanda Tangan Saja">Tanda Tangan Saja</option>
                            <option value="Tanda Tangan dan Cap">Tanda Tangan dan Cap</option>
                        </select>
                    </div>
                    <input type="hidden" name="kategori" value="tugas individu">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>