document.getElementById('submitData').addEventListener('click', function() {
    let table = document.getElementById('dataTable');
    let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    let data = [];

    for (let row of rows) {
        let cells = row.getElementsByTagName('td');
        let rowData = {
            item_id: cells[0].innerText,
            item_name: cells[1].innerText,
            quantity: cells[2].innerText,
            price: cells[3].innerText
        };
        data.push(rowData);
    }

    fetch('process_table_data.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        Swal.fire({
            icon: result.success ? 'success' : 'error',
            title: result.success ? 'Success' : 'Error',
            text: result.message
        });
    })
    .catch(error => console.error('Error:', error));
});


<?php
$conn = new PDO("mysql:host=localhost;dbname=your_database", "root", "");

// Read JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    foreach ($data as $row) {
        $stmt = $conn->prepare("INSERT INTO your_table (item_id, item_name, quantity, price) VALUES (:item_id, :item_name, :quantity, :price)");
        $stmt->bindParam(':item_id', $row['item_id']);
        $stmt->bindParam(':item_name', $row['item_name']);
        $stmt->bindParam(':quantity', $row['quantity']);
        $stmt->bindParam(':price', $row['price']);
        $stmt->execute();
    }
    echo json_encode(["success" => true, "message" => "Data inserted successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "No data received!"]);
}
?>



