<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $post_id = $_SESSION['post_id'];

    // Deleting post
    $secure_sql_query = sprintf("DELETE FROM Posts WHERE id='%s'", $post_id);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $_SESSION['new-post-success'] = '<div class="reg-success">Success! Your post has been <strong>deleted!</strong></div>';
        unset($_SESSION['post_id']);
        $sql_stmt->closeCursor();
        header('Location: ../.');
    } else {
        $_SESSION['new-post-error'] = '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; Warrning! Your post could not be <strong>deleted!</strong> </div>';
        header('Location: ../edit-post.php?id=' . $_SESSION['post_id'] . '');
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../edit-post.php?id=' . $_SESSION['post_id'] . '');
    return;
}
