<?php
// index.php – Homepage for Tuition Website
session_start();

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Simulated simple login/logout
  if (isset($_POST['action']) && $_POST['action'] === 'login') {
    // For demo: accept any email, set session logged in
    $_SESSION['user'] = [
      'name' => $_POST['name'] ?: 'Unknown',
      'email' => $_POST['email'] ?: 'unknown@example.com'
    ];
    // Ensure unlockedModules array exists
    if (!isset($_SESSION['unlockedModules'])) {
      $_SESSION['unlockedModules'] = [
        'ict' => [],
        'commerce' => []
      ];
    }
    $flash = "Logged in as " . htmlentities($_SESSION['user']['name']);
  } elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
    unset($_SESSION['user']);
    // keep unlocked modules in session for demo or remove as you wish
    $flash = "Logged out.";
  }
}

$isLoggedIn = isset($_SESSION['user']);
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
        <a href="modules.php">Modules</a>
        <a href="join.php">Tutes</a>

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
      <a href="modules.php" class="changelog-link">Modules</a>
      <a href="tutes.php" class="changelog-link">Tutes</a>

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

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="hero-bg" style="background:  url(assets/images/classroom-bg-1.jpg) center/cover no-repeat, var(--navy);">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1>Welcome</h1>
          <p>Learn ICT & Accounting with expert guidance for your O/L success.</p>
          <button class="btn-primary">Register</button>
        </div>
      </div>
    </div>
  </section>

  <?php include 'inc/form.php'; ?>

  <?php include 'inc\social-icons.php'; ?>

  <!-- UPDATES -->
  <section class="slider-container">
    <div class="slider-header">
      <span>UPDATES</span>
    </div>

    <div class="slides">
      <div class="slide active">
        <img src="assets/images/classroom-bg-1.jpg" alt="test" style="max-width: 100px;">
        <h3>🎓 A/L 2025 Batch Enrolments Open Now!</h3>
        <p>Join our expert-led classes for Physics, Chemistry, and Combined Maths.</p>
        <div class="date">October 25, 2025</div>
      </div>
      <div class="slide">
        <h3>🧠 Free Physics Revision Class This Sunday!</h3>
        <p>Special free session by Prof. Nimal Silva — all students welcome.</p>
        <div class="date">October 28, 2025</div>
      </div>
      <div class="slide">
        <h3>📘 Grade 11 Mock Test Series Announced</h3>
        <p>Prepare for upcoming O/L exams with our full-length mock test series.</p>
        <div class="date">November 1, 2025</div>
      </div>
      <div class="slide">
        <h3>📢 Join Our Telegram for Daily Quiz Updates!</h3>
        <p>Stay sharp with daily quizzes and challenge questions.</p>
        <div class="date">November 3, 2025</div>
      </div>
    </div>

    <div class="dots">
      <div class="dot active" data-index="0"></div>
      <div class="dot" data-index="1"></div>
      <div class="dot" data-index="2"></div>
      <div class="dot" data-index="3"></div>
    </div>

    <button class="arrow arrow-left">‹</button>
    <button class="arrow arrow-right">›</button>
  </section>

  <script>
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.arrow-left');
    const nextBtn = document.querySelector('.arrow-right');
    let currentIndex = 0;
    const total = slides.length;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active', 'prev');
        if (i === index) slide.classList.add('active');
        else if (i < index) slide.classList.add('prev');
      });

      dots.forEach(dot => dot.classList.remove('active'));
      dots[index].classList.add('active');
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % total;
      showSlide(currentIndex);
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + total) % total;
      showSlide(currentIndex);
    }

    // Auto slide every 5 seconds
    let autoSlide = setInterval(nextSlide, 5000);

    // Dot navigation
    dots.forEach(dot => {
      dot.addEventListener('click', () => {
        currentIndex = parseInt(dot.getAttribute('data-index'));
        showSlide(currentIndex);
        resetInterval();
      });
    });

    // Arrow navigation
    nextBtn.addEventListener('click', () => {
      nextSlide();
      resetInterval();
    });
    prevBtn.addEventListener('click', () => {
      prevSlide();
      resetInterval();
    });

    function resetInterval() {
      clearInterval(autoSlide);
      autoSlide = setInterval(nextSlide, 5000);
    }
  </script>

  <?php include 'inc\time-line.php'; ?>

  <!-- FEATURES SECTION -->
  <section class="features container">
    <h2>Class Plan</h2>
    <div class="feature-grid">
      <div class="feature-card" style="padding-left: 50px;">
        <h3>O/L - ICT</h3>
        <p style="text-align: left; margin-top: 10px;">- Weekly Quizes and Quiz Discussion</p>
        <p style="text-align: left;">- Past Paper Discussion</p>
        <p style="text-align: left;">- Live Classes</p>
        <p style="text-align: left;">- Live Recording Access</p>
      </div>
      <div class="feature-card" style="padding-left: 50px;">
        <h3>O/L - Accounting</h3>
        <p style="text-align: left; margin-top: 10px;">- Weekly Quizes and Quiz Discussion</p>
        <p style="text-align: left;">- Past Paper Discussion</p>
        <p style="text-align: left;">- Live Classes</p>
        <p style="text-align: left;">- Live Recording Access</p>
      </div>
    </div>
  </section>

  <?php include 'test.html'; ?>

  <?php include 'inc/footer.php'; ?>