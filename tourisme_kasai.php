<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
  <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical" href="https://preline.co/">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Crafted for agencies and studios specializing in web design and development.">

  <meta name="twitter:site" content="@preline">
  <meta name="twitter:creator" content="@preline">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Agency Tailwind CSS Template | Preline UI, crafted with Tailwind CSS">
  <meta name="twitter:description" content="Crafted for agencies and studios specializing in web design and development.">
  <meta name="twitter:image" content="https://preline.co/assets/img/og-image.png">

  <meta property="og:url" content="https://preline.co/">
  <meta property="og:locale" content="en_US">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Preline">
  <meta property="og:title" content="Agency Tailwind CSS Template | Preline UI, crafted with Tailwind CSS">
  <meta property="og:description" content="Crafted for agencies and studios specializing in web design and development.">
  <meta property="og:image" content="https://preline.co/assets/img/og-image.png">

    <title>Agency Tailwind CSS Template | Preline UI, crafted with Tailwind CSS</title>

    <link rel="shortcut icon" href="../../favicon.ico">
  <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>
  
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
    const html = document.querySelector('html');
    const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') === 'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
    const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
    else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
    else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
    else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
  </script>

    <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">
  <link rel="stylesheet" href="toursisme.css">
  <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>

</head>

<body class="relative bg-blue-50 dark:bg-black overflow-x-hidden">
<header class="fixed top-0 left-0 w-full bg-blue-600 overflow-hidden z-10 transition-all duration-500 ease-in-out" 
        style="height: 100vh; 
               background: linear-gradient(90deg, rgba(37, 99, 235, 1) 0%, rgba(59, 130, 246, 1) 51%, rgba(0, 140, 255, 1) 100%);
               background-size: 200% 200%;
               animation: gradientBG 15s ease infinite;">
    
        <svg class="absolute bottom-0 left-0 w-full h-32" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
        <path fill="#ffffff" fill-opacity="0.1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    </svg>

    <div class="container relative mx-auto px-4 py-20 z-30 h-full flex flex-col justify-center">
        <a href="site.html" class="flex items-center text-white hover:text-yellow-300 transition-colors duration-300 mb-8">
            <i class="fa-solid fa-arrow-left mr-2"></i>Revenir à l'accueil
        </a>
        
        <h1 class="textes lg:text-6xl text-4xl font-bold text-white mb-4 animate-fadeIn">
            Le tourisme au Kasaï-central
            <span class="block w-20 h-1 bg-yellow-600 mt-4"></span>
        </h1>
        
        <div class="textes inline-flex items-center mb-12 max-w-2xl">
            <span class="size-2 inline-block bg-yellow-600 rounded-full me-2 animate-pulse"></span>
            <span class="text-gray-200">Découvrez les merveilles du Kasaï Central, de ses paysages époustouflants à sa riche culture.</span>    
        </div>
        
        <div id="carousel-containder" class="flex flex-col md:flex-row gap-6 items-start">
            <div class="bg-white bg-opacity-90 w-full md:w-80 rounded-lg p-4 flex justify-between items-center transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                <p class="font-medium text-gray-800">Gallery</p>
                <button class="bg-blue-600 px-6 py-2 hover:bg-blue-700 transition-all duration-300 ease-in-out rounded-md text-white shadow hover:shadow-md">
                    Ouvrir
                </button>
            </div>
            <div class="bg-white bg-opacity-90 w-full md:w-80 rounded-lg p-4 flex justify-between items-center transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                <p class="font-medium text-gray-800">Attractions</p>
                <a href="#attractions" class="bg-blue-600 px-6 py-2 hover:bg-blue-700 transition-all duration-300 ease-in-out rounded-md text-white shadow hover:shadow-md">
                    Explorer
                </a>
            </div>
        </div>
    </div>

        <div class="image-gb absolute flex justify-between w-full h-full bottom-0 left-0 z-10 overflow-hidden">
        <img src="images/bg/gr.svg" alt="" class="absolute h-full w-auto opacity-20 object-cover">
        
    </div>
</header>
<style>
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        header {
            height: 90vh !important;
            padding-top: 4rem;
        }
        
        #carousel-containder {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
    
<div id="hs-slide-down-animation-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-slide-down-animation-modal-label">
  <div class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
    <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
      <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200 dark:border-neutral-700">
        <h3 id="hs-slide-down-animation-modal-label" class="text-xl font-semibold text-gray-900 dark:text-white">
          Kahuzi-Biega National Park
        </h3>
        <button type="button" class="size-10 inline-flex justify-center items-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-slide-down-animation-modal">
          <span class="sr-only">Close</span>
          <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

            <div class="p-6 overflow-y-auto max-h-[60vh]">
        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
          Parc National de Kahuzi-Biega – Trésor naturel du Congo
        </h4>
        <p class="mt-3 text-base text-gray-600 dark:text-neutral-300 leading-relaxed">
          Le Parc National de Kahuzi-Biega, fondé en 1977, est l’un des plus grands parcs naturels d’Afrique centrale, couvrant une superficie impressionnante de 2 000 km². Situé dans l’Est de la République Démocratique du Congo, il doit son nom aux deux anciens volcans Kahuzi (3 308 m) et Biega (2 790 m), qui dominent majestueusement le paysage.
          Ce parc représente un sanctuaire écologique unique, combinant forêts de montagne et forêts tropicales humides de basse altitude. Ce contraste exceptionnel offre un refuge à une faune et une flore d'une richesse inégalée.
        </p>
      </div>
      </div>
      <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-gray-200 dark:border-neutral-700">
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#hs-slide-down-animation-modal">
          Fermer
        </button>
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
          Exporter (PDF)
        </button>
      </div>
    </div>
  </div>
</div>                    

     <section id="why-visit" class="relative bg-gradient-to-b from-blue-50 to-white dark:from-neutral-900 dark:to-neutral-800 py-20 overflow-hidden">
  <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
      <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white tracking-tight animate-fadeIn">
        Pourquoi visiter le Kasaï-Central ?
        <span class="block w-24 h-1.5 bg-blue-600 mt-4 mx-auto rounded-full"></span>
      </h2>
      <p class="mt-4 text-lg md:text-xl text-gray-600 dark:text-neutral-300 max-w-3xl mx-auto leading-relaxed">
        Plongez dans une région où la nature, la culture et l’hospitalité se rencontrent pour créer une expérience inoubliable.
      </p>
    </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-leaf text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Biodiversité unique
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Découvrez le parc Kahuzi-Biega, un sanctuaire abritant des espèces rares comme l’éléphant méditerranéen et des paysages volcaniques spectaculaires.
        </p>
      </div>

            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-landmark text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Culture vibrante
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Immergez-vous dans les festivals traditionnels et l’artisanat local, témoins d’un patrimoine culturel riche et vivant.
        </p>
      </div>

            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-mountain text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Paysages grandioses
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Des rivières majestueuses aux chutes impressionnantes, chaque vue est une invitation à l’émerveillement.
        </p>
      </div>

            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-users text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Accueil chaleureux
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Rencontrez une communauté accueillante qui partage avec passion son histoire et ses traditions.
        </p>
      </div>

            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-gamepad text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Activités variées
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Des parcs d’attractions modernes aux expériences culturelles, il y en a pour tous les goûts.
        </p>
      </div>

            <div class="relative bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl group">
        <div class="flex items-center mb-4">
          <span class="inline-flex justify-center items-center size-12 rounded-full bg-blue-600/90 text-white group-hover:bg-blue-700 transition-colors duration-300">
            <i class="fa-solid fa-camera text-xl"></i>
          </span>
          <h3 class="ml-4 text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
            Vues photogéniques
          </h3>
        </div>
        <p class="text-gray-600 dark:text-neutral-300 text-base leading-relaxed">
          Immortalisez des paysages et une faune uniques, parfaits pour les amateurs de photographie.
        </p>
      </div>
    </div>

        <div class="text-center mt-12">
      <a href="#attractions" class="inline-flex items-center gap-x-3 py-4 px-8 text-base font-medium rounded-full bg-gradient-to-r from-blue-600 to-blue-800 text-white hover:from-blue-700 hover:to-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-300/50 transition-all duration-300">
        Découvrir les attractions
        <i class="fa-solid fa-arrow-right text-white"></i>
      </a>
    </div>
  </div>

    <div class="absolute inset-0 z-0 opacity-10">
    <svg class="w-full h-full" viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
      <path fill="#3b82f6" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    </svg>
  </div>
</section>
    <section id="attractions" class="mt-8 z-20 relative bg-gray-50" style="margin-top: 100vh; height: 100vh;">
      <div class="carousel">

        <div class="list">

            <div class="item relative" style="background-image: url(images/actu/parc.jpg);">
              <div class="absolute top-0 left-0 w-full h-full z-10" style="background: #020024;
                background: linear-gradient(90deg, rgb(1, 0, 15) 0%, rgba(0, 0, 20, 0.589) 36%, rgba(0, 255, 208, 0) 100%);"></div>
                <div class="content z-20">
                    <div class="title"></div>
                    <div class="name text-4xl">Parc national</div><br>
                    <div class="name text-1xl"><span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>KAHUZI BIEGA</div>
                    <div class="des">Un parc créée en <span class="bg-blue-500">1977</span> unique en son genre avec des especes rares
                    comme l’elephant mediteranéen qui borde vers le fleuve jaune
                    au nord le fleuve rouge au sud et avec une superficide de <span class="bg-blue-500">2000km</span>
                    </div>
                    <div class="des rounded-md"><i class="fa-solid fa-location-dot p-2 rounded-md"></i><a href="" class="" style="margin-left: 15px;">Kananga 22.23N, 19.23E</a></div>
                    <div class="name font-medium">
                      <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>Espèces : Croco noire, cendre rouge, éléphant et girafes.
                    </div>
                    <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-gray-200 bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"  aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-slide-down-animation-modal" data-hs-overlay="#hs-slide-down-animation-modal">
                      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                      Découvrir
                  </button>
                </div>               
            </div>
            
            <div class="item relative z-0" style="background-image: url(images/actu/jeux.jpg);">
               <div class="absolute top-0 left-0 w-full h-full z-10" style="background: #020024;
                background: linear-gradient(90deg, rgb(1, 0, 15) 0%, rgba(0, 0, 20, 0.589) 36%, rgba(0, 255, 208, 0) 100%);"></div>
                <div class="content z-20 bg-gray-100 text-blue-600 p-8 rounded-lg">
                    <div class="title"></div>
                    <div class="mt-2 bg-gray-800 text-sm text-white rounded-lg p-4 dark:bg-white dark:text-neutral-800" role="alert" tabindex="-1" aria-labelledby="hs-solid-color-dark-label">
  <span id="hs-solid-color-dark-label" class="font-bold">Divertissement</span>
</div><br>
                    <div class="name text-1xl text-black"><span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>13 kilometres</div>
                    <hr class="border-gray-300 dark:border-white">
                    
                    <div class="des text-gray-700">Un lieu de diverstissement créée en 2015 regoupant les plus grands jeux 
                    tel qu'ils soit autant pour adulte que pour enfant partant du réalisme au virtuel un endroit idéal pour se ragaler surtout.
                    
                    </div>
                    <div class="name font-medium text-black">
                      <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>Jeux : Saut, balançoir, basket ball et courses.
                    </div>
                    <div class="bg-blue-50 border-s-4 mt-1 mb-1 border-blue-600 p-4 dark:bg-blue-600/30" role="alert" tabindex="-1" aria-labelledby="hs-bordered-red-style-label">
                      <div class="flex">
                        <div class="shrink-0">
                                                    <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-blue-100 bg-blue-200 text-blue-800 dark:border-rblue-700 dark:bg-blue-700 dark:text-blue-400">
                            <i class="fa-solid fa-location-dot text-blue-600 p-2 rounded-md"></i>
                          </span>
                                                  </div>
                        <div class="ms-3">
                          <h3 id="hs-bordered-red-style-label" class="text-gray-800 font-semibold dark:text-white">
                            Localisation
                          </h3>
                          <p class="text-sm text-gray-700 dark:text-neutral-400">
                            Kananga 22.23N, 19.23E
                          </p>
                        </div>
                      </div>
                    </div>
                    <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-black bg-white text-gray-800  hover:text-white hover:bg-black focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"  aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-slide-down-animation-modal" data-hs-overlay="#hs-slide-down-animation-modal">
                      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                      Découvrir
                  </button>  
                  </div>

            </div> 

            <div class="item" style="background-image: url(images/actu/riviere.jpg);">
               <div class="absolute top-0 left-0 w-full h-full z-10" style="background: #020024;
                background: linear-gradient(90deg, rgb(1, 0, 15) 0%, rgba(0, 0, 20, 0.589) 36%, rgba(0, 255, 208, 0) 100%);"></div>
                <div class="content z-20">
                    <div class="title"></div>
                    <div class="name text-4xl">Parc national</div><br>
                    <div class="name text-1xl"><span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>KAHUZI BIEGA</div>
                    <div class="des">Un parc créée en <span class="bg-blue-500">1977</span> unique en son genre avec des especes rares
                    comme l’elephant mediteranéen qui borde vers le fleuve jaune
                    au nord le fleuve rouge au sud et avec une superficide de <span class="bg-blue-500">2000km</span>
                    </div>
                    <div class="name font-medium">
                      <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>Espèces : Croco noire, cendre rouge, éléphant et girafes.
                    </div>
                    <div class="des rounded-md"><i class="fa-solid fa-location-dot p-2 rounded-md"></i><a href="" class="underline" style="margin-left: 15px;"
                      >Kananga 22.23N, 19.23E</a></div>
                    <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-gray-200 bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"  aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-slide-down-animation-modal" data-hs-overlay="#hs-slide-down-animation-modal">
                      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                      Découvrir
                  </button>
                </div>
            </div>

            <div class="item" style="background-image: url(images/actu/chute.jpg);">
               <div class="absolute top-0 left-0 w-full h-full z-10" style="background: #020024;
                background: linear-gradient(90deg, rgb(1, 0, 15) 0%, rgba(0, 0, 20, 0.589) 36%, rgba(0, 255, 208, 0) 100%);"></div>
                <div class="content z-20">
                    <div class="title"></div>
                    <div class="name text-4xl">Parc national</div><br>
                    <div class="name text-1xl"><span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>KAHUZI BIEGA</div>
                    <div class="des">Un parc créée en <span class="bg-blue-500">1977</span> unique en son genre avec des especes rares
                    comme l’elephant mediteranéen qui borde vers le fleuve jaune
                    au nord le fleuve rouge au sud et avec une superficide de <span class="bg-blue-500">2000km</span>
                    </div>
                    <div class="name font-medium">
                      <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>Espèces : Croco noire, cendre rouge, éléphant et girafes.
                    </div>
                    <div class="des rounded-md"><i class="fa-solid fa-location-dot p-2 rounded-md"></i><a href="" class="underline" style="margin-left: 15px;"
                      >Kananga 22.23N, 19.23E</a></div>
                    <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-gray-200 bg-white text-gray-800 hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"  aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-slide-down-animation-modal" data-hs-overlay="#hs-slide-down-animation-modal">
                      <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                      Découvrir
                  </button>
                </div>

            </div>

        </div>

                <div class="arrows">
            <button class="prev">< précédent</button>
            <button class="next">suivant ></button>
        </div>


                <div class="timeRunning"></div>
      </div>
    </section>
   <script>
    var nextBtn = document.querySelector('.next'),
    prevBtn = document.querySelector('.prev'),
    carousel = document.querySelector('.carousel'),
    list = document.querySelector('.list'), 
    item = document.querySelectorAll('.item'),
    runningTime = document.querySelector('.carousel .timeRunning') 

let timeRunning = 1000 
let timeAutoNext = 200000

nextBtn.onclick = function(){
    showSlider('next')
}

prevBtn.onclick = function(){
    showSlider('prev')
}

let runTimeOut 

let runNextAuto = setTimeout(() => {
    nextBtn.click()
}, timeAutoNext)


function resetTimeAnimation() {
    runningTime.style.animation = 'none'
    runningTime.offsetHeight /* trigger reflow */
    runningTime.style.animation = null 
    runningTime.style.animation = 'runningTime 7s linear 1 forwards'
}


function showSlider(type) {
    let sliderItemsDom = list.querySelectorAll('.carousel .list .item')
    if(type === 'next'){
        list.appendChild(sliderItemsDom[0])
        carousel.classList.add('next')
    } else{
        list.prepend(sliderItemsDom[sliderItemsDom.length - 1])
        carousel.classList.add('prev')
    }

    clearTimeout(runTimeOut)

    runTimeOut = setTimeout( () => {
        carousel.classList.remove('next')
        carousel.classList.remove('prev')
    }, timeRunning)


    clearTimeout(runNextAuto)
    runNextAuto = setTimeout(() => {
        nextBtn.click()
    }, timeAutoNext)


    resetTimeAnimation() 
    
document.querySelectorAll('.carousel .item').forEach(el => el.classList.remove('active'))
sliderItemsDom[0].classList.add('active')

    
}


resetTimeAnimation()
   </script> 
  <script src="https://unpkg.com/scrollreveal"></script>
  <script>
    ScrollReveal().reveal('.image-gb', {
      delay: 200,
      duration: 1000,
      distance: '200px',
      origin: 'bottom',
      reset: true
    });
    scrollReveal().reveal('.textes', {
      delay: 200,
      duration: 1000,
      distance: '50px',
      origin: 'bottom',
      reset: true
    });
  </script>
  <script>
  
  const themeToggle = document.getElementById('theme-toggle');
  const htmlc = document.querySelector('html');

  themeToggle.addEventListener('click', () => {
    if (html.classList.contains('dark')) {
      htmlc.classList.remove('dark');
      htmlc.classList.add('light');
      localStorage.setItem('hs_theme', 'light');
      themeToggle.innerHTML = '<i class="fa-solid fa-sun text-yellow-500"></i>';
    } else {
      htmlc.classList.remove('light');
      htmlc.classList.add('dark');
      localStorage.setItem('hs_theme', 'dark');
      themeToggle.innerHTML = '<i class="fa-solid fa-moon text-gray-300"></i>';
    }
  });

  
  if (htmlc.classList.contains('dark')) {
    themeToggle.innerHTML = '<i class="fa-solid fa-moon text-gray-300"></i>';
  } else {
    themeToggle.innerHTML = '<i class="fa-solid fa-sun text-yellow-500"></i>';
  }
</script>
  <script>
    ScrollReveal().reveal('.fondation', {
      delay: 200,
      duration: 1000,
      distance: '50px',
      origin: 'bottom',
      reset: true
    });
    ScrollReveal().reveal('.evenements', {
      delay: 200,
      duration: 1000,
      distance: '50px',
      origin: 'bottom',
      reset: true
    });
    ScrollReveal().reveal('.evolution', {
      delay: 200,
      duration: 1000,
      distance: '50px',
      origin: 'bottom',
      reset: true
    });
  </script>
  
      <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>

      <script async src="https://www.googletagmanager.com/gtag/js?id=G-B73TDMXKF5"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
  
    function gtag() {
      dataLayer.push(arguments);
    }
  
    gtag('js', new Date());
    gtag('config', 'G-B73TDMXKF5');
  </script>
  <script>
    
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        const scrollPosition = window.scrollY;
        
        
        header.style.backgroundPositionY = -scrollPosition * 0.5 + 'px';
        
        
        if(scrollPosition > 50) {
            header.style.height = '95vh';
        } else {
            header.style.height = '100vh';
        }
    });
</script>
</body>
</html>