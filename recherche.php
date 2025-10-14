<?php

$searchTerm = isset($_GET['q']) ? trim(htmlspecialchars($_GET['q'])) : '';


$pages = [
    [
        'title' => 'Histoire du Kasaï Central',
        'url' => 'histoire.html',
        'description' => 'Découvrez les origines, les événements marquants et l\'évolution de notre province',
        'keywords' => 'histoire, origines, évolution, dates importantes',
        'category' => 'Présentation',
        'icon' => 'landmark'
    ],
    [
        'title' => 'Géographie du Kasaï Central',
        'url' => 'geo-kasai-central.html',
        'description' => 'Informations sur le relief, le climat et le découpage territorial de la province',
        'keywords' => 'géographie, relief, climat, territoires',
        'category' => 'Présentation',
        'icon' => 'globe'
    ],
    [
        'title' => 'Administration provinciale',
        'url' => 'administration.html',
        'description' => 'Structure du gouvernement provincial et biographies des membres',
        'keywords' => 'gouvernement, gouverneur, ministres, administration',
        'category' => 'Administration',
        'icon' => 'building'
    ],
    [
        'title' => 'Actualités récentes',
        'url' => 'actu-kasai-central.php',
        'description' => 'Les dernières nouvelles et événements du Kasaï Central',
        'keywords' => 'actualités, news, événements, annonces',
        'category' => 'Actualités',
        'icon' => 'newspaper'
    ],
    [
        'title' => 'Tourisme et attractions',
        'url' => 'tourisme.html',
        'description' => 'Découvrez les sites touristiques et attractions de la province',
        'keywords' => 'tourisme, sites, attractions, visites',
        'category' => 'Tourisme',
        'icon' => 'camera'
    ],
    
];


function filterResults($pages, $searchTerm) {
    if (empty($searchTerm)) return [];
    
    $results = [];
    $lowerSearchTerm = strtolower($searchTerm);
    
    foreach ($pages as $page) {
        
        if (strpos(strtolower($page['title']), $lowerSearchTerm) !== false ||
            strpos(strtolower($page['description']), $lowerSearchTerm) !== false ||
            strpos(strtolower($page['keywords']), $lowerSearchTerm) !== false) {
            $results[] = $page;
        }
    }
    
    return $results;
}


$results = filterResults($pages, $searchTerm);
$hasResults = count($results) > 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche | Kasaï Central</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
  
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-header {
            background-color: #2563eb;
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        .search-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .search-term {
            font-weight: bold;
            font-style: italic;
        }
        .results-count {
            color: #6b7280;
            margin-bottom: 20px;
        }
        .result-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .result-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .result-url {
            color: #10b981;
            font-size: 0.875rem;
            margin-bottom: 10px;
            display: block;
        }
        .result-description {
            color: #4b5563;
            margin-bottom: 10px;
        }
        .result-category {
            display: inline-block;
            background-color: #e0e7ff;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .no-results {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
        }
        .highlight {
            background-color: #fef08a;
            padding: 0 2px;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <header class="search-header">
        <div class="container">
            <h1 class="search-title">Résultats de recherche</h1>
            <?php if (!empty($searchTerm)): ?>
                <p>Pour le terme: <span class="search-term"><?php echo $searchTerm; ?></span></p>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <?php if (empty($searchTerm)): ?>
            <div class="no-results">
                <p>Aucun terme de recherche spécifié.</p>
            </div>
        <?php elseif (!$hasResults): ?>
            <div class="no-results">
                <p>Aucun résultat trouvé pour <strong>"<?php echo $searchTerm; ?>"</strong>.</p>
                <p>Suggestions :</p>
                <ul style="text-align: left; max-width: 500px; margin: 10px auto;">
                    <li>Vérifiez l'orthographe de votre recherche</li>
                    <li>Utilisez des termes plus généraux</li>
                    <li>Essayez d'autres mots-clés</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="results-count">
                <?php echo count($results); ?> résultat(s) trouvé(s)
            </div>

            <?php foreach ($results as $result): ?>
                <a href="<?php echo $result['url']; ?>" class="result-card">
                    <div class="result-title">
                        <?php echo highlightKeywords($result['title'], $searchTerm); ?>
                    </div>
                    <span class="result-url"><?php echo $result['url']; ?></span>
                    <p class="result-description">
                        <?php echo highlightKeywords($result['description'], $searchTerm); ?>
                    </p>
                    <span class="result-category">
                        <i class="fas fa-<?php echo $result['icon']; ?>"></i>
                        <?php echo $result['category']; ?>
                    </span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <?php
    
    function highlightKeywords($text, $searchTerm) {
        if (empty($searchTerm)) return htmlspecialchars($text);
        
        $words = explode(' ', $searchTerm);
        $highlighted = htmlspecialchars($text);
        
        foreach ($words as $word) {
            if (strlen($word) > 2) { 
                $highlighted = preg_replace(
                    "/(" . preg_quote($word, '/') . ")/i", 
                    '<span class="highlight">$1</span>', 
                    $highlighted
                );
            }
        }
        
        return $highlighted;
    }
    ?>
</body>
</html>