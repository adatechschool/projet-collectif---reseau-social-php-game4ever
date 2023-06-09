<?php
session_start();
$title = "Actualités";
?>

    <?php include 'includes/header.php' ?>
    <div id="wrapper">
        <?php include 'includes/aside_user.php' ?>

        <main>
            <?php
            include "includes/like_unlike.php";

            // Query to get the 5 last posts
            $laQuestionEnSql = "
                    SELECT posts.id AS post_id,
                    posts.content,
                    posts.created,
                    users.id AS post_user_id,
                    users.alias as author_name,  
                    posts.like_count as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label ORDER BY FIND_IN_SET(tags.id, posts.tag_id_list)) AS taglist,
                    posts.tag_id_list 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Verification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            // Show the posts
            include 'includes/article.php' ?>

        </main>
    </div>
</body>

</html>