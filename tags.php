<?php
session_start();
$title = "Les messages par mot-clé"
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Les message par mot-clé</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'includes/header.php' ?>
    <div id="wrapper">
        <?php
        $tagId = intval($_GET['tag_id']);
        ?>

        <aside>
            <?php
            // Query to get the tag with the tag_id
            $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            $tag = $lesInformations->fetch_assoc();
            ?>
            <img src="image/profilpicture.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages comportant
                    le mot-clé <?php echo '<b>' . $tag['label'] . '</b>' ?>
                    (n° <?php echo $tagId ?>)
                </p>

            </section>
        </aside>
        <main>
            <?php
            // Like/Unlike Function
            include "includes/like_unlike.php";

            // Get the posts with the selected tag_id
            $laQuestionEnSql = "
                    SELECT posts.id AS post_id, 
                    posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id AS post_user_id,  
                    posts.like_count as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label ORDER BY FIND_IN_SET(tags.id, posts.tag_id_list)) AS taglist,
                    posts.tag_id_list  
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            // Show the posts
            include 'includes/article.php' ?>


        </main>
    </div>
</body>

</html>