
<?php
$conn = null;
/**
 * Connects to the database if not connected already
 */
function connectDatabase() {
    global $conn;

    //check if connected already
    if ($conn != null)
        return;

    $serverName = "mssql.iproject.icasites.nl";
    $userId     = "iproject19";
    $password   = 'RDRdrGYX';
    $database   = "iproject19";

    try {
        $conn = new PDO("sqlsrv:server=$serverName;database=$database;ConnectionPooling=0", $userId, $password);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::SQLSRV_ATTR_DIRECT_QUERY, true);

    } catch( PDOException $e ) {
        echo "Error connecting to SQL Server";
        echo $e->getMessage();
        exit();
    }
}

/**
 * Sends a query to the database
 * @param string $sql the query to be sent to the database
 * @return array|int database response or number of affected rows
 */
function queryDatabase($sql, $array) {

    connectDatabase();
    global $conn;

    $stmt = $conn->prepare($sql);
    $stmt->execute($array);

    try {
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data;
    } catch (Exception $e) {
      return $stmt->rowCount();
    }
}
?>