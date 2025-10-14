<?php
session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Bienvenue dans l'administration</h1>
</body>
</html>