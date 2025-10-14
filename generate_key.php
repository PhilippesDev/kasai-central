<?php
require 'config.php'; // Connexion à la base de données

function generateKey($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $key;
}

function saveKeyToDatabase($key, $user_id = null, $expiration = null) {
    global $conn; // Connexion mysqli depuis config.php
    
    // Hacher la clé
    $key_hash = password_hash($key, PASSWORD_BCRYPT);
    
    // Préparer la requête
    $stmt = mysqli_prepare($conn, "INSERT INTO admin_keys (key_hash, user_id, expires_at) VALUES (?, ?, ?)");
    if (!$stmt) {
        return false;
    }
    
    // Binder les paramètres
    mysqli_stmt_bind_param($stmt, "sis", $key_hash, $user_id, $expiration);
    
    // Exécuter la requête
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return $key; // Retourner la clé en clair pour l'afficher à l'utilisateur
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

// Exemple d'utilisation
$key = generateKey(8); // Génère une clé de 8 caractères
$expiration = date('Y-m-d H:i:s', strtotime('+30 days')); // Expire dans 30 jours
$user_id = 1; // ID de l'utilisateur (optionnel)

$generated_key = saveKeyToDatabase($key, $user_id, $expiration);
if ($generated_key) {
    echo "Clé générée et enregistrée : $generated_key";
} else {
    echo "Erreur lors de l'enregistrement de la clé.";
}
?>