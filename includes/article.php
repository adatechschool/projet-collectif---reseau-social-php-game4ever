<!-- Iterate every posts the query returned -->
<?php while ($post = $lesInformations->fetch_assoc()) {

    // Query to check if the post is liked by the connected user
    $sqlCheckIfPostIsLiked = "SELECT * FROM likes "
        . "WHERE user_id = " . $_SESSION['connected_id']
        . " AND post_id = " . $post['post_id'] . ";";

    $sqlCheckIfPostIsLikedResult = $mysqli->query($sqlCheckIfPostIsLiked);

?>
    <article>
        <h3>
            <time><?php echo $post['created']; ?></time>
        </h3>
        <address>par <a href="/gamer4ever/wall.php?user_id=<?php echo $post['post_user_id'] ?>"><?php echo  $post['author_name']; ?></a></address>
        <div>
            <?php 
            // Get a list of tags in the post and another with their id
            $list_of_tags = explode(',', $post['taglist']);
            $list_of_tag_id = explode(',', $post['tag_id_list']);

            $post_content = $post['content'];
            // Replace every tag by a link to the corresponding tag.php page

            for ($i = 0; $i < sizeof($list_of_tags); $i++) {
                $tag_id = $list_of_tag_id[$i];
                $tag = $list_of_tags[$i];
                $tag_link = "<a href=\"tags.php?tag_id=" . $tag_id . "\">#" . $tag . "</a>";
            
                // Utiliser une expression régulière pour rechercher et remplacer les tags
                $pattern = '/#' . preg_quote($tag, '/') . '\b/';
                $post_content = preg_replace($pattern, $tag_link, $post_content);
            }
            ?>

            <p><?php echo $post_content; ?></p>
        </div>
        <footer>
            <!-- Button to like if the post isn't already liked -->
            <?php if ($sqlCheckIfPostIsLikedResult->num_rows === 0) { ?>
                <small>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                        <input type="hidden" name="like_button" value="<?php echo $post['post_id'] ?>">
                        <button type="submit">
                            <img src="icons/coeur-vide.png" class="like" alt="Coeur vide"> <?php echo $post['like_number']; ?>
                        </button>
                    </form>
                </small>
            <?php } // Button to unlike if the post is already liked
            else { ?>
                <small>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                        <input type="hidden" name="unlike_button" value="<?php echo $post['post_id'] ?>">
                        <button type="submit">
                            <img src="icons/coeur-plein.png" class="like" alt="Coeur plein"> <?php echo $post['like_number']; ?>
                        </button>
                    </form>
                </small>
            <?php
            }
            if (isset($_SESSION['connected_id']) && $post['post_user_id'] == $_SESSION['connected_id'] && basename($_SERVER['PHP_SELF']) == 'wall.php') { ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                    <input type="hidden" name="delete_post" value="<?php echo $post['post_id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            <?php
            }
            ?>
        </footer>
    </article>
<?php } ?>