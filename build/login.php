<?php
session_start();

include 'functions.php';

$isLogin = (isset($_SESSION['login']) && $_SESSION['login'] === true);

if (isset($_POST["login"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username ='$username'");

    // cek username
    if (mysqli_num_rows($result) == 1) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {

            // set session
            $_SESSION["login"] = true;

            header("Location: admin.php");
            exit;
        }
    }
    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-amber-100 min-h-screen flex flex-col font-sans">
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
                    <a href="login.php" class="hover:opacity-50 ">
                        <img src="img/icon/admin.svg" class="h-6 w-6" alt="Admin Icon">
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="flex-grow py-24 px-8">
        <form action="" method="post" class="max-w-md mx-auto bg-amber-200 border-4 border-amber-950 p-6 rounded-lg shadow-md">
            <h2 class="text-3xl mb-6 text-center font-bold text-amber-950">
                Login Admin
            </h2>
            <?php if (isset($error)) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">Username atau password salah.</span>
                </div>
            <?php endif; ?>
            <ul class="">
                <li class="mb-4 columns-2">
                    <label for="username" class="block text-amber-950 text-xl font-bold">Username</label>
                    <input type="text" name="username" id="username" required class="bg-amber-100 w-full px-3 py-2 border border-amber-950 rounded-md">
                </li>

                <li class="mb-4 columns-2">
                    <label for="password" class="block text-amber-950 text-xl font-bold">Password</label>
                    <input type="password" name="password" id="password" required class="bg-amber-100 w-full px-3 py-2 border border-amber-950 rounded-md">
                </li>

                <!-- <p class="text-amber-950">Belum jadi admin?
                    <a href="registrasi.php" class="text-blue-800 hover:underline">
                        Daftar di sini!
                    </a>
                </p> -->
                <br>
                <li class="flex items-center justify-center w-full">
                    <a href="index.php" class="transition inline-flex items-center my-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-100 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                        <ion-icon name="chevron-back-outline" class="text-xl mr-1"></ion-icon>
                        Kembali
                    </a>
                    <button type="submit" name="login" class="transition inline-flex items-center m-3 bg-amber-100 hover:bg-amber-950 hover:text-amber-200 border-2 border-amber-950 text-amber-950 text-xl font-bold py-2 px-4 mt-3 rounded-full shadow-md">
                        <ion-icon name="chevron-forward-outline" class="text-xl mr-1"></ion-icon>
                        Login
                    </button>
                </li>
            </ul>
        </form>
    </main>

    <footer class="bg-amber-950 text-amber-200 p-4 text-left text-xs">
        <p>&copy; 2013 Cv. Surya Prima Mandiri</p>
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