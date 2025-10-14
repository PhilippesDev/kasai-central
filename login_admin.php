<?php
require 'config.php'; 

session_start(); 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input_key = implode('', $_POST['pin']);
    $input_key = trim($input_key);

    $query = "SELECT key_hash FROM admin_keys WHERE is_active = 1 ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $key_valid = false;
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($input_key, $row['key_hash'])) {
                $key_valid = true;
                break;
            }
        }
        mysqli_free_result($result);

        if ($key_valid) {
            $_SESSION['authenticated'] = true;
            header('Location: main-app.php'); 
            exit;
        } else {
            $error = "Clé invalide ou expirée.";
        }
    } else {
        $error = "Erreur lors de la vérification de la clé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Site du Kasai Centrale">
    <meta name="keywords" content="Kasai Central, Gouvernorat, RDC, République Démocratique du Congo">
    <meta name="author" content="Ir philippe mirindi lukogo">
    <title>Administration</title>

    <link rel="shortcut icon" href="images/favicon.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-dC7YuqXtKe6lS6b9qsycR4M0klJy+dNf62G9McTcuCuLoXi0ePlKZ4WhtAKazjaDttGGNqqxjJW/78VwYV4n2w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/3137461f7e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://preline.co/assets/css/main.css?v=3.1.0">
    <link rel="stylesheet" href="st.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">

    <style>
        #cartes article {
            background: none;
        }
        #cartes .w-full {
            width: 100%;
            height: 100%;
        }
        input {
            border-color: aqua;
            background-color: black;
            color: white;
        }
        .conteneur {
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="conteneur bg-blue-950 w-full h-full flex items-center justify-center px-4 md:px-6 lg:px-8">
        <div>
            <h1 class="text-white font-bold text-4xl mb-10 text-center">Page administration</h1>
            <h1 class="text-white font-bold text-1xl mb-4 text-center">Entrer votre clé</h1>

            <?php if ($error): ?>
                <div class="bg-red-50 border-s-4 border-red-500 p-4 dark:bg-red-800/30" role="alert">
                    <div class="flex">
                        <div class="shrink-0">
                            <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800 dark:border-red-900 dark:bg-red-800 dark:text-red-400">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="ms-3">
                            <h3 class="text-gray-800 font-semibold dark:text-white">Erreur</h3>
                            <p class="text-sm text-gray-700 dark:text-neutral-400"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="flex gap-x-3 mb-10" data-hs-pin-input="">
                    <?php for ($i = 0; $i < 8; $i++): ?>
                        <input type="password" name="pin[]" maxlength="1" class="block size-15.5 text-center border-2 border-sky-400 rounded-md text-lg [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                    <?php endfor; ?>
                </div>
                <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Valider</button>
            </form>

            <div class="bg-red-50 border-s-4 border-red-500 p-4 dark:bg-red-800/30 mt-4" role="alert" tabindex="-1" aria-labelledby="hs-bordered-red-style-label">
                <div class="flex">
                    <div class="shrink-0">
                        <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-red-100 bg-red-200 text-red-800 dark:border-red-900 dark:bg-red-800 dark:text-red-400">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="ms-3">
                        <h3 id="hs-bordered-red-style-label" class="text-gray-800 font-semibold dark:text-white">Attention !</h3>
                        <p class="text-sm text-gray-700 dark:text-neutral-400">Veuillez quitter cette page si vous n'êtes pas autorisé à y accéder.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/preline/dist/index.js"></script>
    <script>
        document.querySelectorAll('[data-hs-pin-input-item]').forEach((input, index, inputs) => {
    input.addEventListener('input', () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });
});
    </script>
</body>
</html>