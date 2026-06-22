<?php
// Database connection details
$servername = "127.0.0.1"; // Your database server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "job_finder"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to export table data to CSV
function exportToCSV($tableName, $conn) {
    // Prepare the SQL query
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    // Check if the table has data
    if ($result->num_rows > 0) {
        // Open a file for writing
        $filename = "$tableName.csv";
        $file = fopen($filename, 'w');

        // Get and write the column names as the first row
        $fields = $result->fetch_fields();
        $headers = [];
        foreach ($fields as $field) {
            $headers[] = $field->name;
        }
        fputcsv($file, $headers);

        // Fetch data and write each row to the CSV file
        while ($row = $result->fetch_assoc()) {
            fputcsv($file, $row);
        }

        // Close the file
        fclose($file);
        echo "Data from '$tableName' exported to '$filename' successfully.<br>";
    } else {
        echo "No data found in table '$tableName'.<br>";
    }
}

// Export each table
exportToCSV('users', $conn);
exportToCSV('jobs', $conn);
exportToCSV('applications', $conn);

// Close connection
$conn->close();
?>
