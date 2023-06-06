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
            <p><?php echo $post['content']; ?></p>
        </div>
        <footer>
            <?php if ($sqlCheckIfPostIsLikedResult && $sqlCheckIfPostIsLikedResult->num_rows === 0) { ?>
                <small>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                        <input type="hidden" name="like_button" value="<?php echo $post['post_id'] ?>">
                        <button type="submit">
                            <img src="icons/coeur-vide.png" class="like" alt="Coeur vide"> <?php echo $post['like_number']; ?>
                        </button>
                    </form>
                </small>
            <?php } else { ?>
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
            if (isset($_SESSION['connected_id']) && $post['post_user_id'] == $_SESSION['connected_id']) { ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                    <input type="hidden" name="delete_post" value="<?php echo $post['post_id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            <?php
            }
            $list_of_tags = explode(',', $post['taglist']);
            $list_of_tag_id = explode(',', $post['tag_id_list']);
            $last_element = array_pop($list_of_tag_id);
            array_unshift($list_of_tag_id, $last_element);

            if ($list_of_tags !== [""]) {
            ?> <p> <?php
                    for ($i = 0; $i < sizeof($list_of_tags); $i++) {
                        if ($i != 0) {
                            echo ", ";
                        }
                        echo "<a href='/gamer4ever/tags.php?tag_id=" . $list_of_tag_id[$i] . "'>#" . $list_of_tags[$i] . "</a>";
                    }
                    ?> </p> <?php
                            }
                                ?>
        </footer>
    </article>
<?php } ?>