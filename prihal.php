<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}
require_once 'include/functions.php';
require_once 'include/config.php';

// Tambah Data
if (isset($_POST['perihal'])) {
    $tentang = $_POST['tentang'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $pembuka = $_POST['pembuka'];
    $isi = isset($_POST['isi']) ? $_POST['isi'] : '';
    $penutup = $_POST['penutup'];
    $memperhatikan = isset($_POST['memperhatikan']) ? $_POST['memperhatikan'] : '';
    $menimbang = isset($_POST['menimbang']) ? $_POST['menimbang'] : '';
    $mengingat = isset($_POST['mengingat']) ? $_POST['mengingat'] : '';
    $menetapkan_2 = isset($_POST['menetapkan_2']) ? $_POST['menetapkan_2'] : '';
    $menetapkan_3 = isset($_POST['menetapkan_3']) ? $_POST['menetapkan_3'] : '';
    $menetapkan_4 = isset($_POST['menetapkan_4']) ? $_POST['menetapkan_4'] : '';
    $menetapkan_5 = isset($_POST['menetapkan_5']) ? $_POST['menetapkan_5'] : '';

    $stmt = $config->prepare("INSERT INTO tb_perihal (tentang, judul, kategori, pembuka, isi, penutup, memperhatikan, menimbang, mengingat, menetapkan_2, menetapkan_3, menetapkan_4, menetapkan_5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $tentang, $judul, $kategori, $pembuka, $isi, $penutup, $memperhatikan, $menimbang, $mengingat, $menetapkan_2, $menetapkan_3, $menetapkan_4, $menetapkan_5);
    $stmt->execute();
    $stmt->close();
}

// Edit Data
if (isset($_POST['edit_id'])) {
    $id = $_POST['id_perihal'];
    $tentang = $_POST['tentang'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $pembuka = $_POST['pembuka'];
    $isi = isset($_POST['isi']) ? $_POST['isi'] : '';
    $penutup = $_POST['penutup'];
    $memperhatikan = isset($_POST['memperhatikan']) ? $_POST['memperhatikan'] : '';
    $menimbang = isset($_POST['menimbang']) ? $_POST['menimbang'] : '';
    $mengingat = isset($_POST['mengingat']) ? $_POST['mengingat'] : '';
    $menetapkan_2 = isset($_POST['menetapkan_2']) ? $_POST['menetapkan_2'] : '';
    $menetapkan_3 = isset($_POST['menetapkan_3']) ? $_POST['menetapkan_3'] : '';
    $menetapkan_4 = isset($_POST['menetapkan_4']) ? $_POST['menetapkan_4'] : '';
    $menetapkan_5 = isset($_POST['menetapkan_5']) ? $_POST['menetapkan_5'] : '';

    $stmt = $config->prepare("UPDATE tb_perihal SET tentang=?, judul=?, kategori=?, pembuka=?, isi=?, penutup=?, memperhatikan=?, menimbang=?, mengingat=?, menetapkan_2=?, menetapkan_3=?, menetapkan_4=?, menetapkan_5=? WHERE id_perihal=?");
    $stmt->bind_param("sssssssssssssi", $tentang, $judul, $kategori, $pembuka, $isi, $penutup, $memperhatikan, $menimbang, $mengingat, $menetapkan_2, $menetapkan_3, $menetapkan_4, $menetapkan_5, $id);
    $stmt->execute();
    $stmt->close();
}

// Hapus Data
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $config->prepare("DELETE FROM tb_perihal WHERE id_perihal = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$result = $config->query("SELECT * FROM tb_perihal");

include 'include/header.php';
?>

<main role="main" class="main-content">
<div class="container-fluid">
  <h2 class="mb-4 page-title">Perihal</h2>
    <!-- Pilih kategori -->
    <div class="card shadow mb-4">
        <form action="#">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="example-select">Kategori</label>
                    <select class="form-control" id="example-select">
                        <option selected>Pilih Kategori</option>
                        <option value="undangan">Surat Undangan</option>
                        <option value="tugas">Surat Tugas</option>
                        <option value="sk">Surat Keputusan</option>
                        <option value="pemberitahuan">Surat Pemberitahuan</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" onclick="navigateToPage()">Tambah Perihal</button>
            </div>
        </form>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Modal SK -->
    <div class="modal fade" id="skModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
          <div class="modal-header"><h5>Surat Keputusan</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="redirectToPage()">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_perihal">
            <input type="hidden" name="kategori" value="sk">
            <div class="form-group"><label>Judul</label><input type="text" class="form-control" name="judul" required></div>
            <div class="form-group"><label>Tentang</label><input type="text" class="form-control" name="tentang" required></div>
            <div class="form-group"><label>Pembuka</label><input type="text" class="form-control" name="pembuka" required></div>
            <div class="form-group"><label>Memperhatikan</label><textarea class="form-control" name="memperhatikan"></textarea></div>
            <div class="form-group"><label>Menimbang</label><textarea class="form-control" name="menimbang"></textarea></div>
            <div class="form-group"><label>Mengingat</label><textarea class="form-control" name="mengingat"></textarea></div>
            <div class="form-group"><label>Ketetapan 2</label><textarea class="form-control" name="menetapkan_2"></textarea></div>
            <div class="form-group"><label>Ketetapan 3</label><textarea class="form-control" name="menetapkan_3"></textarea></div>
            <div class="form-group"><label>Ketetapan 4</label><textarea class="form-control" name="menetapkan_4"></textarea></div>
            <div class="form-group"><label>Penutup</label><input type="text" class="form-control" name="penutup" required></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="redirectToPage()">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- Modal Undangan -->
    <div class="modal fade" id="undanganModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
          <div class="modal-header"><h5>Surat Undangan</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="redirectToPage()">&times;</button>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="redirectToPage()">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- Modal Tugas -->
    <div class="modal fade" id="tugasModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
          <div class="modal-header"><h5>Surat Tugas</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="redirectToPage()">&times;</button>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="redirectToPage()">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- Modal Pemberitahuan -->
    <div class="modal fade" id="pemberitahuanModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <form method="POST">
          <div class="modal-header"><h5>Surat Pemberitahuan</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="redirectToPage()">&times;</button>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="redirectToPage()">Tutup</button>
            <button type="submit" class="btn btn-primary" name="perihal">Simpan</button>
          </div>
        </form>
      </div></div>
    </div>

    <!-- ================= TABLE ================= -->
    <h2 class="h5 page-title text-muted mt-4">Daftar Perihal</h2>
    <div class="card shadow">
      <div class="card-body">
        <input type="text" id="searchInput" class="form-control mb-3" placeholder=" Cari data perihal disini..." onkeyup="filterTable()">
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
          $result = $config->query("SELECT * FROM tb_perihal");
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                <td>{$no}</td>
                <td>{$row['judul']}</td>
                <td>{$row['tentang']}</td>
                <td>
                  <a href='#' class='text-info' onclick='edit_id({$row['id_perihal']}, \"{$row['judul']}\", \"{$row['tentang']}\", \"{$row['kategori']}\", \"{$row['pembuka']}\", \"{$row['isi']}\", \"{$row['penutup']}\", \"{$row['memperhatikan']}\", \"{$row['menimbang']}\", \"{$row['mengingat']}\", \"{$row['menetapkan_2']}\", \"{$row['menetapkan_3']}\", \"{$row['menetapkan_4']}\", \"{$row['menetapkan_5']}\")'><i class='fe fe-edit'></i></a>
                  <a href='?delete_id={$row['id_perihal']}' class='text-danger' onclick='return confirm(\"Hapus data?\")'><i class='fe fe-trash-2'></i></a>
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
function edit_id(id, judul, tentang, kategori, pembuka, isi, penutup, memperhatikan, menimbang, mengingat, menetapkan_2, menetapkan_3, menetapkan_4, menetapkan_5) {
    const modal = `#${kategori}Modal`;
    document.querySelector(`${modal} [name='id_perihal']`).value = id;
    if (document.querySelector(`${modal} [name='judul']`)) document.querySelector(`${modal} [name='judul']`).value = judul;
    if (document.querySelector(`${modal} [name='tentang']`)) document.querySelector(`${modal} [name='tentang']`).value = tentang;
    if (document.querySelector(`${modal} [name='pembuka']`)) document.querySelector(`${modal} [name='pembuka']`).value = pembuka;
    if (document.querySelector(`${modal} [name='isi']`)) document.querySelector(`${modal} [name='isi']`).value = isi;
    if (document.querySelector(`${modal} [name='penutup']`)) document.querySelector(`${modal} [name='penutup']`).value = penutup;
    if (document.querySelector(`${modal} [name='memperhatikan']`)) document.querySelector(`${modal} [name='memperhatikan']`).value = memperhatikan;
    if (document.querySelector(`${modal} [name='menimbang']`)) document.querySelector(`${modal} [name='menimbang']`).value = menimbang;
    if (document.querySelector(`${modal} [name='mengingat']`)) document.querySelector(`${modal} [name='mengingat']`).value = mengingat;
    if (document.querySelector(`${modal} [name='menetapkan_2']`)) document.querySelector(`${modal} [name='menetapkan_2']`).value = menetapkan_2;
    if (document.querySelector(`${modal} [name='menetapkan_3']`)) document.querySelector(`${modal} [name='menetapkan_3']`).value = menetapkan_3;
    if (document.querySelector(`${modal} [name='menetapkan_4']`)) document.querySelector(`${modal} [name='menetapkan_4']`).value = menetapkan_4;
    if (document.querySelector(`${modal} [name='menetapkan_5']`)) document.querySelector(`${modal} [name='menetapkan_5']`).value = menetapkan_5;

    const btn = document.querySelector(`${modal} button[name='perihal']`);
    btn.innerText = 'Update';
    btn.name = 'edit_id';
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
        // Clear previous input values
        $(modal).find("input[type=text], textarea").val(""); // Clear inputs
        $(modal).find("button[name='perihal']").text('Simpan').attr('name', 'perihal'); // Reset button name
        $(modal).modal('show'); // Show the modal
    }
}
function redirectToPage() {
    window.location.href = 'prihal.php';
}
</script>
