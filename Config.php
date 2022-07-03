<?php
$conn = new mysqli('localhost','root','','shopping_card_website');
if ($conn->connect_error) {
   die("Connection To Database not found".$conn->connect_error);
}
?>
