<!-- Modal untuk Surat Keputusan -->
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
                        <input type="text" class="form-control" name="nomor_surat" id="nomor-surat" placeholder="Contoh: 001/IV.4/A/2025" required>
                    </div>
                    <div class="form-group">
                        <label for="kode-surat">Kode Surat</label>
                        <input type="text" class="form-control" name="kode_surat" id="kode-surat" placeholder="Contoh: Kode-001" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input class="form-control" name="tanggal" id="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tentang">Tentang Perihal SK</label>
                        <input class="form-control" name="tentang" id="tentang" type="text" placeholder="Masukkan Prihal Surat Keputusan" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Ketetapan Poin Pertama</label>
                        <textarea class="form-control" name="isi" id="isi" placeholder="Masukkan Ketetapan Surat Keputusan" required></textarea>
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