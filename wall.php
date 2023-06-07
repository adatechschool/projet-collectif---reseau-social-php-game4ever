<?php
session_start();
$title = "Mur";
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php include 'includes/header.php' ?>

    <div id="wrapper">


        <?php include 'includes/aside_user.php' ?>
        <main>
            <?php
            if ($user_id == $_SESSION['connected_id']) {
                // $_POST['new_post] is retrieved when we click on the button in the form further bellow
                $enCoursDeTraitement = isset($_POST['new_post']);
                if ($enCoursDeTraitement && $_POST['new_post'] !== "") {
                    $author_id = $user_id;
                    $post_content = $_POST['new_post'];
                    // Check for SQL injection
                    $post_content = $mysqli->real_escape_string(($post_content));

                    // Query to insert the post in the DB (DataBase)
                    $lInstructionSql = "INSERT INTO posts "
                        . "(user_id, content, created, parent_id) "
                        . "VALUES ("
                        . $author_id . ","
                        . "'" . $post_content . "', "
                        . "NOW(), "
                        . "NULL);";

                    $ok = $mysqli->query($lInstructionSql);
                    if (!$ok) {
                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                    } else {
                        // Get the id of the post we just created in the DB
                        $post_id = $mysqli->insert_id;
                        // echo "Message posté en tant que : " . $user["alias"]; le message napparait pas
                    

                        // We want to extract the tags # from the message we just posted
                        // Tags extraction from user post
                        preg_match_all("/#(\w+)/", $post_content, $post_tag_list);
                        $post_tag_list = $post_tag_list[1];
                        // Check for repetition in the array
                        $post_tag_list = array_unique($post_tag_list);
                        // Join the list of tag for the query
                        $tags_joined = "'" . implode("','", $post_tag_list) . "'";

                        // SQL request to get a list of already existing tags and another list of their ID
                        $sqlGetTagList = "SELECT id, label FROM tags WHERE label IN (" . $tags_joined . ");";
                        $tags_result = $mysqli->query($sqlGetTagList);
                        $existing_tags = array();
                        $existing_tags_id = array();
                        while ($row = $tags_result->fetch_assoc()) {
                            $existing_tags[] = $row['label'];
                            $existing_tags_id[] = $row['id'];
                        }

                        // Get the list of tags that user used but isn't in the DB
                        $tags_to_insert = array_diff($post_tag_list, $existing_tags);


                        function link_post_to_tag($tag_id, $post_id)
                        {
                            $sqlLinkPostTag = "INSERT INTO posts_tags "
                                . "(post_id, tag_id) "
                                . "VALUES ('" . $post_id . "', '"
                                . $tag_id . "');";

                            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                            $sqlResult = $mysqli->query($sqlLinkPostTag);
                            if (!$sqlResult) {
                                echo $mysqli->error;
                            }
                        }

                        // Link tag_id and post_id in the posts_tags table
                        // For existing tags
                        if ($existing_tags !== [""] && $existing_tags !== []) {
                            foreach ($existing_tags_id as $tag_id) {
                                link_post_to_tag($tag_id, $post_id);
                            }
                        }

                        // Add new tag
                        if ($tags_to_insert !== [""] && $tags_to_insert !== []) {
                            foreach ($tags_to_insert as $new_tag) {
                                $sqlAddNewTag = "INSERT INTO tags (label) VALUES ('" . $new_tag . "');";
                                $ok = $mysqli->query($sqlAddNewTag);
                                if (!$ok) {
                                    echo "Impossible d'ajouter le tag : " . $mysqli->error;
                                } else {
                                    // Get the id of the tag we just created
                                    $tag_id = $mysqli->insert_id;

                                    // Link the new tag to the post
                                    link_post_to_tag($tag_id, $post_id);
                                }
                            }
                        }

                        // Get the tags' id of the post to get a ordered list of tags (without repetition)
                        // First, transform the tag_list into string
                        $post_tags = implode("','", $post_tag_list);
                        $post_tag_list = implode(",", $post_tag_list);
                        // Second, make the query and get the corresponding tag_id_list
                        $sqlGetTagId = "SELECT id FROM tags WHERE label IN ('$post_tags') ORDER BY FIELD(label, '".$post_tag_list."')";
                        $sqlGetTagIdResult = $mysqli->query($sqlGetTagId);
                        $tag_id_list = array();
                        while ($row = $sqlGetTagIdResult->fetch_assoc()) {
                            $tag_id_list[] = $row['id'];
                        }
                        $tag_id_list = implode(',', $tag_id_list);

                        // Then add the tag_id_list to the tag_id_list column of the new post
                        $sqlAddTagIdList = "UPDATE posts
                            SET tag_id_list = '" . $tag_id_list 
                            . "' WHERE posts.id = " . $post_id . ";";
                        $sqlAddTagIdListResult = $mysqli->query($sqlAddTagIdList);
                    }
                }
            ?>
                <article>
                    <form action="wall.php" method="post">
                        <dl>
                            <dt><label for="new_post">Avez-vous quelque chose à dire ?</label></dt>
                            <dd><textarea name="new_post"></textarea></dd>
                        </dl>
                        <button type="submit" id="submit_form">Poster</button>
                    </form>
                </article>
            <?php
            } else {
                if ($_SERVER['REQUEST_METHOD'] === "POST") {
                    // If the subscribe button is pressed
                    if (isset($_POST['subscribe_button'])) {
                        $sqlInstruction = "INSERT INTO followers "
                            . "(followed_user_id, following_user_id) "
                            . "VALUES ("
                            . $user_id . ", "
                            . $_SESSION['connected_id'] . ");";

                        $ok = $mysqli->query($sqlInstruction);
                        if (!$ok) {
                            echo "<article>Impossible de s'abonner : " . $mysqli->error . "</article>";
                        } else {
                            // Reload the current page
                            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
                            exit();
                        }
                    }
                    // If the unsubscribe button is pressed
                    else if (isset($_POST['unsubscribe_button'])) {
                        $sqlInstruction = "DELETE FROM followers "
                            . "WHERE followed_user_id = " . $user_id
                            . " AND following_user_id = " . $_SESSION['connected_id'] . ";";
                        $ok = $mysqli->query($sqlInstruction);
                        if (!$ok) {
                            echo "<article>Impossible de se désabonner : " . $mysqli->error . "</article>";
                        } else {
                            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
                            exit();
                        }
                    }
                }
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

            // Like/Unlike function
            include "includes/like_unlike.php";

            // Get posts from the DB and show them on the wall page
            $laQuestionEnSql = "
                SELECT posts.id AS post_id, posts.content, posts.created, users.alias AS author_name, users.id AS post_user_id,
                posts.like_count AS like_number, GROUP_CONCAT(DISTINCT tags.label ORDER BY FIND_IN_SET(tags.id, posts.tag_id_list)) AS taglist,
                posts.tag_id_list 
                FROM posts
                JOIN users ON users.id = posts.user_id
                LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                LEFT JOIN tags ON posts_tags.tag_id = tags.id
                LEFT JOIN likes ON likes.post_id = posts.id
                WHERE posts.user_id = '$user_id'
                GROUP BY posts.id
                ORDER BY posts.created DESC
                LIMIT 20;";

            $lesInformations = $mysqli->query($laQuestionEnSql);
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }

            // Show the posts
            include 'includes/article.php' ?>

        </main>
    </div>
</body>

</html>