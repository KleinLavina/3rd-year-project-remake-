<?php
    require "database.php";

    $db = new Database();
    $result = $db->getData("SELECT idna, fname FROM students");

    while($row = mysqli_fetch_assoc($result)) {
        echo "Sample:"  $row["idno"] "Name:" $row["name"]. "<br>";
        
        }
?>
