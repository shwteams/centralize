<?php

$conn = PDO('mysql:host=localhost;dbname=surveillance_portefeuille_db', 'root', '');
$query = "SELECT * FROM t_extraction";

$statement = $conn->prepare($query);
$statement->execute();
while($row = $statement->fetch(PDO::FETCH_ASSOC))
{
    $data[] = $row;
}
echo json_encode($data);

?>