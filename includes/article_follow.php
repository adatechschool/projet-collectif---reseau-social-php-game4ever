<?php while ($follow = $lesInformations->fetch_assoc()) {
?>

    <article>
        <img src="image/profilpicture.jpg" alt="blason" />
        <h3><?php echo $follow['alias'] ?></h3>
        <p><?php echo $follow['id'] ?></p>
    </article>
<?php } ?>