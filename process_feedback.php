<?php

session_start();

require('config.php'); 


$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (empty($name) || empty($email) || empty($feedback) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "Veuillez remplir tous les champs correctement.";
    header("Location: feedback.php");
    exit();
}


$sql = "INSERT INTO feedback (name, email, feedback, created_at) VALUES ('$name', '$email', '$feedback', NOW())";

if (mysqli_query($conn, $sql)) {
    $_SESSION['success_message'] = "Votre feedback a été envoyé avec succès ! Merci de partager votre expérience.";
} else {
    $_SESSION['error_message'] = "Erreur lors de l'enregistrement : " . mysqli_error($conn);
}


mysqli_close($conn);


header("Location: feedback.php");
exit();
?>