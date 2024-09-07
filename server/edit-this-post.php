<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $title = htmlentities($_POST['new-post-data-title'], ENT_QUOTES, "utf-8");
    $category = htmlentities($_POST['new-post-data-option'], ENT_QUOTES, "utf-8");
    $content = htmlentities($_POST['new-post-data-text'], ENT_QUOTES, "utf-8");
    $post_id = $_SESSION['post_id'];
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content) || empty($category)) {
        $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block; margin: 5px">Please complete the form!</small>';
        header('Location: ../edit-post.php?id=' . $_SESSION['post_id'] . '');
        return;
    }

    // Updating post
    $secure_sql_query = sprintf("UPDATE Posts SET title='%s', content='%s', category='%s' WHERE id='%s'", $title, $content, $category, $post_id);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $_SESSION['new-post-success'] = '<div class="reg-success">Success! Your post has been <strong>updated!</strong></div>';
        unset($_SESSION['post_id']);
        $sql_stmt->closeCursor();
        header('Location: ../.');
    } else {
        $_SESSION['new-post-error'] = '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; Warrning! Your post could not be <strong>updated!</strong> </div>';
        header('Location: ../edit-post.php?id='. $_SESSION['post_id'].'');
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../edit-post.php?id=' . $_SESSION['post_id'] . '');
    return;
}
