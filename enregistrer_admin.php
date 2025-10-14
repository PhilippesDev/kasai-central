<?php
require 'config.php'; 

session_start(); 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_key = implode('', $_POST['pin']);
    $input_key = trim($input_key);

    $input_key = password_hash($input_key, PASSWORD_BCRYPT);

    $query = "INSERT INTO admin_keys (key_hash,	created_at,	is_active) VALUES (?, NOW() , 1)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $input_key);

    if(mysqli_stmt_execute($stmt)){
        echo '
            <div>
                <p>Admin enregistré avec succès</p>
            </div> ' ;
        }
       
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Enregistreur d'admin</title>

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

</head>
<body>


            <form method="POST" action="">
                <div class="flex gap-x-3 mb-10" data-hs-pin-input="">
                    <?php for ($i = 0; $i < 8; $i++): ?>
                        <input type="password" name="pin[]" maxlength="1" class="block size-15.5 text-center border-2 border-sky-400 rounded-md text-lg [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="⚬" data-hs-pin-input-item="">
                    <?php endfor; ?>
                </div>
                <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Valider</button>
            </form>
</body>
</html>