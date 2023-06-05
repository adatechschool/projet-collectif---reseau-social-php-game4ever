<?php $title = "Inscription" ?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Inscription</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php include 'includes/header.php' ?>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Bienvenu sur notre réseau social.</p>
            </aside>
            <main>
                <article>
                    <h2>Inscription</h2>
                    <?php
                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    $password_mismatch = false;
                    // Check if user entered an email
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        $new_email = $_POST['email'];
                        $new_alias = $_POST['pseudo'];
                        $new_passwd = $_POST['motpasse'];
                        $confirmation_password = $_POST['motpasse2'];

                        // Verify if the passwords match
                        if ($confirmation_password !== $new_passwd) {
                            $password_mismatch = true;
                        } else {

                            // Check for SQL injection
                            $new_email = $mysqli->real_escape_string($new_email);
                            $new_alias = $mysqli->real_escape_string($new_alias);
                            $new_passwd = $mysqli->real_escape_string($new_passwd);
                            // Encrypt password (md5, not secure, we know)
                            $new_passwd = md5($new_passwd);
                            
                            // Query to add new user
                            $lInstructionSql = "INSERT INTO users (id, email, password, alias) "
                                    . "VALUES (NULL, "
                                    . "'" . $new_email . "', "
                                    . "'" . $new_passwd . "', "
                                    . "'" . $new_alias . "'"
                                    . ");";
                                    
                            // DB call
                            $ok = $mysqli->query($lInstructionSql);
                            if ( ! $ok)
                            {
                                // Prompt error message
                                echo "L'inscription a échouée : " . $mysqli->error;
                            } else
                            {
                                echo "Votre inscription est un succès : " . $new_alias . "  ";
                                echo " <a href='login.php'>Connectez-vous.</a>";
                            }
                        }
                    }
                    ?>                     
                    <form action="registration.php" method="post">
                        <input type='hidden'name='???' value='achanger'>
                        <dl>
                            <dt><label for='pseudo'>Pseudo</label></dt>
                            <dd><input type='text'name='pseudo'></dd>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                            <?php
                                if ($password_mismatch) {
                                    echo "<p style='color:red;'>Les mots de passe ne correspondent pas.</p>";
                                }
                            ?>
                            <dt><label for='motpasse'>Confirmer votre mot de passe</label></dt>
                            <dd><input type='password'name='motpasse2'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                </article>
            </main>
        </div>
    </body>
</html>
