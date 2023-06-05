<aside>
    <?php
    $laQuestionEnSql = "SELECT * FROM users WHERE id= '$user_id' ";
    $lesInformations = $mysqli->query($laQuestionEnSql);
    $user = $lesInformations->fetch_assoc();
    ?>
    <img src="user.jpg" alt="Portrait de l'utilisatrice" />
    <section>
        <h3>Présentation</h3>
        <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias'] ?>
            (n° <?php echo $user_id ?>)
        </p>
        <p>
            <?php if ($user_id != $_SESSION['connected_id']) {

                // Query to check if we follow the user
                $sqlIsFollowing = "SELECT * FROM followers "
                    . "WHERE followed_user_id = " . $user_id
                    . " AND following_user_id = " . $_SESSION['connected_id'] . ";";

                $sqlFollowingResult = $mysqli->query($sqlIsFollowing);
                if ($sqlFollowingResult && $sqlFollowingResult->num_rows === 0) { ?>
        <form action="wall.php?user_id=<?php echo $user_id ?>" method="post">
            <button type="submit" name="subscribe_button">Subscribe</button>
        </form>
    <?php } else { ?>
        <form action="wall.php?user_id=<?php echo $user_id ?>" method="post">
            <button type="submit" name="unsubscribe_button">Unsubscribe</button>
        </form>
<?php }
            } ?>

</p>
    </section>
</aside>