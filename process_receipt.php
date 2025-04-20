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

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("INSERT INTO purchases (price, pm_id, branch_id) VALUES (:price, :pm_id, :branch_id)");
    $stmt->execute([
        ':price' => $total_price,
        ':pm_id' => $payment_method,
        ':branch_id' => $branch_id
    ]);
    $purchase_id = $conn->lastInsertId();

    $stmt = $conn->prepare("INSERT INTO purchase_items (purchase_id, item_id, quantity) 
                            VALUES (:purchase_id, :item_id, :quantity)");

    foreach ($receipt as $item) {
        if (!isset($item['item_id'], $item['quantity'], $item['price'])) {
            throw new Exception("Missing item details.");
        }
        $stmt1 = $conn->prepare("SELECT stock FROM items WHERE item_id = :item_id");
        $stmt1->execute([':item_id' => $item['item_id']]);
        $quantity = $stmt1->fetchColumn();
        if($quantity < $item['quantity']){
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
}
?>
