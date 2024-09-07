<?php

session_start();
require_once "../config/settings.php";

try {
    $pdo = new PDO($db_dsn, $db_user, $db_pass);

    $content = htmlentities($_POST['comment-data-text'], ENT_QUOTES, "utf-8");
    $post_id = $_SESSION['post_id'];
    $comment_id = $_SESSION['comment_id'];
    $user_id = $_SESSION['user_id'];

    if (empty($content)) {
        $_SESSION['form-match-error'] = '<small class="form-validation-error" style="display: block; margin: 5px">Please complete the form!</small>';
        header('Location: ../edit-comment.php?id='. $comment_id .'');
        return;
    }

    // Updating post
    $secure_sql_query = sprintf("UPDATE comments SET content='%s' WHERE id='%s'", $content, $comment_id);
    $sql_stmt = $pdo->prepare($secure_sql_query);
    if ($sql_stmt->execute()) {
        $_SESSION['new-comment-success'] = '<div class="reg-success">Success! Your comment has been <strong>updated!</strong></div>';
        $sql_stmt->closeCursor();
        header('Location: ../article.php?id='. $post_id .'');
    } else {
        $_SESSION['new-comment-error'] = '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; Warrning! Your comment could not be <strong>updated!</strong> </div>';
        header('Location: ../edit-comment.php?id='. $comment_id .'');
    }

    $pdo = null;
} catch (PDOException $e) {
    $_SESSION['query-error'] = '<div class="reg-error"> Fatal error! Something went wrong check our website later! </div>';
    header('Location: ../edit-comment.php?id=' . $comment_id . '');
    return;
}
