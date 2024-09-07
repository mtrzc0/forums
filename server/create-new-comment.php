<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $content = htmlentities($_POST['new-comment-data-text'], ENT_QUOTES, "utf-8");
    $user_id = $_SESSION['user_id'];
    $post_id = $_SESSION['post_id'];

    if (empty($content)) {
        $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block; margin: 5px">Please complete the form!</small>';
        header('Location: ../article.php?id='.$_SESSION['post_id']);
        return;
    }

    // Inserting comment to DB
    $secure_sql_query = sprintf("INSERT INTO Comments (content, post_id, user_id) VALUES ('%s', '%s', '%s')", $content, $post_id, $user_id);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $_SESSION['new-comment-success'] = '<div class="reg-success">Success! Your comment has been <strong>published!</strong></div>';
        $sql_stmt->closeCursor();
        header('Location: ../article.php?id='.$_SESSION['post_id']);
    } else {
        $_SESSION['new-comment-error'] = '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; Warrning! Your comment could not be <strong>published!</strong> </div>';
        header('Location: ../article.php?id='.$_SESSION['post_id']);
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../article.php?id='.$_SESSION['post_id']);
    return;
}
