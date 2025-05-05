<?php
require('db.php');

// Get branch_id from query string
$branchId = isset($_GET['branch_id']) ? $_GET['branch_id'] : 1; // Default to 1 if not provided

// Query to get stock data for the selected branch_id
$query = "
    SELECT i.item_name, i.stock
    FROM items i
    WHERE i.branch_id = :branch_id
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':branch_id', $branchId, PDO::PARAM_INT);
$stmt->execute();
$stockData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data for the chart
$itemNames = [];
$stockLevels = [];

foreach ($stockData as $row) {
    $itemNames[] = $row['item_name'];
    $stockLevels[] = (int)$row['stock'];
}

// Return the data as JSON
echo json_encode([
    'labels' => $itemNames,
    'datasets' => [
        [
            'label' => 'Stock Levels',
            'data' => $stockLevels,
            'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'borderWidth' => 1,
            'spanGaps' => 'true'
        ]
    ]
]);
?>