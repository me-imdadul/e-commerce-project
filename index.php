<?php
session_start();
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

if (isset($_SESSION['id'])) {
  $userId = $_SESSION['id'];
  $sql = "SELECT name, username, email, phone_number, address FROM users WHERE id = $userId";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();
  $name = $user['name'];
  $username = $user['username'];
  $email = $user['email'];
  $phone_number = $user['phone_number'];
  $address = $user['address'];
} else {
  $username = '';
  $email = '';
  $phone_number = '';
  $address = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];

  if ($action == "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if ($hashed_password && password_verify($password, $hashed_password)) {
      $stmt = $conn->prepare("SELECT id, username, email, phone_number, address FROM users WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result->fetch_assoc();
      $_SESSION['id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['phone_number'] = $user['phone_number'];
      $_SESSION['address'] = $user['address'];
      header("Location: index.php");
      exit();
    } else {
      echo "<script>alert('Bhai Username password galat dala hai!');</script>";

    }
  } elseif ($action == "signup") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      echo "<script>alert('Email pahle se hi hai!');</script>";
    } else {
      $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, address, username, password) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssss", $name, $email, $phone_number, $address, $username, $password);
      $stmt->execute();
      $userId = $stmt->insert_id;
      $_SESSION['id'] = $userId;
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      $_SESSION['phone_number'] = $phone_number;
      $_SESSION['address'] = $address;
    }

    $stmt->close();
    header("Location: index.php");
    exit();
  } elseif ($action == "logout") {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
  }
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
        <?php if (!empty($email)): ?>
          <p>Welcome, <?php echo $name; ?></p>
          <form action="index.php" method="POST" style="display:inline;">
            <input type="hidden" name="action" value="logout">
            <button class="logout" type="submit">Logout</button>
          </form>
        <?php else: ?>
          <a href="#" onclick="openModal('login-modal')">Sign In / Sign Up</a>
        <?php endif; ?>
      </div>
    </header>
  </nav>

  <div id="login-modal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal('login-modal')">&times;</span>
      <h2>Login</h2>
      <form action="index.php" method="POST">
        <input type="hidden" name="action" value="login">
        <label for="login-username">Username</label>
        <input type="text" id="login-username" name="username" required>

        <label for="login-password">Password</label>
        <input type="password" id="login-password" name="password" required>

        <?php if (isset($_SESSION['loginError'])): ?>
          <p class="error"><?php echo $_SESSION['loginError']; ?></p>
          <?php unset($_SESSION['loginError']); ?>
        <?php endif; ?>

        <button type="submit">Login</button>
      </form>
      <p>Don't have an account? <a href="#" onclick="openModal('signup-modal'); closeModal('login-modal')">Sign up
          here</a></p>
    </div>
  </div>

  <div id="signup-modal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal('signup-modal')">&times;</span>
      <h2>Sign Up</h2>
      <form action="index.php" method="POST">
        <input type="hidden" name="action" value="signup">
        <label for="signup-name">Name</label>
        <input type="text" id="signup-name" name="name" required>

        <label for="signup-email">Email</label>
        <input type="email" id="signup-email" name="email" required>

        <label for="signup-phone">Phone Number</label>
        <input type="tel" id="signup-phone" name="phone_number" required>

        <label for="signup-address">Address</label>
        <input type="text" id="signup-address" name="address" required>

        <label for="signup-username">Username</label>
        <input type="text" id="signup-username" name="username" required>

        <label for="signup-password">Password</label>
        <input type="password" id="signup-password" name="password" required>

        <?php if (isset($_SESSION['signupError'])): ?>
          <p class="error"><?php echo $_SESSION['signupError']; ?></p>
          <?php unset($_SESSION['signupError']); ?>
        <?php endif; ?>

        <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="#" onclick="openModal('login-modal'); closeModal('signup-modal')">Log in
          here</a></p>
    </div>
  </div>

  <div class="home-page">
    <div class="home-details">
      <div class="details-container">
        <h1>Simple, Personal</h1>
        <button>Shop Now</button>
      </div>
    </div>
    <div class="home-image"><img
        src="https://images.unsplash.com/photo-1730829807497-9c5b8c9c41c4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
        alt="stock">
    </div>
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
    <h1>Our Products</h1>
    <div class="products-container">
      <?php
      $sql = "SELECT product_id, product_name, product_description, img_url, price FROM products";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='product-card'>";
          echo "<img src='" . $row["img_url"] . "' alt='" . $row["product_name"] . "'>";
          echo "<h2 class='product-title'>" . $row["product_name"] . "</h2>";
          echo "<p>Price: ₹" . $row["price"] . "</p>";
          echo "<p>" . $row["product_description"] . "</p>";
          ;
          echo "<p class='buy-btn'>Buy Now</p>";
          echo "</div>";
        }
      } else {
        echo "<p>No products found</p>";
      }
      ?>
    </div>
  </div>

  <script>
    function openModal(modalId) {
      document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function (event) {
      const modals = document.querySelectorAll('.modal');
      modals.forEach((modal) => {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
    };
  </script>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <script src="script.js"></script>
</body>

</html>