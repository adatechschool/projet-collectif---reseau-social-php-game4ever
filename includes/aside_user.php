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

                // Subscribe button
                if ($sqlFollowingResult && $sqlFollowingResult->num_rows === 0) { ?>
        <form action="wall.php?user_id=<?php echo $user_id ?>" method="post">
            <button type="submit" name="subscribe_button">Subscribe</button>
        </form>
    <?php } // Unsubscribe button
                else { ?>
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
        if (in_array("Je n'ai pas de console", $_POST['console'])) {
            $selected_consoles = NULL;
        } else {
            $selected_consoles = implode(',', $_POST['console']);
            $selected_consoles = $mysqli->real_escape_string($selected_consoles);
        }
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
    }

    // Query to modify possessed games
    if (isset($_POST['jeux'])) {
        if (in_array("Je n'ai pas de jeu", $_POST['jeux'])) {
            $selected_games = NULL;
        } else {
            $selected_games = implode(',', $_POST['jeux']);
            $selected_games = $mysqli->real_escape_string($selected_games);
        }
        $sqlUpdateJeux = "UPDATE users "
            . "SET jeux ='" . $selected_games
            . "' WHERE users.id = " . $_SESSION['connected_id'] . " ;";
        $sqlUpdateJeuxResult = $mysqli->query($sqlUpdateJeux);
        if (!$sqlUpdateJeuxResult) {
            echo "La modification a échouée : " . $mysqli->error;
        } else {
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
            exit();
        }
    }


    // Console possessed by the user part
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
            <h2>Sur quelle(s) console(s) jouez-vous ?</h2>
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
                <div class="choix-console">
                    <input type="checkbox" name="console[]" value="Je n'ai pas de console">
                    <label for="Je n'ai pas de console">Je n'ai pas de console</label>
                </div>
                <button type="submit">Envoyer</button>
            </div>
        </form>
    <?php }

    // Games possessed by the user part
    // Show the possessed games if the user has any
    if ($user['jeux'] !== NULL and $user['jeux'] !== "") {
        $possessed_jeux = explode(",", $user['jeux']) ?>
        <section>
            <h3>Jeux possédées :</h3>
            <ul>
                <?php foreach ($possessed_jeux as $jeu) {
                    echo "<li>" . $jeu . "</li>";
                } ?>
            </ul>
        </section>
    <?php }
    // Create a button to modify the user's possessed games
    if ($user_id == $_SESSION['connected_id']) { ?>
        <script>
            // Once the button is clicked, show the form to select new possessed games
            function afficherModificationJeux() {
                document.getElementById('afficherModifJeux').style.display = 'none';
                document.getElementById('changerJeuxForm').style.display = 'block';
            }
        </script>
        <!-- The button that hide the form -->
        <button id="afficherModifJeux" onclick="afficherModificationJeux()">Changer les jeux préférés</button>

        <!-- The form where the user select the games -->
        <form method='POST' action='<?php $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] ?>' id="changerJeuxForm" style="display:none;">
            <h2>A quel(s) jeu(x)jouez-vous ?</h2>
            <div>
                <?php
                // Liste des jeux
                $jeux = array(
                    array('name' => 'Metal Gear Solid'),
                    array('name' => 'Street Fighter'),
                    array('name' => 'Monster Hunter'),
                    array('name' => 'Tetris'),
                    array('name' => 'Batman'),
                    array('name' => 'Final Fantasy VII'),
                    array('name' => 'Super Mario'),
                    array('name' => 'Zelda: A Link to the Past'),
                    array('name' => 'Dead or Alive 2'),
                    array('name' => 'League of Legends')
                );

                // Générer le formulaire
                foreach ($jeux as $jeu) {
                    $name = $jeu['name'];

                    // Générer le code HTML pour chaque jeu
                    echo '<div class="choix_jeu">';
                    echo '<input type="checkbox" name="jeux[]" value="' . $name . '">';
                    echo '<label for="' . $name . '">' . $name . '</label>';
                    echo '</div>';
                } ?>
                <div class="choix_jeu">
                    <input type="checkbox" name="jeux[]" value="Je n'ai pas de jeu">
                    <label for="Je n'ai pas de jeu">Je n'ai pas de jeu</label>
                </div>
                <button type="submit">Envoyer</button>
            </div>
        </form>

    <?php } ?>

    <script>
        function handleLastOptionChange(lastOption, checkboxes) {
            if (lastOption.checked) {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = (checkbox === lastOption);
                    checkbox.disabled = (checkbox !== lastOption);
                });
            } else {
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                });
            }
        }

        function handleCheckboxChange(checkbox, lastOption, checkboxes) {
            if (checkbox.checked && lastOption.checked && checkbox !== lastOption) {
                lastOption.checked = false;
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                });
            }
        }

        const lastOptionConsoles = document.querySelector('#changerConsolesForm input[value="Je n\'ai pas de console"]');
        const checkboxesConsoles = document.querySelectorAll('#changerConsolesForm input[name="console[]"]');

        lastOptionConsoles.addEventListener('change', function() {
            handleLastOptionChange(lastOptionConsoles, checkboxesConsoles);
        });

        checkboxesConsoles.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                handleCheckboxChange(checkbox, lastOptionConsoles, checkboxesConsoles);
            });
        });

        const lastOptionJeux = document.querySelector('#changerJeuxForm input[value="Je n\'ai pas de jeu"]');
        const checkboxesJeux = document.querySelectorAll('#changerJeuxForm input[name="jeux[]"]');

        lastOptionJeux.addEventListener('change', function() {
            handleLastOptionChange(lastOptionJeux, checkboxesJeux);
        });

        checkboxesJeux.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                handleCheckboxChange(checkbox, lastOptionJeux, checkboxesJeux);
            });
        });
    </script>
</aside>