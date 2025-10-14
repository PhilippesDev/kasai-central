<?php
require 'config_cit.php';


$defaultCitizenStmt = $conn->query("SELECT * FROM identity ORDER BY RAND() LIMIT 1");
$defaultCitizen = $defaultCitizenStmt->fetch_assoc();


$allCitizensStmt = $conn->query("SELECT * FROM identity");
$allCitizens = [];
while ($row = $allCitizensStmt->fetch_assoc()) {
    $allCitizens[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Application Nationale</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
    body{
     font-family: 'Rajdhani', sans-serif;
    }
    .cyber-nav-btn{
     font-family: 'Rajdhani', sans-serif;
}
    .scroll-face {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin: 0 5px;
        border: 2px solid #0ff;
        border-radius: 4px;
        animation: glow 1s infinite alternate;
    }
    @keyframes glow {
        from { box-shadow: 0 0 5px #0ff; }
        to { box-shadow: 0 0 20px #0ff, 0 0 30px #0ff; }
    }
    .face-scroll {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        flex-wrap: wrap;
    }
    .scan-text {
        text-align: center;
        font-family: 'Rajdhani', monospace;
        color: #0ff;
        font-size: 1.2em;
    }
    .citizen-card {
        background: rgba(0, 0, 0, 0.7);
        border: 1px solid #0ff;
        padding: 20px;
        margin-top: 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .citizen-card:hover {
        transform: scale(1.05);
        border-color: #ff00ff;
    }
    .citizen-photo {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 2px solid #0ff;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    /* Style de la modale */
    .profile-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0a0a1a 0%, #1a1a3a 100%);
        z-index: 1000;
        overflow: hidden; 
        font-family: 'Rajdhani', monospace;
        color: #0ff;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .profile-modal.show {
        display: flex;
    }
    .profile-header {
        text-align: center;
        border-bottom: 2px solid #0ff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        width: 80%;
        max-width: 1000px;
        background: linear-gradient(90deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 255, 0.1));
        
    }
    
    .profile-header h2 {
        color: #00f3ff;
        
        font-size: 2.5em;
        letter-spacing: 2px;
    }
    .profile-content {
        display: flex;
        width: 80%;
        max-width: 1000px;
        height: 80%;
        background: rgba(0, 0, 0, 0.8);
        border: 2px solid #0ff;
        border-radius: 10px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .profile-photo-container {
        
        flex: 0 0 30%;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 255, 255, 0.05);
        border-right: 1px dashed rgb(255, 221, 0);
    }
    .profile-photo {
        width: 400px;
        height: 400px;
        object-fit: cover;
        border: 3px solid #0ff;
        border-radius: 5px;
        
    }
    
    .profile-details {
        flex: 0 0 70%;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .profile-info {
        background: rgba(0, 255, 255, 0.05);
        border: 1px solid #0ff;
        padding: 10px;
        border-radius: 5px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 80%;
    }
    .profile-info h4 {
        color:rgb(255, 221, 0);
        border-bottom: 1px dashed #0ff;
        padding-bottom: 5px;
        margin-bottom: 10px;
        text-shadow: 0 0 5px #ff00ff;
    }
    .profile-info p {
        margin: 3px 0;
        color: #0ff;
        font-size: 0.9em;
        line-height: 1.4;
    }
    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        color: #ff00ff;
        font-size: 2em;
        cursor: pointer;
        animation: flicker 1.5s infinite;
        text-shadow: 0 0 10px #ff00ff;
    }
    @keyframes flicker {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    button{
        background: rgba(0, 243, 255, 0.2);
        border: 1px solid #00f3ff;
        color: #00f3ff;
        padding: 10px 20px;
        margin-top: 4px;
        text-decoration: none;
        border-radius: 5px;
        font-family: 'Rajdhani', monospace;
        transition: all 0.3s;
    }

    /* Style de la modale d'édition */
.edit-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 2000;
    overflow-y: auto;
    font-family: 'Rajdhani', monospace;
    color: #0ff;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}
.edit-modal.show {
    display: flex;
}
.edit-form {
    width: 80%;
    max-width: 1000px;
    background: rgba(0, 0, 0, 0.8);
    border: 2px solid #0ff;
    border-radius: 10px;
    padding: 20px;
}
.edit-form h2 {
    color: #00f3ff;
    text-align: center;
    margin-bottom: 20px;
}
.edit-form label {
    color: #ff00ff;
    margin-top: 10px;
    display: block;
}
.edit-form input, .edit-form select {
    width: 100%;
    padding: 5px;
    margin-top: 5px;
    background: rgba(0, 255, 255, 0.1);
    border: 1px solid #0ff;
    color: #0ff;
    border-radius: 5px;
}
.edit-form button {
    margin-top: 20px;
    width: 100%;
}

.search-result {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

    ::-webkit-scrollbar {
    width: 5px;
    }

    
    ::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey;
    border-radius: 10px;
    }

    
    ::-webkit-scrollbar-thumb {
    background: rgb(255, 221, 0);
    border-radius: 10px;
}
</style>
</head>
<body>
    <div class="cyber-container">
        <!-- En-tête futuriste -->
        <header class="cyber-header">
            <h1><i class="bi bi-person-badge"></i> RENSEIGNEMENT NUMERIQUE NATIONAL</h1>
            <p class="cyber-subtitle">SYSTÈME DE GESTION CENTRALE DES IDENTITÉS</p>
            <div class="cyber-scanline"></div>
        </header>

        <!-- Barre de navigation -->
        <nav class="cyber-nav">
            <a href="main-app.php" class="cyber-nav-btn"><i class="bi bi-house-door"></i> TABLEAU DE BORD</a>
            <a href="recensement.php" class="cyber-nav-btn"><i class="bi bi-bar-chart"></i> RECENSEMENT</a>
            <a href="recherche.php" class="cyber-nav-btn active"><i class="bi bi-search"></i> RECHERCHE</a>
            <a href="search_advanced.php" class="cyber-nav-btn"><i class="bi bi-sort-alpha-down"></i> RECHERCHE AVANCE</a>
        </nav>

        <!-- Section Recherche -->
        <div class="cyber-section">
            <h2><i class="bi bi-search"></i> RECHERCHE DE CITOYEN</h2>
            <form id="searchForm" class="cyber-form">
                <div class="cyber-input-group">
                    <label><i class="bi bi-search"></i> RECHERCHER PAR NOM, POST-NOM OU PRÉNOM</label>
                    <input type="text" name="searchTerm" id="searchTerm" required>
                </div>
                <button type="submit" class="cyber-btn"><i class="bi bi-search"></i> LANCER LA RECHERCHE</button>
            </form>

            <!-- Résultat par défaut -->
            <div class="search-result" id="searchResult">
                <div class="citizen-card" data-citizen-id="<?= htmlspecialchars($defaultCitizen['id']) ?>">
                    <img src="<?= htmlspecialchars($defaultCitizen['photo_path']) ?>" alt="Photo du citoyen" class="citizen-photo">
                    <h3><?= htmlspecialchars($defaultCitizen['prenom'] . ' ' . $defaultCitizen['nom']) ?></h3>
                    <p>Numéro national : <?= htmlspecialchars($defaultCitizen['national_id']) ?></p>
                    <p>Date de naissance : <?= htmlspecialchars($defaultCitizen['dob']) ?></p>
                    <p>Sexe : <?= htmlspecialchars($defaultCitizen['sexe']) ?></p>
                    <p>Adresse : <?= htmlspecialchars($defaultCitizen['avenue'] . ', ' . $defaultCitizen['city'] . ', ' . $defaultCitizen['region']) ?></p>
                    <p>Profession : <?= htmlspecialchars($defaultCitizen['profession'] ?? 'Non spécifié') ?></p>
                </div>
            </div>

            <!-- Conteneur pour l'effet de défilement -->
            <div class="search-animation" id="searchAnimation" style="display: none;">
                <div class="face-scroll" id="faceScroll"></div>
                <p class="scan-text">SCAN EN COURS... <span id="scanCount">0</span> CITOYENS ANALYSÉS</p>
            </div>
        </div>
    </div>

    <!-- Modale pour le profil détaillé -->
    <!-- Modale pour le profil détaillé -->
<div class="profile-modal" id="profileModal">
    <button class="close-btn" id="closeModalBtn">✖</button>
    <div class="profile-header">
        <h2>DOSSIER CITOYEN</h2>
    </div>
    <div class="profile-content" id="profileContent">
        <!-- Le contenu sera rempli dynamiquement -->
    </div>
</div>
<!-- Modale pour l'édition des informations -->
<div class="edit-modal" id="editModal">
    <div class="edit-form">
        <h2>MODIFIER LES INFORMATIONS</h2>
        <form id="editForm">
            <input type="hidden" name="citizen_id" id="editCitizenId">
            <label>Nom</label>
            <input type="text" name="nom" id="editNom" required>
            <label>Prénom</label>
            <input type="text" name="prenom" id="editPrenom" required>
            <label>Post-nom</label>
            <input type="text" name="postnom" id="editPostnom">
            <label>Date de naissance</label>
            <input type="date" name="dob" id="editDob" required>
            <label>Lieu de naissance</label>
            <input type="text" name="birthPlace" id="editBirthPlace" required>
            <label>Sexe</label>
            <select name="sexe" id="editSexe" required>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
                <option value="X">Non-binaire</option>
            </select>
            <label>Type de citoyen</label>
            <select name="citizen_type" id="editCitizenType" required>
                <option value="adulte">Adulte</option>
                <option value="enfant">Enfant</option>
            </select>
            <label>Groupe sanguin</label>
            <select name="blood_type" id="editBloodType">
                <option value="">Non spécifié</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
            <label>Nom de la mère</label>
            <input type="text" name="mother_name" id="editMotherName">
            <label>Nom du père</label>
            <input type="text" name="father_name" id="editFatherName">
            <label>Village/Territoire d'origine</label>
            <input type="text" name="origin_village" id="editOriginVillage">
            <label>Quartier</label>
            <input type="text" name="quarter" id="editQuarter">
            <label>Numéro</label>
            <input type="text" name="street_number" id="editStreetNumber">
            <label>Avenue</label>
            <input type="text" name="avenue" id="editAvenue">
            <label>Commune</label>
            <input type="text" name="commune" id="editCommune">
            <label>Ville</label>
            <input type="text" name="city" id="editCity">
            <label>Province</label>
            <select name="region" id="editRegion" required>
                <option value="">Sélectionner une province</option>
                <option value="Bas-Uele">Bas-Uele</option>
                <option value="Equateur">Equateur</option>
                <option value="Haut-Katanga">Haut-Katanga</option>
                <option value="Haut-Lomami">Haut-Lomami</option>
                <option value="Haut-Uele">Haut-Uele</option>
                <option value="Ituri">Ituri</option>
                <option value="Kasai">Kasai</option>
                <option value="Kasai-Central">Kasai-Central</option>
                <option value="Kasai-Oriental">Kasai-Oriental</option>
                <option value="Kinshasa">Kinshasa</option>
                <option value="Kongo-Central">Kongo-Central</option>
                <option value="Kwango">Kwango</option>
                <option value="Kwilu">Kwilu</option>
                <option value="Lomami">Lomami</option>
                <option value="Lualaba">Lualaba</option>
                <option value="Mai-Ndombe">Mai-Ndombe</option>
                <option value="Maniema">Maniema</option>
                <option value="Mongala">Mongala</option>
                <option value="Nord-Kivu">Nord-Kivu</option>
                <option value="Nord-Ubangi">Nord-Ubangi</option>
                <option value="Sankuru">Sankuru</option>
                <option value="Sud-Kivu">Sud-Kivu</option>
                <option value="Sud-Ubangi">Sud-Ubangi</option>
                <option value="Tanganyika">Tanganyika</option>
                <option value="Tshopo">Tshopo</option>
                <option value="Tshuapa">Tshuapa</option>
            </select>
            <label>Profession</label>
            <input type="text" name="profession" id="editProfession">
            <label>Employeur</label>
            <input type="text" name="employer" id="editEmployer">
            <label>Salaire (USD)</label>
            <input type="number" name="salary" id="editSalary">
            <label>Numéro fiscal</label>
            <input type="text" name="tax_number" id="editTaxNumber">
            <label>Numéro de sécurité sociale</label>
            <input type="text" name="social_security_number" id="editSocialSecurityNumber">
            <label>Niveau d'étude</label>
            <select name="education_level" id="editEducationLevel">
                <option value="">Non spécifié</option>
                <option value="primary">Primaire</option>
                <option value="secondary">Secondaire</option>
                <option value="bachelor">Universitaire (Licence)</option>
                <option value="master">Universitaire (Master)</option>
                <option value="phd">Universitaire (Doctorat)</option>
                <option value="other">Autre</option>
            </select>
            <label>Dernier établissement</label>
            <input type="text" name="last_institution" id="editLastInstitution">
            <label>Diplôme obtenu</label>
            <input type="text" name="diploma" id="editDiploma">
            <label>Année d'obtention</label>
            <input type="number" name="graduation_year" id="editGraduationYear" min="1900" max="2099">
            <label>Statut matrimonial</label>
            <select name="marital_status" id="editMaritalStatus">
                <option value="single">Célibataire</option>
                <option value="married">Marié(e)</option>
                <option value="divorced">Divorcé(e)</option>
                <option value="widowed">Veuf/Veuve</option>
            </select>
            <label>Nom du conjoint</label>
            <input type="text" name="spouse_name" id="editSpouseName">
            <label>Numéro national du conjoint</label>
            <input type="text" name="spouse_national_id" id="editSpouseNationalId">
            <label>Date de naissance du conjoint</label>
            <input type="date" name="spouse_dob" id="editSpouseDob">
            <label>Associations</label>
            <input type="text" name="associations" id="editAssociations">
            <label>Parti politique</label>
            <input type="text" name="political_party" id="editPoliticalParty">
            <label>Assurance maladie</label>
            <input type="text" name="health_insurance" id="editHealthInsurance">
            <label>Téléphone principal</label>
            <input type="tel" name="phone1" id="editPhone1" required>
            <label>Téléphone secondaire</label>
            <input type="tel" name="phone2" id="editPhone2">
            <label>Email</label>
            <input type="email" name="email" id="editEmail">
            <label>Contact d'urgence</label>
            <input type="text" name="emergency_contact" id="editEmergencyContact">
            <label>Téléphone d'urgence</label>
            <input type="tel" name="emergency_phone" id="editEmergencyPhone">
            <button type="submit">Enregistrer les modifications</button>
            <button type="button" onclick="closeEditModal()">Annuler</button>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const allCitizens = <?= json_encode($allCitizens) ?>;
        const form = document.getElementById('searchForm');
        const searchResult = document.getElementById('searchResult');
        const searchAnimation = document.getElementById('searchAnimation');
        const faceScroll = document.getElementById('faceScroll');
        const scanCount = document.getElementById('scanCount');
        const profileModal = document.getElementById('profileModal');
        const profileContent = document.getElementById('profileContent');
        const closeModalBtn = document.getElementById('closeModalBtn');

        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            
            searchResult.style.display = 'none';
            searchAnimation.style.display = 'block';
            
            const searchTerm = document.getElementById('searchTerm').value;
            
            
            let counter = 0;
            const duration = 3000; 
            const interval = 100; 
            const steps = duration / interval;
            const totalCitizens = allCitizens.length;
            
            const scrollInterval = setInterval(() => {
                if (counter >= steps) {
                    clearInterval(scrollInterval);
                    
                    performSearch(searchTerm);
                    return;
                }
                
                faceScroll.innerHTML = '';
                
                for (let i = 0; i < 5; i++) {
                    const randomIndex = Math.floor(Math.random() * totalCitizens);
                    const citizen = allCitizens[randomIndex];
                    const img = document.createElement('img');
                    img.src = citizen.photo_path;
                    img.alt = 'Photo du citoyen';
                    img.classList.add('scroll-face');
                    faceScroll.appendChild(img);
                }
                
                counter++;
                scanCount.textContent = Math.min(counter * 5, totalCitizens);
            }, interval);
        });

        async function performSearch(searchTerm) {
            try {
                const response = await fetch('search_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `searchTerm=${encodeURIComponent(searchTerm)}`
                });
                
                const result = await response.json();
                
                
                searchAnimation.style.display = 'none';
                searchResult.style.display = 'block';
                
                if (result.success) {
                    
                    searchResult.innerHTML = '';
                    
                    
                    result.data.forEach(citizen => {
                        
                        const citizenCard = document.createElement('div');
                        citizenCard.classList.add('citizen-card');
                        citizenCard.setAttribute('data-citizen-id', citizen.id);
                        citizenCard.innerHTML = `
                            <img src="${citizen.photo_path}" alt="Photo du citoyen" class="citizen-photo">
                            <h3>${citizen.prenom} ${citizen.nom}</h3>
                            <p>Numéro national : ${citizen.national_id}</p>
                            <p>Date de naissance : ${citizen.dob}</p>
                            <p>Sexe : ${citizen.sexe}</p>
                            <p>Adresse : ${citizen.avenue || ''}, ${citizen.city || ''}, ${citizen.region || ''}</p>
                            <p>Profession : ${citizen.profession || 'Non spécifié'}</p>
                        `;
                        searchResult.appendChild(citizenCard);
                    });
                } else {
                    searchResult.innerHTML = '<div class="alert alert-danger">Aucun citoyen trouvé</div>';
                }
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
                searchAnimation.style.display = 'none';
                searchResult.style.display = 'block';
                searchResult.innerHTML = '<div class="alert alert-danger">Erreur lors de la recherche</div>';
            }

            
            addCitizenCardClickEvent();
        }

        
        function addCitizenCardClickEvent() {
            const citizenCards = document.querySelectorAll('.citizen-card');
            citizenCards.forEach(card => {
                card.addEventListener('click', async () => {
                    const citizenId = card.getAttribute('data-citizen-id');
                    await loadCitizenProfile(citizenId);
                });
            });
        }

        
        async function loadCitizenProfile(citizenId) {
    try {
        const response = await fetch('get_citizen_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `citizen_id=${encodeURIComponent(citizenId)}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            const citizen = result.data;
            
            profileContent.innerHTML = `
                <div class="profile-photo-container">
                    <img src="${citizen.photo_path}" alt="Photo du citoyen" class="profile-photo">
                </div>
                <div class="profile-details">
                    <div class="profile-info">
                        <h4>INFORMATIONS GÉNÉRALES</h4>
                        <p><strong>Nom complet :</strong> ${citizen.prenom} ${citizen.nom} ${citizen.postnom || ''}</p>
                        <p><strong>Date de naissance :</strong> ${citizen.dob}</p>
                        <p><strong>Lieu de naissance :</strong> ${citizen.birth_place || 'Non spécifié'}</p>
                        <p><strong>Sexe :</strong> ${citizen.sexe}</p>
                        <p><strong>Date d'enregistrement :</strong> ${citizen.registration_date}</p>
                    </div>
                    <div class="profile-info">
                        <h4>ORIGINES</h4>
                        <p><strong>Nom de la mère :</strong> ${citizen.mother_name || 'Non spécifié'}</p>
                        <p><strong>Nom du père :</strong> ${citizen.father_name || 'Non spécifié'}</p>
                        <p><strong>Village/Territoire d'origine :</strong> ${citizen.origin_village || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>ADRESSE</h4>
                        <p><strong>Quartier :</strong> ${citizen.quarter || 'Non spécifié'}</p>
                        <p><strong>Numéro :</strong> ${citizen.street_number || 'Non spécifié'}</p>
                        <p><strong>Avenue :</strong> ${citizen.avenue || 'Non spécifié'}</p>
                        <p><strong>Commune :</strong> ${citizen.commune || 'Non spécifié'}</p>
                        <p><strong>Territoire / Ville :</strong> ${citizen.city || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>INFORMATIONS PROFESSIONNELLES</h4>
                        <p><strong>Profession :</strong> ${citizen.profession || 'Non spécifié'}</p>
                        <p><strong>Employeur :</strong> ${citizen.employer || 'Non spécifié'}</p>
                        <p><strong>Salaire (USD) :</strong> ${citizen.salary || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>FORMATION</h4>
                        <p><strong>Niveau d'étude :</strong> ${citizen.education_level || 'Non spécifié'}</p>
                        <p><strong>Dernier établissement :</strong> ${citizen.last_institution || 'Non spécifié'}</p>
                        <p><strong>Diplôme obtenu :</strong> ${citizen.diploma || 'Non spécifié'}</p>
                        <p><strong>Année d'obtention :</strong> ${citizen.graduation_year || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>INFORMATIONS FAMILIALES</h4>
                        <p><strong>Statut matrimonial :</strong> ${citizen.marital_status || 'Non spécifié'}</p>
                        <p><strong>Conjoint(e) :</strong> ${citizen.spouse_name || 'Non spécifié'}</p>
                        <p><strong>Date de naissance du conjoint :</strong> ${citizen.spouse_dob || 'Non spécifié'}</p>
                        <p><strong>Nombre d'enfants :</strong> ${citizen.children_count || 0}</p>
                        <p><strong>Enfants :</strong> ${
                            citizen.children && citizen.children.length > 0
                                ? citizen.children.map(child => 
                                    `${child.full_name} (Né(e) le ${child.dob}, Sexe: ${child.sex})`
                                  ).join('<br>')
                                : 'Aucun'
                        }</p>
                    </div>
                    <div class="profile-info">
                        <h4>ACTIVITÉS ET SANTÉ</h4>
                        <p><strong>Associations :</strong> ${citizen.associations || 'Non spécifié'}</p>
                        <p><strong>Parti politique :</strong> ${citizen.political_party || 'Non spécifié'}</p>
                        <p><strong>Assurance maladie :</strong> ${citizen.health_insurance || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>CONTACTS</h4>
                        <p><strong>Téléphone principal :</strong> ${citizen.phone1 || 'Non spécifié'}</p>
                        <p><strong>Téléphone secondaire :</strong> ${citizen.phone2 || 'Non spécifié'}</p>
                        <p><strong>Email :</strong> ${citizen.email || 'Non spécifié'}</p>
                        <p><strong>Contact d'urgence :</strong> ${citizen.emergency_contact || 'Non spécifié'}</p>
                        <p><strong>Téléphone d'urgence :</strong> ${citizen.emergency_phone || 'Non spécifié'}</p>
                    </div>
                    <div class="profile-info">
                        <h4>PROPRIÉTÉS IMMOBILIÈRES</h4>
                        <p>${
                            citizen.properties && citizen.properties.length > 0
                                ? citizen.properties.map(prop => 
                                    `Type: ${prop.property_type}, Numéro: ${prop.property_number}, Document: <a href="${prop.document_path}" target="_blank">Voir</a>`
                                  ).join('<br>')
                                : 'Aucune propriété'
                        }</p>
                    </div>
                    <div class="profile-info">
                        <h4>BIOMÉTRIE</h4>
                        <p><strong>Photo :</strong> <a href="${citizen.photo_path}" target="_blank">Voir la photo</a></p>
                        <p><strong>Enregistrement vocal :</strong> ${
                            citizen.voice_path
                                ? `<a href="${citizen.voice_path}" target="_blank">Écouter</a>`
                                : 'Non disponible'
                        }</p>
                        <p><strong>Empreinte digitale :</strong> ${
                            citizen.fingerprint_data
                                ? `<a href="${citizen.fingerprint_data}" target="_blank">Voir</a>`
                                : 'Non disponible'
                        }</p>
                    </div>
                    <div class="profile-info">
                        <h4>ACTIONS</h4>
                        <button onclick="generatePDF(${citizen.id})">Aperçu des informations (PDF)</button>
                        <button onclick="editCitizen(${citizen.id})">Modifier les informations</button>
                    </div>
                </div>
            `;
            profileModal.classList.add('show');
        } else {
            profileContent.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement du profil</div>';
            profileModal.classList.add('show');
        }
    } catch (error) {
        console.error('Erreur lors du chargement du profil:', error);
        profileContent.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement du profil</div>';
        profileModal.classList.add('show');
    }
}


        
        closeModalBtn.addEventListener('click', () => {
            profileModal.classList.remove('show');
        });

        
        profileModal.addEventListener('click', (e) => {
            if (e.target === profileModal) {
                profileModal.classList.remove('show');
            }
        });

        
        addCitizenCardClickEvent();

        async function generatePDF(citizenId) {
    try {
        const response = await fetch('get_citizen_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `citizen_id=${encodeURIComponent(citizenId)}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            const citizen = result.data;
            
            const element = document.createElement('div');
            element.style.padding = '20px';
            element.style.fontFamily = 'Arial, sans-serif';
            element.style.color = '#000';
            element.style.backgroundColor = '#fff';
            element.style.width = '100%';
           element.innerHTML = `
    <h1 style="text-align: center; color: #00f3ff;">DOSSIER CITOYEN</h1>
    <h2 style="text-align: center;">${citizen.prenom} ${citizen.nom} ${citizen.postnom || ''}</h2>
    <hr style="border: 1px solid #0ff;">
    <h3>Informations Générales</h3>
    <p><strong>Numéro national :</strong> ${citizen.national_id}</p>
    <p><strong>Numéro fiscal :</strong> ${citizen.tax_number || 'Non spécifié'}</p>
    <p><strong>Date de naissance :</strong> ${citizen.dob}</p>
    <p><strong>Lieu de naissance :</strong> ${citizen.birth_place || 'Non spécifié'}</p>
    <p><strong>Sexe :</strong> ${citizen.sexe}</p>
    <p><strong>Type :</strong> ${citizen.citizen_type}</p>
    <p><strong>Groupe sanguin :</strong> ${citizen.blood_type || 'Non spécifié'}</p>
    <p><strong>Date d'enregistrement :</strong> ${citizen.registration_date}</p>
    <h3>Origines</h3>
    <p><strong>Nom de la mère :</strong> ${citizen.mother_name || 'Non spécifié'}</p>
    <p><strong>Nom du père :</strong> ${citizen.father_name || 'Non spécifié'}</p>
    <p><strong>Village/Territoire d'origine :</strong> ${citizen.origin_village || 'Non spécifié'}</p>
    <h3>Adresse</h3>
    <p><strong>Quartier :</strong> ${citizen.quarter || 'Non spécifié'}</p>
    <p><strong>Numéro :</strong> ${citizen.street_number || 'Non spécifié'}</p>
    <p><strong>Avenue :</strong> ${citizen.avenue || 'Non spécifié'}</p>
    <p><strong>Commune :</strong> ${citizen.commune || 'Non spécifié'}</p>
    <p><strong>Ville :</strong> ${citizen.city || 'Non spécifié'}</p>
    <p><strong>Province :</strong> ${citizen.region || 'Non spécifié'}</p>
    <p><strong>Pays :</strong> ${citizen.country || 'Non spécifié'}</p>
    <p><strong>Latitude :</strong> ${citizen.latitude || 'Non spécifié'}</p>
    <p><strong>Longitude :</strong> ${citizen.longitude || 'Non spécifié'}</p>
    <h3>Informations Professionnelles</h3>
    <p><strong>Profession :</strong> ${citizen.profession || 'Non spécifié'}</p>
    <p><strong>Employeur :</strong> ${citizen.employer || 'Non spécifié'}</p>
    <p><strong>Salaire (USD) :</strong> ${citizen.salary || 'Non spécifié'}</p>
    <p><strong>Numéro fiscal :</strong> ${citizen.tax_number || 'Non spécifié'}</p>
    <p><strong>Numéro de sécurité sociale :</strong> ${citizen.social_security_number || 'Non spécifié'}</p>
    <h3>Formation</h3>
    <p><strong>Niveau d'étude :</strong> ${citizen.education_level || 'Non spécifié'}</p>
    <p><strong>Dernier établissement :</strong> ${citizen.last_institution || 'Non spécifié'}</p>
    <p><strong>Diplôme obtenu :</strong> ${citizen.diploma || 'Non spécifié'}</p>
    <p><strong>Année d'obtention :</strong> ${citizen.graduation_year || 'Non spécifié'}</p>
    <h3>Informations Familiales</h3>
    <p><strong>Statut matrimonial :</strong> ${citizen.marital_status || 'Non spécifié'}</p>
    <p><strong>Conjoint(e) :</strong> ${citizen.spouse_name || 'Non spécifié'}</p>
    <p><strong>Numéro national du conjoint :</strong> ${citizen.spouse_national_id || 'Non spécifié'}</p>
    <p><strong>Date de naissance du conjoint :</strong> ${citizen.spouse_dob || 'Non spécifié'}</p>
    <p><strong>Nombre d'enfants :</strong> ${citizen.children_count || 0}</p>
    <p><strong>Enfants :</strong> ${
        citizen.children && citizen.children.length > 0
            ? citizen.children.map(child => 
                `${child.full_name} (Né(e) le ${child.dob}, Sexe: ${child.sex})`
              ).join('<br>')
            : 'Aucun'
    }</p>
    <h3>Activités et Santé</h3>
    <p><strong>Associations :</strong> ${citizen.associations || 'Non spécifié'}</p>
    <p><strong>Parti politique :</strong> ${citizen.political_party || 'Non spécifié'}</p>
    <p><strong>Assurance maladie :</strong> ${citizen.health_insurance || 'Non spécifié'}</p>
    <h3>Contacts</h3>
    <p><strong>Téléphone principal :</strong> ${citizen.phone1 || 'Non spécifié'}</p>
    <p><strong>Téléphone secondaire :</strong> ${citizen.phone2 || 'Non spécifié'}</p>
    <p><strong>Email :</strong> ${citizen.email || 'Non spécifié'}</p>
    <p><strong>Contact d'urgence :</strong> ${citizen.emergency_contact || 'Non spécifié'}</p>
    <p><strong>Téléphone d'urgence :</strong> ${citizen.emergency_phone || 'Non spécifié'}</p>
    <h3>Propriétés Immobilières</h3>
    <p>${
        citizen.properties && citizen.properties.length > 0
            ? citizen.properties.map(prop => 
                `Type: ${prop.property_type}, Numéro: ${prop.property_number}`
              ).join('<br>')
            : 'Aucune propriété'
    }</p>
    <h3>Casier Judiciaire</h3>
    <p>${
        citizen.criminal_records && citizen.criminal_records.length > 0
            ? citizen.criminal_records.map(record => 
                `Numéro: ${record.record_number}, Type: ${record.record_type}`
              ).join('<br>')
            : 'Aucun casier judiciaire'
    }</p>
    <h3>Biométrie</h3>
    <p><strong>Photo :</strong> Incluse ci-dessus</p>
    <p><strong>Enregistrement vocal :</strong> ${citizen.voice_path ? 'Disponible' : 'Non disponible'}</p>
    <p><strong>Empreinte digitale :</strong> ${citizen.fingerprint_data ? 'Disponible' : 'Non disponible'}</p>
`;
            
            
            const opt = {
                margin: 1,
                filename: `Dossier_Citoyen_${citizen.national_id}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(element).set(opt).save();
        } else {
            alert('Erreur lors de la génération du PDF.');
        }
    } catch (error) {
        console.error('Erreur lors de la génération du PDF:', error);
        alert('Erreur lors de la génération du PDF.');
    }
}

async function generateVoterCard(citizenId) {
    try {
        const response = await fetch('get_citizen_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `citizen_id=${encodeURIComponent(citizenId)}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            const citizen = result.data;
            
            const element = document.createElement('div');
            element.style.width = '240px'; 
            element.style.height = '150px'; 
            element.style.backgroundColor = '#fff';
            element.style.border = '1px solid #000';
            element.style.padding = '10px';
            element.style.fontFamily = 'Arial, sans-serif';
            element.style.fontSize = '10px';
            element.style.color = '#000';
            element.style.display = 'flex';
            element.style.alignItems = 'center';
            element.innerHTML = `
                <div style="flex: 0 0 30%;">
                    <img src="${citizen.photo_path}" alt="Photo" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #000;">
                </div>
                <div style="flex: 0 0 70%; padding-left: 10px;">
                    <h3 style="font-size: 12px; margin: 0; color: #00f3ff;">CARTE D'ÉLECTEUR</h3>
                    <p style="margin: 2px 0;"><strong>Nom :</strong> ${citizen.prenom} ${citizen.nom}</p>
                    <p style="margin: 2px 0;"><strong>N° National :</strong> ${citizen.national_id}</p>
                    <p style="margin: 2px 0;"><strong>Date de naissance :</strong> ${citizen.dob}</p>
                    <p style="margin: 2px 0;"><strong>Province :</strong> ${citizen.region}</p>
                </div>
            `;
            
            
            const opt = {
                margin: 0,
                filename: `Carte_Electeur_${citizen.national_id}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'px', format: [240, 150], orientation: 'landscape' }
            };
            html2pdf().from(element).set(opt).save();
        } else {
            alert('Erreur lors de la génération de la carte d\'électeur.');
        }
    } catch (error) {
        console.error('Erreur lors de la génération de la carte d\'électeur:', error);
        alert('Erreur lors de la génération de la carte d\'électeur.');
    }
}

async function generateCitizenCard(citizenId) {
    try {
        const response = await fetch('get_citizen_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `citizen_id=${encodeURIComponent(citizenId)}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            const citizen = result.data;
            
            const element = document.createElement('div');
            element.style.width = '240px'; 
            element.style.height = '150px'; 
            element.style.backgroundColor = '#fff';
            element.style.border = '1px solid #000';
            element.style.padding = '10px';
            element.style.fontFamily = 'Arial, sans-serif';
            element.style.fontSize = '10px';
            element.style.color = '#000';
            element.style.display = 'flex';
            element.style.alignItems = 'center';
            element.innerHTML = `
                <div style="flex: 0 0 30%;">
                    <img src="${citizen.photo_path}" alt="Photo" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #000;">
                </div>
                <div style="flex: 0 0 70%; padding-left: 10px;">
                    <h3 style="font-size: 12px; margin: 0; color: #00f3ff;">CARTE DE CITOYEN</h3>
                    <p style="margin: 2px 0;"><strong>Nom :</strong> ${citizen.prenom} ${citizen.nom}</p>
                    <p style="margin: 2px 0;"><strong>N° National :</strong> ${citizen.national_id}</p>
                    <p style="margin: 2px 0;"><strong>Date de naissance :</strong> ${citizen.dob}</p>
                    <p style="margin: 2px 0;"><strong>Province :</strong> ${citizen.region}</p>
                </div>
            `;
            
            
            const opt = {
                margin: 0,
                filename: `Carte_Citoyen_${citizen.national_id}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'px', format: [240, 150], orientation: 'landscape' }
            };
            html2pdf().from(element).set(opt).save();
        } else {
            alert('Erreur lors de la génération de la carte de citoyen.');
        }
    } catch (error) {
        console.error('Erreur lors de la génération de la carte de citoyen:', error);
        alert('Erreur lors de la génération de la carte de citoyen.');
    }
}

const editModal = document.getElementById('editModal');
const editForm = document.getElementById('editForm');

async function editCitizen(citizenId) {
    try {
        const response = await fetch('get_citizen_profile.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `citizen_id=${encodeURIComponent(citizenId)}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            const citizen = result.data;
            
            document.getElementById('editCitizenId').value = citizen.id;
            document.getElementById('editNom').value = citizen.nom;
            document.getElementById('editPrenom').value = citizen.prenom;
            document.getElementById('editPostnom').value = citizen.postnom || '';
            document.getElementById('editDob').value = citizen.dob;
            document.getElementById('editBirthPlace').value = citizen.birth_place || '';
            document.getElementById('editSexe').value = citizen.sexe;
            document.getElementById('editCitizenType').value = citizen.citizen_type;
            document.getElementById('editBloodType').value = citizen.blood_type || '';
            document.getElementById('editMotherName').value = citizen.mother_name || '';
            document.getElementById('editFatherName').value = citizen.father_name || '';
            document.getElementById('editOriginVillage').value = citizen.origin_village || '';
            document.getElementById('editQuarter').value = citizen.quarter || '';
            document.getElementById('editStreetNumber').value = citizen.street_number || '';
            document.getElementById('editAvenue').value = citizen.avenue || '';
            document.getElementById('editCommune').value = citizen.commune || '';
            document.getElementById('editCity').value = citizen.city || '';
            document.getElementById('editRegion').value = citizen.region || '';
            document.getElementById('editProfession').value = citizen.profession || '';
            document.getElementById('editEmployer').value = citizen.employer || '';
            document.getElementById('editSalary').value = citizen.salary || '';
            document.getElementById('editTaxNumber').value = citizen.tax_number || '';
            document.getElementById('editSocialSecurityNumber').value = citizen.social_security_number || '';
            document.getElementById('editEducationLevel').value = citizen.education_level || '';
            document.getElementById('editLastInstitution').value = citizen.last_institution || '';
            document.getElementById('editDiploma').value = citizen.diploma || '';
            document.getElementById('editGraduationYear').value = citizen.graduation_year || '';
            document.getElementById('editMaritalStatus').value = citizen.marital_status || 'single';
            document.getElementById('editSpouseName').value = citizen.spouse_name || '';
            document.getElementById('editSpouseNationalId').value = citizen.spouse_national_id || '';
            document.getElementById('editSpouseDob').value = citizen.spouse_dob || '';
            document.getElementById('editAssociations').value = citizen.associations || '';
            document.getElementById('editPoliticalParty').value = citizen.political_party || '';
            document.getElementById('editHealthInsurance').value = citizen.health_insurance || '';
            document.getElementById('editPhone1').value = citizen.phone1 || '';
            document.getElementById('editPhone2').value = citizen.phone2 || '';
            document.getElementById('editEmail').value = citizen.email || '';
            document.getElementById('editEmergencyContact').value = citizen.emergency_contact || '';
            document.getElementById('editEmergencyPhone').value = citizen.emergency_phone || '';
            
            editModal.classList.add('show');
        } else {
            alert('Erreur lors du chargement des informations pour modification.');
        }
    } catch (error) {
        console.error('Erreur lors du chargement des informations pour modification:', error);
        alert('Erreur lors du chargement des informations pour modification.');
    }
}

function closeEditModal() {
    editModal.classList.remove('show');
}

editForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(editForm);
    try {
        const response = await fetch('update_citizen.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Informations mises à jour avec succès.');
            closeEditModal();
            
            const citizenId = document.getElementById('editCitizenId').value;
            await loadCitizenProfile(citizenId);
        } else {
            alert('Erreur lors de la mise à jour des informations : ' + result.message);
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour des informations:', error);
        alert('Erreur lors de la mise à jour des informations.');
    }
});

    </script>
</body>
</html>