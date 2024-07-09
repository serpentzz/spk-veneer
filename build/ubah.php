<?php
session_start();
include 'functions.php';

$isLogin = (isset($_SESSION['login']) && $_SESSION['login'] === true);

// Check if admin is logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];

// query data veneer berdasarkan id

$vnr = query("SELECT * FROM veneer2 WHERE id = $id")[0];

// cek apakah tombol ubah sudah ditekan atau belum
if (isset($_POST["ubah"])) {

    // cek apakah data berhasil diubah atau tidak
    if (ubah($_POST) > 0) {
        echo "
        <script>
            alert('Data berhasil diubah!');
            document.location.href = 'list.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Data gagal diubah!');
            document.location.href = 'list.php';
        </script>
        ";
    }
}
function ubah($data)
{
    global $conn;

    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $harga = htmlspecialchars($data["harga"]);
    $warna = htmlspecialchars($data["warna"]);
    $pola = htmlspecialchars($data["pola"]);
    $dayaTahan = htmlspecialchars($data["dayaTahan"]);
    $gambarLama = htmlspecialchars($data["gambarLama"]);

    // cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    // query insert data
    $query = "UPDATE veneer2 SET 
                    nama = '$nama',
                    harga ='$harga',
                    warna = '$warna',
                    pola = '$pola',
                    dayaTahan = '$dayaTahan',
                    gambar = '$gambar'
                    WHERE id = $id 
                    ";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SPK VENEER</title>
        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </head>
</head>

<body class="bg-amber-100">
    <header class="bg-amber-950 text-amber-200">
        <nav class="p-5 text-xl shadow md:flex md:item-centers md:justify-between font-medium">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl inline font-bold">
                    🪵 Surya Prima Mandiri
                </h1>
                <span class="text-3xl cursor-pointer mx-2 md:hidden block">
                    <ion-icon name="menu" onclick="Menu(this)"></ion-icon>
                </span>
            </div>
            <ul class="md:flex md:items-center z-[1] md:z-auto md:static absolute bg-amber-950 w-full left-0 md:w-auto md:py-0 md:pl-0 pl-7 md:opacity-100 opacity-0 top-[-400px] transition-all ease-in duration-300">
                <li class="mx-4 my-6 md:my-0">
                    <a href="index.php" class="hover:opacity-50">Beranda</a>
                </li>
                <li class="mx-4 my-6 md:my-0">
                    <a href="hitung.php" class="hover:opacity-50">Hitung</a>
                </li>
                <li class="mx-4 my-6 md:my-0">
                    <a href="list.php" class="hover:opacity-50">Daftar Veneer</a>
                </li>
                <li>
                    <a href="hasil.php" class="hover:opacity-50 hidden">Hasil Perhitungan</a>
                </li>
                <li class="mx-4 my-6 md:my-0">
                    <a href="tentang.php" class="hover:opacity-50">Tentang</a>
                </li>
                <?php
                if ($isLogin) {
                    // Admin is logged in
                    echo '
                        <li class="mx-4 my-6 md:my-0">
                            <a href="logout.php" class="hover:opacity-50">
                                <img src="img/icon/logout.svg" class="h-6 w-6" alt="Logout Icon">
                            </a>
                        </li>
                    ';
                } else {
                    // Admin is not logged in
                    echo '
                        <li class="mx-4 my-6 md:my-0">
                            <a href="login.php" class="hover:opacity-50 ">
                                <img src="img/icon/admin.svg" class="h-6 w-6" alt="Admin Icon">
                            </a>
                        </li>
                    ';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main class="px-8 py-14">
        <form action="" method="post" enctype="multipart/form-data" class="max-w-md mx-auto bg-amber-200 border-4 border-amber-950 p-6 rounded-lg shadow-md">
            <h2 class="text-3xl mb-6 text-center font-bold text-amber-950">
                Ubah data veneer
            </h2>
            <input type="hidden" name="id" value="<?= $vnr["id"]; ?>">
            <input type="hidden" name="gambarLama" value="<?= $vnr["gambar"]; ?>">
            <ul class="space-y-4">
                <li class="mb-4 columns-2">
                    <label for="nama" class="block text-xl text-amber-950 font-bold">Nama</label>
                    <input type="text" name="nama" id="nama" required value="<?= $vnr["nama"]; ?>" class="bg-amber-100 w-full px-3 py-2 border border-amber-950 rounded-md">
                </li>

                <li class="mb-4 columns-2">
                    <label for="harga" class="block text-xl text-amber-950 font-bold mb-2">Harga</label>
                    <p class="text-amber-950 text-xs">(1 untuk sangat murah, <br> 5 untuk sangat mahal)</p>
                    <div id="harga" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                        <label class="ml-2"><input type="radio" name="harga" value="1" <?= ($vnr["harga"] == 1) ? 'checked' : ''; ?>> 1</label>
                        <label class="ml-2"><input type="radio" name="harga" value="2" <?= ($vnr["harga"] == 2) ? 'checked' : ''; ?>> 2</label>
                        <label class="ml-2"><input type="radio" name="harga" value="3" <?= ($vnr["harga"] == 3) ? 'checked' : ''; ?>> 3</label>
                        <label class="ml-2"><input type="radio" name="harga" value="4" <?= ($vnr["harga"] == 4) ? 'checked' : ''; ?>> 4</label>
                        <label class="ml-2"><input type="radio" name="harga" value="5" <?= ($vnr["harga"] == 5) ? 'checked' : ''; ?>> 5</label>
                    </div>
                </li>

                <li class="mb-4 columns-2">
                    <label for="warna" class="block text-xl text-amber-950 font-bold mb-2">Warna</label>
                    <p class="text-amber-950 text-xs">(1 untuk sangat gelap, <br> 5 untuk sangat terang)</p>
                    <div id="warna" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                        <label class="ml-2"><input type="radio" name="warna" value="1" <?= ($vnr["warna"] == 1) ? 'checked' : ''; ?>> 1</label>
                        <label class="ml-2"><input type="radio" name="warna" value="2" <?= ($vnr["warna"] == 2) ? 'checked' : ''; ?>> 2</label>
                        <label class="ml-2"><input type="radio" name="warna" value="3" <?= ($vnr["warna"] == 3) ? 'checked' : ''; ?>> 3</label>
                        <label class="ml-2"><input type="radio" name="warna" value="4" <?= ($vnr["warna"] == 4) ? 'checked' : ''; ?>> 4</label>
                        <label class="ml-2"><input type="radio" name="warna" value="5" <?= ($vnr["warna"] == 5) ? 'checked' : ''; ?>> 5</label>
                    </div>
                </li>

                <li class="mb-4 columns-2">
                    <label for="pola" class="block text-xl text-amber-950 font-bold mb-2">Pola</label>
                    <p class="text-amber-950 text-xs">(1 untuk sangat lurus, <br> 5 untuk sangat bergelombang)</p>
                    <div id="pola" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                        <label class="ml-2"><input type="radio" name="pola" value="1" <?= ($vnr["pola"] == 1) ? 'checked' : ''; ?>> 1</label>
                        <label class="ml-2"><input type="radio" name="pola" value="2" <?= ($vnr["pola"] == 2) ? 'checked' : ''; ?>> 2</label>
                        <label class="ml-2"><input type="radio" name="pola" value="3" <?= ($vnr["pola"] == 3) ? 'checked' : ''; ?>> 3</label>
                        <label class="ml-2"><input type="radio" name="pola" value="4" <?= ($vnr["pola"] == 4) ? 'checked' : ''; ?>> 4</label>
                        <label class="ml-2"><input type="radio" name="pola" value="5" <?= ($vnr["pola"] == 5) ? 'checked' : ''; ?>> 5</label>
                    </div>
                </li>

                <li class="mb-4 columns-2">
                    <label for="dayaTahan" class="block text-xl text-amber-950 font-bold mb-2">Daya Tahan</label>
                    <p class="text-amber-950 text-xs">(1 untuk sangat rendah, <br> 5 untuk sangat tinggi)</p>
                    <div id="dayaTahan" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                        <label class="ml-2"><input type="radio" name="dayaTahan" value="1" <?= ($vnr["dayaTahan"] == 1) ? 'checked' : ''; ?>> 1</label>
                        <label class="ml-2"><input type="radio" name="dayaTahan" value="2" <?= ($vnr["dayaTahan"] == 2) ? 'checked' : ''; ?>> 2</label>
                        <label class="ml-2"><input type="radio" name="dayaTahan" value="3" <?= ($vnr["dayaTahan"] == 3) ? 'checked' : ''; ?>> 3</label>
                        <label class="ml-2"><input type="radio" name="dayaTahan" value="4" <?= ($vnr["dayaTahan"] == 4) ? 'checked' : ''; ?>> 4</label>
                        <label class="ml-2"><input type="radio" name="dayaTahan" value="5" <?= ($vnr["dayaTahan"] == 5) ? 'checked' : ''; ?>> 5</label>
                    </div>
                </li>

                <li class="mb-4 columns-2">
                    <label for="gambar" class="block text-xl text-amber-950 font-bold">Gambar</label>
                    <img src="img/<?= $vnr['gambar']; ?>" alt="Gambar Veneer" class="w-32 h-auto border-2 border-amber-950 cursor-pointer" onclick="openModal('img/<?= $vnr['gambar']; ?>')">
                    <input type="file" name="gambar" id="gambar" class="bg-amber-100 shadow border border-amber-950 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </li>

                <li class="flex items-center justify-center w-full">
                    <a href="list.php" class="transition inline-flex items-center m-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-100 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                        <ion-icon name="chevron-back-outline" class="text-xl mr-1"></ion-icon>
                        Kembali
                    </a>
                    <button type="submit" name="ubah" class="transition inline-flex items-center m-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-100 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                        <ion-icon name="chevron-forward-outline" class="text-xl mr-1"></ion-icon>
                        Ubah data
                    </button>
                </li>
            </ul>
        </form>
    </main>

    <!-- Modal gambar ditekan -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="p-4">
            <div class="relative border-2 border-amber-950 shadow-lg max-w-2xl mx-auto">
                <button class="absolute top-0 right-0 z-10 text-amber-200 hover:bg-amber-200 rounded hover:text-amber-950 hover:border hover:border-amber-950 flex items-center justify-center" onclick="closeModal()">
                    <ion-icon name="close" class="text-3xl"></ion-icon>
                </button>
                <div class="flex justify-center">
                    <img id="modal-img" src="" alt="Gambar Veneer" class="max-w-full max-h-screen">
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-amber-950 text-amber-200 p-4 text-left text-xs">
        <p>&copy; 2013 CV. Surya Prima Mandiri</p>
        <p><ion-icon name="call-outline"></ion-icon> 0812-9935-6751</p>
        <a class="hover:underline " href="https://www.google.com/maps/uv?pb=!1s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e!3m1!7e115!4s%2Fmaps%2Fplace%2Fcv%2Bsurya%2Bprima%2Bmandiri%2F%40-6.2301226%2C106.5690648%2C3a%2C75y%2C237.62h%2C90t%2Fdata%3D*213m4*211e1*213m2*211ss6zTUafsXRy7PONRnqCFWw*212e0*214m2*213m1*211s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e%3Fsa%3DX%26ved%3D2ahUKEwjLvOqu9_WGAxWH-TgGHbdACkMQpx96BAg0EAA!5scv%20surya%20prima%20mandiri%20-%20Google%20Search!15sCgIgAQ&imagekey=!1e2!2ss6zTUafsXRy7PONRnqCFWw&cr=le_a7&hl=en&ved=1t%3A206134&ictx=111">
            <ion-icon name="map-outline"></ion-icon> Jl. Sempur No.131, RT.01/RW.06, Sempur, Kadu, Kec. Curug, Kab. Tangerang, Banten 15810
        </a>
    </footer>

    <script>
        function openModal(src) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal-img').src = src;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal-img').src = '';
        }

        function Menu(e) {
            let list = document.querySelector('ul');

            e.name === 'menu' ? (e.name = 'close',
                list.classList.add('top-[80px]'),
                list.classList.add('opacity-100')) : (
                e.name = "menu", list.classList.remove('top-[80px]'),
                list.classList.remove('opacity-100'));


        }
    </script>
</body>

</html>