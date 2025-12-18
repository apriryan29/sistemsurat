<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['kategori'] === 'sppd') {

    $id_keluar = $_POST['id_keluar'];
    
    if (empty($id_keluar)) {
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
        $tujuan         = $_POST['tempat'];
        $kategori       = $_POST['kategori'];
        $ttd            = $_POST['ttd'];

        //data tambahan
        $pejabat    = $_POST['pejabat'];
        $petugas    = $_POST['pegawai'];
        $jabatan    = $_POST['jabatan'];
        $tempat     = $_POST['tempat'];
        $kendaraan  = $_POST['kendaraan'];
        $berangkat  = $_POST['berangkat'];
        $pulang     = $_POST['pulang'];
        $pengikut   = $_POST['pengikut'];
        $keterangan = $_POST['keterangan'];
        $isi        = $_POST['isi'];

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

            //masukan detail ke tb_sppd
            $stmt2 = $config->prepare(
                "INSERT INTO tb_sppd (id_keluar, pejabat, petugas, jabatan, tempat, kendaraan, berangkat, pulang, pengikut, keterangan, isi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt2->bind_param(
                "issssssssss", 
                $id_keluar, 
                $pejabat, 
                $petugas, 
                $jabatan, 
                $tempat, 
                $kendaraan, 
                $berangkat, 
                $pulang, 
                $pengikut, 
                $keterangan,
                $isi
            );
            $stmt2->execute();

            //eksekusi
            echo "<script>
                window.location.href = 'suratkeluar.php?success_tugasin=1';
            </script>";
            exit;

        } else {
            $errorMsg = "Gagal menyimpan data. Silakan coba lagi.";
        }
    }
    else {
       $kode_surat     = $_POST['kode_surat'];
       $nomor_surat   = $_POST['nomor_surat'];
       $tanggal        = $_POST['tanggal'];
         $tujuan         = $_POST['tempat'];
         $kategori       = $_POST['kategori'];
            $ttd            = $_POST['ttd'];
        //data tambahan
        $pejabat    = $_POST['pejabat'];
        $petugas    = $_POST['pegawai'];
        $jabatan    = $_POST['jabatan'];
        $tempat     = $_POST['tempat'];
        $kendaraan  = $_POST['kendaraan'];
        $berangkat  = $_POST['berangkat'];
        $pulang     = $_POST['pulang'];
        $pengikut   = $_POST['pengikut'];
        $keterangan = $_POST['keterangan'];
        $isi        = $_POST['isi'];
        $status_verifikasi = ($ttd === 'Tanpa Tanda Tangan') ? 'disetujui' : 'menunggu';
        //memperbarui data di tb_keluar
        $stmt = $config->prepare("
            UPDATE tb_keluar 
            SET kode_surat = ?, nomor_surat = ?, tanggal = ?, id_perihal = ?, kategori = ?, tujuan = ?, ttd = ?, status_verifikasi = ?
            WHERE id_keluar = ?
        ");
        $stmt->bind_param(
            "sisissssi",
            $kode_surat, 
            $nomor_surat, 
            $tanggal, 
            $tentang, 
            $kategori,
            $tujuan, 
            $ttd, 
            $status_verifikasi,
            $id_keluar
        );
        //eksekusi data
        if ($stmt->execute()){
            //perbarui detail di tb_sppd
            $stmt2 = $config->prepare(
                "UPDATE tb_sppd 
                SET pejabat = ?, petugas = ?, jabatan = ?, tempat = ?, kendaraan = ?, berangkat = ?, pulang = ?, pengikut = ?, keterangan = ?, isi = ?
                WHERE id_keluar = ?"
            );
            $stmt2->bind_param(
                "ssssssssssi", 
                $pejabat, 
                $petugas, 
                $jabatan, 
                $tempat, 
                $kendaraan, 
                $berangkat, 
                $pulang, 
                $pengikut, 
                $keterangan,
                $isi,
                $id_keluar
            );
            $stmt2->execute();

            //eksekusi
            echo "<script>
                window.location.href = 'suratkeluar.php?success_tugasin=2';
            </script>";
            exit;

        } 
        else {
            $errorMsg = "Gagal memperbarui data. Silakan coba lagi.";
        }
    }
}

$sql_instansi = "SELECT nama_instansi FROM tb_instansi ORDER BY nama_instansi ASC";
$result_instansi = $config->query($sql_instansi);
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
                        <label for="petugas">Pegawai yang diperintah</label>
                        <input type="text" class="form-control" name="pegawai" id="pegawai" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan Pegawai yang diperintah</label>
                        <input type="text" class="form-control" name="jabatan" id="jabatan" required>
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
</script>