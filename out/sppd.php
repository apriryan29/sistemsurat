<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

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
                <form method="POST" action="layoutsurat/cetak_sppd.php">
                    <div class="form-group">
                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" class="form-control" name="nomor_surat" id="nomor-surat" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kode-surat">Pilih Kode Surat</label>
                        <select class="form-control" name="kode_surat" id="kode-surat" required onchange="updateNomorSurat()">
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
                        <input class="form-control" name="tentang" id="tentang" type="text" required>
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
                        <input type="text" class="form-control" name="tempat" id="tempat" required>
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
                    <input type="hidden" name="kategori" value="sppd">
                    <div class="modal-footer">
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