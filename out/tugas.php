<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

?>

<!-- Modal untuk Surat Pemberitahuan -->
<div class="modal fade" id="tugasModal" tabindex="-1" role="dialog" aria-labelledby="tugasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tugasModalLabel">Detail Surat Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="layoutsurat/cetak_tugas.php">
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
                        <select class="form-control" name="tentang" id="tentang" required>
                            <option value="" disabled selected>Pilih Tentang</option>
                            <?php
                            // Ambil data dari tb_perihal untuk kategori 'sk'
                            $sql_perihal = "SELECT id_perihal, tentang FROM tb_perihal WHERE kategori = 'tugas'";
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
                        <label for="tanggal">Tanggal Pelaksanaan</label>
                        <input class="form-control" name="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Keperluan Tugas</label>
                        <textarea class="form-control" name="tentang" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tempat">Tempat Tujuan Tugas</label>
                        <input class="form-control" name="tentang" required>
                    </div>
                    <div class="form-group">
                        <label for="waktu">Waktu Pelaksanaan Tugas</label>
                        <input class="form-control" name="waktu" required>
                        <p><i>contoh penulisan waktu (Pukul 09.00 s/d Selesai)</i></p>
                    </div>
                    <div class="form-group">
                        <label for="petugas">Nama Yang diberi Tugas 1</label>
                        <input type="text" class="form-control" name="petugas" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan petugas 1</label>
                        <input type="text" class="form-control" name="jabatan" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas1">Nama Yang diberi Tugas 2</label>
                        <input type="text" class="form-control" name="petugas1" required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan1">Jabatan petugas 2</label>
                        <input type="text" class="form-control" name="jabatan1" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas2">Nama Yang diberi Tugas 3</label>
                        <input type="text" class="form-control" name="petugas2">
                    </div>
                    <div class="form-group">
                        <label for="jabatan3">Jabatan petugas 3</label>
                        <input type="text" class="form-control" name="jabatan3" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas3">Nama Yang diberi Tugas 4</label>
                        <input type="text" class="form-control" name="petugas3">
                    </div>
                    <div class="form-group">
                        <label for="jabatan4">Jabatan petugas 4</label>
                        <input type="text" class="form-control" name="jabatan4" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas4">Nama Yang diberi Tugas 5</label>
                        <input type="text" class="form-control" name="petugas4">
                    </div>
                    <div class="form-group">
                        <label for="jabatan5">Jabatan petugas 5</label>
                        <input type="text" class="form-control" name="jabatan5" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas5">Nama Yang diberi Tugas 6</label>
                        <input type="text" class="form-control" name="petugas5">
                    </div>
                    <div class="form-group">
                        <label for="jabatan6">Jabatan petugas 6</label>
                        <input type="text" class="form-control" name="jabatan6" required>
                    </div>
                    <div class="form-group">
                        <label for="petugas6">Nama Yang diberi Tugas 7</label>
                        <input type="text" class="form-control" name="petugas6">
                    </div>
                    <div class="form-group">
                        <label for="petugas7">Nama Yang diberi Tugas 8</label>
                        <input type="text" class="form-control" name="petugas7">
                    </div>
                    <div class="form-group">
                        <label for="petugas8">Nama Yang diberi Tugas 9</label>
                        <input type="text" class="form-control" name="petugas8">
                    </div>
                    <div class="form-group">
                        <label for="petugas9">Nama Yang diberi Tugas 10</label>
                        <input type="text" class="form-control" name="petugas9">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan Lain</label>
                        <input type="text" class="form-control" name="keterangan">
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