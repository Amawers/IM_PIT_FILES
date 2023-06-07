<?php
// MySQL database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "pizza_runner";

// Create a PDO connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Function to insert data into a table
function insertData($conn, $tableName, $data)
{
    $columns = implode(',', array_keys($data));
    $values = "'" . implode("','", array_values($data)) . "'";
    $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
    $conn->exec($sql);
}

// Parse and insert data from XML files
$xmlFiles = [
    "runners.xml",
    "runner_orders.xml",
    "pizza_names.xml",
    "customer_orders.xml",
    "pizza_recipes.xml",
    "pizza_toppings.xml"
];

foreach ($xmlFiles as $xmlFile) {
    $xml = simplexml_load_file($xmlFile);

    // Determine the table name based on the XML root element
    $tableName = $xml->getName();

    foreach ($xml->children() as $row) {
        $data = [];
        foreach ($row->children() as $column) {
            $columnName = $column->getName();
            $columnValue = (string) $column;
            $data[$columnName] = $columnValue;
        }
        insertData($conn, $tableName, $data);
    }
}

// Close the database connection
$conn = null;

echo "Data inserted successfully!";
?>
