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
$time = $data['timeSel'];
$ref = $data['reference_number'] ?? null;
$dateTime = $date . ' ' . $time;

// Default values
$cash = 0;
$change_cash = 0;

// 🧮 Handle payment method logic
if ($payment_method == 1) {
    // CASH payment
    $cash = isset($data['cash_provided']) ? (float)$data['cash_provided'] : 0;
    $change_cash = isset($data['change']) ? (float)$data['change'] : 0;
} elseif ($payment_method == 2) {
    // GCASH payment
    $cash = isset($data['cash_provided']) ? (float)$data['cash_provided'] : $total_price;
    $change_cash = 0;
}

// 🖼️ Handle proof of payment (for GCash)
$proofImagePath = null;
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

    // ✅ Insert into purchases table (added cash & change_cash)
    $stmt = $conn->prepare("
        INSERT INTO purchases (price, pm_id, date, branch_id, proof_image, cash, change_cash, ref) 
        VALUES (:price, :pm_id, :date, :branch_id, :proof_image, :cash, :change_cash, :ref)
    ");
    $stmt->execute([
        ':price' => $total_price,
        ':pm_id' => $payment_method,
        ':branch_id' => $branch_id,
        ':date' => $dateTime,
        ':proof_image' => $proofImagePath,
        ':cash' => $cash,
        ':change_cash' => $change_cash,
        ':ref' => $ref
    ]);

    $purchase_id = $conn->lastInsertId();

    // Insert purchased items
    $stmt = $conn->prepare("
        INSERT INTO purchase_items (purchase_id, item_id, quantity) 
        VALUES (:purchase_id, :item_id, :quantity)
    ");

    foreach ($receipt as $item) {
        if (!isset($item['item_id'], $item['quantity'], $item['price'])) {
            throw new Exception("Missing item details.");
        }

        $stmt1 = $conn->prepare("SELECT stock, item_name FROM items WHERE item_id = :item_id");
        $stmt1->execute([':item_id' => $item['item_id']]);
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Item not found.");
        }

        $quantity = $row['stock'];
        $item_name = $row['item_name'];

        if ($quantity < $item['quantity']) {
            throw new Exception("Item '{$item_name}' is out of stock.");
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

        // 🔔 Low stock notification
        if ($newQuantity <= 10) {
            $added_by = $_SESSION['username'];
            $message = "Low stock alert: {$item_name} (only {$newQuantity} pcs left)";
            $icon = "bi-exclamation-circle";
            $target_url = "stock.php";
            $timestamp = date('Y-m-d H:i:s');

            $sql = "SELECT user_id FROM users WHERE branch_id = :branch_id";
            $stmtNotify = $conn->prepare($sql);
            $stmtNotify->bindParam(':branch_id', $_SESSION['branch_id']);
            $stmtNotify->execute();
            $users = $stmtNotify->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $sqlInsert = "INSERT INTO notifications (user_id, message, icon, target_url, created_at)
                              VALUES (:user_id, :message, :icon, :target_url, :created_at)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bindParam(':user_id', $user['user_id']);
                $stmtInsert->bindParam(':message', $message);
                $stmtInsert->bindParam(':icon', $icon);
                $stmtInsert->bindParam(':target_url', $target_url);
                $stmtInsert->bindParam(':created_at', $timestamp);
                $stmtInsert->execute();
            }

            // Also notify the admin
            $sqlInsert = "INSERT INTO notifications (user_id, message, icon, target_url, created_at)
                          VALUES (:user_id, :message, :icon, :target_url, :created_at)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bindParam(':user_id', $_SESSION['user_id']);
            $stmtInsert->bindParam(':message', $message);
            $stmtInsert->bindParam(':icon', $icon);
            $stmtInsert->bindParam(':target_url', $target_url);
            $stmtInsert->bindParam(':created_at', $timestamp);
            $stmtInsert->execute();
        }
    }

    $conn->commit();

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["success" => false, "error" => "Failed to process receipt. " . $e->getMessage()]);
}
?>
