<?php
// Source database connection
$sourceHost = "source_host";
$sourceUsername = "source_username";
$sourcePassword = "source_password";
$sourceDatabase = "source_database";

$sourceDSN = "mysql:host={$sourceHost};dbname={$sourceDatabase}";
$sourceOptions = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $sourceConnection = new PDO($sourceDSN, $sourceUsername, $sourcePassword, $sourceOptions);
} catch (PDOException $e) {
    die("Source Database Connection Failed: " . $e->getMessage());
}

// Target database connection
$targetHost = "target_host";
$targetUsername = "target_username";
$targetPassword = "target_password";
$targetDatabase = "target_database";

$targetDSN = "mysql:host={$targetHost};dbname={$targetDatabase}";

try {
    $targetConnection = new PDO($targetDSN, $targetUsername, $targetPassword, $sourceOptions);
} catch (PDOException $e) {
    die("Target Database Connection Failed: " . $e->getMessage());
}

// Get a list of all tables in the source database
$tablesQuery = $sourceConnection->query("SHOW TABLES");
$tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

// Copy each table from the source to the target database
foreach ($tables as $table) {
    $sourceTable = "`$sourceDatabase`.`$table`";
    $targetTable = "`$targetDatabase`.`$table`"; // You can change the table name in the target database if needed

    // Create a table if it doesn't exist in the target database
    $createTableQuery = "CREATE TABLE IF NOT EXISTS $targetTable AS SELECT * FROM $sourceTable";
    $targetConnection->exec($createTableQuery);
    echo $createTableQuery ."\n";
}

echo "Database copy completed.";

// Close database connections
$sourceConnection = null;
$targetConnection = null;
?>
