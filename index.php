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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="/forums/js/index.js" defer></script>
    <title>Forums | Home Page</title>
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
                        <a href=".">
                            Home &nbsp;
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-buttons-li nav-button-li-dropdown">
                        <a href="#">
                            <strong>
                                Account &nbsp;
                                <i class="fas fa-user"></i>
                            </strong>
                        </a>
                        <div class="nav-button-li-dropdown-content">
                            <a href="server/logout.php">
                                Logout &nbsp; <i class="fas fa-door-open"></i>
                            </a>
                        </div>
                    </li>
                </ul>
                <?php else:?>
                <ul class="nav-buttons-ul">
                    <li class="nav-buttons-li">
                        <a href=".">
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
        <div class="qa-container">
            <div class="qa-main">
                <noscript class="noscript-info">Forums works better with JavaScript &nbsp; <i
                        class="fas fa-exclamation-circle"></i></noscript>
                <?php
                if (isset($_SESSION['new-post-success'])) {
                    echo $_SESSION['new-post-success'];
                    unset($_SESSION['new-post-success']);
                }

                if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true) {
                    echo '
                    <div class="qa-new-post">
                        <input id="redirect-to-create-post" class="qa-new-post-input" type="text" placeholder="Create post">
                    </div>
                    ';
                }

                // if GET method is not set set default sort value to newest
                if (!isset($_GET['sort'])) $_GET['sort'] = "newest";

                ?>
                <div class="qa-sort-container">
                    <ul class="qa-sort">
                        <li>
                            <a href="?sort=newest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "newest") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fas fa-certificate"></i> &nbsp; Newest
                            </a>
                        </li>
                        <li>
                            <a href="?sort=hot"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "hot") echo 'style="background-color: #303030; color:#fff"'; ?>>
                                <i class="fab fa-hotjar"></i> &nbsp; Hot
                            </a>
                        </li>
                        <li>
                            <a href="?sort=top"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "top") echo 'style="background-color: #303030; color: #fff"'; ?>>
                                <i class="fas fa-signal"></i> &nbsp; Top
                            </a>
                        </li>
                        <li>
                            <a href="?sort=oldest"
                                <?php if (isset($_GET['sort']) && $_GET['sort'] == "oldest") echo 'style="background-color: #303030; color: #fff;"'; ?>>
                                <i class="fas fa-book"></i> &nbsp; Oldest
                            </a>
                        </li>
                    </ul>
                </div>
                <main class="qa-main-content">
                    <?php

                    try {
                        renderPosts();
                    } catch (Throwable $t) {
                        echo '<div class="reg-warning"><i class="fas fa-exclamation-triangle"></i> &nbsp; System could not get database! Check our website later! </div>';
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
                            <a href="category/computer_networking">
                                <img src="https://img.icons8.com/fluent/48/000000/ethernet-off.png"
                                    alt="computer networking" title="Computer Networking" />
                            </a>
                            <a href="category/computer_hardware">
                                <img src="https://img.icons8.com/fluent/48/000000/video-card.png"
                                    alt="computer hardware" title="Computer Hardware" />
                            </a>
                            <a href="category/operating_systems">
                                <img src="https://img.icons8.com/fluent/48/000000/windows-10.png"
                                    alt="operating systems" title="Operating Systems" />
                            </a>
                            <a href="category/applications">
                                <img src="https://img.icons8.com/fluent/48/000000/desktop.png" alt="applications"
                                    title="Applications" />
                            </a>
                            <a href="category/art">
                                <img src="https://img.icons8.com/fluent/48/000000/pencil-tip.png" alt="art"
                                    title="Art" />
                            </a>
                            <a href="category/coding">
                                <img src="https://img.icons8.com/fluent/48/000000/code.png" alt="programming"
                                    title="Coding" />
                            </a>
                            <a href="category/learning">
                                <img src="https://img.icons8.com/fluent/48/000000/books.png" alt="learning"
                                    title="Learning" />
                            </a>
                            <a href="category/security">
                                <img src="https://img.icons8.com/fluent/48/000000/password--v2.png" alt="security"
                                    title="Security" />
                            </a>
                            <a href="category/mobile_phones">
                                <img src="https://img.icons8.com/fluent/48/000000/android.png" alt="mobile phones"
                                    title="Mobile phones" />
                            </a>
                            <a href="category/off_top">
                                <img src="https://img.icons8.com/fluent/48/000000/crowd.png" alt="offtop"
                                    title="Off top" />
                            </a>
                            <a href="category/forum_topics">
                                <img src="https://img.icons8.com/fluent/48/000000/bookmark-ribbon.png"
                                    alt="forum topics" title="Forum topics" />
                            </a>
                            <a href="category/support">
                                <img src="https://img.icons8.com/fluent/48/000000/ask-question.png" alt="support"
                                    title="Support" />
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