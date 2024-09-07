<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $user_fn = htmlentities(trim($_POST['user-fn']), ENT_QUOTES, 'utf-8');
    $user_ln = htmlentities(trim($_POST['user-ln']), ENT_QUOTES, 'utf-8');
    $user_login = htmlentities(trim($_POST['user-login']), ENT_QUOTES, 'utf-8');
    $user_email = htmlentities(trim($_POST['user-email']), ENT_QUOTES, 'utf-8');
    $user_pass = htmlentities(trim($_POST['user-password']), ENT_QUOTES, 'utf-8');
    $user_rpass = htmlentities(trim($_POST['user-r-password']), ENT_QUOTES, 'utf-8');
    $user_pass_hash = password_hash($user_pass, PASSWORD_DEFAULT);

    $_SESSION['is_form_correct'] = true;

    if ((empty($user_login) || empty($user_email) || empty($user_pass) || empty($user_rpass))) {
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block">Please complete the registeration form!</small>';
        header('Location: ../sign-up-page.php');
        return;
    }

    if (!preg_match("/(?=^.{3,20}$)^[a-zA-Z][a-zA-Z0-9]*[._-]?[a-zA-Z0-9]+$/", $user_login)) {
        // Lower/upper letters, one of (_-.), numbers or lower/upper letters
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-login-error'] = '<small class="form-validation-error" style="display: block">Wrong username format!</small>';
        header('Location: ../sign-up-page.php');
    }

    $sql_login_check = sprintf("SELECT id FROM Users WHERE BINARY login='%s'", $user_login);
    $sql_stmt = $pdo->prepare($sql_login_check);
    if($sql_stmt->execute()) {
        if ($sql_query_rows = $sql_stmt->rowCount() > 0) {
            $_SESSION['is_form_correct'] = false;
            $_SESSION['form-login-error'] = '<small class="form-validation-error" style="display: block">Username is not unique.</small>';
            header('Location: ../sign-up-page.php');
            $sql_stmt->closeCursor();
        }
    }

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-email-error'] = '<small class="form-validation-error" style="display: block">Wrong email format!</small>';
        header('Location: ../sign-up-page.php');
    }

    $sql_email_check = sprintf("SELECT id FROM Users WHERE BINARY email='%s'", $user_email);
    $sql_stmt = $pdo->prepare($sql_email_check);
    if ($sql_stmt->execute()) {
        if ($sql_query_rows = $sql_stmt->rowCount() > 0) {
            $_SESSION['is_form_correct'] = false;
            $_SESSION['form-email-error'] = '<small class="form-validation-error" style="display: block">Email is not unique.</small>';
            header('Location: ../sign-up-page.php');
            $sql_stmt->closeCursor();
        }
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $user_pass)) {
        // Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-pass-error'] = '<div class="form-validation-error" style="display: block">Password must contain minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character.</div>';
        header('Location: ../sign-up-page.php');
    }

    if (strcmp($user_pass, $user_rpass) <> 0) {
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-pass-error'] = '<small class="form-validation-error" style="display: block">Passwords are not the same.</small>';
        header('Location: ../sign-up-page.php');
    }

    $secret_key = "6LdmoNMZAAAAAKQGGLGgQVs790btNUeTkUgjQJwf";
    $check_rechaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$_POST['g-recaptcha-response']);
    $recive_json = json_decode($check_rechaptcha);

    if ($recive_json->success == false){
        $_SESSION['is_form_correct'] = false;
        $_SESSION['form-recaptcha-error'] = '<small class="form-validation-error" style="display: block">Confirm that you are human.</small>';
        header('Location: ../sign-up-page.php');
    }

    $register_query = "INSERT INTO Users (login, password, email) VALUES ('%s', '%s', '%s')";
    $secure_sql_query = sprintf($register_query, $user_login, $user_pass_hash, $user_email);

    if ($_SESSION['is_form_correct']) {
        $sql_stmt = $pdo->prepare($secure_sql_query);
        if ($sql_stmt->execute()) {
            $_SESSION['query-acc-created'] = '<div class="reg-success"> Success! Your account has been created. You can now login!</div>';
            header('Location: ../sign-in-page.php');
            return;
        } else {
            $_SESSION['query-error'] = '<div class="reg-error"> Query error! Something went wrong check our website later!</div>';
            header('Location: ../sign-up-page.php');
            return;
        }
    }
    $pdo = null;

} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Connecting to database failed! Something went wrong check our website later! </div>';
    header('Location: ../sign-up-page.php');
    return;
}