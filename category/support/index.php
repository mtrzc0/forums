<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../layout/layout.css">
    <link rel="favicon icon" href="https://img.icons8.com/fluent/48/000000/faq.png">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <title>Forums | Support</title>
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <div class="nav-title">
                    Forums
                </div>
                <?php
                if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']) {
                    echo '
                            <ul class="nav-buttons-ul">
                                <li class="nav-buttons-li">
                                    <a href="../../.">
                                        Home &nbsp;
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li class="nav-buttons-li">
                                    <a href="../../account.php">
                                        <strong>
                                            Account &nbsp;
                                            <i class="fas fa-user"></i>
                                        </strong>
                                    </a>
                                </li>
                            </ul>';
                } else {
                    echo '
                            <ul class="nav-buttons-ul">
                                <li class="nav-buttons-li">
                                    <a href="../../.">
                                        Home &nbsp;
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li class="nav-buttons-li">
                                    <a href="../../sign-in-page.php">
                                        <strong>Log In</strong> &nbsp;
                                        <i class="fas fa-sign-in-alt"></i>
                                    </a>
                                </li>
                            </ul>';
                }
                ?>
            </nav>
        </header>
        <div class="qa-container">
            <div class="qa-main">
                <?php

                // if GET method is not set set default sort value to newest
                if (!isset($_GET['sort'])) $_GET['sort'] = "newest";

                ?>
                <div class="qa-sort-container">
                    <ul class="qa-sort">
                        <li>
                            <a href="?sort=newest" <?php if (isset($_GET['sort']) && $_GET['sort'] == "newest") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fas fa-certificate"></i> &nbsp; Newest
                            </a>
                        </li>
                        <li>
                            <a href="?sort=hot" <?php if (isset($_GET['sort']) && $_GET['sort'] == "hot") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fab fa-hotjar"></i> &nbsp; Hot
                            </a>
                        </li>
                        <li>
                            <a href="?sort=top" <?php if (isset($_GET['sort']) && $_GET['sort'] == "top") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fas fa-signal"></i> &nbsp; Top
                            </a>
                        </li>
                        <li>
                            <a href="?sort=oldest" <?php if (isset($_GET['sort']) && $_GET['sort'] == "oldest") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fas fa-book"></i> &nbsp; Oldest
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="article-main-title-content">
                    <div class="article-main-title" style="margin-top: 10px">Support</div>
                </div>
                <main class="qa-main-content">
                    <?php

                    try {

                        include_once $_SERVER['DOCUMENT_ROOT'] . "/forums/service/bootstrap.php";
                        require_once $_SERVER['DOCUMENT_ROOT'] . "/forums/config/settings.php";

                        if (!isset($_GET['sort']) || $_GET['sort'] == 'newest') {
                            $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND p.category=\'Support\' ORDER BY p.date DESC';
                        } else if ($_GET['sort'] == 'oldest') {
                            $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND p.category=\'Support\' ORDER BY p.date ASC';
                        } else if ($_GET['sort'] == 'hot') {
                            $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND p.category=\'Support\' AND (DATEDIFF(NOW(), p.date) <= 7 AND p.votes > 100) ORDER BY p.date DESC';
                        } else if ($_GET['sort'] == 'top') {
                            $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND p.category=\'Support\' AND (p.votes > 100) ORDER BY comments DESC';
                        } else {
                            $sql_query = 'SELECT p.id, p.title, p.category, p.content, p.votes, p.date, p.user_id, u.login, (SELECT COUNT(*) FROM Comments AS c WHERE c.post_id = p.id) AS comments FROM Posts AS p, Users AS u WHERE u.id = p.user_id AND p.category=\'Support\' ORDER BY p.date DESC';
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
                                                    <img class="qa-post-user-img" src="../../img/user-avatar.png">
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
                                                        <a href="#"> <i class="fas fa-share"></i> &nbsp; Share </a>
                                                    </div>
                                                </div>
                                        </div>';
                                }
                                $sql_stmt->closeCursor();
                            } else {
                                $_SESSION['query-acc-error'] = '<div class="reg-warning"> Query error! Check our website later! </div>';
                                return;
                            }
                        } catch (PDOException $e) {
                            $_SESSION['query-acc-error'] = '<div class="reg-error"> System could not connect to the database! Check our website later! </div>';
                            return;
                        }
                    } catch (Throwable $t) {
                        echo '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; System could not get content! Check our website later! </div>';
                    }

                    if (isset($_SESSION['query-error'])) {
                        echo $_SESSION['query-error'];
                        unset($_SESSION['query-error']);
                    }

                    ?>
                </main>
            </div>
            <aside class="main-aside">
                <div class="main-aside-search-bar">
                    <form action="/forums/search.php" method="POST" autocomplete="off">
                        <input class="main-aside-search" name="searchBar" type="text" placeholder="Search...">
                        <button class="main-aside-search-btn" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="main-aside-content">
                    <div class="main-aside-cat">
                        <h4 class="main-aside-cat-h4">Categories</h4>
                        <div class="main-aside-cat-elements">
                            <a href="../computer_networking">
                                <img src="https://img.icons8.com/fluent/48/000000/ethernet-off.png" alt="computer networking" title="Computer Networking" />
                            </a>
                            <a href="../computer_hardware">
                                <img src="https://img.icons8.com/fluent/48/000000/video-card.png" alt="computer hardware" title="Computer Hardware" />
                            </a>
                            <a href="../operating_systems">
                                <img src="https://img.icons8.com/fluent/48/000000/windows-10.png" alt="operating systems" title="Operating Systems" />
                            </a>
                            <a href="../applications">
                                <img src="https://img.icons8.com/fluent/48/000000/desktop.png" alt="applications" title="Applications" />
                            </a>
                            <a href="../art">
                                <img src="https://img.icons8.com/fluent/48/000000/pencil-tip.png" alt="art" title="Art" />
                            </a>
                            <a href="../coding">
                                <img src="https://img.icons8.com/fluent/48/000000/code.png" alt="programming" title="Coding" />
                            </a>
                            <a href="../learning">
                                <img src="https://img.icons8.com/fluent/48/000000/books.png" alt="learning" title="Learning" />
                            </a>
                            <a href="../security">
                                <img src="https://img.icons8.com/fluent/48/000000/password--v2.png" alt="security" title="Security" />
                            </a>
                            <a href="../mobile_phones">
                                <img src="https://img.icons8.com/fluent/48/000000/android.png" alt="mobile phones" title="Mobile phones" />
                            </a>
                            <a href="../off_top">
                                <img src="https://img.icons8.com/fluent/48/000000/crowd.png" alt="offtop" title="Off top" />
                            </a>
                            <a href="../forum_topics">
                                <img src="https://img.icons8.com/fluent/48/000000/bookmark-ribbon.png" alt="forum topics" title="Forum topics" />
                            </a>
                            <a href="../support">
                                <img src="https://img.icons8.com/fluent/48/000000/ask-question.png" alt="support" title="Support" />
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

        </div>

        <footer>
            Mateusz Trzeciak &copy; <?php echo date('Y') ?>. All rights reserved.
            <a class="footer-link" href="https://icons8.com" target="blank">Website icons by Icons8</a>
        </footer>
    </div>
</body>

</html>