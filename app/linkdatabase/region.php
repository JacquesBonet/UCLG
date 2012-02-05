<?php

require_once __DIR__ . "/../utils/db.php";

$query = "SELECT * FROM region";

$db = getDB();
$result = mysql_query($query, $db) or die(mysql_error());
$count = mysql_num_rows($result);
$rows = Array();
while($row = mysql_fetch_assoc( $result)) {
    array_push($rows, $row);
}

echo json_encode(Array(
    "total"=>$count,
    "data"=>$rows
));

mysql_close( $db);
?>