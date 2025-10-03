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
                <form method="POST" action="layoutsurat/cetak_pemberitahuan.php">
                    <div class="form-group">
                        <label for="nomor-surat">Nomor Surat</label>
                        <input type="text" class="form-control" name="nomor_surat" placeholder="Contoh: 001/IV.4/A/2025" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input class="form-control" name="tanggal" type="date" required>
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Tujuan</label>
                        <input type="text" class="form-control" name="tujuan" placeholder="Masukkan Tujuan" required>
                    </div>
                    <div class="form-group">
                        <label for="perihal">Perihal</label>
                        <input type="text" class="form-control" name="perihal" placeholder="Masukkan Perihal" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Surat</label>
                        <textarea class="form-control" name="isi" placeholder="Masukkan Isi Surat" required></textarea>
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