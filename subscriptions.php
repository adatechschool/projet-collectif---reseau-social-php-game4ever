<?php
session_start();
$title = "Mes abonnements"
?>

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