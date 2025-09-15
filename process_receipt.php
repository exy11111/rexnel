<?php
require "db.php";
require "session.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['receipt']) || empty($data['receipt']) || 
    !isset($data['total_price']) || !isset($data['payment_method'])) {
    echo json_encode(["success" => false, "error" => "Invalid data received."]);
    exit;
}

$receipt = $data['receipt'];
$total_price = $data['total_price'];
$payment_method = $data['payment_method'];
$branch_id = $data['branch_id'];
$date = $data['dateSel'];
$randomHour   = rand(0, 23);
$randomMinute = rand(0, 59);
$randomSecond = rand(0, 59);

$dateTime = $data['dateSel'] . ' ' . sprintf('%02d:%02d:%02d', $randomHour, $randomMinute, $randomSecond);
$timestamp = strtotime($dateTime);

$proofImagePath = null;

// ✅ Handle base64 proof image if provided
if (isset($data['proof_image']) && !empty($data['proof_image'])) {
    $base64Image = $data['proof_image'];

    // Remove the data URI scheme part
    if (strpos($base64Image, 'base64,') !== false) {
        $base64Image = explode('base64,', $base64Image)[1];
    }

    $base64Image = str_replace(' ', '+', $base64Image);
    $decodedImage = base64_decode($base64Image);

    if ($decodedImage !== false) {
        $uploadDir = 'uploads/proof_images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'proof_' . uniqid() . '.png';
        $filePath = $uploadDir . $filename;

        if (file_put_contents($filePath, $decodedImage)) {
            $proofImagePath = $filePath;
        } else {
            echo json_encode(["success" => false, "error" => "Failed to save proof image."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid image data."]);
        exit;
    }
}

try {
    $conn->beginTransaction();

    // 👇 Add proof_image to the insert if it's available
    $stmt = $conn->prepare("
        INSERT INTO purchases (price, pm_id, date, branch_id, proof_image) 
        VALUES (:price, :pm_id, :date, :branch_id, :proof_image)
    ");
    $stmt->execute([
        ':price' => $total_price,
        ':pm_id' => $payment_method,
        ':branch_id' => $branch_id,
        ':date' => $timestamp,
        ':proof_image' => $proofImagePath
    ]);
    $purchase_id = $conn->lastInsertId();

    $stmt = $conn->prepare("
        INSERT INTO purchase_items (purchase_id, item_id, quantity) 
        VALUES (:purchase_id, :item_id, :quantity)
    ");

    foreach ($receipt as $item) {
        if (!isset($item['item_id'], $item['quantity'], $item['price'])) {
            throw new Exception("Missing item details.");
        }

        $stmt1 = $conn->prepare("SELECT stock FROM items WHERE item_id = :item_id");
        $stmt1->execute([':item_id' => $item['item_id']]);
        $quantity = $stmt1->fetchColumn();

        if ($quantity < $item['quantity']) {
            throw new Exception("Item out of stock.");
        }

        $stmt->execute([
            ':purchase_id' => $purchase_id,
            ':item_id' => $item['item_id'],
            ':quantity' => $item['quantity']
        ]);

        $newQuantity = $quantity - $item['quantity'];

        $stmt2 = $conn->prepare("UPDATE items SET stock = :stock WHERE item_id = :item_id");
        $stmt2->execute([
            ':stock' => $newQuantity,
            ':item_id' => $item['item_id']
        ]);
    }

    $conn->commit();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["success" => false, "error" => "Failed to process receipt. " . $e->getMessage()]);
}?>
