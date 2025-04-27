<?php
    $dateQuery = $conn->query("SELECT DISTINCT DATE(date) as day FROM purchases ORDER BY day ASC");
    $dates = $dateQuery->fetchAll(PDO::FETCH_COLUMN);

    $salesQuery = $conn->query("
        SELECT branch_id, DATE(date) AS day, SUM(price) AS total_price
        FROM purchases
        GROUP BY branch_id, DATE(date)
        ORDER BY day ASC
    ");
    $salesData = $salesQuery->fetchAll(PDO::FETCH_ASSOC);

    $branches = [];
    foreach ($salesData as $row) {
        $branches[$row['branch_id']][$row['day']] = $row['total_price'];
    }

    $datasets = [];
    $colors = [
        1 => 'rgba(75, 192, 192, 1)',
        2 => 'rgba(255, 99, 132, 1)',
        3 => 'rgba(54, 162, 235, 1)'
    ];

    foreach ($branches as $branchId => $salesByDate) {
        $data = [];
        foreach ($dates as $date) {
            $data[] = isset($salesByDate[$date]) ? (float)$salesByDate[$date] : null;
        }
        $datasets[] = [
            'label' => 'Branch ' . $branchId,
            'data' => $data,
            'borderColor' => $colors[$branchId],
            'backgroundColor' => str_replace('1)', '0.2)', $colors[$branchId]),
            'borderWidth' => 2,
            'tension' => 0.4,
            'fill' => false
        ];
    }

    echo json_encode([
        'labels' => $dates,
        'datasets' => $datasets
    ]);
?>