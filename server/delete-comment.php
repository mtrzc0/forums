<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);
    $comment_id = $_SESSION['comment_id'];

    // Deleting comment
    $secure_sql_query = sprintf("DELETE FROM Comments WHERE id='%s'", $comment_id);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $_SESSION['new-comment-success'] = '<div class="reg-success">Success! Your comment has been <strong>deleted!</strong></div>';
        $sql_stmt->closeCursor();
        header('Location: ../article.php?id='. $_SESSION['post_id'] .'');
    } else {
        $_SESSION['new-comment-error'] = '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; Warrning! Your comment could not be <strong>deleted!</strong> </div>';
        header('Location: ../article.php?id='. $_SESSION['post_id'].'');
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../article.php?id='. $_SESSION['post_id'].'');
    return;
}
