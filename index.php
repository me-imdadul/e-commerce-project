<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecom";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$categories = [
  ["name" => "Fashion", "image" => "public/fashion.jpg"],
  ["name" => "Men", "image" => "public/men.jpg"],
  ["name" => "Electronics", "image" => "public/electronics.jpg"],
  ["name" => "Groceries", "image" => "public/groceries.jpg"],
  ["name" => "Laptops", "image" => "public/laptops.jpg"]
];

$products = [
  ["name" => "PersonalPrint Tops", "image" => "public/tote-bag.jpg", "price" => 115.00, "old_price" => 130.00, "rating" => 5],
  ["name" => "Print A-Line Maxi Dress", "image" => "public/pillow.jpg", "price" => 50.00, "old_price" => 55.00, "rating" => 5],
  ["name" => "Little Explorer Bag", "image" => "public/pouch.jpg", "price" => 15.00, "rating" => 5],
  ["name" => "Stylish Protection Hat", "image" => "public/hat.jpg", "price" => 45.00, "old_price" => 46.02, "rating" => 5]
];

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
  <div class="categories-page">

    <h1>Top categories</h1>

    <div class="categories-container">
      <?php foreach ($categories as $category): ?>
        <div class="category-box">
          <div class="category-card">
            <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>">
          </div>
          <h2><?php echo $category['name']; ?></h2>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
  <div class="product-page">
    <h1>Featured Products</h1>
    <div class="products-container">
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
          <div class="product-info">
            <h2><?php echo $product['name']; ?></h2>
            <div class="product-pricing">
              <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
              <?php if (isset($product['old_price'])): ?>
                <span class="old-price">$<?php echo number_format($product['old_price'], 2); ?></span>
              <?php endif; ?>
            </div>
            <div class="product-rating">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <box-icon name='star' <?php if ($i < $product['rating'])
                  echo 'type="solid"'; ?>></box-icon>
              <?php endfor; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>