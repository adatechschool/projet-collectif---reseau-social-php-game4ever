<?php
session_start();
$title = "Flux";
?>

    <?php include 'includes/header.php' ?>
        <div id="wrapper">

            <?php include 'includes/aside_user.php' ?>
            <main>
                <?php
                // Like/Unlike function
                include "includes/like_unlike.php";
                
                // Query to get the posts of the user we follow
                $laQuestionEnSql = "
                    SELECT posts.id AS post_id,
                    posts.content,
                    posts.created,
                    users.id AS post_user_id,
                    users.alias as author_name,  
                    posts.like_count as like_number,
                    GROUP_CONCAT(DISTINCT tags.label ORDER BY FIND_IN_SET(tags.id, posts.tag_id_list)) AS taglist,
                    posts.tag_id_list  
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$user_id' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC 
                    LIMIT 20; 
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                // Show the posts
                include 'includes/article.php' ?>


            </main>
        </div>
    </body>
</html>
