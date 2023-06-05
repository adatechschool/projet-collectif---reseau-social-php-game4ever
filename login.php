<?php
session_start();
$title = "Connexion";
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - <?php echo $title ?></title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'includes/header.php' ?>

    <div id="wrapper">

        <aside>
            <h2>Présentation</h2>
            <p>Bienvenu sur notre réseau social.</p>
        </aside>
        <main>
            <article>
                <h2>Connexion</h2>
                <?php
                /**
                 * TRAITEMENT DU FORMULAIRE
                 */

                // Check if user entered an email
                $enCoursDeTraitement = isset($_POST['email']);
                if ($enCoursDeTraitement) {
                    // Visualize $_POST variable
                    // echo "<pre>" . print_r($_POST, 1) . "</pre>";

                    $emailAVerifier = $_POST['email'];
                    $passwdAVerifier = $_POST['motpasse'];

                    // Check for potential SQL injection
                    $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                    $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                    // Encryption of the password (md5, not really secure though)
                    $passwdAVerifier = md5($passwdAVerifier);

                    // Query to select the row of the user where the email correspond
                    $lInstructionSql = "SELECT * "
                        . "FROM users "
                        . "WHERE "
                        . "email LIKE '" . $emailAVerifier . "'";

                    // Send query to DB and check if password correspond and email exist in DB
                    $res = $mysqli->query($lInstructionSql);
                    $user = $res->fetch_assoc();
                    if (!$user or $user["password"] != $passwdAVerifier) {
                        echo "La connexion a échouée. ";
                    } else {
                        echo "Votre connexion est un succès : " . $user['alias'] . ".";

                        // Stock the user_id in the SESSION
                        $_SESSION['connected_id'] = $user['id'];
                        // refresh to show login or logout
                        header("Location: " . $_SERVER['PHP_SELF']);
                    }
                }
                ?>
                <form action="login.php" method="post">
                    <input type='hidden' name='???' value='achanger'>
                    <dl>
                        <dt><label for='email'>E-Mail</label></dt>
                        <dd><input type='email' name='email'></dd>
                        <dt><label for='motpasse'>Mot de passe</label></dt>
                        <dd><input type='password' name='motpasse'></dd>
                    </dl>
                    <input type='submit'>
                </form>
                <p>
                    Pas de compte?
                    <a href='registration.php'>Inscrivez-vous.</a>
                </p>

            </article>
        </main>
    </div>
</body>

</html>