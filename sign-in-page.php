<?php

session_start();

if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']) {
    header('Location: ../index.php');
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
    <title>Forums | Log In Page</title>
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
                        <a href="."> Home &nbsp;
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-buttons-li">
                        <a href="sign-in-page.php">
                            <strong>Log In</strong> &nbsp;
                            <i class="fas fa-sign-in-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <?php
        if (isset($_SESSION['query-acc-created'])) {
            echo $_SESSION['query-acc-created'];
            unset($_SESSION['query-acc-created']);
        } else if (isset($_SESSION['query-error'])){
            echo $_SESSION['query-error'];
            unset($_SESSION['query-error']);
        }
        ?>

        <main class="form-main">
            <h1 class="form-h1">Log In</h1>

            <form action="server/login.php" method="post" autocomplete="off">
                <label for="login-field">Username:</label> <br>
                <input type="text" name="user-login" id="login-field" class="form-input" maxlength="25" required> <br>

                <?php
                if (isset($_SESSION['form-login-error'])) {
                    echo $_SESSION['form-login-error'];
                    unset($_SESSION['form-login-error']);
                }
                ?>

                <label for="password-field">Password:</label> <br>
                <input type="password" name="user-password" id="password-field" class="form-input" required> <br>

                <?php
                if (isset($_SESSION['form-match-error'])) {
                    echo $_SESSION['form-match-error'];
                    unset($_SESSION['form-match-error']);
                }
                ?>

                <div class="form-rem-forg-field">
                    <span class="form-checkbox">
                        Remember me <input class="checkbox-field" type="checkbox" name="user-remember-me">
                    </span>

                    <span>
                        <a class="form-forgot-pass-link" href="#">Forgot password?</a>
                    </span>
                </div>
                <input type="submit" id="submit-button" class="form-submit" value="Log In!"> <br>
            </form>
        </main>

        <div class="under-form">
            Not member yet?
            <a class="under-form-link" href="sign-up-page.php">
                Sign Up
            </a>
        </div>

        <footer>
            Mateusz Trzeciak &copy; <?php echo date('Y') ?>. All rights reserved.
            <a class="footer-link" href="https://icons8.com/icon/119073/faq" target="blank">Website icons by Icons8</a>
        </footer>
    </div>
</body>

</html>