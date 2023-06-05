<?php
$current_user_id = $_SESSION['connected_id'];
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['like_button'])) {
    $post_id = $_POST['like_button'];

    $sqlCheckLike = "SELECT * FROM likes WHERE user_id = $current_user_id AND post_id = $post_id";
    $resultCheckLike = $mysqli->query($sqlCheckLike);

    if ($resultCheckLike->num_rows === 0) {
        $sqlLike = "INSERT INTO likes "
            . "(user_id, post_id) "
            . "VALUES (" . $current_user_id . ", "
            . $post_id .");";
        
        // Redeclared because it's in the header.php and no access to header.php
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        $ok = $mysqli->query($sqlLike);
        if ( ! $ok)
        {
            echo "<article>Impossible de liker : " . $mysqli->error . "</article>";
        } else {
            $sqlUpdateLikeNumber = "UPDATE posts SET like_count = like_count + 1 WHERE id = $post_id";
            $ok = $mysqli->query($sqlUpdateLikeNumber);
            unset($_POST['like_button']);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['unlike_button'])) {
    $post_id = $_POST['unlike_button'];
    $sqlCheckUnlike = "SELECT * FROM likes WHERE user_id = $current_user_id AND post_id = $post_id";
    $resultCheckUnlike = $mysqli->query($sqlCheckUnlike);

    if ($resultCheckUnlike->num_rows > 0) {
        $sqlUnlike = "DELETE FROM likes "
            . "WHERE user_id = " . $_SESSION['connected_id']
            . " AND post_id = " . $post_id . ";";

        $ok = $mysqli->query($sqlUnlike);
        if ( ! $ok)
        {
            echo "<article>Impossible de unliker : " . $mysqli->error . "</article>";
        } else {
            $sqlUpdateLikeNumber = "UPDATE posts SET like_count = like_count - 1 WHERE id = $post_id";
            $ok = $mysqli->query($sqlUpdateLikeNumber);
            unset($_POST['unlike_button']);
        }
    }
} ?>