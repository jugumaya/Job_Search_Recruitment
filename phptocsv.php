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

// Function to export table data to a single CSV file
function exportToCSV($tables, $conn) {
    // Open a single CSV file for writing
    $filename = "job_finder_export.csv";
    $file = fopen($filename, 'w');

    // Write the headers first
    $firstTable = true;

    foreach ($tables as $tableName) {
        // Prepare the SQL query
        $sql = "SELECT * FROM $tableName";
        $result = $conn->query($sql);

        // Check if the table has data
        if ($result->num_rows > 0) {
            if (!$firstTable) {
                // Add a separator row between tables
                fputcsv($file, ['']); // Empty row as a separator between tables
            }

            // Write the column headers (only for the first table)
            $fields = $result->fetch_fields();
            $headers = [];
            foreach ($fields as $field) {
                $headers[] = $field->name;
            }
            fputcsv($file, $headers);

            // Fetch and write each row of data from the current table
            while ($row = $result->fetch_assoc()) {
                fputcsv($file, $row);
            }

            $firstTable = false; // Set flag to false after the first table
        } else {
            echo "No data found in table '$tableName'.<br>";
        }
    }

    // Close the file
    fclose($file);
    echo "All data exported to '$filename' successfully.<br>";
}

// Tables you want to export
$tables = ['users', 'jobs', 'applications'];

// Export all tables to one CSV
exportToCSV($tables, $conn);

// Close connection
$conn->close();
?>
