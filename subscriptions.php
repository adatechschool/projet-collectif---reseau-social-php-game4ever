<?php
session_start();
$title = "Mes abonnements"
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnements</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div id="wrapper">
        <?php include 'includes/aside_user.php' ?>

        </section>
        </aside>
        <main class='contacts'>
            <?php
            // Get the user we follow
            $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$user_id'
                    GROUP BY users.id
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);

            // Show the posts
            include 'includes/article_follow.php';
            ?>
        </main>
    </div>
</body>

</html>