<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $user_login = htmlentities($_POST['user-login'], ENT_QUOTES, "utf-8");
    $user_pass = htmlentities($_POST['user-password'], ENT_QUOTES, "utf-8");
    // $user_remember = $_POST['user-remember-me'];

    if (empty($user_login) || empty($user_pass)) {
        $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block">Please complete the Log In form!</small>';
        header('Location: ../sign-in-page.php');
        return;
    }

    // Checking login input format
    if (!preg_match("/(?=^.{3,20}$)^[a-zA-Z][a-zA-Z0-9]*[._-]?[a-zA-Z0-9]+$/", $user_login)) {
        // lower/upper letters, one of (_-.), numbers or lower/upper letters
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-login-error'] = '<small class="form-validation-error" style="display: block"> Wrong username format!</small>';
        header('Location: ../sign-in-page.php');
        return;
    }

    // Looking for user in DB
    $secure_sql_query = sprintf("SELECT * FROM Users WHERE BINARY login='%s'", $user_login);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $query_row = $sql_stmt->fetch();
        if ($sql_stmt->rowCount() == 1 && password_verify($user_pass, $query_row['password'])){
            $_SESSION['isLogged'] = true;
            $_SESSION['user_id'] = $query_row['id'];
            $_SESSION['login'] = $query_row['login'];
            $_SESSION['password'] = $query_row['password'];

            $sql_stmt->closeCursor();
            header('Location: ../index.php');
        } else {
            $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block">Login or password not match.</small>';
            header('Location: ../sign-in-page.php');
        }
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../sign-in-page.php');
    return;
}