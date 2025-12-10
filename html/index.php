<?php
session_start();
require_once '../config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if (empty($username) || empty($password)) {
    $error = "Please fill in both fields.";
  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password == $user['password_']) {
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      // Redirect to dashboard
     if($user['role'] == 'MAO'){
       header("Location: ./moa/dashboard.php?");
        exit;
     }elseif($user['role'] == 'Coordinator'){
        header("Location: ./coordinator/dashboard.php");
        exit;
     }elseif($user['role'] == 'Vet'){
        header("Location: ./veterinarian/dashboard.php");
        exit;
     }
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ALAGANG BARAS - Livestock Management System</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/styles/layouts/index.css" />
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="login-container">
            <!-- Logo Section -->
            <div class="logo-container">
              <div class="logo">
                <img src="../assets/images/64c57e19-d2a4-42cf-9a5b-4b35dd14f9f7.png" alt="">
              </div>
              <div class="logo secondary">
                <img src="../assets/images/9302dfc3-307b-42b1-a9d2-8c31dc0cb9c6.png" alt="">
              </div>
              <div class="logo tertiary">
                <img src="../assets/images/c567f4c0-7403-4ca0-8556-5bac257c9190.png" alt="">
              </div>
            </div>

            <!-- Title -->
            <h1 class="system-title">ALAGANG BARAS</h1>
            <p class="system-subtitle">Livestock Management System</p>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="">
              <div class="form-floating mb-3">
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  name="username"
                  placeholder="Username"
                  required
                />
                <label for="username"
                  ><i class="fas fa-user me-2"></i>Username</label
                >
              </div>

              <div class="form-floating mb-3">
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Password"
                  required
                />
                <label for="password"
                  ><i class="fas fa-lock me-2"></i>Password</label
                >
              </div>

              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="rememberMe"
                />
                <label class="form-check-label" for="rememberMe">
                  Remember me
                </label>
              </div>

              <button type="submit" class="btn btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
              </button>
            </form>

            <div class="forgot-password">
              <p>
                Don't have an account?
                <a href="#">Sign up!</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/scripts/index.js"></script>
  </body>
</html>
