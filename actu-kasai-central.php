<?php

require 'config.php';

$sort = $_GET['sort'] ?? 'newest';
$limit = $_GET['limit'] ?? 'all';

$query = "
    SELECT a.id, a.titre, a.description, a.categorie, a.date, m.type, m.url
    FROM actualites a
    LEFT JOIN media m ON a.id = m.article_id
";


if ($sort === 'oldest') {
    $query .= " ORDER BY a.date ASC";
} else {
    $query .= " ORDER BY a.date DESC";
}

if ($limit !== 'all') {
    $query .= " LIMIT " . (int)$limit;
}

$result = mysqli_query($conn, $query);
$articles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $article_id = $row['id'];
    if (!isset($articles[$article_id])) {
        $articles[$article_id] = [
            'titre' => $row['titre'],
            'description' => $row['description'],
            'categorie' => $row['categorie'],
            'date' => $row['date'],
            'media' => []
        ];
    }
    if ($row['url']) {
        $articles[$article_id]['media'][] = [
            'type' => $row['type'],
            'url' => $row['url']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="page d'actualités de Kasai Central, avec des articles récents et des médias associés.">
    <meta name="keywords" content="Kasai Central, actualités, articles, médias, vidéos, images">
    <meta name="author" content="Ir Philippe mirindi lukogo">

    <title>Actualités | Kasai Central</title>
    <link rel="shortcut icon" href="images/favicon.jpg" type="image/x-icon">
    <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nunito:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        .category-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #6b7280b4;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 50;
        }

        .video-overlay {
            pointer-events: none;
        }

        header {
            position: relative !important;
            height: auto !important;
            min-height: unset !important;
            max-height: none !important;
        }
    </style>
</head>

<body class="relative h-0 bg-blue-50 dark:bg-black" style="font-family: 'Nunito', sans-serif;">
    <div class="p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 border border-gray-200 border-t-4 border-b-gray-600">
        <h2 class="font-bold text-2xl text-blue-600 whitespace-nowrap">Nos actualités</h2>

        <div class="flex-grow w-full sm:max-w-md border border-gray-200 rounded-xl shadow-2xs dark:bg-neutral-800 dark:border-neutral-700">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none ps-3.5 z-20">
                    <svg class="shrink-0 w-6 h-6 text-gray-400 dark:text-white/60" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                <input
                    id="search-input"
                    type="text"
                    placeholder="Rechercher une actualité..."
                    class="block w-full py-2.5 ps-10 pe-4 rounded-lg border border-gray-200 sm:text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" />
            </div>
        </div>

        <a href="index.php" class="flex items-center gap-2 text-blue-600 font-bold whitespace-nowrap">
            <i class="fa-solid fa-arrow-left p-2 bg-blue-600  text-white rounded-lg"></i>
            <span class="">Retour à l'accueil</span>
        </a>
    </div>


    <div id="hs-scale-animation-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
    <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 max-w-md w-full md:max-w-3xl lg:max-w-4xl lg:w-full m-3 md:mx-auto min-h-[calc(100%-56px)] flex items-center">
        <div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200 dark:border-neutral-700">
                <h3 id="modal-title" class="font-extrabold text-2xl text-gray-800 dark:text-white"></h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-scale-animation-modal">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/2">
                    <img id="modal-image" src="" alt="" class="rounded-lg object-cover h-auto max-h-96 w-full">
                </div>
                <div class="w-full md:w-1/2">
                    <p id="modal-description" class="text-md font-semibold text-black dark:text-white text-justify" style="text-align: justify;"></p>
                    <p id="modal-date" class="text-sm text-gray-500 dark:text-neutral-400"></p>
                    <p id="modal-category" class="text-sm text-gray-500 dark:text-neutral-400"></p>
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-gray-200 dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#hs-scale-animation-modal">
                    Fermer
                </button>
                <div class="relative">
                    <button id="share-button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Partager cette article <i class="fa-solid fa-square-share-nodes"></i>
                    </button>
                    <div id="share-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
                        <a id="share-facebook" href="#" class="block font-bold px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700"><i class="bi bi-facebook font-bold text-blue-600"></i> Facebook</a>
                        <a id="share-whatsapp" href="#" class="block font-bold px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700"><i class="bi bi-whatsapp font-bold text-green-600"></i> WhatsApp</a>
                        <a id="share-x" href="#" class="block font-bold px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700"><i class="bi bi-twitter-x font-bold"></i> X (Twitter)</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <h1 class="font-bold text-center bg-blue-600 text-white text-sm"><i class="fa-solid fa-circle-check p-2 sm:p-4"></i> Notre source fiable d'information.</h1>
    <section id="header" class="relative sm:p-8 pb-0">
        <div class="border-b border-gray-200 dark:border-neutral-700">
            <nav class="flex flex-col sm:flex-row gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                <button type="button" class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-x-2 border-b-2 border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 focus:outline-hidden focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:text-blue-500 active" id="tabs-with-badges-item-1" aria-selected="true" data-hs-tab="#tabs-with-badges-1" aria-controls="tabs-with-badges-1" role="tab">
                    Tous <span class="hs-tab-active:bg-blue-100 hs-tab-active:text-blue-600 dark:hs-tab-active:bg-blue-800 dark:hs-tab-active:text-white ms-1 py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300"><?php echo count($articles); ?>+</span>
                </button>
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 p-4">

                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm font-medium">Trier par :</label>
                        <select id="sort" class="border border-gray-200 rounded-lg p-2 text-sm">
                            <option value="newest">Plus récentes</option>
                            <option value="oldest">Plus anciennes</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="limit" class="text-sm font-medium">Afficher :</label>
                        <select id="limit" class="border border-gray-200 rounded-lg p-2 text-sm">
                            <option value="10" <?php if($limit==='10') echo 'selected'; ?>>10 articles</option>
                            <option value="20" <?php if($limit==='20') echo 'selected'; ?>>20 articles</option>
                            <option value="50" <?php if($limit==='50') echo 'selected'; ?>>50 articles</option>
                            <option value="100" <?php if($limit==='100') echo 'selected'; ?>>100 articles</option>
                            <option value="all" <?php if($limit==='all') echo 'selected'; ?>>Tous</option>
                        </select>
                    </div>
                </div>
            </nav>

        </div>
        <div id="tabs-with-badges-1" class="mt-3 mb-3 flex justify-around flex-wrap gap-4" role="tabpanel" aria-labelledby="tabs-with-badges-item-1">
            <?php foreach ($articles as $article_id => $article): ?>
                <div data-hs-carousel='{
                    "loadingClasses": "opacity-0",
                    "dotsItemClasses": "hs-carousel-active:bg-blue-700 hs-carousel-active:border-blue-700 size-3 border border-gray-400 rounded-full cursor-pointer dark:border-neutral-600 dark:hs-carousel-active:bg-blue-500 dark:hs-carousel-active:border-blue-500",
                    "speed": 10000
                }' class="relative w-96 cursor-pointer">
                    <span class="category-label"><?php echo htmlspecialchars($article['categorie']); ?></span>
                    <div class="hs-carousel relative overflow-hidden min-h-64 bg-white rounded-lg">
                        <div class="absolute bottom-0 start-0 end-0 z-50">
                            <div class="p-4 md:p-5">
                                <p class="mt-1 text-white font-bold text-sm sm:text-base">
                                    <?php echo htmlspecialchars(stripslashes($article['titre'])); ?>
                                </p>
                            </div>
                        </div>
                        <div class="overlay absolute w-full h-full bottom-0 left-0 z-40 pointer-events-none" style="background: linear-gradient(360deg,rgba(2, 0, 36, 1) 0%, rgba(51, 51, 51, 1) 6%, rgba(255, 255, 255, 0) 84%, rgba(255, 255, 255, 0) 100%);">
                            <button class="overlay absolute top-2 right-5 rounded-full pointer-events-auto bg-blue-700 z-60 py-2 px-4 shadow-xl text-sm text-white disabled:pointer-events-none"
                                aria-haspopup="dialog"
                                aria-expanded="false"
                                aria-controls="hs-scale-animation-modal"
                                data-hs-overlay="#hs-scale-animation-modal"
                                data-title="<?php echo htmlspecialchars(stripslashes($article['titre'])); ?>"
                                data-description="<?php echo htmlspecialchars(stripslashes($article['description'])); ?>"
                                data-date="<?php echo htmlspecialchars($article['date']); ?>"
                                data-category="<?php echo htmlspecialchars($article['categorie']); ?>"
                                data-media="<?php echo !empty($article['media']) ? htmlspecialchars($article['media'][0]['url']) : ''; ?>">
                                Consulter <i class="fa-solid fa-up-right-from-square"></i>
                            </button>
                        </div>
                        <div class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
                            <?php foreach ($article['media'] as $media): ?>
                                <div class="relative hs-carousel-slide z-0">
                                    <div class="relative bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
                                        <?php if ($media['type'] === 'image'): ?>
                                            <img class="w-full h-full rounded-xl" src="<?php echo htmlspecialchars($media['url']); ?>" alt="Article Media">
                                        <?php else: ?>

                                            <a class="absolute z-10 w-full h-64 rounded-xl flex items-center justify-center" href="<?php echo htmlspecialchars($media['url']); ?>" target="_blank">
                                                <i class="fa-solid fa-circle-play text-white text-4xl"></i>
                                            </a>

                                            <video class="relative w-full h-full rounded-xl video-overlay z-0" controls>
                                                <source src="<?php echo htmlspecialchars($media['url']); ?>" type="video/<?php echo pathinfo($media['url'], PATHINFO_EXTENSION); ?>">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="button" class="hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:cursor-default absolute z-60 top-1/2 start-2 inline-flex justify-center items-center py-2 px-1 bg-white border border-gray-100 rounded-md text-gray-800 shadow-2xs hover:bg-gray-100 -translate-y-1/2 focus:outline-hidden">
                        <span class="text-2xl" aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button type="button" class="hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:cursor-default absolute z-60 top-1/2 end-2 inline-flex justify-center items-center py-2 px-1 bg-white border border-gray-100 text-gray-800 rounded-md shadow-2xs hover:bg-gray-100 -translate-y-1/2 focus:outline-hidden">
                        <span class="sr-only">Next</span>
                        <span class="text-2xl" aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>
                    </button>
                    <div class="hs-carousel-pagination flex justify-center absolute bottom-3 start-0 end-0 flex gap-x-2"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <footer class="mt-auto bg-gray-900 w-full dark:bg-neutral-950 mt-4">
        <div class="mt-auto w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <div class="col-span-full lg:col-span-1">
                    <img src="images/gouvernorat/logo.jpg" alt="logo-gouvernorat-kasai-central" class="w-60 rounded-lg">
                </div>
                <div class="col-span-1">
                    <h4 class="font-semibold text-gray-100">Liens rapides</h4>
                    <div class="mt-3 grid space-y-3">
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="gouvernorat.html">Gouvernorat</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="agriculture.html">Agriculture</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="histoire.html">Historique</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="geo-kasai-central.html">Géographie</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="demographie.html">Demographie</a></p>

                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="tourisme.html">Tourisme</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="galerie.html">Galerie</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="sante.html">Santé</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="education.html">Éducation</a></p>
                    </div>
                </div>
                <div class="col-span-1">
                    <h4 class="font-semibold text-gray-100">Contacts</h4>
                    <div class="mt-3 grid space-y-3">
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">+243 81 234 5678</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">+243 81 234 5678</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">@kasaig.com</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">@kasaie.com</a></p>
                    </div>
                </div>
                <div class="col-span-2">
                    <h4 class="font-semibold text-gray-100">Newsletter</h4>
                    <form method="post" action="newsletter.php">
                        <div class="mt-4 flex flex-col items-center gap-2 sm:flex-row sm:gap-3 bg-white rounded-lg p-2 dark:bg-neutral-900">
                            <div class="w-full">
                                <label for="hero-input" class="sr-only">S'inscrire</label>
                                <input type="text" id="hero-input" name="email" class="py-2.5 sm:py-3 px-4 block w-full border-transparent rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-transparent dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Entrer votre email" required>
                            </div>
                            <button type="submit" name="submit" class="w-full sm:w-auto whitespace-nowrap p-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                S'inscrire
                            </button>
                        </div>
                        <p class="mt-3 text-sm text-gray-400">
                            Recevez nos actualités et mises à jour directement dans votre boîte mail.
                        </p>
                    </form>
                </div>
            </div>
            <div class="mt-5 sm:mt-12 grid gap-y-2 sm:gap-y-0 sm:flex sm:justify-between sm:items-center">
                <div class="flex flex-wrap justify-between items-center gap-2">
                    <p class="text-sm text-gray-400 dark:text-neutral-400">© 2025 Kasaï Central.</p>
                </div>
                <div>
                    <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                        </svg>
                    </a>
                    <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                        </svg>
                    </a>
                    <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                        </svg>
                    </a>
                    <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                        </svg>
                    </a>
                    <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.362 10.11c0 .926-.756 1.681-1.681 1.681S0 11.036 0 10.111C0 9.186.756 8.43 1.68 8.43h1.682v1.68zm.846 0c0-.924.756-1.68 1.681-1.68s1.681.756 1.681 1.68v4.21c0 .924-.756 1.68-1.68 1.68a1.685 1.685 0 0 1-1.682-1.68v-4.21zM5.89 3.362c-.926 0-1.682-.756-1.682-1.681S4.964 0 5.89 0s1.68.756 1.68 1.68v1.682H5.89zm0 .846c.924 0 1.68.756 1.68 1.681S6.814 7.57 5.89 7.57H1.68C.757 7.57 0 6.814 0 5.89c0-.926.756-1.682 1.68-1.682h4.21zm6.749 1.682c0-.926.755-1.682 1.68-1.682.925 0 1.681.756 1.681 1.681s-.756 1.681-1.68 1.681h-1.681V5.89zm-.848 0c0 .924-.755 1.68-1.68 1.68A1.685 1.685 0 0 1 8.43 5.89V1.68C8.43.757 9.186 0 10.11 0c.926 0 1.681.756 1.681 1.68v4.21zm-1.681 6.748c.926 0 1.682.756 1.682 1.681S11.036 16 10.11 16s-1.681-.756-1.681-1.68v-1.682h1.68zm0-.847c-.924 0-1.68-.755-1.68-1.68 0-.925.756-1.681 1.68-1.681h4.21c.924 0 1.68.756 1.68 1.68 0 .926-.756 1.681-1.68 1.681h-4.21z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
    <script src="script/script-actu.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const sortSelect = document.getElementById('sort');
            const limitSelect = document.getElementById('limit');

            function updateArticles() {
                const params = new URLSearchParams(window.location.search);
                params.set('sort', sortSelect.value);
                params.set('limit', limitSelect.value);

                window.location.search = params.toString();
            }

            sortSelect.addEventListener('change', updateArticles);
            limitSelect.addEventListener('change', updateArticles);

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('sort')) {
                sortSelect.value = urlParams.get('sort');
            }
            if (urlParams.has('limit')) {
                limitSelect.value = urlParams.get('limit');
            }
        });
    </script>
</body>

</html>