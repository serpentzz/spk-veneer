<?php 
$servername = "localhost";
$username = "root";
$password = "";
$db = "topsisv";

$conn = mysqli_connect($servername, $username, $password, $db);

// cek koneksi database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }

    return $rows;
}

function upload() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // Check if no file is uploaded
    if ($error === 4) {
        echo "<script>
                alert('Pilih gambar terlebih dahulu');
              </script>";
        return false;
    }

    // Check if the uploaded file is an image
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('Ini bukan gambar!');
              </script>";
        return false;
    }

    // Check if the file size is too large
    if ($ukuranFile > 1000000) { // 1MB limit
        echo "<script>
                alert('Ukuran gambar terlalu besar');
              </script>";
        return false;
    }

    // Passed all checks, file is ready to be uploaded
    // Generate a new unique name for the image
    // $namaFileBaru = uniqid() . '.' . $ekstensiGambar;

    // Move the uploaded file to the target directory
    // move_uploaded_file($tmpName, 'img/' . $namaFileBaru);
    move_uploaded_file($tmpName, 'img/' . $namaFile);
    // return $namaFileBaru;
    return $namaFile;
}    


?>