<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/forums/service/bootstrap.php";

function renderPosts()
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/forums/config/settings.php";

    if (!isset($_GET['sort']) || $_GET['sort'] == 'newest') {
        $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id ORDER BY p.date DESC';
    } else if ($_GET['sort'] == 'oldest') {
        $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id ORDER BY p.date ASC';
    } else if ($_GET['sort'] == 'hot') {
        $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND (DATEDIFF(NOW(), p.date) <= 7 AND p.votes > 100) ORDER BY p.date DESC';
    } else if ($_GET['sort'] == 'top') {
        $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND (p.votes > 100) ORDER BY comments DESC';
    } else {
        $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id ORDER BY p.date DESC';
    }

    try {
        $pdo = new PDO($db_dsn, $db_user, $db_pass);
        $sql_stmt = $pdo->prepare($sql_query);
        if ($sql_stmt->execute()) {
            $rows = $sql_stmt->fetchAll();
            for ($i = 0; $i < sizeof($rows); $i++) {
                echo '
                    <div class="qa-post-main-element">
                        <a href="/forums/article.php?' . "id=" . $rows[$i]['id'] . '" class="qa-post-main-element-link">
                            <div class="qa-post-data">
                                <img class="qa-post-user-img" src="/forums/img/user-avatar.png">
                                    <span class="qa-post-user-info">
                                            <span class="qa-post-header">
                                                Posted by
                                            </span> ' . '<span class="qa-post-user-login">' . $rows[$i]['login'] . '</span>' . ' <span class="qa-post-header"> ' . toTime($rows[$i]['date']) . ' </span>
                                    </span>
                            </div>
                            <h5 class="qa-post-title"> ' . $rows[$i]['title'] . '<span class="qa-post-cat">' . $rows[$i]['category'] . '</span>' . ' </h5>
                            <div class="qa-post-content"> ' . $rows[$i]['content'] . ' </div>
                        </a>
                            <div class="qa-post-buttons">
                                <div class="qa-post-button-arrow-up">
                                    <a href="#"> <i class="fas fa-chevron-up"></i> &nbsp; ' . $rows[$i]['votes'] . ' </a>
                                </div>
                                <div class="qa-post-button-comment">
                                    <a href="article.php?' . "id=" . $rows[$i]['id'] . '"> <i class="fas fa-comment-alt"></i> &nbsp; ' . printComment($rows[$i]['comments']) . ' </a>
                                </div>
                                <div class="qa-post-button-share">
                                    <a href="?id='.$rows[$i]['id'].'">
                                        <i class="fas fa-share"></i>
                                        <span>&nbsp; Share</span>
                                    </a>
                                </div>
                            </div>
                    </div>';
            }
            $sql_stmt->closeCursor();
        } else {
            $_SESSION['query-acc-error'] = '<div class="reg-warning"> System could not get content! Check our website later! </div>';
            return;
        }
    } catch (PDOException $e) {
        $_SESSION['query-acc-error'] = '<div class="reg-error"> System could not connect to the database! Check our website later! </div>';
        return;
    }
}

function renderComments()
{
    include $_SERVER['DOCUMENT_ROOT'] . "/forums/config/settings.php";
    // $_SESSION['post_id'] = isset($_GET['id']) ? $_GET['id'] : null;

    try {
        $pdo = new PDO($db_dsn, $db_user, $db_pass);
        $sql_query = 'SELECT c.id AS comment_id, c.content, c.votes, c.date, c.user_id, c.post_id, p.id, u.login FROM Posts AS p, Users AS u, Comments AS c WHERE p.id = c.post_id AND u.id = c.user_id AND c.post_id=' . $_SESSION['post_id'] . ' ORDER BY c.date ASC';
        $sql_stmt = $pdo->prepare($sql_query);
        if ($sql_stmt->execute()) {
            $rows = $sql_stmt->fetchAll();
            if (!empty($rows)) {
                for ($i = 0; $i < sizeof($rows); $i++) {
                    echo '
                        <div class="article-comment-main-element">
                            <a href="/forums/comment.php?' . "id=" . $rows[$i]['comment_id'] . '" class="article-comment-main-element-link">
                                <div class="article-comment-data">
                                    <div class="article-comment-data-container">
                                        <img class="article-comment-user-img" src="/forums/img/user-avatar.png">
                                        <span class="article-comment-user-info">
                                                <span class="article-comment-header">
                                                    Commented by
                                                </span> ' .
                                                '<span class="article-comment-user-login">' .
                                                    $rows[$i]['login'] .
                                                '</span>' .
                                                ' <span class="article-comment-header"> ' .
                                                toTime($rows[$i]['date']) . '</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="article-comment-content"> ' . $rows[$i]['content'] . ' </div>
                            </a>
                                <div class="article-comment-buttons">
                                    <div class="article-comment-button-arrow-up">
                                        <a href="#"> <i class="fas fa-chevron-up"></i> &nbsp; ' . $rows[$i]['votes'] . ' </a>
                                    </div>
                                    <div class="article-comment-button-comment">
                                        <a href="#"> <i class="fas fa-comment-alt"></i> &nbsp; Reply </a>
                                    </div>
                                </div>
                        </div>';
                }
            } else {
                echo '
                    <div class="nocomment-main-element nohover">
                        <div class="nocomment">
                            <i class="fas fa-comments"></i> <br>
                            &nbsp; No comments yet <br>
                            What do you mean? Share with others!
                        </div>
                    </div>';
            }
            $sql_stmt->closeCursor();
        } else {
            $_SESSION['query-acc-error'] = '<div class="reg-warning"> System could not get content! Check our website later! </div>';
            return;
        }
    } catch (PDOException $e) {
        $_SESSION['query-acc-error'] = '<div class="reg-error"> System could not connect to the database! Check our website later! </div>';
        return;
    }
}

function renderSearchResult()
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/forums/config/settings.php";

    $phrase = isset($_POST['searchBar']) ? htmlspecialchars($_POST['searchBar']) : '';

    $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE (u.id = p.user_id) AND (p.title LIKE "%'. $phrase .'%") ORDER BY p.date DESC';

    try {
        $pdo = new PDO($db_dsn, $db_user, $db_pass);
        $sql_stmt = $pdo->prepare($sql_query);
        if ($sql_stmt->execute()) {
            $rows = $sql_stmt->fetchAll();
            for ($i = 0; $i < sizeof($rows); $i++) {
                echo '
                    <div class="qa-post-main-element">
                        <a href="/forums/article.php?' . "id=" . $rows[$i]['id'] . '" class="qa-post-main-element-link">
                            <div class="qa-post-data">
                                <img class="qa-post-user-img" src="/forums/img/user-avatar.png">
                                    <span class="qa-post-user-info">
                                            <span class="qa-post-header">
                                                Posted by
                                            </span> ' . '<span class="qa-post-user-login">' . $rows[$i]['login'] . '</span>' . ' <span class="qa-post-header"> ' . toTime($rows[$i]['date']) . ' </span>
                                    </span>
                            </div>
                            <h5 class="qa-post-title"> ' . $rows[$i]['title'] . '<span class="qa-post-cat">' . $rows[$i]['category'] . '</span>' . ' </h5>
                            <div class="qa-post-content"> ' . $rows[$i]['content'] . ' </div>
                        </a>
                            <div class="qa-post-buttons">
                                <div class="qa-post-button-arrow-up">
                                    <a href="#"> <i class="fas fa-chevron-up"></i> &nbsp; ' . $rows[$i]['votes'] . ' </a>
                                </div>
                                <div class="qa-post-button-comment">
                                    <a href="article.php?' . "id=" . $rows[$i]['id'] . '"> <i class="fas fa-comment-alt"></i> &nbsp; ' . printComment($rows[$i]['comments']) . ' </a>
                                </div>
                                <div class="qa-post-button-share">
                                    <a href="?id='.$rows[$i]['id'].'">
                                        <i class="fas fa-share"></i>
                                        <span>&nbsp; Share</span>
                                    </a>
                                </div>
                            </div>
                    </div>';
            }

            $sql_stmt->closeCursor();
        } else {
            $_SESSION['query-acc-error'] = '<div class="reg-warning"> System could not get content! Check our website later! </div>';
            header('location: index.php');
            return;
        }
    } catch (PDOException $e) {
        $_SESSION['query-acc-error'] = '<div class="reg-error"> System could not connect to the database! Check our website later! </div>';
        header('location: index.php');
        return;
    }
}