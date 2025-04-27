<?php
    require ('db.php');
    require ('session.php');

    // Query to fetch branch names and stock data for each branch
    $query = "
        SELECT b.branch_name, SUM(i.stock) AS total_stock
        FROM items i
        JOIN sizes s ON i.size_id = s.size_id
        JOIN branches b ON i.branch_id = b.branch_id
        GROUP BY b.branch_name
    ";

    $stmt = $conn->query($query);
    $stockData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the labels and stock values for the chart
    $branches = [];
    $stockLevels = [];

    foreach ($stockData as $row) {
        $branches[] = $row['branch_name'];
        $stockLevels[] = (int)$row['total_stock'];
    }

    // Return the data as JSON for the front-end
    echo json_encode([
        'labels' => $branches,
        'datasets' => [
            [
                'label' => 'Stock Levels',
                'data' => $stockLevels,
                'backgroundColor' => [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                'borderColor' => [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                'borderWidth' => 1
            ]
        ]
    ]);
?>