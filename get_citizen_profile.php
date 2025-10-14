<?php
require 'config_cit.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['citizen_id'])) {
    $citizen_id = filter_input(INPUT_POST, 'citizen_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $conn->prepare("SELECT * FROM identity WHERE id = ?");
    $stmt->bind_param('i', $citizen_id);
    $stmt->execute();
    $citizen = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$citizen) {
        echo json_encode(['success' => false, 'message' => 'Citoyen non trouvé']);
        exit;
    }

    $stmt = $conn->prepare("SELECT property_type, property_number, document_path FROM properties WHERE citizen_id = ?");
    $stmt->bind_param('i', $citizen_id);
    $stmt->execute();
    $properties = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $conn->prepare("SELECT record_number, record_type, document_path FROM criminal_records WHERE citizen_id = ?");
    $stmt->bind_param('i', $citizen_id);
    $stmt->execute();
    $criminal_records = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $conn->prepare("SELECT full_name, dob, sex FROM children WHERE identity_id = ?");
    $stmt->bind_param('i', $citizen_id);
    $stmt->execute();
    $children = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $citizen['properties'] = $properties;
    $citizen['criminal_records'] = $criminal_records;
    $citizen['children'] = $children;
    $citizen['children_count'] = count($children);
    $citizen['children_names'] = implode(', ', array_column($children, 'full_name'));

    echo json_encode(['success' => true, 'data' => $citizen]);
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}

$conn->close();
?>