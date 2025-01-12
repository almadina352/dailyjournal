<?php
// Update profile logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];

    // Update password jika diisi
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']); // Hash password dengan MD5
        $sql = "UPDATE user SET password='$password' WHERE username='$username'";
        mysqli_query($conn, $sql);
    }

    // Update foto profil jika file diupload
    if (!empty($_FILES['foto']['name'])) {
        $foto_name = time() . '_' . $_FILES['foto']['name']; // Rename file dengan timestamp
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_folder = "img/" . $foto_name;

        // Pindahkan file ke folder img
        if (move_uploaded_file($foto_tmp, $foto_folder)) {
            $sql = "UPDATE user SET foto='$foto_name' WHERE username='$username'";
            mysqli_query($conn, $sql);
        }
    }

    // Redirect setelah update
  if (isset($update_status)){
    header("location:admin.php?page=profile&status=success");
  } else {
    header ("location:admin.php?page=profile");
  }
    exit;
}

// Ambil data user dari database
$sql = "SELECT * FROM user WHERE username = '".$_SESSION['username']."'";
$hasil = $conn->query($sql);
if ($hasil->num_rows > 0) {
    $user = $hasil->fetch_assoc();
} else {
    echo "User  tidak ditemukan!";
}
?>


<div class="profile">
            <h3>Profil</h3>
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Profil berhasil diperbarui!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="password" class="form-label">Ganti Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Ganti Foto Profil</label>
                            <input class="form-control" type="file" id="foto" name="foto">
                        </div>
                        <div class="col-md-6">
                    <label class="form-label">Foto Profil Saat Ini</label><br>
                    <!-- tampilkan foto -->
                    <img src="img/<?= $user['foto'] ?>" alt="Foto Profil" width="200">
                    <br><br>
                </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-secondary mb-3 shadow" style="max-width: 18rem;">Simpan</button>
                        </div>
                    </form>
                    <br>
                </div>
                
            </div>
</section>