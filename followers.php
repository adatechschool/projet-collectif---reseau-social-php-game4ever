<?php
session_start();
$title = "Mes abonné.es"
?>

    <?php include 'includes/header.php' ?>
    <div id="wrapper">
        <?php include 'includes/aside_user.php' ?>
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