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

function getSkalaText($value, $criteria) {
    $skalaText = [
        'C1' => ['1' => 'Sangat murah', '2' => 'Murah', '3' => 'Cukup', '4' => 'Mahal', '5' => 'Sangat mahal'],
        'C2' => ['1' => 'Sangat gelap', '2' => 'Gelap', '3' => 'Cukup', '4' => 'Terang', '5' => 'Sangat terang'],
        'C3' => ['1' => 'Sangat lurus', '2' => 'Lurus', '3' => 'Cukup', '4' => 'Bergelombang', '5' => 'Sangat bergelombang'],
        'C4' => ['1' => 'Sangat rendah', '2' => 'Rendah', '3' => 'Cukup', '4' => 'Tinggi', '5' => 'Sangat tinggi'],
    ];

    return $skalaText[$criteria][$value];
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

    <main class="max-w-6xl mx-auto p-5 min-h-screen">
        <div class="container mx-auto pt-8">
            <h1 class="text-4xl font-bold mb-8 text-center text-amber-950">
                Skala Bobot Kriteria
            </h1>

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

            <h1 id="daftar" class="text-4xl font-bold mb-8 text-center text-amber-950">
                Daftar Nilai Matriks Veneer
            </h1>

            <!-- Tombol cari -->
            <div class="flex justify-center mb-4">
                <form action="" method="post" class="flex items-center w-full max-w-xl mx-auto">
                    <input type="text" name="keyword" class="text-amber-950 bg-amber-200 border-2 border-amber-950 px-4 py-2 rounded-lg placeholder-amber-950 placeholder-opacity-50 flex-1" autofocus placeholder="Masukan nama veneer..." autocomplete="off">
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
                                <td class="px-6 py-4 text-lg font-bold text-center text-amber-950"><?= $row["nama"]; ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center text-amber-950"><?= getSkalaText($row["harga"], 'C1'); ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center text-amber-950"><?= getSkalaText($row["warna"], 'C2'); ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center text-amber-950"><?= getSkalaText($row["pola"], 'C3'); ?></td>
                                <td class="px-6 py-4 text-lg font-medium text-center text-amber-950"><?= getSkalaText($row["dayaTahan"], 'C4'); ?></td>
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