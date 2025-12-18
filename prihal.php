<?php
session_start();
include 'include/config.php';

// Simpan data perihal
if (isset($_POST['perihal'])) {

    $tentang        = $_POST['tentang'];
    $judul          = $_POST['judul'];
    $kategori       = $_POST['kategori'];
    $pembuka        = $_POST['pembuka'];
    $isi            = $_POST['isi'];
    $penutup        = $_POST['penutup'];
    $memperhatikan  = $_POST['memperhatikan'] ?? '';
    $menimbang      = $_POST['menimbang'] ?? '';
    $mengingat      = $_POST['mengingat'] ?? '';
    $menetapkan_2   = $_POST['menetapkan_2'] ?? '';
    $menetapkan_3   = $_POST['menetapkan_3'] ?? '';
    $menetapkan_4   = $_POST['menetapkan_4'] ?? '';


    $stmt = $config->prepare("
        INSERT INTO tb_perihal 
        (tentang, judul, kategori, pembuka, isi, penutup, memperhatikan, menimbang, mengingat, menetapkan_2, menetapkan_3, menetapkan_4)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssssssss",
        $tentang, $judul, $kategori, $pembuka, $isi, $penutup,
        $memperhatikan, $menimbang, $mengingat, 
        $menetapkan_2, $menetapkan_3, $menetapkan_4
    );

    if ($stmt->execute()) {
        header("Location: prihal.php?add_success=1");
        exit;
    } else {
        die("Gagal menyimpan data perihal! " . $stmt->error);
    }
}



// fungsi untuk update data perihal
if (isset($_POST['edit_id'])) {

    $id             = $_POST['id_perihal'];
    $tentang        = $_POST['tentang'];
    $judul          = $_POST['judul'];
    $kategori       = $_POST['kategori'];
    $pembuka        = $_POST['pembuka'];
    $isi            = $_POST['isi'];
    $penutup        = $_POST['penutup'];
    $memperhatikan  = $_POST['memperhatikan'] ?? '';
    $menimbang      = $_POST['menimbang'] ?? '';
    $mengingat      = $_POST['mengingat'] ?? '';
    $menetapkan_2   = $_POST['menetapkan_2'] ?? '';
    $menetapkan_3   = $_POST['menetapkan_3'] ?? '';
    $menetapkan_4   = $_POST['menetapkan_4'] ?? '';


    $stmt = $config->prepare("
        UPDATE tb_perihal SET 
            tentang = ?, 
            judul = ?,
            kategori = ?,
            pembuka = ?, 
            isi = ?, 
            penutup = ?, 
            memperhatikan = ?, 
            menimbang = ?, 
            mengingat = ?, 
            menetapkan_2 = ?, 
            menetapkan_3 = ?, 
            menetapkan_4 = ?
        WHERE id_perihal = ?
    ");

    $stmt->bind_param(
        "ssssssssssssi",
        $tentang, $judul, $kategori, $pembuka, $isi, $penutup,
        $memperhatikan, $menimbang, $mengingat,
        $menetapkan_2, $menetapkan_3, $menetapkan_4,
        $id
    );

    if ($stmt->execute()) {
        header("Location: prihal.php?update_success=1");
        exit;
    } else {
        die("Gagal update data perihal! " . $stmt->error);
    }
}


// Hapus data perihal
if (isset($_GET['delete_id'])) {

    $id = intval($_GET['delete_id']);

    $stmt = $config->prepare("DELETE FROM tb_perihal WHERE id_perihal = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: prihal.php?delete_success=1");
    exit;
}
?>

<?php include 'include/header.php'?>

<main role="main" class="main-content">
<div class="container-fluid">
  <h2 class="mb-4 page-title">Perihal</h2>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="example-select">Kategori</label>
                <select class="form-control" id="example-select">
                    <option selected>Pilih Kategori</option>
                    <option value="undangan">Surat Undangan</option>
                    <option value="sk">Surat Keputusan</option>
                    <option value="pemberitahuan">Surat Pemberitahuan</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" onclick="navigateToPage()">Tambah Perihal</button>
        </div>
    </div>

    <!-- MODAL SK -->
    <div class="modal fade" id="skModal" tabindex="-1">
      <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" class="form-perihal">
          <div class="modal-header"><h5>Surat Keputusan (SK)</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_perihal">
            <input type="hidden" name="kategori" value="sk">
            <div class="form-group">
              <label>Judul</label>
              <input type="text" class="form-control" name="judul" required>
            </div>
            <div class="form-group">
              <label>Tentang</label>
              <input type="text" class="form-control" name="tentang" required>
            </div>
            <div class="form-group">
              <label>Pembuka</label>
              <input type="text" class="form-control" name="pembuka" required>
            </div>
            <div class="form-group">
              <label>Memperhatikan</label>
              <textarea class="form-control" name="memperhatikan"></textarea>
            </div>
            <div class="form-group">
              <label>Menimbang</label>
              <textarea class="form-control" name="menimbang"></textarea>
            </div>
            <div class="form-group">
              <label>Mengingat</label>
              <textarea class="form-control" name="mengingat"></textarea>
            </div>

            <div class="form-group"><label>Menetapkan 2</label><textarea class="form-control" name="menetapkan_2"></textarea></div>
            <div class="form-group"><label>Menetapkan 3</label><textarea class="form-control" name="menetapkan_3"></textarea></div>
            <div class="form-group"><label>Menetapkan 4</label><textarea class="form-control" name="menetapkan_4"></textarea></div>

            <div class="form-group"><label>Isi (opsional)</label><textarea class="form-control" name="isi"></textarea></div>
            <div class="form-group"><label>Penutup</label><input type="text" class="form-control" name="penutup" required></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <!-- Default tombol untuk insert -->
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
            <!-- Tombol berubah name menjadi edit_id lewat JS saat edit -->
            <button type="submit" class="btn btn-primary d-none" id="skEditBtn" name="edit_id">Update</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- MODAL UNDANGAN -->
    <div class="modal fade" id="undanganModal" tabindex="-1">
      <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" class="form-perihal">
          <div class="modal-header"><h5>Surat Undangan</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_perihal">
            <input type="hidden" name="kategori" value="undangan">
            <div class="form-group"><label>Judul</label><input type="text" class="form-control" name="judul" required></div>
            <div class="form-group"><label>Tentang</label><input type="text" class="form-control" name="tentang" required></div>
            <div class="form-group"><label>Pembuka</label><textarea class="form-control" name="pembuka" required></textarea></div>
            <div class="form-group"><label>Isi</label><textarea class="form-control" name="isi"></textarea></div>
            <div class="form-group"><label>Penutup</label><textarea class="form-control" name="penutup" required></textarea></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
            <button type="submit" class="btn btn-primary d-none" id="undanganEditBtn" name="edit_id">Update</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- MODAL TUGAS -->
    <div class="modal fade" id="tugasModal" tabindex="-1">
      <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" class="form-perihal">
          <div class="modal-header"><h5>Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_perihal">
            <input type="hidden" name="kategori" value="tugas">
            <div class="form-group"><label>Judul</label><input type="text" class="form-control" name="judul" required></div>
            <div class="form-group"><label>Tentang</label><input type="text" class="form-control" name="tentang" required></div>
            <div class="form-group"><label>Pembuka</label><textarea class="form-control" name="pembuka" required></textarea></div>
            <div class="form-group"><label>Isi</label><textarea class="form-control" name="isi"></textarea></div>
            <div class="form-group"><label>Penutup</label><textarea class="form-control" name="penutup" required></textarea></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
            <button type="submit" class="btn btn-primary d-none" id="tugasEditBtn" name="edit_id">Update</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- MODAL PEMBERITAHUAN -->
    <div class="modal fade" id="pemberitahuanModal" tabindex="-1">
      <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" class="form-perihal">
          <div class="modal-header"><h5>Surat Pemberitahuan</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_perihal">
            <input type="hidden" name="kategori" value="pemberitahuan">
            <div class="form-group"><label>Judul</label><input type="text" class="form-control" name="judul" required></div>
            <div class="form-group"><label>Tentang</label><input type="text" class="form-control" name="tentang" required></div>
            <div class="form-group"><label>Pembuka</label><textarea class="form-control" name="pembuka" required></textarea></div>
            <div class="form-group"><label>Isi</label><textarea class="form-control" name="isi"></textarea></div>
            <div class="form-group"><label>Penutup</label><textarea class="form-control" name="penutup" required></textarea></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
            <button type="submit" class="btn btn-primary d-none" id="pemberitahuanEditBtn" name="edit_id">Update</button>
          </div>
        </form>
      </div></div>
    </div>

<!-- pesan sukses -->
<?php if (isset($_GET['add_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Data perihal Surat berhasil disimpan.
    </div>
<?php endif; ?>
<?php if (isset($_GET['update_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Data perihal Surat berhasil diperbarui.
    </div>
<?php endif; ?>
<?php if (isset($_GET['delete_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Data perihal Surat berhasil dihapus.
    </div>
<?php endif; ?>



    <!-- TABLE -->
    <h2 class="h5 page-title text-muted mt-4">Daftar Perihal</h2>
    <div class="card shadow">
      <div class="card-body">
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari data perihal..." onkeyup="filterTable()">
        <table class="table" id="dataTable-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tentang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
          <tbody>
          <?php
          $no = 1;
          $result = $config->query("SELECT * FROM tb_perihal ORDER BY id_perihal DESC");
          while ($row = $result->fetch_assoc()) {
              // create safe edit button using json_encode for each field (prevents JS errors)
              $json_args = [
                $row['id_perihal'],
                $row['judul'],
                $row['tentang'],
                $row['kategori'],
                $row['pembuka'],
                $row['isi'],
                $row['penutup'],
                $row['memperhatikan'],
                $row['menimbang'],
                $row['mengingat'],
                $row['menetapkan_2'],
                $row['menetapkan_3'],
                $row['menetapkan_4'],
              ];
              // build JS argument list
              $js_args = array_map(function($v){ return json_encode($v); }, $json_args);
              $js_args_str = implode(", ", $js_args);

              echo "<tr>
                <td>{$no}</td>
                <td>" . htmlspecialchars($row['judul']) . "</td>
                <td>" . htmlspecialchars($row['tentang']) . "</td>
                <td>
                  <a href='#' class='text-info' onclick='edit_id({$js_args_str})' title='Edit'><i class='fe fe-edit'></i></a>
                  &nbsp;
                  <a href='?delete_id={$row['id_perihal']}' class='text-danger' onclick='return confirm(\"Hapus data?\")' title='Hapus'><i class='fe fe-trash-2'></i></a>
                </td>
              </tr>";
              $no++;
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>

</div>
</main>

<?php include 'include/footer.php'; ?>

<script>
function edit_id(
    id, judul, tentang, kategori, pembuka, isi, penutup,
    memperhatikan, menimbang, mengingat,
    menetapkan_2, menetapkan_3, menetapkan_4
) {
    kategori = (kategori || '').toLowerCase().trim();
    const modal = `#${kategori}Modal`;

    if (!document.querySelector(modal)) {
        alert("Modal untuk kategori '" + kategori + "' tidak ditemukan!");
        return;
    }

    const setVal = (selector, value) => {
        const el = document.querySelector(`${modal} [name='${selector}']`);
        if (el) el.value = value || '';
    };

    setVal('id_perihal', id);
    setVal('judul', judul);
    setVal('tentang', tentang);
    setVal('kategori', kategori);
    setVal('pembuka', pembuka);
    setVal('isi', isi);
    setVal('penutup', penutup);
    setVal('memperhatikan', memperhatikan);
    setVal('menimbang', menimbang);
    setVal('mengingat', mengingat);
    setVal('menetapkan_2', menetapkan_2);
    setVal('menetapkan_3', menetapkan_3);
    setVal('menetapkan_4', menetapkan_4);

    // BUTTON FIX
    const modalEl = document.querySelector(modal);

    modalEl.querySelectorAll("button[name='perihal']").forEach(b => {
        b.classList.add('d-none');
    });

    const editBtn = document.getElementById(kategori + "EditBtn");
    if (editBtn) {
        editBtn.classList.remove('d-none');
    }

    $(modal).modal('show');
}


function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const trs = document.querySelectorAll("#dataTable-1 tbody tr");
    trs.forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

function navigateToPage() {
    const val = document.getElementById('example-select').value;
    if (val) {
        const modal = `#${val}Modal`;
        // Reset form inside chosen modal
        const form = document.querySelector(modal + " form");
        if (!form) {
            alert("Modal belum tersedia: " + modal);
            return;
        }
        form.reset();
        // Ensure id_perihal empty
        const idField = form.querySelector("[name='id_perihal']");
        if (idField) idField.value = "";
        // Reset buttons: show perihal (insert), hide edit button
        form.querySelectorAll("button[name='perihal']").forEach(b => { b.classList.remove('d-none'); });
        const modalId = modal.replace('#', '');
        const editBtn = document.getElementById(modalId + "EditBtn");
        if (editBtn) editBtn.classList.add('d-none');
        // remove any existing hidden edit_id input
        const hidden = form.querySelector("input[name='edit_id']");
        if (hidden) hidden.remove();

        // show modal
        $(modal).modal('show');
    }
}

function redirectToPage() {
    window.location.href = 'perihal.php';
}

// Auto hide alert setelah 3 detik
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.classList.remove('show');
        alert.classList.add('hide');
        setTimeout(() => alert.remove(), 500);
    }
}, 3000);
</script>
