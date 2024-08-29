<?php
 $servername = "localhost";
 $username = "root";  // replace with your database username
 $password_db = "";  // replace with your database password
 $dbname = "imboni";

 // Create connection
 $conn = new mysqli($servername, $username, $password_db, $dbname);

 // Check connection
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }

?>