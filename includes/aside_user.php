<aside>
    <?php
    $laQuestionEnSql = "SELECT * FROM users WHERE id= '$user_id' ";
    $lesInformations = $mysqli->query($laQuestionEnSql);
    $user = $lesInformations->fetch_assoc();
    ?>
    <img src="image/profilpicture.jpg" alt="Portrait de l'utilisatrice" />
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
    <?php
    // Query to modify the users possessed consoles
    if (isset($_POST['console'])) {
        $selected_consoles = implode(',', $_POST['console']);
        $selected_consoles = $mysqli->real_escape_string($selected_consoles);
        $sqlUpdateConsole = "UPDATE users "
            . "SET console ='" . $selected_consoles
            . "' WHERE users.id = " . $_SESSION['connected_id'] . " ;";
        $sqlUpdateConsoleResult = $mysqli->query($sqlUpdateConsole);
        if (!$sqlUpdateConsoleResult) {
            echo "La modification a échouée : " . $mysqli->error;
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
            exit();
        }
    } else {
        $selected_consoles = "";
    }



    // Show the consoles the user has if he has any
    if ($user['console'] !== NULL and $user['console'] !== "") {
        $possessed_consoles = explode(",", $user['console']) ?>
        <section>
            <h3>Consoles possédées :</h3>
            <ul>
                <?php foreach ($possessed_consoles as $console) {
                    echo "<li>" . $console . "</li>";
                } ?>
            </ul>
        </section>
    <?php }
    // Create a button to modify the user's possessed consoles
    if ($user_id == $_SESSION['connected_id']) { ?>
        <script>
            // Once the button is clicked, show the form to select new possessed consoles
            function afficherModificationConsole() {
                document.getElementById('afficherModifConsole').style.display = 'none';
                document.getElementById('changerConsolesForm').style.display = 'block';
            }
        </script>
        <!-- The button that hide the form -->
        <button id="afficherModifConsole" onclick="afficherModificationConsole()">Changer les consoles possédées</button>

        <!-- The form where the user select the consoles -->
        <form method='POST' action='<?php $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] ?>' id="changerConsolesForm" style="display:none;">
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
                    <button type="submit">Envoyer</button>
                </div>
        </form>
    <?php } ?>

</aside>