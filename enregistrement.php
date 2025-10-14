<?php
$host = 'localhost';
$dbname = 'rnnc';
$username = 'root';
$password = '';


$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}


function generateNationalId($region, $birthYear, $conn) {
    $prefix = substr(strtoupper($region), 0, 3);
    $yearCode = substr($birthYear, -2);
    $random = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
    $id = $prefix . $yearCode . $random;

    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM identity WHERE national_id = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if ($count > 0) {
        return generateNationalId($region, $birthYear, $conn); 
    }
    return $id;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'citizen_type' => $_POST['citizenType'] ?? '',
        'nom' => filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING) ?? '',
        'prenom' => filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING) ?? '',
        'postnom' => filter_input(INPUT_POST, 'postnom', FILTER_SANITIZE_STRING) ?? '',
        'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING) ?? '',
        'birth_place' => filter_input(INPUT_POST, 'birthPlace', FILTER_SANITIZE_STRING) ?? '',
        'sexe' => filter_input(INPUT_POST, 'sexe', FILTER_SANITIZE_STRING) ?? '',
        'national_id' => $_POST['nationalId'] ?? generateNationalId($_POST['region'] ?? 'UNK', explode('-', $_POST['dob'] ?? '')[0], $conn),
        'tax_number' => filter_input(INPUT_POST, 'taxNumber', FILTER_SANITIZE_STRING) ?? '',
        'blood_type' => filter_input(INPUT_POST, 'bloodType', FILTER_SANITIZE_STRING) ?? '',
        'quarter' => filter_input(INPUT_POST, 'quarter', FILTER_SANITIZE_STRING) ?? '',
        'street_number' => filter_input(INPUT_POST, 'streetNumber', FILTER_SANITIZE_STRING) ?? '',
        'avenue' => filter_input(INPUT_POST, 'avenue', FILTER_SANITIZE_STRING) ?? '',
        'commune' => filter_input(INPUT_POST, 'commune', FILTER_SANITIZE_STRING) ?? '',
        'city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING) ?? '',
        'region' => filter_input(INPUT_POST, 'region', FILTER_SANITIZE_STRING) ?? '',
        'country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING) ?? 'République Démocratique du Congo',
        'profession' => filter_input(INPUT_POST, 'profession', FILTER_SANITIZE_STRING) ?? '',
        'employer' => filter_input(INPUT_POST, 'employer', FILTER_SANITIZE_STRING) ?? '',
        'salary' => filter_input(INPUT_POST, 'salary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? '',
        'marital_status' => filter_input(INPUT_POST, 'maritalStatus', FILTER_SANITIZE_STRING) ?? '',
        'education_level' => filter_input(INPUT_POST, 'educationLevel', FILTER_SANITIZE_STRING) ?? '',
        'last_institution' => filter_input(INPUT_POST, 'lastInstitution', FILTER_SANITIZE_STRING) ?? '',
        'diploma' => filter_input(INPUT_POST, 'diploma', FILTER_SANITIZE_STRING) ?? '',
        'graduation_year' => filter_input(INPUT_POST, 'graduationYear', FILTER_SANITIZE_NUMBER_INT) ?? '',
        'associations' => filter_input(INPUT_POST, 'associations', FILTER_SANITIZE_STRING) ?? '',
        'political_party' => filter_input(INPUT_POST, 'politicalParty', FILTER_SANITIZE_STRING) ?? '',
        'health_insurance' => filter_input(INPUT_POST, 'healthInsurance', FILTER_SANITIZE_STRING) ?? '',
        'social_security_number' => filter_input(INPUT_POST, 'socialSecurityNumber', FILTER_SANITIZE_STRING) ?? '',
        'phone1' => filter_input(INPUT_POST, 'phone1', FILTER_SANITIZE_STRING) ?? '',
        'phone2' => filter_input(INPUT_POST, 'phone2', FILTER_SANITIZE_STRING) ?? '',
        'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '',
        'emergency_contact' => filter_input(INPUT_POST, 'emergencyContact', FILTER_SANITIZE_STRING) ?? '',
        'emergency_phone' => filter_input(INPUT_POST, 'emergencyPhone', FILTER_SANITIZE_STRING) ?? '',
        'mother_name' => filter_input(INPUT_POST, 'motherName', FILTER_SANITIZE_STRING) ?? '',
        'father_name' => filter_input(INPUT_POST, 'fatherName', FILTER_SANITIZE_STRING) ?? '',
        'origin_village' => filter_input(INPUT_POST, 'originVillage', FILTER_SANITIZE_STRING) ?? '',
        'latitude' => filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? null,
        'longitude' => filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?? null,
        'registration_date' => date('Y-m-d H:i:s')
    ];

    
    if ($_POST['maritalStatus'] === 'married') {
        $data['spouse_name'] = filter_input(INPUT_POST, 'spouseName', FILTER_SANITIZE_STRING) ?? '';
        $data['spouse_national_id'] = filter_input(INPUT_POST, 'spouseNationalId', FILTER_SANITIZE_STRING) ?? '';
        $data['spouse_dob'] = filter_input(INPUT_POST, 'spouseDob', FILTER_SANITIZE_STRING) ?? '';
    }

    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'Uploads/photos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $data['photo_path'] = $targetPath;
        }
    }

    
    if (isset($_FILES['voice']) && $_FILES['voice']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'Uploads/voice/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = uniqid() . '_voice.wav';
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['voice']['tmp_name'], $targetPath)) {
            $data['voice_path'] = $targetPath;
        }
    }

    
    if (isset($_POST['fingerprint'])) {
        $data['fingerprint_data'] = $_POST['fingerprint'];
    }

    
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($data), '?');
    $sql = "INSERT INTO identity (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $types = str_repeat('s', count($data));
    $stmt->bind_param($types, ...array_values($data));

    if ($stmt->execute()) {
        $identityId = $conn->insert_id;

        
        if (isset($_POST['propertyCount'])) {
            for ($i = 1; $i <= $_POST['propertyCount']; $i++) {
                if (!empty($_POST["propertyNumber$i"])) {
                    $docPath = '';
                    if (isset($_FILES["propertyDoc$i"]) && $_FILES["propertyDoc$i"]['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = 'Uploads/properties/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                        $fileName = uniqid() . '_' . basename($_FILES["propertyDoc$i"]['name']);
                        $targetPath = $uploadDir . $fileName;
                        if (move_uploaded_file($_FILES["propertyDoc$i"]['tmp_name'], $targetPath)) {
                            $docPath = $targetPath;
                        }
                    }
                    $stmt = $conn->prepare("INSERT INTO properties (citizen_id, property_type, property_number, document_path) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $identityId, $_POST["propertyType$i"], $_POST["propertyNumber$i"], $docPath);
                    $stmt->execute();
                }
            }
        }

        
        if (!empty($_POST['criminalRecordNumber'])) {
            $docPath = '';
            if (isset($_FILES['criminalRecordDoc']) && $_FILES['criminalRecordDoc']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'Uploads/criminal_records/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = uniqid() . '_' . basename($_FILES['criminalRecordDoc']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['criminalRecordDoc']['tmp_name'], $targetPath)) {
                    $docPath = $targetPath;
                }
            }
            $stmt = $conn->prepare("INSERT INTO criminal_records (citizen_id, record_number, record_type, document_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $identityId, $_POST['criminalRecordNumber'], $_POST['criminalRecordType'], $docPath);
            $stmt->execute();
        }

        
        if ($_POST['maritalStatus'] === 'married' && isset($_POST['childCount'])) {
            for ($i = 1; $i <= $_POST['childCount']; $i++) {
                if (!empty($_POST["childName$i"])) {
                    $stmt = $conn->prepare("INSERT INTO children (identity_id, full_name, dob, sex) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $identityId, $_POST["childName$i"], $_POST["childDob$i"], $_POST["childSex$i"]);
                    $stmt->execute();
                }
            }
        }

           echo json_encode(['id' => $identityId]);
            exit();
    } else {
        echo json_encode(['error' => "Erreur d'enregistrement : " . $stmt->error]);
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre National Numérique</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="cyber-container">
        <header class="cyber-header">
            <h1><i class="bi bi-person-badge"></i> REGISTRE NATIONAL NUMÉRIQUE</h1>
            <p class="cyber-subtitle">SYSTÈME INTELLIGENT D'IDENTIFICATION CITOYENNE</p>
            <div class="cyber-scanline"></div>
        </header>

        <div class="cyber-tabs">
            <button class="cyber-tab active" data-tab="identity">Identité</button>
            <button class="cyber-tab" data-tab="address">Adresse</button>
            <button class="cyber-tab" data-tab="professional">Professionnel</button>
            <button class="cyber-tab" data-tab="biometrics">Biométrie</button>
            <button class="cyber-tab" data-tab="additional">Additionnel</button>
        </div>

        <form id="citizenForm" action="index.php" method="POST" enctype="multipart/form-data">
                        <div class="cyber-section" id="identity">
                <h2><i class="bi bi-file-person"></i> Identité Civile</h2>
                <div class="cyber-grid">
                    <div class="cyber-input-group">
                        <label><i class="bi bi-person-vcard"></i> Type de Citoyen</label>
                        <div class="cyber-radio-group">
                            <label class="cyber-radio">
                                <input type="radio" name="citizenType" value="adulte" checked>
                                <span class="cyber-radio-btn"></span> Adulte
                            </label>
                            <label class="cyber-radio">
                                <input type="radio" name="citizenType" value="enfant">
                                <span class="cyber-radio-btn"></span> Enfant
                            </label>
                        </div>
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-card-heading"></i> Nom</label>
                        <input type="text" name="nom">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-card-heading"></i> Prénom</label>
                        <input type="text" name="prenom">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-card-heading"></i> Post-Nom</label>
                        <input type="text" name="postnom">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-calendar"></i> Date de Naissance</label>
                        <input type="date" name="dob" id="dob">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-geo-alt"></i> Lieu de Naissance</label>
                        <input type="text" name="birthPlace">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-gender-ambiguous"></i> Sexe</label>
                        <select name="sexe">
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-droplet"></i> Groupe Sanguin</label>
                        <select name="bloodType">
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
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-gender-female"></i> Nom de la Mère</label>
                        <input type="text" name="motherName">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-gender-male"></i> Nom du Père</label>
                        <input type="text" name="fatherName">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-geo-alt"></i> Village d'Origine</label>
                        <input type="text" name="originVillage">
                    </div>
                </div>
            </div>

                        <div class="cyber-section" id="address" style="display: none;">
                <h2><i class="bi bi-house-door"></i> Adresse</h2>
                <div class="cyber-grid">
                    <div class="cyber-input-group">
                        <label><i class="bi bi-building"></i> Quartier</label>
                        <input type="text" name="quarter">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-signpost"></i> Numéro</label>
                        <input type="text" name="streetNumber">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-signpost-2"></i> Avenue</label>
                        <input type="text" name="avenue">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-map"></i> Commune</label>
                        <input type="text" name="commune">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-city"></i> Ville</label>
                        <input type="text" name="city">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-globe"></i> Territoire / Ville</label>
                        <select name="region" id="region">
                            <option value="">Sélectionner la region</option>
                            <option value="Bas-Uele">Kananga</option>
                            <option value="Equateur">Demba</option>
                            <option value="Haut-Katanga">Dimbelenge</option>
                            <option value="Haut-Lomami">Luiza</option>
                            <option value="Haut-Uele">Dibaya</option>
                            <option value="Ituri">Kazumba</option>
                        </select>
                    </div>
                </div>
            </div>

                        <div class="cyber-section" id="professional" style="display: none;">
                <h2><i class="bi bi-briefcase"></i> Informations Professionnelles</h2>
                <div class="cyber-grid">
                    <div class="cyber-input-group">
                        <label><i class="bi bi-person-workspace"></i> Profession</label>
                        <input type="text" name="profession">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-building"></i> Employeur</label>
                        <input type="text" name="employer">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-cash-stack"></i> Salaire (USD)</label>
                        <input type="number" name="salary" step="0.01">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-heart"></i> Situation Matrimoniale</label>
                        <select name="maritalStatus" id="maritalStatus">
                            <option value="single">Célibataire</option>
                            <option value="married">Marié(e)</option>
                            <option value="divorced">Divorcé(e)</option>
                            <option value="widowed">Veuf/Veuve</option>
                        </select>
                    </div>
                </div>
            </div>

                        <div class="cyber-section" id="biometrics" style="display: none;">
                <h2><i class="bi bi-fingerprint"></i> Données Biométriques</h2>
                <div class="cyber-grid">
                    <div class="cyber-input-group">
                        <label><i class="bi bi-camera"></i> Photo d'Identité</label>
                        <input type="file" name="photo" accept="image/*" capture="camera">
                    </div>
                </div>
            </div>

                        <div class="cyber-section" id="additional" style="display: none;">
                <h2><i class="bi bi-info-circle"></i> Informations Additionnelles</h2>
                <div class="cyber-grid">
                    <div class="cyber-input-group">
                        <label><i class="bi bi-book"></i> Niveau d'Étude</label>
                        <select name="educationLevel">
                            <option value="primary">Primaire</option>
                            <option value="secondary">Secondaire</option>
                            <option value="bachelor">Licence</option>
                            <option value="master">Master</option>
                            <option value="phd">Doctorat</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-building"></i> Dernier Établissement</label>
                        <input type="text" name="lastInstitution">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-award"></i> Diplôme</label>
                        <input type="text" name="diploma">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-calendar"></i> Année d'Obtention</label>
                        <input type="number" name="graduationYear" min="1900" max="2099">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-diagram-3"></i> Associations</label>
                        <input type="text" name="associations">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-shield-check"></i> Numéro Sécurité Sociale</label>
                        <input type="text" name="socialSecurityNumber">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-phone"></i> Téléphone Principal</label>
                        <input type="tel" name="phone1">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-phone"></i> Téléphone Secondaire</label>
                        <input type="tel" name="phone2">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" name="email">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-person-lines-fill"></i> Contact d'Urgence</label>
                        <input type="text" name="emergencyContact">
                    </div>
                    <div class="cyber-input-group">
                        <label><i class="bi bi-telephone-plus"></i> Téléphone d'Urgence</label>
                        <input type="tel" name="emergencyPhone">
                    </div>
                </div>
                <div>
                    <h3><i class="bi bi-house"></i> Propriétés Immobilières</h3>
                    <div id="propertiesContainer"></div>
                    <button type="button" id="addPropertyBtn" class="cyber-btn">
                        <i class="bi bi-plus-circle"></i> Ajouter Propriété
                    </button>
                    <input type="hidden" name="propertyCount" id="propertyCount" value="0">
                </div>
                
            </div>

            <div class="cyber-nav-buttons">
                <button type="button" id="prevBtn" class="cyber-btn" disabled>Précédent</button>
                <button type="button" id="nextBtn" class="cyber-btn">Suivant</button>
                <button type="submit" id="submitBtn" class="cyber-submit-btn" style="display: none;">
                    <span class="cyber-btn-text"><i class="bi bi-save"></i> Valider</span>
                    <div class="cyber-btn-loader"></div>
                </button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('citizenForm');
    const tabs = document.querySelectorAll('.cyber-tab');
    const sections = document.querySelectorAll('#citizenForm > .cyber-section');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let currentTab = 0;

    
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            sections.forEach(s => s.style.display = 'none');
            sections[index].style.display = 'block';
            currentTab = index;
            updateNavButtons();
        });
    });

    function updateNavButtons() {
        prevBtn.disabled = currentTab === 0;
        nextBtn.style.display = currentTab === tabs.length - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = currentTab === tabs.length - 1 ? 'inline-block' : 'none';
    }

    prevBtn.addEventListener('click', () => {
        if (currentTab > 0) {
            currentTab--;
            tabs[currentTab].click();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentTab < tabs.length - 1) {
            currentTab++;
            tabs[currentTab].click();
        }
    });

    
    function generateNationalId() {
        const region = document.getElementById('region').value;
        const dob = document.getElementById('dob').value;
        if (region && dob) {
            fetch(`generate_id.php?region=${encodeURIComponent(region)}&year=${dob.split('-')[0]}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nationalId').value = data.id;
                })
                .catch(error => console.error('Error generating ID:', error));
        }
    }

    document.getElementById('dob').addEventListener('change', generateNationalId);
    document.getElementById('region').addEventListener('change', generateNationalId);

    
    const map = L.map('map').setView([-4.0383, 21.7587], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    let marker;

    map.on('click', e => {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(8);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(8);
    });

    document.getElementById('locateBtn').addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const latlng = [pos.coords.latitude, pos.coords.longitude];
                    map.setView(latlng, 15);
                    if (marker) map.removeLayer(marker);
                    marker = L.marker(latlng).addTo(map);
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(8);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(8);
                },
                () => alert('Impossible d\'obtenir la position.')
            );
        } else {
            alert('La géolocalisation n\'est pas supportée.');
        }
    });

    
    const recordBtn = document.getElementById('recordVoice');
    const voicePreview = document.getElementById('voicePreview');
    const voiceInput = document.getElementById('voiceInput');
    let mediaRecorder, audioChunks = [];

    recordBtn.addEventListener('click', async () => {
        if (recordBtn.textContent.includes('Stop')) {
            mediaRecorder.stop();
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();
            recordBtn.textContent = 'Stop Enregistrement';
            audioChunks = [];

            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                voicePreview.src = URL.createObjectURL(audioBlob);
                voicePreview.style.display = 'block';
                recordBtn.textContent = 'Re-enregistrer';

                const file = new File([audioBlob], 'voice.wav', { type: 'audio/wav' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                voiceInput.files = dataTransfer.files;
            };
        } catch (err) {
            alert('Erreur d’accès au microphone: ' + err.message);
        }
    });

    
    const canvas = document.getElementById('fingerprintCanvas');
    const ctx = canvas.getContext('2d');
    const captureBtn = document.getElementById('captureFingerprint');
    let isDrawing = false;

    canvas.addEventListener('mousedown', () => isDrawing = true);
    canvas.addEventListener('mouseup', () => isDrawing = false);
    canvas.addEventListener('mousemove', e => {
        if (isDrawing) {
            ctx.beginPath();
            ctx.arc(e.offsetX, e.offsetY, 5, 0, 2 * Math.PI);
            ctx.fillStyle = '#00f3ff';
            ctx.fill();
        }
    });

    captureBtn.addEventListener('click', () => {
        const data = canvas.toDataURL('image/png');
        document.getElementById('fingerprintData').value = data;
        alert('Empreinte capturée.');
    });

    
    let propertyCount = 0;
    document.getElementById('addPropertyBtn').addEventListener('click', () => {
        propertyCount++;
        const container = document.getElementById('propertiesContainer');
        const div = document.createElement('div');
        div.className = 'cyber-grid';
        div.innerHTML = `
            <div class="cyber-input-group">
                <label>Type de Propriété</label>
                <select name="propertyType${propertyCount}">
                    <option value="parcelle">Parcelle</option>
                    <option value="maison">Maison</option>
                    <option value="appartement">Appartement</option>
                    <option value="terrain">Terrain</option>
                </select>
            </div>
            <div class="cyber-input-group">
                <label>Numéro de Parcelle</label>
                <input type="text" name="propertyNumber${propertyCount}">
            </div>
            <div class="cyber-input-group">
                <label>Document</label>
                <input type="file" name="propertyDoc${propertyCount}" accept=".pdf,.jpg,.png">
            </div>
        `;
        container.appendChild(div);
        document.getElementById('propertyCount').value = propertyCount;
    });

    
    let childCount = 0;
    document.getElementById('addChildBtn').addEventListener('click', () => {
        childCount++;
        const container = document.getElementById('childrenContainer');
        const div = document.createElement('div');
        div.className = 'cyber-grid';
        div.innerHTML = `
            <div class="cyber-input-group">
                <label>Nom Complet</label>
                <input type="text" name="childName${childCount}">
            </div>
            <div class="cyber-input-group">
                <label>Date de Naissance</label>
                <input type="date" name="childDob${childCount}">
            </div>
            <div class="cyber-input-group">
                <label>Sexe</label>
                <select name="childSex${childCount}">
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
        `;
        container.appendChild(div);
        document.getElementById('childCount').value = childCount;
    });

    
    document.getElementById('maritalStatus').addEventListener('change', () => {
        const isMarried = document.getElementById('maritalStatus').value === 'married';
        document.getElementById('spouseSection').style.display = isMarried ? 'block' : 'none';
        document.getElementById('childrenSection').style.display = isMarried ? 'block' : 'none';
    });

    
    document.querySelectorAll('input[name="citizenType"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.getElementById('professional').style.display = radio.value === 'enfant' ? 'none' : 'block';
        });
    });

    
    form.addEventListener('submit', e => {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        const loader = submitBtn.querySelector('.cyber-btn-loader');
        loader.style.display = 'block';
        submitBtn.querySelector('.cyber-btn-text').textContent = 'Enregistrement...';

        const formData = new FormData(form);
        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                loader.style.display = 'none';
                submitBtn.querySelector('.cyber-btn-text').textContent = 'Valider';
            } else {
                window.location.href = `success.php?id=${data.id}`;
            }
        })
        .catch(error => {
            alert('Erreur: ' + error.message);
            loader.style.display = 'none';
            submitBtn.querySelector('.cyber-btn-text').textContent = 'Valider';
        });
    });
});
</script>
</html>
<style>
body{
     font-family: 'Rajdhani', sans-serif;
}
    
.cyber-section {
    padding: 20px;
    border: 1px solid #00f3ff;
    border-radius: 8px;
    background: rgba(0, 20, 40, 0.5);
    margin-bottom: 20px;
    opacity: 0; 
    transform: scale(0.98);
    display: none;
    transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
}

.cyber-section[style*="display: block"] {
    opacity: 1; 
    transform: scale(1);
}
.cyber-input-group input{
    width: 100%;
    outline: none;
    border: none;
    border-radius: 8px;
    padding: 15px;
    background: rgba(0, 255, 255, 0.1);
    color: #00f3ff;
}
.cyber-input-group input:focus{
    border: 1px solid #00f3ff;
    box-shadow: none;
}
.cyber-input-group select, .cyber-input-group button{
     border-radius: 8px;
    padding: 15px;
}
</style>