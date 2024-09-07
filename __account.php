<?php

session_start();

if (!isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == false) {
    header('Location: sign-in-page.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="layout/layout.css">
    <link rel="favicon icon" href="https://img.icons8.com/fluent/48/000000/faq.png">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="javascript/account.js"></script>
    <title>Forums | Account Page</title>
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <div class="nav-title">
                Forums
                </div>
                <ul class="nav-buttons-ul">
                    <li class="nav-buttons-li">
                        <a href=".">
                            Home &nbsp;
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-buttons-li nav-button-li-dropdown">
                        <a href="account.php">
                            <strong>
                                Account &nbsp;
                                <i class="fas fa-user"></i>
                            </strong>
                        </a>
                        <div class="nav-button-li-dropdown-content">
                            <a href="server/logout.php">
                                Logout &nbsp; <i class="fas fa-door-open"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="main-acc-content">
            <h1 class="acc-h1">My account</h1>
            <h2 class="acc-cat-h2"> Personal</h2>
        </main>
        <footer>
            Mateusz Trzeciak &copy; <?php echo date('Y') ?>. All rights reserved.
            <a class="footer-link" href="https://icons8.com/icon/119073/faq" target="blank">Website icons by Icons8</a>
        </footer>
    </div>
</body>

</html>