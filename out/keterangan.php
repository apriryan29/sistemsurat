<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);


if($_SERVER['REQUEST_METHOD']=='POST' && $_POST['kategori'] === 'keterangan') {
    
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
    $loker          = $_POST['loker'];
    $ttd            = $_POST['ttd'];

    //data tambahan
    $lahir          = $_POST['lahir'];
    $nis            = $_POST['nis'];
    $sekolah        = $_POST['sekolah'];
    $ortu           = $_POST['ortu'];
    $isi            = $_POST['isi'];

    $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';

    //memasukan data ke tb_keluar
    $stmt = $config->prepare("
            INSERT INTO tb_keluar 
                (kode_surat, nomor_surat, tanggal, id_perihal, kategori, loker, tujuan, ttd, status_verifikasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sisisssss",
        $kode_surat, 
        $nomor_surat, 
        $tanggal, 
        $tentang, 
        $kategori,
        $loker, 
        $tujuan, 
        $ttd, 
        $status_verifikasi
    );

    //eksekusi data
    if ($stmt->execute()){

        //ambil id keluar
        $id_keluar = $stmt->insert_id;

        //masukan detail ke tb_keterangan
        $stmt2 = $config->prepare(
            "INSERT INTO tb_keterangan (id_keluar, ttl, nis, sekolah, ortu, isi)
            VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt2->bind_param(
            "isssss", $id_keluar, $lahir, $nis, $sekolah, $ortu, $isi);

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
}
?>

<!-- Modal untuk Surat Pemberitahuan -->
<div class="modal fade" id="keteranganModal" tabindex="-1" role="dialog" aria-labelledby="keteranganModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keteranganModalLabel">Detail Surat Keterangan Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="tentang"></label>
                        <select class="form-control" disabled>
                            <option>Surat Keterangan</option>
                        </select>
                        <input type="hidden" name="tentang" value="1">
                    </div>
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
                        <label for="tanggal">Tanggal</label>
                        <input class="form-control" name="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Nama Siswa</label>
                        <input type="text" class="form-control" name="tujuan" placeholder="Masukkan Nama Lengkap Siswa" required>
                    </div>
                    <div class="form-group">
                        <label for="lahir">Tempat, tanggal lahir</label>
                        <input class="form-control" name="lahir" placeholder="Masukkan Tempat Tanggal Lahir Siswa" required>
                    </div>
                    <div class="form-group">
                        <label for="nis">Nomor Induk Siswa</label>
                        <input type="text" class="form-control" name="nis" placeholder="Masukkan Nomor Induk Siswa" required>
                    </div>
                    <div class="form-group">
                        <label for="sekolah">Asal Sekolah</label>
                        <input type="text" class="form-control" name="sekolah" placeholder="Masukkan Asal Sekolah" required>
                    </div>
                    <div class="form-group">
                        <label for="ortu">Nama Orang Tua/ Wali</label>
                        <input type="text" class="form-control" name="ortu" placeholder="Masukkan Nama Orang Tua" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Surat Keterangan</label>
                        <textarea type="text" class="form-control" name="isi" placeholder="Masukkan Isi Surat Keterangan" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="loker">Loker Arsip File</label>
                        <select class="form-control" id="loker" name="loker" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $q_loker = mysqli_query($config, "SELECT * FROM tb_loker WHERE kategori_loker = 'Loker Surat Keluar'");
                            while ($data = mysqli_fetch_assoc($q_loker)) {
                                echo "<option value='{$data['loker']}'>{$data['loker']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ttd">Pilih Tanda Tangan</label>
                        <select name="ttd" id="ttd" class="form-control">
                            <option value="Tanpa Tanda Tangan">Tanpa Tanda Tangan</option>
                            <option value="Tanda Tangan Saja">Tanda Tangan Saja</option>
                            <option value="Tanda Tangan dan Cap">Tanda Tangan dan Cap</option>
                        </select>
                    </div>
                    <input type="hidden" name="kategori" value="keterangan">
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

<script>
function updateNomorSurat() {
    const kodeSuratSelect = document.getElementById('kode-surat');
    const nomorSuratInput = document.getElementById('nomor-surat');
    
    // Ambil kode surat yang dipilih
    const selectedKode = kodeSuratSelect.value;

    // Dapatkan tahun saat ini
    const currentYear = new Date().getFullYear();

    // Buat format nomor surat
    const nomorSurat = "011/IV.4/" + selectedKode + "/" + currentYear;

    console.log("Kode Surat yang dipilih:", selectedKode); // Debugging
    console.log("Nomor Surat yang dihasilkan:", nomorSurat); // Debugging

    // Update input nomor_surat
    nomorSuratInput.value = nomorSurat;
}
</script>