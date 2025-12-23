<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

// Ambil daftar instansi
$sql_instansi = "SELECT nama_instansi FROM tb_instansi ORDER BY nama_instansi ASC";
$result_instansi = $config->query($sql_instansi);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['kategori'] === 'sppd') {

    $id_keluar = $_POST['id_keluar'];
    $kode_surat = $_POST['kode_surat'];
    $tentang = $_POST['tentang'];
    $tanggal = $_POST['tanggal'];
    $tempat = $_POST['tempat'];
    $kategori = $_POST['kategori'];
    $ttd = $_POST['ttd'];

    // Data tambahan
    $pejabat = $_POST['pejabat'];
    $id_pegawai = intval($_POST['id_pegawai']);
    $kendaraan = $_POST['kendaraan'];
    $berangkat = $_POST['berangkat'];
    $pulang = $_POST['pulang'];
    $pengikut = $_POST['pengikut'];
    $keterangan = $_POST['keterangan'];
    $isi = $_POST['isi'];

    // Ambil pegawai & jabatan
    $qPegawai = mysqli_query($config, "SELECT pegawai, jabatan FROM tb_pegawai WHERE id_pegawai = $id_pegawai");
    $p = mysqli_fetch_assoc($qPegawai);
    $petugas = $p['pegawai'];
    $jabatan = $p['jabatan'];

    // Set status verifikasi
    $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';

    // Simpan instansi baru jika belum ada
    if (!empty($tempat)) {
        $stmtCheck = $config->prepare("SELECT id_instansi FROM tb_instansi WHERE nama_instansi = ?");
        $stmtCheck->bind_param("s", $tempat);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        if ($stmtCheck->num_rows === 0) {
            $stmtInsert = $config->prepare("INSERT INTO tb_instansi (nama_instansi) VALUES (?)");
            $stmtInsert->bind_param("s", $tempat);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
        $stmtCheck->close();
    }

    // Insert baru
    if (empty($id_keluar)) {
        $tahun = date("Y");
        $q = mysqli_query($config, "SELECT MAX(nomor_surat) AS last FROM tb_keluar WHERE kode_surat = '$kode_surat' AND YEAR(tanggal) = '$tahun'");
        $d = mysqli_fetch_assoc($q);
        $nomor_surat = ($d['last']) ? $d['last'] + 1 : 1;

        $stmt = $config->prepare("
            INSERT INTO tb_keluar 
                (kode_surat, nomor_surat, tanggal, id_perihal, kategori, tujuan, ttd, status_verifikasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sisissss", $kode_surat, $nomor_surat, $tanggal, $tentang, $kategori, $tempat, $ttd, $status_verifikasi);

        if ($stmt->execute()) {
            $id_keluar = $stmt->insert_id;

            $stmt2 = $config->prepare("
                INSERT INTO tb_sppd (id_keluar, pejabat, petugas, jabatan, tempat, kendaraan, berangkat, pulang, pengikut, keterangan, isi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt2->bind_param("issssssssss", $id_keluar, $pejabat, $petugas, $jabatan, $tempat, $kendaraan, $berangkat, $pulang, $pengikut, $keterangan, $isi);
            $stmt2->execute();

            echo "<script>window.location.href = 'suratkeluar.php?success_tugasin=1';</script>";
            exit;
        } else {
            $errorMsg = "Gagal menyimpan data. Silakan coba lagi.";
        }

    } else { // Update
        $nomor_surat = $_POST['nomor_surat'];

        $stmt = $config->prepare("
            UPDATE tb_keluar 
            SET kode_surat = ?, nomor_surat = ?, tanggal = ?, id_perihal = ?, kategori = ?, tujuan = ?, ttd = ?, status_verifikasi = ?
            WHERE id_keluar = ?
        ");
        $stmt->bind_param("sisissssi", $kode_surat, $nomor_surat, $tanggal, $tentang, $kategori, $tempat, $ttd, $status_verifikasi, $id_keluar);

        if ($stmt->execute()) {
            $stmt2 = $config->prepare("
                UPDATE tb_sppd 
                SET pejabat = ?, petugas = ?, jabatan = ?, tempat = ?, kendaraan = ?, berangkat = ?, pulang = ?, pengikut = ?, keterangan = ?, isi = ?
                WHERE id_keluar = ?
            ");
            $stmt2->bind_param("ssssssssssi", $pejabat, $petugas, $jabatan, $tempat, $kendaraan, $berangkat, $pulang, $pengikut, $keterangan, $isi, $id_keluar);
            $stmt2->execute();

            echo "<script>window.location.href = 'suratkeluar.php?success_tugasin=2';</script>";
            exit;
        } else {
            $errorMsg = "Gagal memperbarui data. Silakan coba lagi.";
        }
    }
}
?>

<!-- Modal untuk Surat Perintah Perjalanan Dinas -->
<div class="modal fade" id="sppdModal" tabindex="-1" role="dialog" aria-labelledby="sppdModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sppdModalLabel">Detail Surat Perintah Perjalanan Dinas</h5>
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
                            <option value="" disabled selected></option>
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
                        <input class="form-control" name="tanggal" id="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Tentang Perihal SPPD</label>
                        <select class="form-control" disabled>
                            <option>Perjalanan Dinas</option>
                        </select>
                        <input type="hidden" name="tentang" value="1">
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Perjalanan Dinas</label>
                        <input type="text" class="form-control" name="isi" id="isi" required>
                    </div>
                    <div class="form-group">
                        <label for="pejabat">Yang memberikan Tugas</label>
                        <select class="form-control" name="pejabat" id="pejabat" required>
                            <option selected></option>
                            <option value="Kepala Tata Usaha">Kepala Tata Usaha</option>
                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pegawai yang diperintah</label>
                        <select class="form-control" name="id_pegawai" required>
                            <option value="" disabled selected>Pilih Pegawai</option>
                            <?php
                            $q = mysqli_query($config, "SELECT id_pegawai, pegawai FROM tb_pegawai");
                            while ($p = mysqli_fetch_assoc($q)) {
                                echo "<option value='{$p['id_pegawai']}'>
                                        {$p['pegawai']}
                                    </option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tempat">Tempat Tujuan Perjalanan Dinas</label>
                        <input list="list-instansi" type="text" class="form-control" name="tempat" id="tempat" required>
                        
                        <datalist id="list-instansi">
                            <?php while ($row = $result_instansi->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row['nama_instansi']); ?>"></option>
                            <?php endwhile; ?>
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="kendaraan">Kendaraan yang digunakan</label>
                        <select class="form-control" name="kendaraan" id="kendaraan" required>
                            <option selected></option>
                            <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                            <option value="Kendaraan Sekolah">Kendaraan Sekolah</option>
                            <option value="Angkutan Umum">Angkutan Umum</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="berangkat">Pilih tanggal berangkat</label>
                        <input class="form-control" name="berangkat" id="berangkat" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="pulang">Pilih tanggal kembali</label>
                        <input class="form-control" name="pulang" id="pulang" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="pengikut">Isi Pengikut/Pegawai Dinas</label>
                        <input type="text" class="form-control" name="pengikut" id="pengikut" required>
                        <p><i>jika tidak ada pengikut maka isikan dengan simbol (-)</i></p>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Isi Keterangan Lainnya</label>
                        <input type="text" class="form-control" name="keterangan" id="keterangan" required>
                        <p><i>jika tidak ada keterangan lainnya maka isikan dengan simbol (-)</i></p>
                    </div>
                    <div class="form-group">
                        <label for="ttd">Pilih Tanda Tangan</label>
                        <select name="ttd" id="ttd" class="form-control">
                            <option value="Tanpa Tanda Tangan">Tanpa Tanda Tangan</option>
                            <option value="Tanda Tangan Saja">Tanda Tangan Saja</option>
                            <option value="Tanda Tangan dan Cap">Tanda Tangan dan Cap</option>
                        </select>
                    </div>
                    <input type="hidden" name="kategori" value="sppd">
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


<script src="./js/jquery.min.js"></script>
<script src="./js/moment.min.js"></script>
<script src="./js/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="./css/daterangepicker.css" />

<script>
$(function() {
    $('#date-input1').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD-MM-YYYY' // Format tanggal
        }
    });
});

const berangkat = document.getElementById('berangkat');
const pulang = document.getElementById('pulang');
const form = document.getElementById('sppdForm');

// Disable input pulang dulu
pulang.disabled = true;

// Aktifkan pulang jika berangkat sudah diisi
berangkat.addEventListener('change', function() {
    if (this.value) {
        pulang.disabled = false;
        pulang.min = this.value; // minimal pulang = berangkat
    } else {
        pulang.disabled = true;
        pulang.value = ''; // hapus jika sebelumnya sudah diisi
    }
});

// Validasi saat submit
form.addEventListener('submit', function(e) {
    if (!berangkat.value) {
        e.preventDefault();
        alert("Harap isi tanggal berangkat terlebih dahulu!");
        berangkat.focus();
        return;
    }
    if (!pulang.value) {
        e.preventDefault();
        alert("Harap isi tanggal pulang!");
        pulang.focus();
        return;
    }
    if (berangkat.value >= pulang.value) {
        e.preventDefault();
        alert("Tanggal berangkat harus sebelum tanggal pulang!");
        berangkat.focus();
    }
});
</script>