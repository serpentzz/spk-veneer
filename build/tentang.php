<?php
session_start();
include 'functions.php';

$isLogin = (isset($_SESSION['login']) && $_SESSION['login'] === true);

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

<body class="min-h-screen bg-amber-100 font-sans flex flex-col">
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

    <main class="flex-grow p-8 ">
        <h1 class="text-3xl font-bold pb-4 text-left text-amber-950">
            Tentang website
        </h1>
        <p class="text-amber-950 text-justify">
            Website ini adalah sebuah Sistem Pendukung Keputusan (SPK) yang dirancang khusus untuk membantu pengguna dalam memilih veneer kayu yang sesuai dengan kebutuhan mereka. Dengan menyediakan cara yang mudah, cepat, dan praktis untuk melakukan pemilihan, website ini mempermudah pengguna dalam menentukan pilihan veneer kayu terbaik berdasarkan bobot kriteria yang dimasukan. </p>
        <br>
        <h1 class="text-3xl font-bold pb-4 text-left text-amber-950">
            Apa itu SPK?
        </h1>
        <p class="text-amber-950 text-justify">
            Sistem Pendukung Keputusan (SPK) adalah sebuah sistem yang dirancang untuk membantu proses pengambilan keputusan dengan menyediakan analisis data yang relevan. SPK menggunakan berbagai metode dan algoritma untuk menganalisis informasi dan memberikan rekomendasi yang dapat membantu pengguna dalam membuat keputusan yang lebih baik.
        </p>
        <br>
        <h1 class="text-3xl font-bold pb-4 text-left text-amber-950">
            Bagaimana cara kerja SPK?
        </h1>
        <p class="text-amber-950 text-justify">
            SPK bekerja dengan mengumpulkan data yang relevan, memproses data tersebut melalui algoritma tertentu, dan memberikan hasil analisis dalam bentuk rekomendasi atau keputusan. Dalam konteks website ini, SPK membantu pengguna memilih veneer kayu berdasarkan beberapa kriteria seperti harga, warna, pola, dan daya tahan.
        </p>
        <br>
        <h1 class="text-3xl font-bold pb-4 text-left text-amber-950">
            Metode SPK apa yang digunakan?
        </h1>
        <p class="text-amber-950 text-justify">
            Website ini menggunakan metode TOPSIS (<span class="italic">Technique for Order Preference by Similarity to Ideal Solution</span>) untuk membantu dalam proses pengambilan keputusan. TOPSIS adalah salah satu metode yang banyak digunakan karena dapat mempertimbangkan berbagai kriteria secara cepat dan memberikan hasil yang mudah dipahami.
            <br>
            Proses TOPSIS melibatkan beberapa langkah, yaitu:
        </p>
        <br>
        <ol class="list-decimal list-inside text-amber-950">
            <li>Menentukan Matriks Keputusan:</li>
            <li>Mengnormalisasi matriks keputusan untuk membandingkan data kriteria.</li>
            <li>Membobotan matriks ternormalisasi dengan bobot yang ditentukan untuk setiap kriteria.</li>
            <li>Menentukan solusi ideal positif dan negatif untuk setiap kriteria.</li>
            <li>Menghitung jarak dari solusi ideal untuk setiap alternatif.</li>
            <li>Menghitung nilai preferensi berdasarkan jarak dari solusi ideal.</li>
            <li>Menyusun ranking alternatif berdasarkan nilai preferensi yang diperoleh.</li>
        </ol>
        <br>
        <p class="text-amber-950 text-justify">
            Dengan metode ini, pengguna dapat dengan mudah melihat veneer kayu mana yang sesuai dengan preferensi mereka dalam membantu membuat keputusan yang lebih baik.
        </p>

    </main>

    <footer class="bg-amber-950 text-amber-200 p-4 text-left text-xs mt-auto">
        <p>&copy; 2013 CV. Surya Prima Mandiri</p>
        <p><ion-icon name="call-outline"></ion-icon> 0812-9935-6751</p>
        <a class="hover:underline" href="https://www.google.com/maps/uv?pb=!1s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e!3m1!7e115!4s%2Fmaps%2Fplace%2Fcv%2Bsurya%2Bprima%2Bmandiri%2F%40-6.2301226%2C106.5690648%2C3a%2C75y%2C237.62h%2C90t%2Fdata%3D*213m4*211e1*213m2*211ss6zTUafsXRy7PONRnqCFWw*212e0*214m2*213m1*211s0x2e69fdd644db35b9%3A0x3ff6a95a9c55314e%3Fsa%3DX%26ved%3D2ahUKEwjLvOqu9_WGAxWH-TgGHbdACkMQpx96BAg0EAA!5scv%20surya%20prima%20mandiri%20-%20Google%20Search!15sCgIgAQ&imagekey=!1e2!2ss6zTUafsXRy7PONRnqCFWw&cr=le_a7&hl=en&ved=1t%3A206134&ictx=111">
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