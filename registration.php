<?php session_start();
$title = "Inscription" ?>

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
                            
                            if(isset($_POST['console'])) {
                                $selected_consoles = implode(',', $_POST['console']);
                                $selected_consoles = $mysqli->real_escape_string($selected_consoles);
                            } else {
                                $selected_consoles = "";
                            }

                            // Query to add new user
                            $lInstructionSql = "INSERT INTO users (id, email, password, alias, console) "
                                    . "VALUES (NULL, "
                                    . "'" . $new_email . "', "
                                    . "'" . $new_passwd . "', "
                                    . "'" . $new_alias . "', '"
                                    . $selected_consoles
                                    . "');";
                                    
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
                        <div  style="display:flex;">
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
                        <!-- Form to get the plateforms on which the user's playing -->
                        <dl>
                            <h2>Sur quelle(s) console(s) jouez-vous ?</h3>
                            <div id="consoles">
                                <div class="choix_console">
                                    <input type="checkbox" name="console[]" value="Switch">
                                    <label for="Switch">Switch</label>
                                </div>

                                <div class="choix_console">
                                    <input type="checkbox" name="console[]" value="PC">
                                    <label for="PC">PC</label>
                                </div>

                                <div class="choix_console">
                                    <input type="checkbox" name="console[]" value="PS4/PS5">
                                    <label for="PS4/PS5">PS4/PS5</label>
                                </div>

                                <div class="choix_console">
                                    <input type="checkbox" name="console[]" value="XBOX">
                                    <label for="XBOX">XBOX</label>
                                </div>

                                <div class="choix_console">
                                    <input type="checkbox" name="console[]" value="Mobile">
                                    <label for="Mobile">Mobile</label>
                                </div>
                            </div>
                        </dl>
                        </div>
                        <input type='submit'>
                    </form>
                    <p> Vous avez déjà un compte ? <a href="/gamer4ever/login.php" style="color:blue;">Connectez-vous</a></p>
                </article>
            </main>
        </div>
    </body>
</html>
