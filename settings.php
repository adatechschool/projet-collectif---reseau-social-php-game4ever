<?php
session_start();
$title = "Paramètres";
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Paramètres</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php include 'includes/header.php' ?>
        <div id="wrapper" class='profile'>


            <?php include 'includes/aside_user.php' ?>
            <main>
                <?php
                // Get the user info
                $laQuestionEnSql = "
                    SELECT users.*, 
                    count(DISTINCT posts.id) as totalpost, 
                    count(DISTINCT given.post_id) as totalgiven, 
                    count(DISTINCT recieved.user_id) as totalreceived 
                    FROM users 
                    LEFT JOIN posts ON posts.user_id=users.id 
                    LEFT JOIN likes as given ON given.user_id=users.id 
                    LEFT JOIN likes as recieved ON recieved.post_id=posts.id 
                    WHERE users.id = '$user_id' 
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                } else {
                    $user = $lesInformations->fetch_assoc();
                    ?>                
                    <article class='parameters'>
                        <h3>Mes paramètres</h3>
                        <dl>
                            <dt>Pseudo</dt>
                            <dd><?php echo $user['alias'] ?></dd>
                            <dt>Email</dt>
                            <dd><?php echo $user['email'] ?></dd>
                            <dt>Nombre de message</dt>
                            <dd><?php echo $user['totalpost'] ?></dd>
                            <dt>Nombre de "J'aime" donnés </dt>
                            <dd><?php echo $user['totalgiven'] ?></dd>
                            <dt>Nombre de "J'aime" reçus</dt>
                            <dd><?php echo $user['totalreceived'] ?></dd>
                        </dl>

                    </article>
                <?php } ?>
                
            </main>
        </div>
    </body>
</html>
