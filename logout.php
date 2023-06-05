<?php
// unset of data in PHPSESSID and destruction with a negativ cookie
session_start();
session_unset();
session_destroy();

// Supprimer le cookie PHPSESSID
setcookie("PHPSESSID", "", time() - 3600, "/");
// Rediriger vers la page login.php
header("Location: login.php");
exit();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="author" content="">
</head>

<body></body>

</html>