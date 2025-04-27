<?php
require('db.php');

// Query to get stock data for branch_id = 1
$query = "
    SELECT i.item_name, i.stock
    FROM items i
    WHERE i.branch_id = 1
";

$stmt = $conn->query($query);
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
            'borderWidth' => 1
        ]
    ]
]);
?>