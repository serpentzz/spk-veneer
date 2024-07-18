<?php
session_start();

$isLogin = (isset($_SESSION['login']) && $_SESSION['login'] === true);

if (!isset($_POST["hitung"])) {
    header("Location: hitung.php");
    exit;
}

include 'functions.php';

// Bobot
$harga = $_POST['harga'];
$warna = $_POST['warna'];
$pola = $_POST['pola'];
$dayaTahan = $_POST['dayaTahan'];

// Fungsi pembagi normalisasi
function pembagiNM($matrix)
{
    $pembagi = [];
    for ($i = 0; $i < count($matrix[0]); $i++) {
        $sum = 0;
        foreach ($matrix as $row) {
            $sum += pow($row[$i], 2);
        }
        $pembagi[$i] = sqrt($sum);
    }
    return $pembagi;
}

// Fungsi normalisasi matriks
function normalisasi($matrix, $pembagi)
{
    $normalized = [];
    foreach ($matrix as $row) {
        $normalizedRow = [];
        foreach ($row as $i => $value) {
            $normalizedRow[] = $value / $pembagi[$i];
        }
        $normalized[] = $normalizedRow;
    }
    return $normalized;
}

// Fungsi normalisasi matriks terbobot ('R)
function normalisasiTerbobot($matrix, $bobot)
{
    $weighted = [];
    foreach ($matrix as $row) {
        $weightedRow = [];
        foreach ($row as $i => $value) {
            $weightedRow[] = $value * $bobot[$i];
        }
        $weighted[] = $weightedRow;
    }
    return $weighted;
}

// Fungsi transpose matriks
function transpose($squareArray)
{
    if ($squareArray == null) {
        return null;
    }
    $rotatedArray = array();
    foreach ($squareArray as $r => $row) {
        foreach ($row as $c => $cell) {
            $rotatedArray[$c][$r] = $cell;
        }
    }
    return $rotatedArray;
}

// Fungsi menghitung jarak ke solusi ideal positif dan negatif 
function jarakIdeal($Aplus, $ideal)
{
    $distances = [];
    foreach ($Aplus as $row) {
        $sum = 0;
        for ($i = 0; $i < count($row); $i++) {
            $sum += pow(($row[$i] - $ideal[$i]), 2);
        }
        $distances[] = sqrt($sum);
    }
    return $distances;
}

// Fungsi menghitung nilai preferensi
function nilaiPreferensi($Dplus, $Dmin)
{
    $V = [];
    foreach ($Dmin as $i => $value) {
        $V[] = $value / ($value + $Dplus[$i]);
    }
    return $V;
}

// Ambil data dari database
$query = mysqli_query($conn, "SELECT * FROM veneer2");
$matrix = [];
$nama = [];
while ($row = mysqli_fetch_assoc($query)) {
    $matrix[] = [$row['harga'], $row['warna'], $row['pola'], $row['dayaTahan']];
    $nama[] = $row['nama'];
}

$bobot = [$harga, $warna, $pola, $dayaTahan];

// Pembagi normalisasi
$pembagi = pembagiNM($matrix);

// Matriks ternormalisasi ('r)
$matrixR = normalisasi($matrix, $pembagi);

// Matriks ternormalisasi terbobot (y)
$matrixY = normalisasiTerbobot($matrixR, $bobot);

// Solusi ideal positif (A+) dan negatif (A-)
$transposedMatrixY = transpose($matrixY);
$Aplus = [];
$Amin = [];
foreach ($transposedMatrixY as $index => $criteria) {
    if ($index == 0) { // Assuming C1 (Harga) is the cost criteria
        $Aplus[] = min($criteria);
        $Amin[] = max($criteria);
    } else { // C2, C3, C4 are benefit criteria
        $Aplus[] = max($criteria);
        $Amin[] = min($criteria);
    }
}

// Jarak antara terbobot solusi ideal positif (D+) dan negatif (D-)
$Dplus = jarakIdeal($matrixY, $Aplus);
$Dmin = jarakIdeal($matrixY, $Amin);

// Nilai preferensi (V)
$V = nilaiPreferensi($Dplus, $Dmin);

// Menentukan alternatif terbaik (Rank)
$rank = array_keys($V, max($V))[0];

// Array untuk menyimpan indeks alternatif dan nilai preferensi
$alternatif = range(1, count($V));
$sortHighest = array_combine($alternatif, $V);

// Mengurutkan alternatif berdasarkan nilai preferensi tertinggi
arsort($sortHighest);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK VENEER</title>
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
                    <a>Hasil</a>
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
        <div class="container mx-auto py-8">
            <div class=>
                <h1 class="text-4xl font-bold mb-8 text-center text-amber-950 ">
                    Hasil Perhitungan TOPSIS
                </h1>

                <!-- Tabel Nilai Matriks -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pb-4">
                    Nilai Matriks
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-amber-950 ">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950">Alternatif</th>
                                <th class="px-6 py-2 text-amber-950">C1 (Harga)</th>
                                <th class="px-6 py-2 text-amber-950">C2 (Warna)</th>
                                <th class="px-6 py-2 text-amber-950">C3 (Pola)</th>
                                <th class="px-6 py-2 text-amber-950">C4 (Daya Tahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($matrix as $row) {
                            ?>
                                <tr class="text-lg text-amber-950 border-b border-amber-950">
                                    <td class="px-6 py-4 text-center"><?= "A", $no ?></td>
                                    <td class="px-6 py-4 text-center"><?= $row[0] ?></td>
                                    <td class="px-6 py-4 text-center"><?= $row[1] ?></td>
                                    <td class="px-6 py-4 text-center"><?= $row[2] ?></td>
                                    <td class="px-6 py-4 text-center"><?= $row[3] ?></td>
                                </tr>
                            <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Bobot Kriteria -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                    Bobot Kriteria (<span class="italic">W</span>)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-amber-950 ">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950">C1 (Harga)</th>
                                <th class="px-6 py-2 text-amber-950">C2 (Warna)</th>
                                <th class="px-6 py-2 text-amber-950">C3 (Pola)</th>
                                <th class="px-6 py-2 text-amber-950">C4 (Daya Tahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-lg text-amber-950 border-b border-amber-950">
                                <td class="px-6 py-4 text-center"><?= $harga ?></td>
                                <td class="px-6 py-4 text-center"><?= $warna ?></td>
                                <td class="px-6 py-4 text-center"><?= $pola ?></td>
                                <td class="px-6 py-4 text-center"><?= $dayaTahan ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Matriks Ternormalisasi -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                    Matriks Ternormalisasi (<span class="italic">'r</span>)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-amber-950">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950">Alternatif</th>
                                <th class="px-6 py-2 text-amber-950">C1 (Harga)</th>
                                <th class="px-6 py-2 text-amber-950">C2 (Warna)</th>
                                <th class="px-6 py-2 text-amber-950">C3 (Pola)</th>
                                <th class="px-6 py-2 text-amber-950">C4 (Daya Tahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matrixR as $index => $row) : ?>
                                <tr class="text-lg text-amber-950 border-b border-amber-950">
                                    <td class="px-6 py-4 text-center"><?= "A", $index + 1 ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[0], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[1], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[2], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[3], 4) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Matriks Ternormalisasi Terbobot -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                    Matriks Ternormalisasi Terbobot (<span class="italic">y</span>)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 rounded-lg border-amber-950">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950">Alternatif</th>
                                <th class="px-6 py-2 text-amber-950">C1 (Harga)</th>
                                <th class="px-6 py-2 text-amber-950">C2 (Warna)</th>
                                <th class="px-6 py-2 text-amber-950">C3 (Pola)</th>
                                <th class="px-6 py-2 text-amber-950">C4 (Daya Tahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matrixY as $index => $row) : ?>
                                <tr class="text-lg text-amber-950 border-b border-amber-950">
                                    <td class="px-6 py-4 text-center"><?= "A", $index + 1 ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[0], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[1], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[2], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($row[3], 4) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Solusi Ideal Positif dan Negatif -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                    Solusi Ideal Positif (<span class="italic">A<sup>+</sup></span>) dan Negatif (<span class="italic">A<sup>-</sup></span>)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-amber-950">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950"></th>
                                <th class="px-6 py-2 text-amber-950">C1 (Harga)</th>
                                <th class="px-6 py-2 text-amber-950">C2 (Warna)</th>
                                <th class="px-6 py-2 text-amber-950">C3 (Pola)</th>
                                <th class="px-6 py-2 text-amber-950">C4 (Daya Tahan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-lg text-amber-950 border-b border-amber-950">
                                <td class="px-6 py-4">A<sup>+</sup></td>
                                <?php foreach ($Aplus as $value) : ?>
                                    <td class="px-6 py-4 text-center"><?= round($value, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr class="text-lg text-amber-950 border-b border-amber-950">
                                <td class="px-6 py-4">A<sup>-</sup></td>
                                <?php foreach ($Amin as $value) : ?>
                                    <td class="px-6 py-4 text-center"><?= round($value, 4) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Jarak Antara Nilai Terbobot Solusi Ideal Positif dan Negatif -->
                <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                    Jarak Antara Nilai Terbobot Solusi Ideal Positif (<span class="italic">D<sup>+</sup></span>) dan Negatif (<span class="italic">D<sup>-</sup></span>)
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b-2 border-amber-950">
                            <tr class="text-lg">
                                <th class="px-6 py-2 text-amber-950">D<sup>+</sup></th>
                                <th class="px-6 py-2 text-amber-950">(Jarak ke Solusi Ideal Positif)</th>
                                <th class="px-6 py-2 text-amber-950">D<sup>-</sup></th>
                                <th class="px-6 py-2 text-amber-950">(Jarak ke Solusi Ideal Negatif)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matrixY as $index => $row) : ?>
                                <tr class="text-lg text-amber-950 border-b border-amber-950">
                                    <td class="px-6 py-4 text-center"><?= "D", $index + 1 ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($Dplus[$index], 4) ?></td>
                                    <td class="px-6 py-4 text-center"><?= "D", $index + 1 ?></td>
                                    <td class="px-6 py-4 text-center"><?= round($Dmin[$index], 4) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Nilai Preferensi (V) -->
                <div class="max-w-md mx-auto">
                    <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                        Nilai Preferensi (<span class="italic">V</span>)
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b-2 border-amber-950">
                                <tr class="text-lg">
                                    <th class="px-6 py-2 text-amber-950">Alternatif</th>
                                    <th class="px-6 py-2 text-amber-950">V</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < sizeof($V); $i++) {
                                ?>
                                    <tr class="text-lg text-amber-950 border-b border-amber-950">
                                        <td class="px-6 py-4 text-center"><?= "A", $i + 1 ?></td>
                                        <td class="px-6 py-4 text-center"><?= round($V[$i], 4) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Pengurutan Ranking -->
                <div class="max-w-md mx-auto">
                    <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                        Pengurutan Ranking
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b-2 border-amber-950">
                                <tr class="text-lg">
                                    <th class="px-6 py-2 text-amber-950">Rank</th>
                                    <th class="px-6 py-2 text-amber-950">Alternatif</th>
                                    <th class="px-6 py-2 text-amber-950">V</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // Sort the alternatives based on V in descending order
                                arsort($sortHighest);

                                foreach ($sortHighest as $index => $nilaiV) {
                                ?>
                                    <tr class="text-lg text-amber-950 border-b border-amber-950">
                                        <td class="px-6 py-4 text-center"><?= $no ?></td>
                                        <td class="px-6 py-4 text-center"><?= "A", $index ?></td>
                                        <td class="px-6 py-4 text-center"><?= round($nilaiV, 4) ?></td>
                                    </tr>
                                <?php
                                    $no++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Penentuan Ranking Alternatif Terbaik-->
                    <div class="max-w-md mx-auto">
                        <h4 class="text-3xl text-center font-semibold text-amber-950 pt-20 pb-4">
                            Ranking Alternatif Terbaik
                        </h4>
                        <div class="bg-green-100 p-4 rounded-lg border-2 border-amber-950 shadow-md">
                            <p class="text-xl text-center font-medium text-green-700">
                                Ranking alternatif terbaik adalah: <br>
                                <span class="font-semibold"><?= 'A' . ($rank + 1) ?> </span> 
                                yaitu 
                                <span class="font-semibold"><?= $nama[$rank] ?> </span>
                                dengan nilai V = <?= round($V[$rank], 4) ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-center p-6">
                        <a href="hitung.php" class="transition inline-flex bg-amber-200 hover:bg-amber-950 hover:text-amber-200 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mr-4 rounded-full shadow-md items-center whitespace-nowrap">
                            <ion-icon name="chevron-back-outline" class="mr-2"></ion-icon>
                            Hitung ulang
                        </a>
                        <a href="list.php" class="transition inline-flex bg-amber-200 hover:bg-amber-950 hover:text-amber-200 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 rounded-full shadow-md items-center whitespace-nowrap">
                            <ion-icon name="chevron-forward-outline" class="mr-2"></ion-icon>
                            Lihat Daftar Veneer
                        </a>
                    </div>
                </div>

                <div class="max-w-screen-sm mx-auto">
                    <h2 class="font-bold text-amber-950 text-3xl pb-2">
                        Ingin berkunjung?
                    </h2>

                    <div class="relative pt-64 border-2 border-amber-950 shadow-lg">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13340.857542356964!2d106.57118848323516!3d-6.2293238296508076!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e!2sCV.%20Surya%20Prima%20Mandiri!5e0!3m2!1sen!2sid!4v1719307037366!5m2!1sen!2sid" class="absolute inset-0 w-full h-full" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-amber-950 text-amber-200 p-4 text-left text-xs">
        <p>&copy; 2013 CV. Surya Prima Mandiri</p>
        <p><ion-icon name="call-outline"></ion-icon> 0812-9935-6751</p>
        <a class="hover:underline " href="https://www.google.com/maps/uv?pb=!1s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e!3m1!7e115!4s%2Fmaps%2Fplace%2Fcv%2Bsurya%2Bprima%2Bmandiri%2F%40-6.2301226%2C106.5690648%2C3a%2C75y%2C237.62h%2C90t%2Fdata%3D*213m4*211e1*213m2*211ss6zTUafsXRy7PONRnqCFWw*212e0*214m2*213m1*211s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e%3Fsa%3DX%26ved%3D2ahUKEwjLvOqu9_WGAxWH-TgGHbdACkMQpx96BAg0EAA!5scv%20surya%20prima%20mandiri%20-%20Google%20Search!15sCgIgAQ&imagekey=!1e2!2ss6zTUafsXRy7PONRnqCFWw&cr=le_a7&hl=en&ved=1t%3A206134&ictx=111">
            <ion-icon name="map-outline"></ion-icon> Jl. Sempur No.131, RT.01/RW.06, Sempur, Kadu, Kec. Curug, Kab. Tangerang, Banten 15810
        </a>
    </footer>

    <script>
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