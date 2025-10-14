  <link rel="shortcut icon" href="images/favicon.jpg" type="image/x-icon">

  <div id="page-loader" class="h-full w-full" style="position:fixed;z-index:9999;inset:0;display:flex;align-items:center;justify-content:center;background: #2563eb;transition:opacity 0.6s cubic-bezier(0.4,0,0.2,1);">
    <div class="h-full w-full flex flex-col bg-blue-600 border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
      <div class="flex flex-auto flex-col justify-center items-center p-4 md:p-5">
        <div class="flex justify-center">
          <div class="animate-spin inline-block size-6 border-3 border-current border-t-transparent text-white rounded-full dark:text-blue-500" role="status" aria-label="loading">
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('page-loader');
    setTimeout(function() {
      loader.style.opacity = '0';
      loader.style.pointerEvents = 'none';
      setTimeout(function() {
        loader.style.display = 'none';
      }, 600); 
    }, 2000);
  });
</script>
<?php
require('config.php');

$articles = [];
$sql_articles = "SELECT id, titre, description, categorie, date 
                 FROM actualites 
                 ORDER BY date DESC 
                 LIMIT 2";
$result_articles = mysqli_query($conn, $sql_articles);

while ($row = mysqli_fetch_assoc($result_articles)) {
    $articles[$row['id']] = [
        'titre'       => $row['titre'],
        'description' => $row['description'],
        'categorie'   => $row['categorie'],
        'date'        => $row['date'],
        'media'       => []
    ];
}

if (!empty($articles)) {
    $ids = implode(',', array_keys($articles));
    $sql_media = "SELECT article_id, type, url 
                  FROM media 
                  WHERE article_id IN ($ids)";
    $result_media = mysqli_query($conn, $sql_media);

    while ($row = mysqli_fetch_assoc($result_media)) {
        $articles[$row['article_id']]['media'][] = [
            'type' => $row['type'],
            'url'  => $row['url']
        ];
    }
}


session_start();

$showSuccessToast = false;
$showErrorToast = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];

    $query = 'INSERT INTO messages(nom_complet, email, adresse, numero, message) VALUES (?,?,?,?,?)';
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt && mysqli_stmt_bind_param($stmt, 'sssss', $nom, $email, $adresse, $telephone, $message) && mysqli_stmt_execute($stmt)) {
        $_SESSION['form_status'] = 'success';
        header('Location: '.$_SERVER['PHP_SELF']); 
        exit();
    } else {
        $_SESSION['form_status'] = 'error';
        $showErrorToast = true;
    }
    
    mysqli_stmt_close($stmt);
}

if (isset($_SESSION['form_status'])) {
    if ($_SESSION['form_status'] === 'success') {
        $showSuccessToast = true;
    } elseif ($_SESSION['form_status'] === 'error') {
        $showErrorToast = true;
    }
    unset($_SESSION['form_status']); 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Site du kasai centrale ">
  <meta name="keywords" content="Kasai Central, Gouvernorat, RDC, République Démocratique du Congo">
  <meta name="author" content="Ir philippe mirindi lukogo">
  <title>kasai central</title>

  <link rel="shortcut icon" href="images/favicon.jpg" type="image/x-icon">
  
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-dC7YuqXtKe6lS6b9qsycR4M0klJy+dNf62G9McTcuCuLoXi0ePlKZ4WhtAKazjaDttGGNqqxjJW/78VwYV4n2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>
  
  <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">
  <link rel="stylesheet" href="st.css">
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:wght@300..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
<style>
  #cartes article {
  background: none;
}
#cartes .w-full {
  width: 100%;
  height: 100%;
}
       .section-title {
            background: linear-gradient(to right, #1e3a8a, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    @media (max-width: 450px){
      header{
        height: auto;
      }
    }
    @media (min-width: 500px) {
      .mon-hero{
        height: calc(100vh - 100px);
      }
    }
</style>
</head>

<body class="bg-neutral-900 overflow-auto" style="font-family:'Nunito'">
<div class="">
<header class="flex p-4 flex-wrap md:justify-start md:flex-nowrap z-50 bg-white border-b border-gray-200 " style="">
<img src="images/gouvernorat/logo.jpg" alt="logo-gouvernorat-kasai-central" class="w-60 rounded-lg">
  <nav class="relative max-w-[85rem] w-full mx-auto md:flex md:items-center md:justify-between md:gap-3 ">
    <div class="flex justify-end items-center gap-x-1">
  
      <button type="button" class="hs-collapse-toggle md:hidden relative size-9 flex justify-center items-center font-medium text-sm rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" id="hs-header-base-collapse" aria-expanded="false" aria-controls="hs-header-base" aria-label="Toggle navigation" data-hs-collapse="#hs-header-base">
        <svg class="hs-collapse-open:hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
        <svg class="hs-collapse-open:block shrink-0 hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        <span class="sr-only">Toggle navigation</span>
      </button>
      
    </div>

    
    <div id="hs-header-base" class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block" aria-labelledby="hs-header-base-collapse">
      <div class="overflow-hidden overflow-y-auto max-h-[75vh] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300">
        <div class="py-2 md:py-0 flex flex-col md:flex-row md:items-center gap-0.5 md:gap-1">
          <div class="grow">
            <div class="flex flex-col md:flex-row md:justify-start md:items-center gap-0.5 md:gap-1">
              <a class="p-2 flex items-center font-bold text-sm bg-gray-100 text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-blue-600 focus:text-white" href="#" aria-current="page">
                Accueil
              </a>

              
              <div class="hs-dropdown [--strategy:static] md:[--strategy:absolute] [--adaptive:none] [--is-collapse:true] md:[--is-collapse:false]">
                <button id="hs-header-base-mega-menu-fullwidth" type="button" class="hs-dropdown-toggle font-bold w-full p-2 flex items-center text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100" aria-haspopup="menu" aria-expanded="false" aria-label="Mega Menu">
                  Présentation
                  <svg class="hs-dropdown-open:-rotate-180 md:hs-dropdown-open:rotate-0 duration-300 shrink-0 size-4 ms-auto md:ms-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div class="hs-dropdown-menu z-50 transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 relative w-full min-w-60 hidden z-10 top-full start-0 before:absolute before:-top-5 before:start-0 before:w-full before:h-5 bg-[$f4f4f4]" role="menu" aria-orientation="vertical" aria-labelledby="hs-header-base-mega-menu-fullwidth">
                  <div class="md:mx-6 lg:mx-8 bg-gray-50 shadow-lg md:rounded-lg md:shadow-md">
                    
                    <div class="py-1 md:p-2 md:grid md:grid-cols-2 lg:grid-cols-3 gap-4 bg-[#gf9fafb]">
                        <div class="p-4 ">
                            <h3 class="text-lg font-semibold mb-2"><i class="fas fa-hourglass-start text-blue-500 p-2  rounded-sm"></i>  Historique</h3>
                            <p class="text-sm text-gray-700 my-4 min-h-10">Notre histoire – Archives et patrimoine de la province</p>
                             <ul  class="marker:text-blue-600 ps-5 space-y-2 text-sm text-gray-300 rounded-lg p-5 overflow-hidden bg-white-700 bg-white shadow-xs">
                                <li><a href="histoire.html#fondation" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110  px-3 py-2 rounded-lg my-0.5  block text-sm text-gray-500  hover:text-gray-900"><i class="fas fa-landmark  text-blue-600 p-2   bg-blue-50  rounded-sm"></i>     Origines et fondations <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                                <li><a href="histoire.html#evenements" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 px-3 py-2 rounded-lg my-0.5 block text-sm text-gray-500   hover:text-gray-900"><i class="fas fa-calendar-alt text-blue-600 p-2  bg-blue-50  rounded-sm"></i>     Evenements marquants <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                                <li><a href="histoire.html#evolution" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110  px-3 py-2 rounded-lg my-0.5 block text-sm text-gray-500   hover:text-gray-900"><i class="fas fa-chart-line text-blue-600 p-2   bg-blue-50   rounded-sm"></i>     Evolution <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                            </ul>
                        </div>                     
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2"><i class="fas fa-globe text-blue-500 p-2  rounded-sm"></i> Géographie</h3>
                           <p class="text-sm text-gray-700 my-4 min-h-10">Notre territoire – Relief, climat et ressources naturelles au cœur de notre identité provinciale.</p>
                             <ul  class="marker:text-blue-600 ps-5 space-y-2 text-sm text-gray-300 rounded-lg p-5 overflow-hidden bg-white-700 bg-white shadow-xs">
                                <li><a href="geo-kasai-central.html#reliefs" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 px-3 py-2 rounded-lg my-0.5  block text-sm text-gray-500 hover:text-gray-900"><i class="fas fa-mountain text-blue-600 p-2   bg-blue-50  rounded-sm"></i>  Relief et climat  <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                                <li><a href="geo-kasai-central.html#decoupage" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110  px-3 py-2 rounded-lg my-0.5  block text-sm text-gray-500 hover:text-gray-900"><i class="fas fa-map text-blue-600 p-2   bg-blue-50  rounded-sm"></i>  Découpage territorial <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                                <li><a href="geo-kasai-central.html#richesses" class="menu-link relative font-semibold transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 px-3 py-2 rounded-lg my-0.5  block text-sm text-gray-500 hover:text-gray-900"><i class="fas fa-seedling text-blue-600 p-2   bg-blue-50  rounded-sm"></i>  Ressources naturelles <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                            </ul>
                        </div>
                         <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2"><i class="fas fa-user-friends text-blue-500 p-2  rounded-sm"></i> Demographie</h3>
                            <p class="text-sm text-gray-700 my-4 min-h-10">Portrait démographique – Chiffres clés, pyramide des âges et tendances pour comprendre notre communauté.</p>
                             <ul  class="marker:text-blue-600 ps-5 space-y-2 text-sm text-gray-300 rounded-lg p-5 overflow-hidden bg-white-700 bg-white shadow-xs">
                                <li><a href="demographie.html#population" class="menu-link relative transition font-semibold delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110  px-3 py-2 rounded-lg my-0.5 block text-sm text-gray-500 hover:text-gray-900"><i class="fas fa-users text-blue-600 p-2  bg-blue-50  rounded-sm"></i>  Densité <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                                <li><a href="demographie.html#ethnies" class="menu-link relative transition font-semibold delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110  px-3 py-2 rounded-lg my-0.5 block text-sm text-gray-500 hover:text-gray-900"><i class="fas fa-language text-blue-600 p-2  bg-blue-50 rounded-sm"></i>  Groupes ethniques et langues <span class="absolute left-0 bottom-0 h-0.5 w-0 bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-300 group-hover:w-full"></span></a></li>
                            </ul>
                        </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              
            
              <a class="p-2 flex items-center font-bold text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100" href="gouvernorat.html">
                Gouvernorat
              </a>

              
              <a class="p-2 flex items-center font-bold text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100" href="actu-kasai-central.php">
                Actualités
              </a>
              

              <a class="p-2 flex items-center font-bold text-sm text-gray-800 hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100" href="tourisme.html">
                Tourisme
              </a>

              <a class="p-2 flex items-center bg-blue-600 font-bold text-sm text-white rounded-lg focus:outline-hidden focus:bg-gray-100" href="login_admin.php">
                Administration
              </a>
            </div>
          </div>

          <div class="my-2 md:my-0 md:mx-2">
            <div class="w-full h-px md:w-px md:h-4 bg-gray-100 md:bg-gray-300"></div>
          </div>

          
           <div>
          <label for="hs-trailing-button-add-on-with-icon" class="sr-only">Label</label>
          <form class="flex rounded-lg" action="recherche.php" method="get">
            <input type="text" placeholder="Rercherche..." id="hs-trailing-button-add-on-with-icon" name="q" class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-s-lg sm:text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
            <button type="submit" class="size-11.5 shrink-0 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-e-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
              <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
              </svg>
            </button>
          </form>
        </div>
          
        </div>
      </div>
    </div>
    
  </nav>
</header>
</div>


<main >  
  <div class="">
    <div class="mon-hero swiper h-120 md:h-[100dvh] m-8 xs:rounded-none md:rounded-2xl overflow-hidden" style="">
      <div class="swiper-wrapper">
         
        <div class="swiper-slide" data-image="ville2">
          <img src="ville2.jpg" alt="" class="w-full h-full object-cover">
        </div>
        <div class="swiper-slide" data-image="paysage">
          <img src="paysage.jpg" alt="" class="w-full h-full object-cover">
        </div>
        <div class="swiper-slide" data-image="africain">
          <img src="population.jpg" alt="" class="w-full h-full object-cover">
        </div>
      </div>

      
      <div class="swiper-pagination"></div>

      
      <div class="absolute w-full h-full inset-0 bg-black opacity-50 z-30 ">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-blue-900 to-transparent"></div>
      </div>

<div class="absolute z-60 right-5 top-5 flex flex-col gap-4 items-end group" id="news">
  
  <button id="news-btn" type="button" class="flex items-center gap-x-2 bg-white text-blue-600 px-6 py-2.5 rounded-full font-medium text-sm hover:bg-blue-50 hover:scale-105 hover:shadow-lg hover:ring-2 hover:ring-blue-200 focus:outline-none focus:bg-blue-100 transition-all duration-300" aria-label="Dernières nouvelles">
    <span class="text-sm">À la une</span>
    <i class="fa-solid fa-newspaper"></i>
  </button>
  
  <div id="alaune" class="hidden group-hover:flex w-full md:w-96 bg-white rounded-xl shadow-2xl border border-gray-100 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 ease-in-out">
    <div class="py-4 px-4">
      <?php foreach($articles as $article_id => $article):?>
      
      <a href="actu-kasai-central.php" class="flex items-start gap-x-4 p-3 rounded-lg hover:bg-blue-50 hover:border hover:border-blue-600  hover:-translate-y-0.5 transition-all duration-300 group" aria-label="Lire l'article 1">
        <img src="<?php echo !empty($article['media']) ? htmlspecialchars($article['media'][0]['url']) : ''; ?>" alt="Article 1" class="w-16 md:w-20 h-16 md:h-20 object-cover rounded-md">
        <div class="flex-1">
          <h4 class="text-sm font-semibold text-blue-600 group-hover:text-blue-700"><?php echo htmlspecialchars(stripslashes($article['titre'])); ?></h4>
          <p class="text-xs text-black font-bold mt-1 line-clamp-2"><?php echo htmlspecialchars(stripslashes($article['description'])); ?></p>
          <span class="text-xs text-gray-400 mt-1"><?php echo 'Publié au : '. htmlspecialchars($article['date']) ?></span>
        </div>
      </a>        
      <?php endforeach; ?>
      
      <div class="mt-4 pt-3 border-t border-gray-200">
        <a href="actu-kasai-central.php" class="relative flex gap-4 justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-blue-600 text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-900 hover:scale-105 transition-all duration-300 before:absolute before:inset-0 before:rounded-lg before:border before:border-blue-300/30 before:transition before:duration-300 hover:before:border-blue-400/50 text-blue-600" aria-label="Voir toutes les actualités">
          Toutes les actualités
          <i class="fa-solid fa-arrow-right ml-2"></i>
        </a>
      </div>
    </div>
  </div>
</div>
            


      
      <div class="text-content absolute bottom-0 left-0 md:max-w-lg ps-5 pb-5 md:ps-10 md:pb-10 z-40">
        <h1 class="text-xl mb-8 text-3xl font-bold lg:text-7xl text-white">Kasai central</h1>
        <h4 class="text-white font-medium text-2xl"  style="" id="dynamic-text">Symbole de la diversité culturelle et traditionnel.</h4>
        <p class="text-white">Province lumière de la <span class="border-yellow-500 outline-2"> R D C .</span></p>
       
        <div class="bg-white w-24/3 rounded-full my-10" style="height: 5px; background: linear-gradient(to right, blue 0%, blue 33%, red 33%, red 66%, yellow 66%, yellow 100%);"></div>
      </div>

    </div>
  </div>
</main>
<section id="apropos" class="relative z-10 py-8 md:py-12 min-h-[calc(100vh-4rem)] flex items-center" style="background-color: #ffffff;">
  <div class="absolute w-full top-0 left-0 z-10">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" class="fill-blue-50" fill-opacity="1" d="M0,192L1440,64L1440,0L0,0Z"></path></svg>
  </div>
  <div class="absolute w-full bottom-0 left-0 z-10">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" class="fill-blue-50" fill-opacity="1" d="M0,160L48,160C96,160,192,160,288,144C384,128,480,96,576,101.3C672,107,768,149,864,160C960,171,1056,149,1152,128C1248,107,1344,85,1392,74.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
  </div>
  <div class="flex flex-col-reverse md:flex-row p-4 md:p-8 w-full max-w-7xl mx-auto h-full overflow-hidden z-20 rounded-lg">
    <div class="w-full md:w-1/2 h-auto flex justify-center items-center mb-6 md:mb-0">
        <img src="images/bg/kananga.jpg" alt="" class="rounded-lg shadow-lg w-full h-auto object-cover" style="max-height: 400px; max-width: 100%;">
    </div>
    <div class="p-4 md:p-8 flex justify-center items-center flex-col w-full md:w-1/2">
      <h1 id="apropos" class="font-bold text-xl md:text-2xl p-2 bg-blue-100 text-center">À propos du kasai central</h1>
      <p id="apropos" class="font-medium text-gray-900 mt-2 text-sm md:text-base" style="text-align: justify;">
        Bienvenue au <span class="font-bold text-blue-900"> Kasaï Central</span>, une province dynamique située au cœur de la <span class="font-bold text-blue-900">République Démocratique du Congo</span>. Avec <span class="font-bold text-blue-900">Kananga </span> comme capitale, cette région est le berceau du peuple Luba, une des ethnies les plus importantes et influentes du pays.
        Le Kasaï Central est une terre de traditions ancestrales. L'art Luba, réputé pour ses sculptures sur bois et ses masques rituels, est un pilier de l'identité culturelle de la région. Ces œuvres d'art ne sont pas de simples objets ; elles sont imprégnées de significations spirituelles et sociales, et certaines sont exposées dans des musées à travers le monde.
        L'économie locale est principalement basée sur <span class="font-bold text-blue-900">l'agriculture et les vastes ressources minières, notamment les diamants</span>. Ce potentiel économique, combiné à la richesse culturelle, fait du Kasaï Central une province d'opportunités.
        Visiter le Kasaï Central, c'est s'immerger dans une culture vibrante, découvrir des paysages naturels et rencontrer un peuple chaleureux, fier de son héritage
      </p>
    </div>
  </div>
</section>
<section id="discover" class="relative z-60 py-8 md:py-12 min-h-[calc(100vh-4rem)] flex items-center" style="background-color: #ffffff;">
     <div class="absolute w-full top-0 left-0">
       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" class="fill-blue-50" fill-opacity="1" d="M0,192L1440,64L1440,0L0,0Z"></path></svg>
     </div>
    <div class="absolute w-full bottom-0 left-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" class="fill-blue-50"  fill-opacity="1" d="M0,160L48,160C96,160,192,160,288,144C384,128,480,96,576,101.3C672,107,768,149,864,160C960,171,1056,149,1152,128C1248,107,1344,85,1392,74.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
     </div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    

    <div class="text-center mb-10 md:mb-12">
      <p class="mt-2 text-sm md:text-base text-gray-600 font-bold max-w-2xl mx-auto" style="font-family: 'Nunito';">
        Une province riche en histoire, culture et opportunités au cœur de la République Démocratique du Congo
      </p>
      <div class="mt-3 h-0.5 w-16 bg-blue-600 mx-auto rounded-full"></div>
    </div>
<div class="flex flex-col md:flex-row gap-4 md:gap-8 justify-center">

  
  <div class="w-full md:w-1/2 lg:w-3/5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" style="font-family: 'Nunito', sans-serif;">
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg shadow-2xs dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white" style="font-family: 'Nunito', sans-serif;">
            Localisation
          </h3>
          <div class="mt-2 text-gray-500 dark:text-neutral-400 flex-grow">
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600  text-sm font-semibold   dark:text-neutral-400">Pays République D.C</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm  font-semibold dark:text-neutral-400">Chef lieu Kananga</span>
            </div> 
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Superficie 59 111km</span>
            </div> 
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold dark:text-neutral-400">5°4′S 22°24′E</span>
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="geo-kasai-central.html">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
      
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg shadow-2xs dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white">
            Administration
          </h3>
          <div class="space-y-3 mt-2 flex-grow">
            <div class="flex items-center gap-x-3">
              <img src="images/gouvernorat/gouverneur.jpg" alt="Gouverneur" class="w-10 h-10 rounded-full object-cover border border-blue-600/20">
              <div>
                <p class="text-sm font-medium text-gray-800">Joseph K'ambulu</p>
                <p class="text-xs text-gray-500">Gouverneur</p>
              </div>
            </div>
            <div class="flex items-center gap-x-3">
              <img src="images/gouvernorat/vice-gouverneur.jpg" alt="Vice-Gouverneure" class="w-10 h-10 rounded-full object-cover border border-blue-600/20">
              <div>
                <p class="text-sm font-medium text-gray-800">Job Kuyindama</p>
                <p class="text-xs text-gray-500">Vice-Gouverneure</p>
              </div>
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="gouvernorat.html">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
      
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg shadow-2xs dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white">
            Langues
          </h3>
          <div class="space-y-3 mt-2 flex-grow">
            <div class="flex gap-2 items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Kikongo </span>
              <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500" style="width: 80%"></div>
              </div>
            </div>
            <div class="flex gap-2 items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Français</span>
              <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500" style="width: 50%"></div>
              </div>
            </div>
                        <div class="flex gap-2 items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Tshiluba</span>
              <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                <div class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500" style="width: 70%"></div>
              </div>
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="demographie.html#ethnies">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
      
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg shadow-2xs dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white">
            Population
          </h3>
          <div class="space-y-3 mt-2 flex-grow">
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold   dark:text-neutral-400">Total 4 211 190</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold   dark:text-neutral-400">Place 8ème</span>
            </div> 
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600  text-sm font-semibold  dark:text-neutral-400">Densité 71/km <sup>2</sup> </span>
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="demographie.html#population">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
      
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg  shadow-2xs  dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white">
           Ethnies
          </h3>
          <div class="space-y-3 mt-2 flex-grow">
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Luba</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Lulua</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold dark:text-neutral-400">Tshiluba</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Kikongo</span>  
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="demographie.html#ethnies">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
      
      
      <div class="info flex flex-col bg-white border border-gray-200 border-t-4 rounded-lg  shadow-2xs dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70 h-full">
        <div class="p-4 md:p-5 h-full flex flex-col rounded-lg">
          <h3 class="text-lg font-bold text-blue-900 bg-blue-100 rounded-sm p-2 dark:text-white">
            Territoire
          </h3>
          <div class="space-y-3 mt-2 flex-grow">
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Kananga</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Demba</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Dibaya</span>
            </div>
            <div class="inline-flex items-center">
              <span class="size-2 inline-block bg-blue-600 rounded-full me-2"></span>
              <span class="text-gray-600 text-sm font-semibold  dark:text-neutral-400">Kazumba</span>    
            </div>
          </div>
          <a class="mt-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent text-blue-600 decoration-2 hover:text-blue-700 hover:underline focus:underline focus:outline-hidden focus:text-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-600 dark:focus:text-blue-600" href="geo-kasai-central.html#decoupage">
            Explorer
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m9 18 6-6-6-6"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
 
        
  </div>
</div>

  
  
  <style>
    .toggle-switch.active .toggle-slider {
      transform: translateX(100%);
    }
  </style>

  
  
  <script>
      
      const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const delay = parseInt(entry.target.dataset.animate || 0);
            setTimeout(() => {
              entry.target.style.opacity = '1';
              if (entry.target.classList.contains('animate-scale-in')) {
                entry.target.style.transform = 'scale(1)';
              } else {
                entry.target.style.transform = 'translateY(0)';
              }
            }, delay);
             observer.unobserve(entry.target); 
          }
        });
      }, observerOptions);

      document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        if (el.classList.contains('animate-scale-in')) {
          el.style.transform = 'scale(0.95)';
        } else {
          el.style.transform = 'translateY(30px)';
        }
        observer.observe(el);
      });
  </script>
</section>

</section>

 <section id="Administration" class="bg-gray-50 p-8" style="font-family: 'Nunito';">

<div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white section-title">Nos secteurs</h2>
    <p class="mt-1 text-gray-600 dark:text-neutral-400">Nos secteurs d'activité avec lesquels nous travaillons</p>
  </div>
  

  
  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8 md:gap-12">
    <div class="relative text-center">
      <div class="relative size-24 mx-auto group hs-tooltip [--placement:top]">
          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
            Vers la page du gouvernorat
          </span>
          <a href="gouvernorat.html" class="absolute w-full h-full bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 ease-in duration-200"><i class="fa-solid fa-arrow-right text-white text-2xl"></i></a>
          <img class="rounded-full size-24 mx-auto  object-cover" src="images/gouvernorat/logo-gouvernorat.jpg" alt="Avatar">
      </div>
      <div class="mt-2 sm:mt-4">
        <h3 class="font-bold text-gray-800 dark:text-neutral-200">
          Gouvernorat
        </h3>
      </div>
    </div>
    

    <div class="text-center">
      <div class="relative size-24 mx-auto group hs-tooltip [--placement:top]">
          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
            Vers la page de la santé
          </span>
          <a href="sante.html" class="absolute w-full h-full bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 ease-in duration-200"><i class="fa-solid fa-arrow-right text-white text-2xl"></i></a>
          <img class="rounded-full size-24 mx-auto  object-cover" src="images/gouvernorat/sante_1.jpg" alt="Avatar">
      </div>
      <div class="mt-2 sm:mt-4">
        <h3 class="font-bold text-gray-800 dark:text-neutral-200">
          Santé
        </h3>
      </div>
    </div>
    

    <div class="text-center">
      <div class="relative size-24 mx-auto group hs-tooltip [--placement:top]">
          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
            Vers la page de l'éducation
          </span>
          <a href="education.html" class="absolute w-full h-full bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 ease-in duration-200"><i class="fa-solid fa-arrow-right text-white text-2xl"></i></a>
          <img class="rounded-full size-24 mx-auto  object-cover" src="images/gouvernorat/education.jfif" alt="Avatar">
      </div>
      <div class="mt-2 sm:mt-4">
        <h3 class="font-bold text-gray-800 dark:text-neutral-200">
          Education
        </h3>
      </div>
    </div>
    

    <div class="text-center">
      <div class="relative size-24 mx-auto group hs-tooltip [--placement:top]">
          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
            Vers la page de l'agriculture
          </span>
          <a href="agriculture.html" class="absolute w-full h-full bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 ease-in duration-200"><i class="fa-solid fa-arrow-right text-white text-2xl"></i></a>
          <img class="rounded-full size-24 mx-auto  object-cover" src="images/gouvernorat/agriculture.jfif" alt="Avatar">
      </div>
      <div class="mt-2 sm:mt-4">
        <h3 class="font-bold text-gray-800 dark:text-neutral-200">
          Agriculture
        </h3>
      </div>
    </div>
    

    <div class="text-center">
      <div class="relative size-24 mx-auto group hs-tooltip [--placement:top]">
          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700" role="tooltip">
            Vers la page infastructures
          </span>
          <a href="infrastructures.html" class="absolute w-full h-full bg-blue-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 ease-in duration-200"><i class="fa-solid fa-arrow-right text-white text-2xl"></i></a>
          <img class="rounded-full size-24 mx-auto  object-cover" src="images/gouvernorat/mairie.jpg" alt="Avatar">
      </div>
      <div class="mt-2 sm:mt-4">
        <h3 class="font-bold text-gray-800 dark:text-neutral-200">
          Infrastructures
        </h3>
      </div>
    </div>
    
</div>

    
</section>


<div id="gouverneur_mots" class="max-w-[85rem] bg-white px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  
  <div class="md:grid md:grid-cols-2 md:gap-10 lg:gap-16 md:items-center">
    <div class="hidden md:block mb-24 md:mb-0 sm:px-6">
      <div class="relative">
        <div class="relative  group" style="width: 100%;">
          <img src="images/gouvernorat/gouverneur.jpg" alt="" class="w-full h-auto duration-300 ease-in rounded-lg object-cover cursor-pointer hover:scale-105 z-10">
            <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out backdrop-blur-sm" 
                style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);">
                <a href="gouverneur.html" 
                  class="py-3.5 px-7 rounded-xl text-sm font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md transition-all duration-300 ease-out hover:bg-white/90 hover:text-gray-800 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transform translate-y-2 group-hover:translate-y-0">
                    Biographie
                </a>
            </div>
        </div>

        
        <div class="absolute bottom-0 start-0 -z-1 translate-y-10 -translate-x-14">
          <svg class="max-w-40 h-auto text-blue-600" width="696" height="653" viewBox="0 0 696 653" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="72.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="171.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="270.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="369.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="468.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="567.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="666.5" cy="29.5" r="29.5" fill="currentColor"/>
            <circle cx="29.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="128.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="227.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="326.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="425.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="524.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="623.5" cy="128.5" r="29.5" fill="currentColor"/>
            <circle cx="72.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="171.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="270.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="369.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="468.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="567.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="666.5" cy="227.5" r="29.5" fill="currentColor"/>
            <circle cx="29.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="128.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="227.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="326.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="425.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="524.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="623.5" cy="326.5" r="29.5" fill="currentColor"/>
            <circle cx="72.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="171.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="270.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="369.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="468.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="567.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="666.5" cy="425.5" r="29.5" fill="currentColor"/>
            <circle cx="29.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="128.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="227.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="326.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="425.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="524.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="623.5" cy="524.5" r="29.5" fill="currentColor"/>
            <circle cx="72.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="171.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="270.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="369.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="468.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="567.5" cy="623.5" r="29.5" fill="currentColor"/>
            <circle cx="666.5" cy="623.5" r="29.5" fill="currentColor"/>
          </svg>
        </div>
        
      </div>
    </div>
    

    <div>
      
      <blockquote class="relative">
        <svg class="absolute top-0 start-0 transform -translate-x-8 -translate-y-4 size-24 text-gray-200 dark:text-neutral-700" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M7.39762 10.3C7.39762 11.0733 7.14888 11.7 6.6514 12.18C6.15392 12.6333 5.52552 12.86 4.76621 12.86C3.84979 12.86 3.09047 12.5533 2.48825 11.94C1.91222 11.3266 1.62421 10.4467 1.62421 9.29999C1.62421 8.07332 1.96459 6.87332 2.64535 5.69999C3.35231 4.49999 4.33418 3.55332 5.59098 2.85999L6.4943 4.25999C5.81354 4.73999 5.26369 5.27332 4.84476 5.85999C4.45201 6.44666 4.19017 7.12666 4.05926 7.89999C4.29491 7.79332 4.56983 7.73999 4.88403 7.73999C5.61716 7.73999 6.21938 7.97999 6.69067 8.45999C7.16197 8.93999 7.39762 9.55333 7.39762 10.3ZM14.6242 10.3C14.6242 11.0733 14.3755 11.7 13.878 12.18C13.3805 12.6333 12.7521 12.86 11.9928 12.86C11.0764 12.86 10.3171 12.5533 9.71484 11.94C9.13881 11.3266 8.85079 10.4467 8.85079 9.29999C8.85079 8.07332 9.19117 6.87332 9.87194 5.69999C10.5789 4.49999 11.5608 3.55332 12.8176 2.85999L13.7209 4.25999C13.0401 4.73999 12.4903 5.27332 12.0713 5.85999C11.6786 6.44666 11.4168 7.12666 11.2858 7.89999C11.5215 7.79332 11.7964 7.73999 12.1106 7.73999C12.8437 7.73999 13.446 7.97999 13.9173 8.45999C14.3886 8.93999 14.6242 9.55333 14.6242 10.3Z" fill="currentColor"/>
        </svg>

        <div class="relative z-10">
          <p class="text-xs font-semibold text-gray-500 uppercase mb-3 dark:text-neutral-200">
            Mots du gouverneur
          </p>

          <p class="text-xl font-bold italic text-gray-800 md:text-2xl md:leading-normal xl:text-3xl xl:leading-normal dark:text-neutral-200" style="font-family: 'Satisfy'">
              Chers habitants de notre belle province,
              Ensemble, poursuivons nos efforts pour bâtir une région plus forte, plus juste et prospère. Votre engagement et votre résilience sont la clé de notre développement. Je vous invite à continuer à croire en notre avenir commun
          </p>
        </div>

        <footer class="mt-6">
          <div class="flex items-center">
            <div class="md:hidden shrink-0">
              <img class="size-12 rounded-full" src="images/gouvernorat/gouverneur.jpg" alt="Avatar">
            </div>
            <div class="ms-4 md:ms-0">
              <div class="text-base font-semibold text-gray-800 dark:text-neutral-200"> Joseph-Moïse Kambulu </div>
              <div class="text-xs text-gray-500 dark:text-neutral-400">Gouverneur | kasai central</div>
            </div>
          </div>
        </footer>

        <div class="mt-8 lg:mt-14 flex justify-between">
          <a class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl bg-blue-600 text-white hover:bg-white hover:text-blue-600 duration-200 ease-out text-sm bg-blue-600 hover:bg-white hover:text-blue-600 font-medium text-white border-2 border-blue-600 focus:outline-hidden focus:bg-gray-900 disabled:opacity-50 disabled:pointer-events-none dark:bg-white dark:text-neutral-800" href="gouverneur.html">
            Biographie
          </a>
          <img src="images/gouvernorat/logo.jpg" alt="logo-gouvernorat-kasai-central" class=" w-40">
        </div>
      </blockquote>
      
    </div>
    
  </div>
  
</div>

<section id="Administration" class="bg-gray-50 p-8" style="font-family: 'Nunito';">
  
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white section-title">Projets en cours</h2>
    <p class="mt-1 text-gray-600 dark:text-neutral-400"> Découvrez nos projets mis en place </p>
  </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <a class="group item overlay-before max-w-xs h-96 relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-hidden focus:shadow-lg bg-[url('')] transition" style="background-image: url(construction-routiere.avif); font-family: 'Nunito', sans-serif;" href="kasai_project.html#projet-construction">
        <div class="flex-auto p-4 md:p-6 z-60">
          <h3 class="text-md text-white/90 group-hover:text-white"><span class="font-bold">Construction</span> asphaltage d'une route de transport agricole près de Murundu.</h3>
        </div>
        <div class="pt-0 p-4 md:p-6 z-60">
          <div class="ct_projets inline-flex items-center gap-2 text-sm font-medium text-white group-hover:text-white/70">
         En savoir plus
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
          </div>
        </div>
      </a>
      <a class="group item overlay-before max-w-xs h-96 relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-hidden focus:shadow-lg bg-[url('')] transition" style="background-image: url(eau_potable.png); font-family: 'Nunito', sans-serif;" href="kasai_project.html#projet-eau">
        <div class="flex-auto p-4 md:p-6 z-60">
          <h3 class="text-md text-white/90 group-hover:text-white"><span class="font-bold">Environnement</span> mise en disponibilité de l'eau potable dans les milieux ruraux par l'AGDC.</h3>
        </div>
        <div class="pt-0 p-4 md:p-6 z-60">
          <div class="inline-flex ct_projets items-center gap-2 text-sm font-medium text-white group-hover:text-white/70">
        En savoir plus
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
          </div>
        </div>
      </a>

<a class="group item  overlay-before max-w-xs h-96 relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-hidden focus:shadow-lg bg-[url('')] transition" style="background-image: url(vaccin.jpg); font-family: 'Nunito', sans-serif;" href="kasai_project.html#projet-vaccin">
        <div class="flex-auto p-4 md:p-6 z-60">
          <h3 class="text-md text-white/90 group-hover:text-white"><span class="font-bold">Vaccin</span> programme de vaccinnation des enfants contre la rougeole.</h3>
        </div>
        <div class="pt-0 p-4 md:p-6 z-60">
          <div class="inline-flex ct_projets items-center gap-2 text-sm font-medium text-white group-hover:text-white/70">
        En savoir plus
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
          </div>
        </div>
      </a>

<a class="group item  overlay-before max-w-xs h-96 relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-hidden focus:shadow-lg bg-[url('')] transition" style="background-image: url(assainissaiment.jpg); font-family: 'Nunito', sans-serif;" href="kasai_project.html#projet-assainissement">
        <div class="flex-auto p-4 md:p-6 z-60">
          <h3 class="text-md text-white/90 group-hover:text-white"><span class="font-bold">Assainissement</span> lancement d'un programme d'assainissement dans tous les secteurs.</h3>
        </div>
        <div class="pt-0 p-4 md:p-6 z-60">
          <div class="inline-flex ct_projets items-center gap-2 text-sm font-medium text-white group-hover:text-white/70">
        En savoir plus
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m9 18 6-6-6-6"></path>
        </svg>
          </div>
        </div>
      </a>



    </div>
    
</section>


<style>
  .partenaires-container {
    width: 100%;
    overflow: hidden;
    position: relative;
  }
  
  .partenaires-slide {
    display: flex;
    width: max-content;
    animation: defilement 30s linear infinite;
  }
  
  .partenaires-slide:hover {
    animation-play-state: paused;
  }
  
  @keyframes defilement {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(-50%);
    }
  }
  
  .partenaires-slide img {
    flex-shrink: 0;
  }
</style>

<div class="bg-blue-600" style="font-family: 'Nunito';">
  <div class="max-w-[85rem] px-4 py-4 sm:px-6 lg:px-8 mx-auto text-center">
    <a class="group p-4 rounded-lg inline-flex flex-wrap items-center bg-white/10 hover:bg-white group focus:outline-hidden focus:bg-white/10 border border-white/10 p-1 ps-4 " href="gouvernorat.html">
      <p class="me-2 text-white group-hover:text-blue-600 font-bold">
        Consulter notre administration
      </p>
      <span class="group-hover:bg-white/10 group-focus:bg-white/10 py-1.5 px-2.5 inline-flex justify-center items-center gap-x-2 rounded-full bg-white/10 font-semibold text-white text-sm group-hover:text-blue-600">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </span>
    </a>
  </div>
</div>
<aside id="dirigeants" class="p-8 bg-gray-50" style="font-family:'Nunito', sans-serif">
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white section-title">Nos dirigeants</h2>
    <p class="mt-1 text-gray-600 dark:text-neutral-400"> Dépuis la création de la province jusqu'à aujourd'hui </p>
  </div>
  <div class="flex flex-col gap-8 lg:flex-row lg:gap-4 justify-around items-center p-8 rounded-lg bg-gray-50">
    <div class="relative w-64 h-64 rounded-lg">
      <img src="images/gouvernorat/alex.jpg" alt="" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-300 cursor-pointer">
      <h1 class="absolute left-0 bottom-5 p-4 bg-black/50 text-white"><span class="font-bold">Alex Kande Mupompa</span> <div class="text-xs">2015-2017</div></h1>
    </div>
    <div class="relative w-64 h-64 rounded-lg">
      <img src="images/gouvernorat/kambayi.jpg" alt="" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-300">
      <h1 class="absolute left-0 bottom-5 p-4 bg-black/50 text-white"><span class="font-bold">Denis Kambayi</span> <div class="text-xs">2017-2019</div></h1>
    </div>
    <div class="relative w-64 h-64 rounded-lg">
      <img src="images/gouvernorat/kabuya.jfif" alt="" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-300">
      <h1 class="absolute left-0 bottom-5 p-4 bg-black/50 text-white"><span class="font-bold">Martin Kabuya</span> <div class="text-xs">2019-2022</div></h1>
    </div>
    <div class="relative w-64 h-64 rounded-lg">
      <img src="images/gouvernorat/john.jfif" alt="" class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-300">
      <h1 class="absolute left-0 bottom-5 p-4 bg-black/50 text-white"><span class="font-bold">John Kabeya Shikayi </span> <div class="text-xs">2022-2024</div></h1>
    </div>
    <div class="relative w-64 h-64 rounded-lg">
      <img src="images/gouvernorat/gouverneur.jpg" alt="" class="w-full h-full object-cover hover:grayscale-0 transition duration-300">
      <h1 class="absolute left-0 bottom-5 p-4 bg-black/50 text-white"><span class="font-bold">Joseph-Moïse K'ambulu </span> <div class="text-xs">depuis 2024</div></h1>
    </div>
  </div>
</aside>
<section id="tout_savoir" class="w-full bg-white">
  <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
    <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white section-title">Tout savoir</h2>
    <p class="mt-1 text-gray-600 dark:text-neutral-400">Ne ratez aucun sujet sur le Kasai-Central</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-0 w-full">
    <div class="relative group aspect-square w-full h-full">
      <img src="images/tout_savoir/manifestation.jpg" alt="" class="w-full h-full object-cover">
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center flex-col p-8 rounded-none opacity-100 group-hover:-translate-y-2 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(255, 255, 255, 0.3) 100%);">
        <h1 class="text-2xl font-bold text-white mb-4">
          Découvrez les dernières actualités
        </h1>
        <p class="text-white mb-6">
          Soyez le premier à connaître les nouvelles importantes du Kasai-Central.
        </p>
      </div>
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center rounded-none opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);">
        <a href="actu-kasai-central.php"
          class="py-3.5 px-7 rounded-xl text-sm font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md transition-all duration-300 ease-out hover:bg-white/90 hover:text-gray-800 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transform translate-y-2 group-hover:translate-y-0">
          Actualités
        </a>
      </div>
    </div>

    <div class="relative group aspect-square w-full h-full">
      <img src="images/tout_savoir/tourisme.jpg" alt="" class="w-full h-full object-cover">
            <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center flex-col p-8 rounded-none opacity-100 group-hover:-translate-y-2 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(255, 255, 255, 0.3) 100%);">
        <h1 class="text-2xl font-bold text-white mb-4">
          Explorez les merveilles du Kasai-Central
        </h1>
        <p class="text-white mb-6">
          les sites touristiques incontournables et les activités à ne pas manquer.
        </p>
      </div>
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center rounded-none opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);">
        <a href="tourisme.html"
          class="py-3.5 px-7 rounded-xl text-sm font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md transition-all duration-300 ease-out hover:bg-white/90 hover:text-gray-800 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transform translate-y-2 group-hover:translate-y-0">
          Tourisme
        </a>
      </div>
    </div>

    <div class="relative group aspect-square w-full h-full">
      <img src="images/tout_savoir/geographie.jpg" alt="" class="w-full h-full object-cover">
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center flex-col p-8 rounded-none opacity-100 group-hover:-translate-y-2 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(255, 255, 255, 0.3) 100%);">
        <h1 class="text-2xl font-bold text-white mb-4">
          Plongez dans la géographie du Kasai-Central
        </h1>
        <p class="text-white mb-6">
          Découvrez les paysages, les rivières et les montagnes qui font la beauté de notre province.
        </p>
      </div>
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center rounded-none opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);">
        <a href="geo-kasai-central.html"
          class="py-3.5 px-7 rounded-xl text-sm font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md transition-all duration-300 ease-out hover:bg-white/90 hover:text-gray-800 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transform translate-y-2 group-hover:translate-y-0">
          Géographie
        </a>
      </div>
    </div>

    <div class="relative group aspect-square w-full h-full">
      <img src="images/tout_savoir/galerie1.jpg" alt="" class="w-full h-full object-cover">
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center flex-col p-8 rounded-none opacity-100 group-hover:-translate-y-2 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(255, 255, 255, 0.3) 100%);">
        <h1 class="text-2xl font-bold text-white mb-4">
          Admirez la beauté du Kasai-Central
        </h1>
        <p class="text-white mb-6">
          Explorez notre galerie de photos pour découvrir les paysages et la culture de notre province.
        </p>
      </div>
      <div class="absolute cursor-pointer top-0 left-0 w-full h-full flex justify-center items-center rounded-none opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out backdrop-blur-sm"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.1) 100%);">
        <a href="galerie.html"
          class="py-3.5 px-7 rounded-xl text-sm font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-md transition-all duration-300 ease-out hover:bg-white/90 hover:text-gray-800 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl transform translate-y-2 group-hover:translate-y-0">
          Galerie
        </a>
      </div>
    </div>
  </div>
</section>

<section id="faq" style="font-family:'Nunito', sans-serif">

<div class="max-w-[85rem] px-4 py-10 bg-white sm:px-6 lg:px-8 lg:py-14 mx-auto">
  
  <div class="grid md:grid-cols-5 gap-10">
    <div class="md:col-span-2">
      <div class="max-w-xs">
        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight text-blue-600 dark:text-white">Questions<br>fréquentes</h2>
        <p class="mt-1 hidden md:block text-gray-600 dark:text-neutral-400">Réponses aux questions les plus posées sur le Kasaï Central.</p>
        <img src="images/bg/faq.jpg" alt="" class="w-40">
      </div>
    </div>
    

    <div class="md:col-span-3">
      
      <div class="hs-accordion-group divide-y divide-gray-200 dark:divide-neutral-700">
        <div class="hs-accordion pb-3 active" id="hs-heading-one">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="true" aria-controls="hs-collapse-one">
            Quelles sont les principales villes du Kasaï Central ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-one">
            <p class="text-gray-600 dark:text-neutral-400">
              Le Kasaï Central a pour chef-lieu Kananga. Les autres villes importantes incluent Tshikapa, Luebo, Mweka, Demba, et Dibaya. La province compte 5 territoires : Demba, Dibaya, Dimbelenge, Kazumba et Luiza.
            </p>
          </div>
        </div>

        <div class="hs-accordion pt-6 pb-3" id="hs-heading-two">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="false" aria-controls="hs-collapse-two">
            Quelles sont les langues parlées dans la province ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-two">
            <p class="text-gray-600 dark:text-neutral-400">
              Le français est la langue officielle, mais le tshiluba est la langue vernaculaire la plus parlée. On trouve également d'autres langues locales comme le kikongo, le lingala, ainsi que divers dialectes selon les communautés.
            </p>
          </div>
        </div>

        <div class="hs-accordion pt-6 pb-3" id="hs-heading-three">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="false" aria-controls="hs-collapse-three">
            Quelles sont les activités économiques principales ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-three" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-three">
            <p class="text-gray-600 dark:text-neutral-400">
              Le Kasaï Central a une économie basée sur :
              <ul class="list-disc pl-5 mt-2">
                <li>L'agriculture (maïs, manioc, arachides)</li>
                <li>L'exploitation minière (diamants à Tshikapa)</li>
                <li>Le commerce</li>
                <li>L'artisanat (notamment les tissus Kuba)</li>
              </ul>
              La province possède également un potentiel touristique avec ses chutes et sites culturels.
            </p>
          </div>
        </div>

        <div class="hs-accordion pt-6 pb-3" id="hs-heading-four">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="false" aria-controls="hs-collapse-four">
            Comment se rendre au Kasaï Central ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-four" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-four">
            <p class="text-gray-600 dark:text-neutral-400">
              Accès principalement par :
              <ul class="list-disc pl-5 mt-2">
                <li><strong>Avion</strong> : Aéroport de Kananga avec des vols réguliers depuis Kinshasa</li>
                <li><strong>Route</strong> : Réseau routier reliant à d'autres provinces (état variable selon la saison)</li>
                <li><strong>Train</strong> : Via le chemin de fer du Kasaï (Kananga-Ilebo)</li>
              </ul>
              Il est recommandé de se renseigner sur les conditions de voyage avant le départ.
            </p>
          </div>
        </div>

        <div class="hs-accordion pt-6 pb-3" id="hs-heading-five">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="false" aria-controls="hs-collapse-five">
            Quelles sont les institutions universitaires de la province ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-five" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-five">
            <p class="text-black font-bold dark:text-neutral-400">
              Le Kasaï Central compte plusieurs institutions d'enseignement supérieur dont :
              <ul class="list-disc pl-5 mt-2">
                <li>Université de Kananga (UNIKAN)</li>
                <li>Université Notre-Dame du Kasaï (UNDK)</li>
                <li>Institut Supérieur Pédagogique (ISP)</li>
                <li>Plusieurs instituts techniques et professionnels</li>
              </ul>
              Ces institutions offrent des formations dans divers domaines académiques et professionnels.
            </p>
          </div>
        </div>

        <div class="hs-accordion pt-6 pb-3" id="hs-heading-six">
          <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-blue-800 rounded-lg transition hover:text-gray-500 focus:outline-hidden focus:text-gray-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" aria-expanded="false" aria-controls="hs-collapse-six">
            Quels sont les sites touristiques à visiter ?
            <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500 dark:text-neutral-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
          </button>
          <div id="hs-collapse-six" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-heading-six">
            <p class="text-gray-600 dark:text-neutral-400">
              Parmi les sites remarquables :
              <ul class="list-disc pl-5 mt-2">
                <li>Les chutes de la rivière Lulua</li>
                <li>Le musée national du Kasaï à Kananga</li>
                <li>Les centres artisanaux de tissage Kuba</li>
                <li>Les sites historiques de la chefferie Bena Lulua</li>
                <li>Les paysages de la savane et forêts galeries</li>
              </ul>
              La province travaille au développement de son offre touristique.
            </p>
          </div>
        </div>
      </div>
      
    </div>
    
  </div>
  
</div>

</section>
<section id="contact" style="font-family:'Nunito', sans-serif">

<div id="contact" class="relative bg-gray-100 border-t border-gray-200">
  <div class="absolute z-0 w-full left-0 bottom-0">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#0099ff" class="fill-blue-200" fill-opacity="1" d="M0,224L80,213.3C160,203,320,181,480,160C640,139,800,117,960,96C1120,75,1280,53,1360,42.7L1440,32L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path></svg>
  </div>
  <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">
    
    <div class="max-w-3xl mb-10 lg:mb-14">
      <h2 class="text-blue-600 font-bold text-2xl md:text-4xl md:leading-tight">Contactez-nous</h2>
      <p class="mt-1 text-gray-600">Vos questions et suggestions pour le développement du Kasaï Central</p>
    </div>
    
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 lg:gap-x-16">
      <div class="order-2 border-b border-gray-200 pb-10 mb-10 md:border-b-0 md:pb-0 md:mb-0">
        <form method="post" action="">
          <div class="space-y-4">
            
            <div class="relative">
              <input type="text" id="contact-name" class="peer p-3 sm:p-4 block w-full font-bold bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-800 placeholder:text-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none
              focus:pt-6
              focus:pb-2
              not-placeholder-shown:pt-6
              not-placeholder-shown:pb-2" name="nom" placeholder="Nom Complet" required>
              <label for="contact-name" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-gray-500 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
                peer-focus:text-xs
                peer-focus:-translate-y-1.5
                peer-focus:text-blue-600
                peer-not-placeholder-shown:text-xs
                peer-not-placeholder-shown:-translate-y-1.5
                peer-not-placeholder-shown:text-gray-500" >Nom Complet</label>
            </div>
            

            
            <div class="relative">
              <input type="email" id="contact-email" class="peer p-3 sm:p-4 block w-full font-bold bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-800 placeholder:text-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none
              focus:pt-6
              focus:pb-2
              not-placeholder-shown:pt-6
              not-placeholder-shown:pb-2" name="email" placeholder="Email" required>
              <label for="contact-email" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-gray-500 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
                peer-focus:text-xs
                peer-focus:-translate-y-1.5
                peer-focus:text-blue-600
                peer-not-placeholder-shown:text-xs
                peer-not-placeholder-shown:-translate-y-1.5
                peer-not-placeholder-shown:text-gray-500">Adresse Email</label>
            </div>
            

            
            <div class="relative">
              <input type="text" id="contact-commune" class="peer p-3 sm:p-4 block w-full font-bold bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-800 placeholder:text-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none
              focus:pt-6
              focus:pb-2
              not-placeholder-shown:pt-6
              not-placeholder-shown:pb-2" name="adresse" placeholder="Commune/Territoire" required>
              <label for="contact-commune" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-gray-500 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
                peer-focus:text-xs
                peer-focus:-translate-y-1.5
                peer-focus:text-blue-600
                peer-not-placeholder-shown:text-xs
                peer-not-placeholder-shown:-translate-y-1.5
                peer-not-placeholder-shown:text-gray-500">Commune/Territoire</label>
            </div>
            

            
            <div class="relative">
              <input type="text" id="contact-phone" class="peer p-3 sm:p-4 block w-full font-bold bg-gray-50 border border-gray-300 rounded-lg sm:text-sm text-gray-800 placeholder:text-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none
              focus:pt-6
              focus:pb-2
              not-placeholder-shown:pt-6
              not-placeholder-shown:pb-2" name="telephone" placeholder="Téléphone" required>
              <label for="contact-phone" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-gray-500 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
                peer-focus:text-xs
                peer-focus:-translate-y-1.5
                peer-focus:text-blue-600
                peer-not-placeholder-shown:text-xs
                peer-not-placeholder-shown:-translate-y-1.5
                peer-not-placeholder-shown:text-gray-500">Numéro de Téléphone</label>
            </div>
                     
            <div class="relative">
              <textarea id="contact-message" class="peer p-3 sm:p-4 block w-full bg-gray-50 font-bold border border-gray-300 rounded-lg sm:text-sm text-gray-800 placeholder:text-transparent focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none
              focus:pt-6
              focus:pb-2
              not-placeholder-shown:pt-6
              not-placeholder-shown:pb-2" name="message" placeholder="Votre message" required data-hs-textarea-auto-height></textarea>
              <label for="contact-message" class="absolute top-0 start-0 p-3 sm:p-4 h-full text-gray-500 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
                peer-focus:text-xs
                peer-focus:-translate-y-1.5
                peer-focus:text-blue-600
                peer-not-placeholder-shown:text-xs
                peer-not-placeholder-shown:-translate-y-1.5
                peer-not-placeholder-shown:text-gray-500">Votre message</label>
            </div>
            
          </div>

          <div class="relative mt-2 z-20">
            <p class="mt-5">
              <button type="submit" name="submit" class="group z-20 flex w-full items-center justify-center gap-x-2 py-4 px-6 bg-blue-700 hover:bg-blue-800 font-medium text-sm text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                Envoyer
                <svg class="shrink-0 size-4 transition group-hover:translate-x-0.5 group-focus:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
              </button>
            </p>
          </div>
        </form>
      </div>
      

      <div class="space-y-14">
        
        <div class="flex gap-x-5 relative z-10 mb-4">
          <div class="grow">
            <h4 class="text-blue-600 font-semibold">AVEZ VOUS UN MESSAGE ?</h4>
              <h2>ECRIVEZ NOUS , NOUS VOUS REPONDRONS</h2>
          </div>
        </div>
             
      </div>
      
    </div>
    
  </div>
</div>

</section>
<section style="font-family: 'Nunito', sans-serif;">
  <div class="relative z-10">
    <div class="max-w-5xl px-4 xl:px-0 mx-auto">
      <div class="mb-4 mt-4">
        <h2 class="text-gray-50 font-bold text-xl">Nos partenaires dans le monde</h2>
      </div>
      <div class="partenaires-container relative overflow-hidden">
        <div class="partenaires-slide flex justify-between gap-6 p-8">
          
          <img src="images/partenaires/Logo_of_UNICEF.svg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/UN_emblem_blue.svg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/logo-cd.jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (2).jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (3).jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/AMBLEME-CONGO2.png" alt="" class="w-50 bg-white rounded-lg">
          <img src="images/partenaires/World-Bank-logo.png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/caritas.png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/Logo-gouv_EPST.png" alt="" class="w-64 bg-white rounded-lg">
          <img src="images/partenaires/images (2).png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (3).png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/3235.jpg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/equity-bank-logo.png" alt="" class="w-40 rounded-lg">
          <img src="images/partenaires/cropped-FBNBank-Senegal-Logo2-1.png" alt="" class="w-64 rounded-lg">
          
          
          <img src="images/partenaires/Logo_of_UNICEF.svg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/UN_emblem_blue.svg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/logo-cd.jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (2).jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (3).jfif" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/AMBLEME-CONGO2.png" alt="" class="w-50 bg-white rounded-lg">
          <img src="images/partenaires/World-Bank-logo.png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/caritas.png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/Logo-gouv_EPST.png" alt="" class="w-64 bg-white rounded-lg">
          <img src="images/partenaires/images (2).png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/images (3).png" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/3235.jpg" alt="" class="w-20 rounded-lg">
          <img src="images/partenaires/equity-bank-logo.png" alt="" class="w-40 rounded-lg">
          <img src="images/partenaires/cropped-FBNBank-Senegal-Logo2-1.png" alt="" class="w-64 rounded-lg">
        </div>
      </div>
    </div>
  </div>
</section>
<footer class="mt-auto bg-gray-900 w-full dark:bg-neutral-950">
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
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="infrastructures.html">Agriculture</a></p>
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
          <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">@central.com</a></p>
          <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-hidden focus:text-gray-200 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200" href="#">@kasaicentral.com</a></p>
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
            <button type="submit" name="submit" class="w-full sm:w-auto whitespace-nowrap p-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
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
        <p class="text-sm text-gray-400 dark:text-neutral-400">
          © 2025 kasai central.
        </p>
      </div>
      

      
      <div>
        <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
          </svg>
        </a>
        <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
          </svg>
        </a>
        <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
          </svg>
        </a>
        <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
          </svg>
        </a>
        <a class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-hidden focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none" href="#">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M3.362 10.11c0 .926-.756 1.681-1.681 1.681S0 11.036 0 10.111C0 9.186.756 8.43 1.68 8.43h1.682v1.68zm.846 0c0-.924.756-1.68 1.681-1.68s1.681.756 1.681 1.68v4.21c0 .924-.756 1.68-1.68 1.68a1.685 1.685 0 0 1-1.682-1.68v-4.21zM5.89 3.362c-.926 0-1.682-.756-1.682-1.681S4.964 0 5.89 0s1.68.756 1.68 1.68v1.682H5.89zm0 .846c.924 0 1.68.756 1.68 1.681S6.814 7.57 5.89 7.57H1.68C.757 7.57 0 6.814 0 5.89c0-.926.756-1.682 1.68-1.682h4.21zm6.749 1.682c0-.926.755-1.682 1.68-1.682.925 0 1.681.756 1.681 1.681s-.756 1.681-1.68 1.681h-1.681V5.89zm-.848 0c0 .924-.755 1.68-1.68 1.68A1.685 1.685 0 0 1 8.43 5.89V1.68C8.43.757 9.186 0 10.11 0c.926 0 1.681.756 1.681 1.68v4.21zm-1.681 6.748c.926 0 1.682.756 1.682 1.681S11.036 16 10.11 16s-1.681-.756-1.681-1.68v-1.682h1.68zm0-.847c-.924 0-1.68-.755-1.68-1.68 0-.925.756-1.681 1.68-1.681h4.21c.924 0 1.68.756 1.68 1.68 0 .926-.756 1.681-1.68 1.681h-4.21z"/>
          </svg>
        </a>
      </div>
      
    </div>
  </div>
</footer>


<script>
    let btn_news = document.getElementById('news');
    let alaune = document.getElementById('alaune');
    btn_news.addEventListener('mouseover', function() {
        alaune.classList.remove('hidden');
    });
    btn_news.addEventListener('mouseout', function() {
        alaune.classList.add('hidden');
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
  const carouselEl = document.querySelector('[data-hs-carousel]');
  
    const carousel = new HSCarousel(carouselEl, {
    isAutoPlay: false
  });

    setTimeout(() => {
    carousel.startAutoPlay();
    carousel.options.interval = 15000; 
  }, 15000);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
<script src="https://unpkg.com/scrollreveal"></script>
<script>
  
  ScrollReveal().reveal('.item', {
    origin: 'bottom',
    distance: '40px',
    duration: 800,
    delay: 100,
    opacity: 0,
    easing: 'ease-out',
    interval: 120,
    reset: false
  });
  ScrollReveal().reveal('.info', {
    origin: 'left',
    distance: '40px',
    duration: 800,
    delay: 100,
    opacity: 0,
    easing: 'ease-out',
    interval: 120,
    reset: false
  });
  ScrollReveal().reveal('.carte', {
    origin: 'right',
    distance: '40px',
    duration: 800,
    delay: 100,
    opacity: 0,
    easing: 'ease-out',
    interval: 120,
    reset: false
  });
    ScrollReveal().reveal('#apropos', {
      delay: 100,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });

    ScrollReveal().reveal('#discover', {
      delay: 600,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });
    ScrollReveal().reveal('#Administration', {
      delay: 1100,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });
      ScrollReveal().reveal('#gouverneur_mots', {
      delay: 1100,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });
      ScrollReveal().reveal('#dirigeants', {
      delay: 1100,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });
          ScrollReveal().reveal('#apropos', {
      delay: 1100,
      distance: '40px',
      duration: 800,
      origin: 'bottom',
      opacity: 0,
      easing: 'ease',
      reset: false
    });

  document.querySelectorAll('[role="progressbar"]').forEach(function(bar) {
    const value = parseInt(bar.getAttribute('aria-valuenow'), 10) || 0;
    const inner = bar.querySelector('div');
    if (!inner) return;
    inner.style.width = '0%';
    let current = 0;
    const step = Math.max(1, Math.round(value / 40));
    function animate() {
      if (current < value) {
        current += step;
        if (current > value) current = value;
        inner.style.width = current + '%';
        requestAnimationFrame(animate);
      }
    }
    setTimeout(animate, 300);
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.swiper', {
      loop: true,
      autoplay: {
        delay: 10000, 
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      on: {
        slideChange: function () {
          const activeSlide = this.slides[this.activeIndex];
          const imageName = activeSlide.getAttribute('data-image');
          const textElement = document.getElementById('dynamic-text');

          switch (imageName) {
            case 'ville2':
              textElement.textContent = 'Une infrastructure riche et moderne en constante évolution';
              break;
            case 'paysage':
              textElement.textContent = 'Une diversité culturelle et traditionnelle hors norme';
              break;
            case 'africain':
              textElement.textContent = 'Une population solidaire et engagée';
              break;
            default:
              textElement.textContent = 'Symbole de la diversité culturelle et économique';
          }
        },
      },
    });
  });
</script>
<button id="back-to-top" class="w-15 h-15 rounded-full bg-blue-600 text-white z-80 flex items-center justify-center" style="position: fixed; bottom: 20px; right: 20px;"><i class="fa-solid fa-arrow-up"></i></button>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');
    backToTopButton.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', function() {
      if (window.scrollY > 200) {
        backToTopButton.style.display = 'flex';
      } else {
        backToTopButton.style.display = 'none';
      }
    });
  });
</script>
<div id="toast-container" class="fixed bottom-4 right-4 z-90">
    <?php if ($showSuccessToast): ?>
        <div class="max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700 mb-3" role="alert">
            <div class="flex p-4">
                <div class="shrink-0">
                    <svg class="shrink-0 size-4 text-teal-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
                    </svg>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700 dark:text-neutral-400">
                        Message envoyé avec succès!
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($showErrorToast): ?>
        <div class="max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700 mb-3" role="alert">
            <div class="flex p-4">
                <div class="shrink-0">
                    <svg class="shrink-0 size-4 text-red-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"></path>
                    </svg>
                </div>
                <div class="ms-3">
                    <p class="text-sm text-gray-700 dark:text-neutral-400">
                        Erreur lors de l'envoi du message!
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('#toast-container > div');
    
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    });
});
</script>
</body>
</html>