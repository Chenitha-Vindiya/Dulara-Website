<?php
// index.php – Homepage for Tuition Website
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dulara Hettiarachchi</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/form.css">
  <link rel="stylesheet" href="assets/css/time-line.css">
  <link rel="stylesheet" href="assets/css/social-icons.css">
  <script src="assets/js/app.js" defer></script>
</head>

<body>

  <!-- Scroll to Top Button -->
  <button id="scrollTopBtn" title="Go to top">▲</button>

  <!-- HEADER / NAVIGATION -->
  <header class="header">
    <div class="container nav-container">
      <div class="logo">
        <img src="assets/images/logo.png" alt="logo">
        <span class="logo-text">Dulara Hettiarachchi</span>
      </div>
      <nav class="nav-links" id="navLinks">
        <a href="index.php" class="active">Home</a>
        <a href="classes.php">Classes</a>
        <a href="join.php">Tutes</a>
        <a href="#Updates">Updates</a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="student/dashboard.php">Dashboard</a>
          <a href="logout.php" class="btn-small">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn-small">Sign in</a>
        <?php endif; ?>
      </nav>
      <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    </div>
  </header>

  <div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <button class="close-menu-btn" onclick="toggleMenu()">×</button>
    <div class="menu-content">

      <a href="index.php" class="changelog-link">Home</a>
      <a href="classes.php" class="changelog-link">Classes</a>
      <a href="tutes.php" class="changelog-link">Tutes</a>
      <a href="#Updates" class="changelog-link" onclick="toggleMenu()">Updates</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="student/dashboard.php">Dashboard</a>
        <a href="logout.php" class="btn-full-width">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn-full-width">Sign In</a>
      <?php endif; ?>

      <!-- <a href="app.php" class="btn-enter-app">Enter App &rarr;</a> -->

      <div class="menu-icon-area">
        <div class="menu-icon">
        </div>
      </div>

      <div class="social-icons">
        <a href="#"><i class="fab fa-github">GIT</i></a>
        <a href="#"><i class="fab fa-discord">DISC</i></a>
        <a href="#"><i class="fab fa-youtube">YT</i></a>
      </div>

    </div>
  </div>