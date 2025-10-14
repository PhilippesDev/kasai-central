<?php
require 'config_cit.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Nationale de Renseignement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
     <div class="loader-container" id="loader">
        <div class="loader-logo">◉ ANDR ◉</div>
        <div class="progress-container">
            <div class="progress-bar" id="progressBar"></div>
        </div>
        <div class="loading-text" id="loadingText">INITIALISATION DU SYSTÈME...</div>
    </div>

    <div class="cyber-container">
                <header class="cyber-header">
            <h1><i class="bi bi-person-badge"></i> ADMINISTATION KASAI CENTRAL</h1>
            <p class="cyber-subtitle">GESTION DES CITOYENS </p>
            <div class="cyber-scanline"></div>
        </header>

                <nav class="cyber-nav">
            <a href="main-app.php" class="cyber-nav-btn active"><i class="bi bi-house-door"></i> TABLEAU DE BORD</a>
            <a href="recensement.php" class="cyber-nav-btn"><i class="bi bi-bar-chart"></i> RECENSEMENT</a>
            <a href="recherche.php" class="cyber-nav-btn"><i class="bi bi-search"></i> RECHERCHE</a>
            <a href="tri.php" class="cyber-nav-btn"><i class="bi bi-sort-alpha-down"></i> TRI</a>
            <a href="index.php" class="cyber-nav-btn"><i class="bi bi-person"></i> ENREGISTREMENT</a>
            <a href="citoyens.php" class="cyber-nav-btn"><i class="bi bi-person"></i> CITOYENS</a>
            <a href="etrangers.php" class="cyber-nav-btn"><i class="bi bi-person"></i> ETRANGER</a>
        </nav>

                <div class="cyber-section">
            <h2><i class="bi bi-house-door"></i> BIENVENUE</h2>
            <p> Statistiques générales sur les citoyens </p>
            <div class="dashboard-quick-stats">
                <div class="stat-card">
                    <h3>TOTAL CITOYENS</h3>
                    <?php
                    $totalCitizens = $conn->query("SELECT COUNT(*) as total FROM identity")->fetch_assoc()['total'];
                    ?>
                    <p><?= $totalCitizens ?></p>
                </div>
                <div class="stat-card">
                    <h3>ADULTES</h3>
                    <?php
                    $totalAdults = $conn->query("SELECT COUNT(*) as total FROM identity WHERE citizen_type = 'adulte'")->fetch_assoc()['total'];
                    ?>
                    <p><?= $totalAdults ?></p>
                </div>
                <div class="stat-card">
                    <h3>ENFANTS</h3>
                    <?php
                    $totalChildren = $conn->query("SELECT COUNT(*) as total FROM identity WHERE citizen_type = 'enfant'")->fetch_assoc()['total'];
                    ?>
                    <p><?= $totalChildren ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
    <script>
         document.addEventListener('DOMContentLoaded', () => {
            startLoader();
        });
        
         
        function startLoader() {
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('mainContent');
            const loadingText = document.getElementById('loadingText');
            
            const loadingSteps = [
                'INITIALISATION DU SYSTÈME...',
                'CONNEXION SÉCURISÉE...',
                'CHARGEMENT DES MODULES...',
                'VÉRIFICATION DES ACCÈS...',
                'ACTIVATION DE L\'INTERFACE...'
            ];

            let currentStep = 0;
            
            const stepInterval = setInterval(() => {
                if (currentStep < loadingSteps.length) {
                    loadingText.textContent = loadingSteps[currentStep];
                    currentStep++;
                } else {
                    clearInterval(stepInterval);
                }
            }, 300);

            
            setTimeout(() => {
                loader.classList.add('hidden');
                mainContent.classList.add('loaded');
                
                
                setTimeout(() => {
                    loader.remove();
                }, 800);
            }, 2000);
        }
    </script>
</body>
</html>
<style>
    body {
    background: #0a0a1a url("fond1.png") no-repeat center/cover;
    color: #00f3ff;
     font-family: 'Rajdhani', sans-serif;
    margin: 0;
    padding: 0;
    overflow: hidden;
}
.hidden{
    opacity: 0;
}
/* Progress Bar Loader */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0a1a 0%, #1a0a2e 50%, #16213e 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.8s ease-out;
        }

        .loader-container.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-logo {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: #00f3ff;
            text-shadow: 0 0 30px #00f3ff, 0 0 60px #00f3ff;
            margin-bottom: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .progress-container {
            width: 400px;
            height: 8px;
            background: rgba(0, 243, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(0, 243, 255, 0.3);
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #00f3ff, #7df9ff, #00f3ff);
            background-size: 200% 100%;
            animation: progressMove 1.5s ease-in-out, shimmer 2s ease-in-out infinite;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.6);
            width: 0%;
        }

        @keyframes progressMove {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .loading-text {
            margin-top: 1rem;
            font-family: 'Orbitron', monospace;
            color: #7df9ff;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

</style>