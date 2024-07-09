<?php 
session_start();
include 'functions.php';

// Check if admin is logged in
if (!isset($_SESSION['login']) ){
    header("Location: login.php");
    exit;
}


    $id = $_GET["id"];

    if(hapus($id) > 0) {
        echo "
        <script>
            alert('Data berhasil dihapus!');
            document.location.href = 'list.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Data gagal dihapus');
            document.location.href = 'list.php';
        </script>
        ";
    }
    
    function hapus($id) {
        global $conn;
        $namaFileBaru = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM veneer2 WHERE id=$id"));
        unlink('img/' . $namaFileBaru["gambar"]);
        mysqli_query($conn,"DELETE FROM veneer2 WHERE id=$id");
    
        return mysqli_affected_rows($conn);
        
        
    }
?>