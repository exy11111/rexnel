<?php
    require('db.php');

    // Fetch all branches
    $query = "
        SELECT b.branch_id, b.branch_name, i.item_name, i.stock
        FROM items i
        JOIN branches b ON i.branch_id = b.branch_id
    ";

    $stmt = $conn->query($query);
    $data = [];

    // Group data by branch_id
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $branchId = $row['branch_id'];
        $data[$branchId]['branch_name'] = $row['branch_name'];
        $data[$branchId]['item_names'][] = $row['item_name'];
        $data[$branchId]['stock_levels'][] = (int)$row['stock'];
    }

    // Convert to a numerical index for the response
    $response = array_values($data);

    // Return the data as JSON
    echo json_encode($response);
?>