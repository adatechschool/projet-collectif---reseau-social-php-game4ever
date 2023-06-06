<?php
session_start();
$title = "Mes abonné.es"
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mes abonnés </title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div id="wrapper">
        <aside>
            <img src="image/profilpicture.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez la liste des personnes qui
                    suivent les messages de l'utilisatrice
                    n° <?php echo $_SESSION['connected_id'] ?></p>

            </section>
        </aside>
        <main class='contacts'>
            <?php
            // Get our followers
            $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$user_id'
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