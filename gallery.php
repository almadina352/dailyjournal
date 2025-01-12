<div class="container">
    <!-- Button trigger modal for adding an article -->
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Gambar
    </button>

    <div class="row">
        <div class="table-responsive" id="gallery_data">
            <!-- Article data will be loaded here -->
        </div>
        <!-- Awal Modal Tambah-->
        <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Gambar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="floatingTextarea2">user name</label>
                                <textarea class="form-control" id="username" name="username" placeholder="Tuliskan User Name" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput2" class="form-label">Gambar</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="simpan" name="simpan" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Akhir Modal Tambah-->
    </div>
</div>

<?php
include "upload_foto.php";

if (isset($_POST['simpan'])) {  
    $tanggal = date("Y-m-d H:i:s");  
    $username = $_SESSION['username'];  
    $gambar = '';  
    $nama_gambar = $_FILES['gambar']['name'];  
  
    // Jika file diunggah  
    if ($nama_gambar != '') {  
        $cek_upload = upload_foto($_FILES["gambar"]);  
  
        if ($cek_upload['status']) {  
            $gambar = $cek_upload['message'];  
        } else {  
            echo "<script>  
                alert('" . $cek_upload['message'] . "');  
                document.location='admin.php?page=gallery';  
            </script>";  
            die;  
        }  
    }  
  
    if (isset($_POST['id'])) {  
        $id = $_POST['id'];  
  
        if ($nama_gambar == '') {  
            $gambar = $_POST['gambar_lama'];  
        } else {  
            unlink("img/" . $_POST['gambar_lama']);  
        }  
  
        $stmt = $conn->prepare("UPDATE gallery   
                                SET   
                                tanggal = ?,   
                                username = ?,   
                                gambar = ?   
                                WHERE id = ?");  
  
        $stmt->bind_param("sssi", $tanggal, $username, $gambar, $id); // Perbaikan di sini  
        $simpan = $stmt->execute();  
    } else {  
        $stmt = $conn->prepare("INSERT INTO gallery (tanggal, username, gambar) VALUES (?, ?, ?)");  
  
        $stmt->bind_param("sss", $tanggal, $username, $gambar); // Perbaikan di sini  
        $simpan = $stmt->execute();  
    }  
  
    if ($simpan) {  
        echo "<script>  
            alert('Simpan data sukses');  
            document.location='admin.php?page=gallery';  
        </script>";  
    } else {  
        echo "<script>  
            alert('Simpan data gagal');  
            document.location='admin.php?page=gallery';  
        </script>";  
    }  
  
    $stmt->close();  
    $conn->close();  
}  


// If the delete button is clicked
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        // Delete the image file
        unlink("img/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id =?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>
            alert('Hapus data sukses');
            document.location='admin.php?page=gallery';
        </script>";
    } else {
        echo "<script>
            alert('Hapus data gagal');
            document.location='admin.php?page=gallery';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<script>
$(document).ready(function(){
    load_data();
    function load_data(hlm){
        $.ajax({
            url : "gallery_data.php",
            method : "POST",
            data : {
                hlm: hlm
            },
            success : function(data){
                $('#gallery_data').html(data);
            }
        })
    } 
    $(document).on('click', '.halaman', function(){
    var hlm = $(this).attr("id");
    load_data(hlm);
});
});
</script>
