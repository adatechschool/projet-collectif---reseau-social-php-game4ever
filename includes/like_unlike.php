<?php
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['like_button'])) {
    $sqlLike = "INSERT INTO likes "
        . "(user_id, post_id) "
        . "VALUES (" . $_SESSION['connected_id'] . ", "
        . $_POST['like_button'] .");";
    
    // Redeclared because it's in the header.php and no access to header.php
    $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
    $ok = $mysqli->query($sqlLike);
    if ( ! $ok)
    {
        echo "<article>Impossible de liker : " . $mysqli->error . "</article>";
    }
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['unlike_button'])) {
    $sqlUnlike = "DELETE FROM likes "
        . "WHERE user_id = " . $_SESSION['connected_id']
        . " AND post_id = " . $_POST['unlike_button'] . ";";

    $ok = $mysqli->query($sqlUnlike);
    if ( ! $ok)
    {
        echo "<article>Impossible de unliker : " . $mysqli->error . "</article>";
    }
} ?>