<?php

$servername = "localhost";
$username = ""; 
$password = "";
$dbname = "ecom";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-COM</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <nav>
    <header>
      <h1 class="logo">E-COM</h1>
      <div class="menu">
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Categories</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
      <div class="right-menu">
        <box-icon class="menu-icon" name='search-alt'></box-icon>
        <box-icon class="menu-icon" name='cart'></box-icon>
        <box-icon class="menu-icon" name='user'></box-icon>
      </div>
    </header>
  </nav>

  <div class="home-page">
    <div class="home-details">
      <div class="details-container">
        <h1>Simple, Personal</h1>
        <button>Shop Now</button>
      </div>

    </div>
    <div class="home-image"><img
        src="https://images.unsplash.com/photo-1730829807497-9c5b8c9c41c4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        alt="stock"></div>
  </div>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</body>

</html>