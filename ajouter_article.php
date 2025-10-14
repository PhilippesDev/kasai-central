<?php
session_start();


$connexion = mysqli_connect('127.0.0.1', 'root', '', 'bd_kasai_c');
if (!$connexion) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = mysqli_real_escape_string($connexion, $_POST['titre']);
    $description = mysqli_real_escape_string($connexion, $_POST['description']);
    $categorie = mysqli_real_escape_string($connexion, $_POST['categorie']);
    $date = $_POST['date'];

    
    $query = "INSERT INTO actualites (titre, description, categorie, date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', $titre, $description, $categorie, $date);
    mysqli_stmt_execute($stmt);

    $article_id = mysqli_insert_id($connexion);

    
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['media']['name'] as $key => $name) {
        if ($_FILES['media']['error'][$key] == 0) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $new_name = uniqid() . '.' . $ext;
            $destination = $upload_dir . $new_name;

            $type = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) ? 'video' : 'image';

            if (move_uploaded_file($_FILES['media']['tmp_name'][$key], $destination)) {
                $query = "INSERT INTO media (article_id, type, url) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($connexion, $query);
                mysqli_stmt_bind_param($stmt, 'iss', $article_id, $type, $destination);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header('Location: actu-kasai-central.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un nouvel article</title>
    <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100" style="font-family: 'Quicksand', sans-serif;">
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Ajouter un nouvel article</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="titre" class="block text-sm font-medium">Titre</label>
                <input type="text" id="titre" name="titre" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium">Description</label>
                <textarea id="description" name="description" required class="w-full p-2 border rounded" rows="6"></textarea>
            </div>

            <div>
                <label for="categorie" class="block text-sm font-medium">Catégorie</label>
                <select id="categorie" name="categorie" required class="w-full p-2 border rounded">
                    <option value="Administration">Administration</option>
                    <option value="Culture">Culture</option>
                    <option value="Sport">Sport</option>
                    <option value="Economie">Economie</option>
                </select>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium">Date de publication</label>
                <input type="datetime-local" id="date" name="date" required class="w-full p-2 border rounded">
            </div>

            <div>
                <label for="media" class="block text-sm font-medium">Médias (Images/Vidéos)</label>
                <input type="file" id="media" name="media[]" multiple accept="image/*,video/*" class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Publier</button>
        </form>
    </div>
</body>
</html>