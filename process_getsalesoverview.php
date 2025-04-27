<?php
    require ('db.php');
    require ('session.php');
    $dateQuery = $conn->query("SELECT DISTINCT DATE(date) as day FROM purchases ORDER BY day ASC");
    $dates = $dateQuery->fetchAll(PDO::FETCH_COLUMN);

    $salesQuery = $conn->query("
        SELECT p.branch_id, b.branch_name, DATE(p.date) AS day, SUM(p.price) AS total_price
        FROM purchases p
        JOIN branch b ON p.branch_id = b.branch_id
        GROUP BY p.branch_id, day
        ORDER BY day ASC
    ");
    $salesData = $salesQuery->fetchAll(PDO::FETCH_ASSOC);

    $branches = [];
    foreach ($salesData as $row) {
        $branches[$row['branch_id']]['name'] = $row['branch_name'];
        $branches[$row['branch_id']]['sales'][$row['day']] = $row['total_price'];
    }

    $datasets = [];
    $colors = [
        1 => 'rgba(75, 192, 192, 1)',
        2 => 'rgba(255, 99, 132, 1)',
        3 => 'rgba(54, 162, 235, 1)'
    ];

    foreach ($branches as $branchId => $branchInfo) {
        $data = [];
        foreach ($dates as $date) {
            $data[] = isset($branchInfo['sales'][$date]) ? (float)$branchInfo['sales'][$date] : null;
        }
        $datasets[] = [
            'label' => $branchInfo['name'],
            'data' => $data,
            'borderColor' => $colors[$branchId] ?? 'rgba(0,0,0,1)',
            'backgroundColor' => str_replace('1)', '0.2)', $colors[$branchId] ?? 'rgba(0,0,0,0.2)'),
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