<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - <?php echo $title ?></title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <img src="image/casque.png" alt="Logo de notre réseau social" />
        <?php
        // User cannot access any other page if not connected
        if (!isset($_SESSION['connected_id'])) {
            ?>
            <nav id="menu">
                <a href="login.php">Login</a>
                <a href="registration.php">Inscription</a>
            </nav>
        </header>
        <article id='not_connected'>Vous n'êtes pas connecté.</article>
        <?php
        if ($_SERVER['PHP_SELF'] !== "/gamer4ever/registration.php" && $_SERVER['PHP_SELF'] !== "/gamer4ever/login.php") {
            header("Location: /gamer4ever/login.php");
            exit();
        }
    } else {
        ?>
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php">Mur</a>
            <a href="feed.php">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <a href="#">Profil</a>
            <ul>
                <li><a href="settings.php">Paramètres</a></li>
                <li><a href="followers.php">Mes suiveurs</a></li>
                <li><a href="subscriptions.php">Mes abonnements</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

<?php
}
// Check if a user_id is in the URL.
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
}
// Else, check if there is a user_id stored in the $_SESSION.
else if (isset($_SESSION['connected_id'])) {
    $user_id = intval($_SESSION['connected_id']);
}

// Connection to the DB (DataBase)
$mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

// Verification
if ($mysqli->connect_errno) {
    echo "<article>";
    echo ("Échec de la connexion : " . $mysqli->connect_error);
    echo ("<p>Indice: Vérifiez les paramètres de <code>new mysqli(...</code></p>");
    echo "</article>";
    exit();
}

// query to delete a post 
if (isset($_SESSION['connected_id']) && isset($_POST['delete_post'])) {
    // Delete the post from the posts table
    $sqlDeletePost = "DELETE FROM posts WHERE id = " . $_POST['delete_post'] . ";";
    $ok = $mysqli->query($sqlDeletePost);
    if (!$ok) {
        echo "Impossible de supprimer le post : " . $mysqli->error;
    } else {
        echo "message supprimé";
        header('Location: ' . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
    }
}
?>
