<?php
require 'config.php';

// Statistiques globales
$totalCitizensStmt = $conn->query("SELECT COUNT(*) as total FROM identity");
$totalCitizens = $totalCitizensStmt->fetch_assoc()['total'] ?: 1;
$totalAdults = $conn->query("SELECT COUNT(*) as total FROM identity WHERE citizen_type = 'adulte'")->fetch_assoc()['total'];
$totalChildren = $conn->query("SELECT COUNT(*) as total FROM identity WHERE citizen_type = 'enfant'")->fetch_assoc()['total'];
$totalFemales = $conn->query("SELECT COUNT(*) as total FROM identity WHERE sexe = 'F'")->fetch_assoc()['total'];
$totalMales = $conn->query("SELECT COUNT(*) as total FROM identity WHERE sexe = 'M'")->fetch_assoc()['total'];
$totalEmployed = $conn->query("SELECT COUNT(*) as total FROM identity WHERE profession IS NOT NULL AND profession != ''")->fetch_assoc()['total'];
$totalUnemployed = $conn->query("SELECT COUNT(*) as total FROM identity WHERE profession IS NULL OR profession = ''")->fetch_assoc()['total'];
$totalSingle = $conn->query("SELECT COUNT(*) as total FROM identity WHERE marital_status = 'single'")->fetch_assoc()['total'];
$totalMarried = $conn->query("SELECT COUNT(*) as total FROM identity WHERE marital_status = 'married'")->fetch_assoc()['total'];
$totalWidowed = $conn->query("SELECT COUNT(*) as total FROM identity WHERE marital_status = 'widowed'")->fetch_assoc()['total'];
$totalDivorced = $conn->query("SELECT COUNT(*) as total FROM identity WHERE marital_status = 'divorced'")->fetch_assoc()['total'];
$totalGraduated = $conn->query("SELECT COUNT(*) as total FROM identity WHERE diploma IS NOT NULL AND diploma != ''")->fetch_assoc()['total'];
$totalSecondary = $conn->query("SELECT COUNT(*) as total FROM identity WHERE education_level = 'primary'")->fetch_assoc()['total'];
$totalUniversity = $conn->query("SELECT COUNT(*) as total FROM identity WHERE education_level = 'bachelor'")->fetch_assoc()['total'];

// Statistiques par région (exemple : province)
$citizensByProvince = [];
$result = $conn->query("SELECT region, COUNT(*) as total FROM identity WHERE region IS NOT NULL GROUP BY region");
$provinceLabels = [];
$provinceData = [];
while ($row = $result->fetch_assoc()) {
    $provinceLabels[] = $row['region'] ?: 'Non spécifié';
    $provinceData[] = (int)$row['total'];
}

// Statistiques par ville
$citizensByCity = [];
$result = $conn->query("SELECT city, COUNT(*) as total FROM identity WHERE city IS NOT NULL GROUP BY city");
$cityLabels = [];
$cityData = [];
while ($row = $result->fetch_assoc()) {
    $cityLabels[] = $row['city'] ?: 'Non spécifié';
    $cityData[] = (int)$row['total'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensement - Application Nationale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="cyber-container">
                <header class="cyber-header">
            <h1><i class="bi bi-person-badge"></i> RENSEIGNEMENT NUMERIQUE NATIONAL</h1>
            <p class="cyber-subtitle">SYSTÈME DE GESTION CENTRALE DES IDENTITÉS</p>
            <div class="cyber-scanline"></div>
        </header>

                <nav class="cyber-nav">
            <a href="main-app.php" class="cyber-nav-btn"><i class="bi bi-house-door"></i> TABLEAU DE BORD</a>
            <a href="recensement.php" class="cyber-nav-btn active"><i class="bi bi-bar-chart"></i> RECENSEMENT</a>
            <a href="recherche.php" class="cyber-nav-btn"><i class="bi bi-search"></i> RECHERCHE</a>
            <a href="tri.php" class="cyber-nav-btn"><i class="bi bi-sort-alpha-down"></i> TRI</a>
        </nav>

                <div class="cyber-section">
            <h2><i class="bi bi-bar-chart"></i> RECENSEMENT DES CITOYENS</h2>

                        <div class="stat-grid">
                <div class="stat-card">
                    <h3>TOTAL CITOYENS</h3>
                    <p><span class="counter" data-target="<?= $totalCitizens ?>">0</span></p>
                </div>
                <div class="stat-card">
                    <h3>ADULTES</h3>
                    <p><span class="counter" data-target="<?= $totalAdults ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalAdults / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>ENFANTS</h3>
                    <p><span class="counter" data-target="<?= $totalChildren ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalChildren / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>FEMMES</h3>
                    <p><span class="counter" data-target="<?= $totalFemales ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalFemales / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>HOMMES</h3>
                    <p><span class="counter" data-target="<?= $totalMales ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalMales / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>EMPLOYÉS</h3>
                    <p><span class="counter" data-target="<?= $totalEmployed ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalEmployed / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>SANS EMPLOI</h3>
                    <p><span class="counter" data-target="<?= $totalUnemployed ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalUnemployed / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>CÉLIBATAIRES</h3>
                    <p><span class="counter" data-target="<?= $totalSingle ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalSingle / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>MARIÉ(E)S</h3>
                    <p><span class="counter" data-target="<?= $totalMarried ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalMarried / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>VEUF/VEUVE</h3>
                    <p><span class="counter" data-target="<?= $totalWidowed ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalWidowed / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>DIVORCÉ(E)S</h3>
                    <p><span class="counter" data-target="<?= $totalDivorced ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalDivorced / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
                <div class="stat-card">
                    <h3>DIPLÔMÉS</h3>
                    <p><span class="counter" data-target="<?= $totalGraduated ?>">0</span> (<span class="counter-percentage" data-target="<?= round(($totalGraduated / $totalCitizens) * 100, 2) ?>">0</span>%)</p>
                </div>
            </div>

                        <div class="chart-grid">
                                <div class="preliminaire">
                <div class="chart-card">
                    <h3>Répartition par Sexe</h3>
                    <div class="chart-container">
                        <canvas id="sexChart"></canvas>
                    </div>
                </div>

                                <div class="chart-card">
                    <h3>Répartition par Statut Matrimonial</h3>
                    <div class="chart-container">
                        <canvas id="maritalStatusChart"></canvas>
                    </div>
                </div>

                                  <div class="chart-card">
                    <h3>Répartition par Niveau d'étude</h3>
                    <div class="chart-container">
                        <canvas id="niveauetude"></canvas>
                    </div>
                </div>
                </div>
                                <div class="chart-card">
                    <h3>Citoyens par Province</h3>
                    <div class="chart-container">
                        <canvas id="provinceChart"></canvas>
                    </div>
                </div>

                                <div class="chart-card">
                    <h3>Citoyens par Ville</h3>
                    <div class="chart-container">
                        <canvas id="cityChart"></canvas>
                    </div>
                </div>

                <div>
                    <a href="carte.html"><button>CARTOGRAPHIE AVANCE</button></a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="main.js"></script>
  <script>
        // Débogage des données
        console.log('Données pour le graphique par sexe:', [<?= $totalFemales ?>, <?= $totalMales ?>]);
        console.log('Données pour le graphique par statut matrimonial:', [<?= $totalSingle ?>, <?= $totalMarried ?>, <?= $totalWidowed ?>, <?= $totalDivorced ?>]);
        console.log('Données pour le graphique par diplome secondaire:', [<?= $totalSecondary ?>, <?= $totalUniversity ?>]);
        console.log('Labels provinces:', <?= json_encode($provinceLabels) ?>);
        console.log('Données provinces:', <?= json_encode($provinceData) ?>);
        console.log('Labels villes:', <?= json_encode($cityLabels) ?>);
        console.log('Données villes:', <?= json_encode($cityData) ?>);

        // Configuration des couleurs modernes
        const modernColors = {
            primary: '#6366f1',
            secondary: '#8b5cf6',
            accent: '#06b6d4',
            success: '#10b981',
            warning: '#f59e0b',
            error: '#ef4444',
            gradients: {
                purple: ['#667eea', '#764ba2'],
                blue: ['#4facfe', '#00f2fe'],
                pink: ['#f093fb', '#f5576c'],
                orange: ['#ffecd2', '#fcb69f'],
                green: ['#a8edea', '#fed6e3'],
                red: ['#ff9a9e', '#fecfef']
            }
        };

        // Effet de compteur progressif
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.counter');
            const percentageCounters = document.querySelectorAll('.counter-percentage');
            const speed = 200;

            counters.forEach(counter => {
                const updateCounter = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCounter, 10);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });

            percentageCounters.forEach(counter => {
                const updateCounter = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = target / speed;

                    if (count < target) {
                        counter.innerText = (count + increment).toFixed(2);
                        setTimeout(updateCounter, 10);
                    } else {
                        counter.innerText = target.toFixed(2);
                    }
                };
                updateCounter();
            });
        });

        // Fonction pour créer des gradients
        function createGradient(ctx, colors, direction = 'vertical') {
            const gradient = direction === 'vertical' 
                ? ctx.createLinearGradient(0, 0, 0, 400)
                : ctx.createLinearGradient(0, 0, 400, 0);
            
            gradient.addColorStop(0, colors[0]);
            gradient.addColorStop(1, colors[1]);
            return gradient;
        }

        // Configuration commune pour tous les graphiques
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutCubic'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        color: '#f8fafc',
                        font: {
                            family: "'Inter', 'Segoe UI', sans-serif",
                            size: 13,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#f8fafc',
                    bodyColor: '#e2e8f0',
                    borderColor: '#334155',
                    borderWidth: 1,
                    cornerRadius: 12,
                    padding: 12,
                    displayColors: true,
                    titleFont: {
                        family: "'Inter', 'Segoe UI', sans-serif",
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        family: "'Inter', 'Segoe UI', sans-serif",
                        size: 13,
                        weight: '400'
                    }
                }
            }
        };

        // Graphique : Répartition par sexe (Design moderne avec gradient)
        try {
            const sexCtx = document.getElementById('sexChart').getContext('2d');
            const sexChart = new Chart(sexCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Femmes', 'Hommes'],
                    datasets: [{
                        data: [<?= $totalFemales ?>, <?= $totalMales ?>],
                        backgroundColor: [
                            createGradient(sexCtx, modernColors.gradients.pink),
                            createGradient(sexCtx, modernColors.gradients.blue)
                        ],
                        borderWidth: 0,
                        hoverOffset: 15,
                        spacing: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '75%',
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: 'Répartition par Sexe',
                            color: '#f8fafc',
                            font: {
                                family: "'Inter', 'Segoe UI', sans-serif",
                                size: 18,
                                weight: '700'
                            },
                            padding: 25
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Erreur lors de la création du graphique par sexe:', error);
        }

        // Graphique : Répartition par statut matrimonial (Design élégant)
        try {
            const maritalCtx = document.getElementById('maritalStatusChart').getContext('2d');
            const maritalStatusChart = new Chart(maritalCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Célibataire', 'Marié(e)', 'Veuf/Veuve', 'Divorcé(e)'],
                    datasets: [{
                        data: [<?= $totalSingle ?>, <?= $totalMarried ?>, <?= $totalWidowed ?>, <?= $totalDivorced ?>],
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(6, 182, 212, 0.8)',
                            'rgba(16, 185, 129, 0.8)'
                        ],
                        borderColor: [
                            '#6366f1',
                            '#8b5cf6',
                            '#06b6d4',
                            '#10b981'
                        ],
                        borderWidth: 2,
                        hoverBackgroundColor: [
                            'rgba(99, 102, 241, 0.9)',
                            'rgba(139, 92, 246, 0.9)',
                            'rgba(6, 182, 212, 0.9)',
                            'rgba(16, 185, 129, 0.9)'
                        ]
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        r: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                lineWidth: 1
                            },
                            angleLines: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                lineWidth: 1
                            },
                            pointLabels: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Inter', 'Segoe UI', sans-serif",
                                    size: 12,
                                    weight: '500'
                                }
                            },
                            ticks: {
                                color: '#94a3b8',
                                backdropColor: 'transparent'
                            }
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: 'Répartition par Statut Matrimonial',
                            color: '#f8fafc',
                            font: {
                                family: "'Inter', 'Segoe UI', sans-serif",
                                size: 18,
                                weight: '700'
                            },
                            padding: 25
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Erreur lors de la création du graphique par statut matrimonial:', error);
        }

        // Niveau d'études (Design minimaliste moderne)
        try {
            const eduCtx = document.getElementById('niveauetude').getContext('2d');
            const educationChart = new Chart(eduCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Diplômé', 'Licencié'],
                    datasets: [{
                        data: [<?= $totalUniversity ?>, <?= $totalSecondary ?>],
                        backgroundColor: [
                            createGradient(eduCtx, modernColors.gradients.green),
                            createGradient(eduCtx, modernColors.gradients.orange)
                        ],
                        borderWidth: 0,
                        hoverOffset: 10,
                        spacing: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '70%',
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: "Répartition par Niveau d'Étude",
                            color: '#f8fafc',
                            font: {
                                family: "'Inter', 'Segoe UI', sans-serif",
                                size: 18,
                                weight: '700'
                            },
                            padding: 25
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Erreur lors de la création du graphique par niveau d\'étude:', error);
        }

        // Graphique : Citoyens par province (Design moderne avec effets)
        try {
            const provinceCtx = document.getElementById('provinceChart').getContext('2d');
            const provinceChart = new Chart(provinceCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($provinceLabels) ?>,
                    datasets: [{
                        label: 'Nombre de Citoyens',
                        data: <?= json_encode($provinceData) ?>,
                        backgroundColor: createGradient(provinceCtx, modernColors.gradients.purple),
                        borderColor: modernColors.primary,
                        borderWidth: 2,
                        borderRadius: {
                            topLeft: 8,
                            topRight: 8,
                            bottomLeft: 0,
                            bottomRight: 0
                        },
                        hoverBackgroundColor: createGradient(provinceCtx, ['#7c3aed', '#a855f7']),
                        hoverBorderColor: '#8b5cf6',
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                lineWidth: 1,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Inter', 'Segoe UI', sans-serif",
                                    size: 12,
                                    weight: '500'
                                },
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Inter', 'Segoe UI', sans-serif",
                                    size: 12,
                                    weight: '500'
                                },
                                maxRotation: 45,
                                padding: 10
                            }
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribution des Citoyens par Province',
                            color: '#f8fafc',
                            font: {
                                family: "'Inter', 'Segoe UI', sans-serif",
                                size: 18,
                                weight: '700'
                            },
                            padding: 25
                        }
                    },
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }
            });
        } catch (error) {
            console.error('Erreur lors de la création du graphique par province:', error);
        }

        // Graphique : Citoyens par ville (Design horizontal moderne)
        try {
            const cityCtx = document.getElementById('cityChart').getContext('2d');
            const cityChart = new Chart(cityCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($cityLabels) ?>,
                    datasets: [{
                        label: 'Nombre de Citoyens',
                        data: <?= json_encode($cityData) ?>,
                        backgroundColor: createGradient(cityCtx, modernColors.gradients.red),
                        borderColor: modernColors.error,
                        borderWidth: 2,
                        borderRadius: {
                            topLeft: 8,
                            topRight: 8,
                            bottomLeft: 0,
                            bottomRight: 0
                        },
                        hoverBackgroundColor: createGradient(cityCtx, ['#f43f5e', '#ec4899']),
                        hoverBorderColor: '#f43f5e',
                        hoverBorderWidth: 3
                    }]
                },
                options: {
                    ...commonOptions,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                lineWidth: 1,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Inter', 'Segoe UI', sans-serif",
                                    size: 12,
                                    weight: '500'
                                },
                                padding: 10
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#e2e8f0',
                                font: {
                                    family: "'Inter', 'Segoe UI', sans-serif",
                                    size: 12,
                                    weight: '500'
                                },
                                padding: 10
                            }
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribution des Citoyens par Ville',
                            color: '#f8fafc',
                            font: {
                                family: "'Inter', 'Segoe UI', sans-serif",
                                size: 18,
                                weight: '700'
                            },
                            padding: 25
                        }
                    },
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }
            });
        } catch (error) {
            console.error('Erreur lors de la création du graphique par ville:', error);
        }
</script>
</body>
</html>
<style>
body{
     font-family: 'Rajdhani', sans-serif;
}
/* Grille pour les statistiques */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

/* Grille pour les graphiques */
.chart-grid {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

/* Conteneur des graphiques */
.chart-card {
    background: rgba(0, 243, 255, 0.1);
    border: 1px solid #00f3ff;
    border-radius: 5px;
    padding: 15px;
    width: 100%;
 
    margin: 0 auto; /* Centrer les graphiques */
}

/* Conteneur pour le canvas */
.chart-container {
    position: relative;
    width: 100%;
    max-width: 100%;
}


/* Laisser Chart.js gérer la hauteur via aspectRatio */
.chart-container canvas {
    width: 100% !important;
}

/* Styles améliorés pour les graphiques */
.chart-grid {
    display: block;
   
    margin-top: 30px;
}


.chart-card {
    background: rgba(0, 243, 255, 0.1);
    border: none;
    border-radius: 15px;
    padding: 20px;
    width: 100%;
    margin-top: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.chart-card h3 {
    color: #00ffea;
    margin-bottom: 15px;
    font-size: 1.2rem;
    text-align: center;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 300px;
    margin: 0 auto;
}

.preliminaire{
    display: flex;
    justify-content: space-between;
    gap: 30;
}
.preliminaire .chart-card{
    max-width: 500px;
     font-family: 'Rajdhani', sans-serif;
}
/* Styles pour les cartes de statistiques */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.stat-card {
    background: rgba(0, 243, 255, 0.1);
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-card h3 {
    font-size: 0.9rem;
    color: #00ffea;
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 1.2rem;
    font-weight: bold;
    color: #00ffea;
    margin: 0;
}

.stat-card .counter {
    color: #FF8C00;
}

.stat-card .counter-percentage {
    color: #DC143C;
    font-size: 0.9rem;
}
</style>