<?php

session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/forums/lib/render-content.php";

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
    <title>Forums | Comment Page</title>
</head>

<body>
    <div class="container">
        <header>
            <nav>
                <div class="nav-title">
                    Forums
                </div>
                <?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged']):?>
                            <ul class="nav-buttons-ul">
                                <li class="nav-buttons-li">
                                    <a href="index.php">
                                        Home &nbsp;
                                        <i class="fas fa-home"></i>
                                    </a>
                                </li>
                                <li class="nav-buttons-li">
                                    <a href="account.php">
                                        <strong>
                                            Account &nbsp;
                                            <i class="fas fa-user"></i>
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                <?php else:?>
                            <ul class="nav-buttons-ul">
                                <li class="nav-buttons-li">
                                    <a href="index.php">
                                        Home &nbsp;
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
                <?php endif?>
            </nav>
        </header>
        <div class="comment-container">
            <div class="comment-main">
                <?php
                if (isset($_SESSION['new-comment-success'])) {
                    echo $_SESSION['new-comment-success'];
                    unset($_SESSION['new-comment-success']);
                } else if (isset($_SESSION['new-comment-error'])) {
                    echo $_SESSION['new-comment-error'];
                    unset($_SESSION['new-comment-error']);
                }
                ?>
                <main class="comment-main-content">
                    <?php

                    try {
                        require_once $_SERVER['DOCUMENT_ROOT'] . "/forums/config/settings.php";
                        $_SESSION['comment_id'] = isset($_GET['id']) ? $_GET['id'] : null;

                        try {

                            $pdo = new PDO($db_dsn, $db_user, $db_pass);
                            $sql_query = 'SELECT id, date, content, votes, user_id, post_id, (SELECT login FROM `users` WHERE users.id = comments.user_id) AS login FROM `comments` WHERE id='.$_SESSION['comment_id'];
                            $sql_stmt = $pdo->prepare($sql_query);
                            if ($sql_stmt->execute()) {
                                $row = $sql_stmt->fetch();
                    ?>
                                <div class="comment-main-element">
                                    <div class="comment-data">
                                        <img class="comment-user-img" src="/forums/img/user-avatar.png">
                                        <div class="comment-user-info">
                                            <span class="comment-header">
                                                Commented by
                                            </span> <span class="comment-user-login"><?php echo $row['login'] ?> </span><span class="comment-header"> <?php toTime($row['date']) ?> </span>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['isLogged']) && $_SESSION['login'] === $row['login']) {
                                            printf('
                                                <div class="comment-edit">
                                                    <a href="/forums/edit-comment.php?id=%s"><i class="far fa-edit"></i> &nbsp; Edit</a>
                                                </div>', $_SESSION['comment_id']);
                                        }
                                        ?>
                                    </div>
                                    <div class="comment-content"> <?php echo $row['content'] ?> </div>
                                    <div class="comment-buttons">
                                        <div class="comment-button-arrow-up">
                                            <a href="#"> <i class="fas fa-chevron-up"></i> &nbsp; <?php echo $row['votes'] ?> </a>
                                        </div>
                                    </div>
                                </div>
                    <?php
                                $sql_stmt->closeCursor();
                            } else {
                                $_SESSION['query-acc-error'] = '<div class="reg-warning"> System could not get content! Check our website later! </div>';
                                return;
                            }
                        } catch (PDOException $e) {
                            $_SESSION['query-acc-error'] = '<div class="reg-error"> System could not connect to the database! Check our website later! </div>';
                            return;
                        }

                    } catch (Throwable $t) {
                        echo '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; System could not get database! Check our website later! </div>';
                    }

                    ?>

                    <?php

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
                            <a href="category/computer_networking">
                                <img src="https://img.icons8.com/fluent/48/000000/ethernet-off.png" alt="computer networking" title="Computer Networking" />
                            </a>
                            <a href="category/computer_hardware">
                                <img src="https://img.icons8.com/fluent/48/000000/video-card.png" alt="computer hardware" title="Computer Hardware" />
                            </a>
                            <a href="category/operating_systems">
                                <img src="https://img.icons8.com/fluent/48/000000/windows-10.png" alt="operating systems" title="Operating Systems" />
                            </a>
                            <a href="category/applications">
                                <img src="https://img.icons8.com/fluent/48/000000/desktop.png" alt="applications" title="Applications" />
                            </a>
                            <a href="category/art">
                                <img src="https://img.icons8.com/fluent/48/000000/pencil-tip.png" alt="art" title="Art" />
                            </a>
                            <a href="category/coding">
                                <img src="https://img.icons8.com/fluent/48/000000/code.png" alt="programming" title="Coding" />
                            </a>
                            <a href="category/learning">
                                <img src="https://img.icons8.com/fluent/48/000000/books.png" alt="learning" title="Learning" />
                            </a>
                            <a href="category/security">
                                <img src="https://img.icons8.com/fluent/48/000000/password--v2.png" alt="security" title="Security" />
                            </a>
                            <a href="category/mobile_phones">
                                <img src="https://img.icons8.com/fluent/48/000000/android.png" alt="mobile phones" title="Mobile phones" />
                            </a>
                            <a href="category/off_top">
                                <img src="https://img.icons8.com/fluent/48/000000/crowd.png" alt="offtop" title="Off top" />
                            </a>
                            <a href="category/forum_topics">
                                <img src="https://img.icons8.com/fluent/48/000000/bookmark-ribbon.png" alt="forum topics" title="Forum topics" />
                            </a>
                            <a href="category/support">
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