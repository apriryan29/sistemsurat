<?php 
include './include/config.php';

// Ambil data dari tabel tb_kode
$sql_kode = "SELECT id_kode, kode_surat, pokok_kode FROM tb_kode";
$result_kode = $config->query($sql_kode);

?>

<!-- Modal untuk Surat Keputusan -->
<div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-labelledby="skModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="skModalLabel">Detail Surat Keputusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="layoutsurat/cetak_sk.php">
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
                        <label for="tentang">Tentang Perihal SK</label>
                        <select class="form-control" name="tentang" id="tentang" required>
                            <option value="" disabled selected>Pilih Tentang</option>
                            <?php
                            // Ambil data dari tb_perihal untuk kategori 'sk'
                            $sql_perihal = "SELECT id_perihal, tentang FROM tb_perihal WHERE kategori = 'sk'";
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
                        <label for="isi">Ketetapan Poin Pertama</label>
                        <textarea type="text" class="form-control" name="isi" placeholder="Masukkan Ketetapan Surat Keputusan" required></textarea>
                    </div>
                    <input type="hidden" name="kategori" value="sk">
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
    const nomorSurat = "001/IV.4/" + selectedKode + "/" + currentYear;

    // Update input nomor_surat
    nomorSuratInput.value = nomorSurat;
}
</script>