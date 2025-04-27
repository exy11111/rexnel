<?php
    require ('db.php');
    require ('session.php');

    $query = "
        SELECT b.branch_name, i.item_name, i.stock
        FROM items i
        JOIN sizes s ON i.size_id = s.size_id
        JOIN branches b ON i.branch_id = b.branch_id
        WHERE b.branch_id IN (1, 2, 3)
    ";

    $stmt = $conn->query($query);
    $itemsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the chart or table
    $branches = [];
    $itemNames = [];
    $stockLevels = [];

    foreach ($itemsData as $row) {
        // Store the branch name
        $branches[] = $row['branch_name'];

        // Store the item name and stock level for each item
        $itemNames[] = $row['item_name'];
        $stockLevels[] = (int)$row['stock'];
    }

    // Return the data as JSON for the front-end
    echo json_encode([
        'branches' => $branches,
        'items' => $itemNames,
        'stocks' => $stockLevels,
    ]);
?>
