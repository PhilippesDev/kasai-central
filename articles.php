<?php
header('Content-Type: application/json');
$connexion = mysqli_connect('127.0.0.1', 'root', '', 'bd_kasai_c');
if (!$connexion) {
    die(json_encode(['error' => 'Échec de la connexion à la base de données']));
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;
$categorie = isset($_GET['categorie']) ? mysqli_real_escape_string($connexion, $_GET['categorie']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($connexion, $_GET['search']) : '';

$query = "SELECT a.*, m.url, m.type 
          FROM actualites a 
          LEFT JOIN media m ON a.id = m.article_id 
          WHERE 1=1";

if ($categorie) {
    $query .= " AND a.categorie = '$categorie'";
}
if ($search) {
    $query .= " AND (a.titre LIKE '%$search%' OR a.description LIKE '%$search%')";
}

$query .= " ORDER BY a.date DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($connexion, $query);
$articles = [];
$current_article_id = null;

while ($row = mysqli_fetch_assoc($result)) {
    if ($current_article_id !== $row['id']) {
        $articles[$row['id']] = [
            'id' => $row['id'],
            'titre' => $row['titre'],
            'description' => $row['description'],
            'categorie' => $row['categorie'],
            'date' => $row['date'],
            'media' => []
        ];
        $current_article_id = $row['id'];
    }

    if ($row['url']) {
        $articles[$row['id']]['media'][] = [
            'type' => $row['type'],
            'url' => $row['url']
        ];
    }
}

echo json_encode(array_values($articles));
mysqli_close($connexion);
?>