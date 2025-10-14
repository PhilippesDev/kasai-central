<?php
require 'config_cit.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchTerm = $_POST['searchTerm'] ?? '';
    $searchTerm = '%' . $conn->real_escape_string($searchTerm) . '%';
    
    $stmt = $conn->prepare("SELECT * FROM identity WHERE nom LIKE ? OR prenom LIKE ? OR postnom LIKE ?");
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $citizens = $result->fetch_all(MYSQLI_ASSOC); 
    
    if ($citizens) {
        echo json_encode(['success' => true, 'data' => $citizens]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucun citoyen trouvé']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}

$conn->close();
?>