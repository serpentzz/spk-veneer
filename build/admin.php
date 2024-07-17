<?php
session_start();

include 'functions.php';

$isLogin = (isset($_SESSION['login']) && $_SESSION['login'] === true);

$veneer2 = query("SELECT * FROM veneer2");

// tombol cari ditekan

if (isset($_POST["cari"])) {
    $veneer2 = cari($_POST["keyword"]);
}

function cari($keyword)
{
    $query = "SELECT * FROM veneer2 WHERE nama LIKE '%$keyword%'";
    return query($query);
}

// tambah data
// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["tambah"])) {

    // cek apakah data berhasil ditambahkan atau tidak
    if (tambah($_POST) > 0) {
        echo "
                <script>
                    alert('Data berhasil ditambahkan!');
                    document.location.href = 'admin.php';
                </script>
                ";
    } else {
        echo "
                <script>
                    alert('Data gagal ditambahkan!');
                    document.location.href = 'admin.php';
                </script>
                ";
    }
}

function tambah($data)
{
    global $conn;
    $nama = htmlspecialchars($data["nama"]);
    $harga = htmlspecialchars($data["harga"]);
    $warna = htmlspecialchars($data["warna"]);
    $pola = htmlspecialchars($data["pola"]);
    $dayaTahan = htmlspecialchars($data["dayaTahan"]);
    $gambar = htmlspecialchars($data["gambar"]);

    // upload gambar
    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    // query insert data
    $query = "INSERT INTO veneer2 VALUES
        ('', '$nama', '$harga', '$warna', '$pola', '$dayaTahan', '$gambar')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK VENEER</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="min-h-screen bg-amber-100 font-sans">
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
                <?php
                if ($isLogin) {
                    // Admin is logged in
                    echo '
                            <li class="mx-4 my-6 md:my-0">
                                <a href="admin.php" class="hover:opacity-50">Daftar Veneer</a>
                            </li>
                        ';
                } else {
                    // Admin is not logged in
                    echo '
                            <li class="mx-4 my-6 md:my-0">
                                <a href="list.php" class="hover:opacity-50">Daftar Veneer</a>
                            </li>
                        ';
                }
                ?>
                <li>
                    <a href="hasil.php" class="hover:opacity-50 hidden">Hasil Perhitungan</a>
                </li>
                <li class="mx-4 my-6 md:my-0">
                    <a href="tentang.php" class="hover:opacity-50">Tentang</a>
                </li>
                <li class="mx-4 my-6 md:my-0">
                    <a href="logout.php" class="hover:opacity-50">
                        <img src="img/icon/logout.svg" class="h-6 w-6" alt="Logout Icon">
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto p-5 min-h-screen">
        <div class="container mx-auto pt-8">
            <h1 id="daftar" class="text-5xl font-bold pb-12 text-center text-amber-950 border-b-2 border-amber-950">
                DASHBOARD ADMIN
            </h1>
            <h2 class="text-4xl font-bold py-8 text-center text-amber-950">
                Skala Bobot Kriteria
            </h2>

            <!-- Table Skala -->
            <div class="overflow-x-auto pb-14">
                <table class="min-w-full">
                    <thead class="border-b-2 border-amber-950">
                        <tr>
                            <th class="px-3 py-2 text-lg text-amber-950 col-span-2" colspan="2"> Bobot Kriteria</th>
                            <th class="px-3 py-2 text-lg text-amber-950">C1 <br> Harga</th>
                            <th class="px-3 py-2 text-lg text-amber-950">C2 <br> Warna</th>
                            <th class="px-3 py-2 text-lg text-amber-950">C3 <br> Pola</th>
                            <th class="px-3 py-2 text-lg text-amber-950">C4 <br> Daya Tahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950 row-span-5" rowspan="5">Skala</td>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950">5</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Sangat mahal</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Sangat terang</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Sangat bergelombang</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Sangat tinggi</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950">4</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Mahal</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Terang</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Bergelombang</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 font-normal text-amber-950">Tinggi</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950">3</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Cukup</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Cukup</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Cukup</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Cukup</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950">2</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Murah</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Gelap</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Lurus</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Rendah</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 text-lg text-center border-r border-b font-bold border-amber-950 text-amber-950">1</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Sangat murah</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Sangat gelap</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Sangat lurus</td>
                            <td class="px-3 py-2 text-md text-center border-b border-amber-950 text-amber-950">Sangat rendah</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h1 class="text-4xl font-bold mb-8 text-center text-amber-950">
                Daftar Nilai Matriks Veneer
            </h1>

            <!-- Tombol cari -->
            <div class="flex justify-center mb-4">
                <form action="" method="post" class="flex items-center w-full max-w-xl mx-auto">
                    <input type="text" name="keyword" class="text-amber-950 bg-amber-200 border-2 border-amber-950 px-4 py-2 rounded-lg placeholder-amber-950 placeholder-opacity-50 flex-1" placeholder="Masukan nama veneer..." autocomplete="off">
                    <button type="submit" name="cari" class="transition bg-amber-200 border-2 border-amber-950 hover:bg-amber-950 text-amber-950 hover:text-amber-200 font-semibold text-lg p-1 ml-2 rounded-lg flex items-center">
                        <ion-icon name="search-circle" class=""></ion-icon>
                        Cari!
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b-2 border-amber-950">
                        <tr>
                            <th class="px-6 py-2 text-lg text-amber-950">Alternatif</th>
                            <th class="px-6 py-4 text-lg text-amber-950">Gambar</th>
                            <th class="px-6 py-4 text-lg text-amber-950">Nama</th>
                            <th class="px-6 py-4 text-lg text-amber-950">C1 <br> Harga</th>
                            <th class="px-6 py-4 text-lg text-amber-950">C2 <br> Warna</th>
                            <th class="px-6 py-4 text-lg text-amber-950">C3 <br> Pola</th>
                            <th class="px-6 py-4 text-lg text-amber-950">C4 <br> Daya tahan</th>
                            <?php if ($isLogin) : ?>
                                <th class="px-6 py-4 text-lg text-amber-950 col-span-2" colspan="2">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($veneer2 as $row) : ?>
                            <tr class="border-b border-amber-950 max-w">
                                <td class="px-6 py-4 text-lg font-medium text-center">A<?= $i; ?></td>
                                <td class="px-6 py-4 text-center w-44">
                                    <div class="flex justify-center">
                                        <img src="img/<?= $row["gambar"]; ?>" alt="<?= $row["nama"]; ?>" class="max-w-full h-auto border-2 border-amber-950 cursor-pointer" onclick="openModal('img/<?= $row['gambar']; ?>')">
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-lg font-medium text-center"><?= $row["nama"]; ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center"><?= $row["harga"]; ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center"><?= $row["warna"]; ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center"><?= $row["pola"]; ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center "><?= $row["dayaTahan"]; ?></td>
                                <?php if ($isLogin) : ?>
                                    <td class="p-6 text-lg font-medium text-center border-l border-amber-950 inline-block sm:flex sm:justify-center">
                                        <a href="ubah.php?id=<?= $row["id"]; ?>" class="text-amber-950 hover:underline sm:inline">
                                            <img src="img/icon/edit.svg" class="h-6 w-6 mx-auto sm:mx-0" alt="Edit Icon">
                                        </a>
                                    </td>
                                    <td class="p-6 text-lg font-medium text-center border-l border-amber-950 inline-block sm:flex sm:justify-center">
                                        <a href="hapus.php?id=<?= $row["id"]; ?>" class="text-amber-950 hover:underline sm:inline" onclick="return confirm('Yakin ingin menghapus?');">
                                            <img src="img/icon/trash.svg" class="h-6 w-6 mx-auto sm:mx-0" alt="Trash Icon">
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal gambar ditekan -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="p-4">
                <div class="relative border-2 border-amber-950 shadow-lg max-w-2xl mx-auto">
                    <button class="absolute top-0 right-0 text-amber-200 hover:bg-amber-200 rounded hover:text-amber-950 hover:border hover:border-amber-950 flex items-center justify-center" onclick="closeModal()">
                        <ion-icon name="close" class="text-3xl"></ion-icon>
                    </button>
                    <div class="flex justify-center">
                        <img id="modal-img" src="" alt="Gambar Veneer" class="max-w-full max-h-screen ">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Button -->
        <?php if ($isLogin) : ?>
            <div class="flex items-center justify-center">
                <button id="modal-toggle-tambah" class="transition bg-amber-200 hover:bg-amber-950 hover:text-amber-200 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md items-center" type="button">
                    + Tambah
                </button>
            </div>
        <?php endif; ?>

        <!-- Main Tambah Modal -->
        <div id="authentication-modal-tambah" class="flex hidden fixed inset-0 z-50  items-center justify-center w-full p-4 h-full bg-black bg-opacity-50">
            <div class="relative w-full max-w-lg bg-amber-950 rounded-lg shadow-lg">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-600 rounded-t">
                    <h3 class="text-2xl font-bold text-amber-200">
                        Tambah data veneer
                    </h3>
                    <button id="close-modal-btn-tambah" class="transition text-amber-200 bg-transparent hover:bg-amber-200 hover:text-amber-950 rounded-lg text-2xl ml-auto inline-flex items-center">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                </div>
                <div class="p-6 bg-amber-950 rounded-lg">
                    <form action="" method="post" enctype="multipart/form-data" class="bg-amber-200 p-6 rounded shadow-md w-full border-amber-950">
                        <ul>
                            <li class="mb-4 columns-2">
                                <label for="nama" class="block text-lg text-amber-950 font-bold mb-2">Nama</label>
                                <input type="text" name="nama" id="nama" class="bg-amber-100 placeholder:text-gray-700 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-amber-950 leading-tight cursor-text" placeholder="Masukan nama..." required>
                            </li>

                            <li class="mb-4 columns-2">
                                <label for="harga" class="block text-xl text-amber-950 font-bold mb-2">Harga</label>
                                <p class="text-amber-950 text-xs">(1 untuk sangat murah, <br> 5 untuk sangat mahal)</p>
                                <div id="harga" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                                    <label class="ml-2"><input type="radio" name="harga" value="1" required> 1</label>
                                    <label class="ml-2"><input type="radio" name="harga" value="2" required> 2</label>
                                    <label class="ml-2"><input type="radio" name="harga" value="3" required> 3</label>
                                    <label class="ml-2"><input type="radio" name="harga" value="4" required> 4</label>
                                    <label class="ml-2"><input type="radio" name="harga" value="5" required> 5</label>
                                </div>
                            </li>

                            <li class="mb-4 columns-2">
                                <label for="warna" class="block text-xl text-amber-950 font-bold mb-2">Warna</label>
                                <p class="text-amber-950 text-xs">(1 untuk sangat gelap, <br> 5 untuk sangat terang)</p>
                                <div id="warna" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                                    <label class="ml-2"><input type="radio" name="warna" value="1" required> 1</label>
                                    <label class="ml-2"><input type="radio" name="warna" value="2" required> 2</label>
                                    <label class="ml-2"><input type="radio" name="warna" value="3" required> 3</label>
                                    <label class="ml-2"><input type="radio" name="warna" value="4" required> 4</label>
                                    <label class="ml-2"><input type="radio" name="warna" value="5" required> 5</label>
                                </div>
                            </li>

                            <li class="mb-4 columns-2">
                                <label for="pola" class="block text-xl text-amber-950 font-bold mb-2">Pola</label>
                                <p class="text-amber-950 text-xs">(1 untuk sangat lurus, <br> 5 untuk sangat bergelombang)</p>
                                <div id="pola" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                                    <label class="ml-2"><input type="radio" name="pola" value="1" required> 1</label>
                                    <label class="ml-2"><input type="radio" name="pola" value="2" required> 2</label>
                                    <label class="ml-2"><input type="radio" name="pola" value="3" required> 3</label>
                                    <label class="ml-2"><input type="radio" name="pola" value="4" required> 4</label>
                                    <label class="ml-2"><input type="radio" name="pola" value="5" required> 5</label>
                                </div>
                            </li>

                            <li class="mb-4 columns-2">
                                <label for="dayaTahan" class="block text-xl text-amber-950 font-bold mb-2">Daya Tahan</label>
                                <p class="text-amber-950 text-xs">(1 untuk sangat rendah, <br> 5 untuk sangat tinggi)</p>
                                <div id="dayaTahan" class="bg-amber-100 accent-amber-950 shadow cursor-pointer border border-amber-950 rounded w-full py-2 px-3 text-lg text-amber-950 leading-tight focus:outline-none focus:shadow-outline flex justify-between">
                                    <label class="ml-2"><input type="radio" name="dayaTahan" value="1" required> 1</label>
                                    <label class="ml-2"><input type="radio" name="dayaTahan" value="2" required> 2</label>
                                    <label class="ml-2"><input type="radio" name="dayaTahan" value="3" required> 3</label>
                                    <label class="ml-2"><input type="radio" name="dayaTahan" value="4" required> 4</label>
                                    <label class="ml-2"><input type="radio" name="dayaTahan" value="5" required> 5</label>
                                </div>
                            </li>

                            <li class="mb-4 columns-2">
                                <label for="gambar" class="block text-lg text-amber-950 font-bold mb-2">Gambar</label>
                                <input type="file" name="gambar" id="gambar" class="shadow bg-amber-100 border border-amber-950 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </li>

                            <li class="flex items-center justify-center w-full">
                                <a href="admin.php" class="transition inline-flex items-center m-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-100 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                                    <ion-icon name="chevron-back-outline" class="text-xl mr-1"></ion-icon>
                                    Kembali
                                </a>
                                <button type="submit" name="tambah" class="transition inline-flex items-center m-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-100 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                                    <ion-icon name="chevron-forward-outline" class="text-xl mr-1"></ion-icon>
                                    Tambah data
                                </button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div class="pt-14">
        <footer class="bg-amber-950 text-amber-200 p-4 text-left mt-auto text-xs">
            <p>&copy; 2013 CV. Surya Prima Mandiri</p>
            <p><ion-icon name="call-outline"></ion-icon> 0812-9935-6751</p>
            <a class="hover:underline " href="https://www.google.com/maps/uv?pb=!1s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e!3m1!7e115!4s%2Fmaps%2Fplace%2Fcv%2Bsurya%2Bprima%2Bmandiri%2F%40-6.2301226%2C106.5690648%2C3a%2C75y%2C237.62h%2C90t%2Fdata%3D*213m4*211e1*213m2*211ss6zTUafsXRy7PONRnqCFWw*212e0*214m2*213m1*211s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e%3Fsa%3DX%26ved%3D2ahUKEwjLvOqu9_WGAxWH-TgGHbdACkMQpx96BAg0EAA!5scv%20surya%20prima%20mandiri%20-%20Google%20Search!15sCgIgAQ&imagekey=!1e2!2ss6zTUafsXRy7PONRnqCFWw&cr=le_a7&hl=en&ved=1t%3A206134&ictx=111">
                <ion-icon name="map-outline"></ion-icon> Jl. Sempur No.131, RT.01/RW.06, Sempur, Kadu, Kec. Curug, Kab. Tangerang, Banten 15810
            </a>
        </footer>
    </div>

    <script>
        function openModal(src) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal-img').src = src;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal-img').src = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalToggleTambah = document.getElementById('modal-toggle-tambah');
            const modalTambah = document.getElementById('authentication-modal-tambah');
            const closeModalBtnTambah = document.getElementById('close-modal-btn-tambah');

            modalToggleTambah.addEventListener('click', function() {
                modalTambah.classList.remove('hidden');
                document.body.classList.add('modal-open-tambah');
            });

            closeModalBtnTambah.addEventListener('click', function() {
                modalTambah.classList.add('hidden');
                document.body.classList.remove('modal-open-tambah');
            });

            window.addEventListener('click', function(event) {
                if (event.target === modalTambah) {
                    modalTambah.classList.add('hidden');
                    document.body.classList.remove('modal-open-tambah');
                }
            });

        });

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