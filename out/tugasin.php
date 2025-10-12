<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

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
                <form method="POST" action="layoutsurat/cetak_tugasin.php">
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
                        <label for="tentang">Tentang Perihal SK</label>
                        <input type="text" class="form-control" name="tentang" id="tentang" required>
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
                        <label for="tempat">Tempat Tujuan Tugas</label>
                        <input class="form-control" name="tempat" required>
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
                        <p><i>contoh penulisan waktu (Pukul 09.00 s/d Selesai)</i></p>
                    </div>
                    <input type="hidden" name="kategori" value="tugas">
                    <div class="modal-footer">
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
    
    const selectedKode = kodeSuratSelect.value;
    const currentYear = new Date().getFullYear();
    const nomorSurat = "011/IV.4/" + selectedKode + "/" + currentYear;

    nomorSuratInput.value = nomorSurat;
}
</script>