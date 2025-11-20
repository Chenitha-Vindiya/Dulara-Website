<?php
// classes.php
session_start();

/*
  Simple demo classes page:
  - Save as classes.php
  - Ensure PHP has permission to create /uploads (script tries to create it)
  - This is a demo — replace simulated login/payment with real backend / verification as needed.
*/

// Create uploads folder if missing
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0755, true);
}

/* -------------------------
   Handle form actions (POST)
   ------------------------- */
$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulated simple login/logout
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        // For demo: accept any email, set session logged in
        $_SESSION['user'] = [
            'name' => $_POST['name'] ?: 'Unkown',
            'email' => $_POST['email'] ?: 'unkown@example.com'
        ];
        // Ensure unlockedModules array exists
        if (!isset($_SESSION['unlockedModules'])) {
            $_SESSION['unlockedModules'] = [
                'ict' => [],
                'accounting' => []
            ];
        }
        $flash = "Logged in as " . htmlentities($_SESSION['user']['name']);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
        unset($_SESSION['user']);
        // keep unlocked months in session for demo or remove as you wish
        $flash = "Logged out.";
    }

    // Upload slip: simulate verification and unlock month immediately
    if (isset($_POST['action']) && $_POST['action'] === 'uploadSlip') {
        $subject = $_POST['subject'] ?? 'ict';
        $month = $_POST['month'] ?? '';
        if ($month === '') {
            $flash = "Please choose a month.";
        } elseif (!isset($_FILES['slip']) || $_FILES['slip']['error'] !== UPLOAD_ERR_OK) {
            $flash = "Please upload a valid file for the payment slip.";
        } else {
            // Save file
            $f = $_FILES['slip'];
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($f['name'], PATHINFO_FILENAME));
            $target = $uploadDir . '/' . $safeName . '_' . time() . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $target)) {
                // For demo: mark month as unlocked (you would verify before unlocking in production)
                if (!isset($_SESSION['unlockedModules'])) {
                    $_SESSION['unlockedModules'] = ['ict' => [], 'accounting' => []];
                }
                if (!in_array($month, $_SESSION['unlockedModules'][$subject])) {
                    $_SESSION['unlockedModules'][$subject][] = $month;
                }
                $flash = "Payment slip uploaded and month unlocked for {$subject} / {$month}. (Demo: unlocked immediately)";
            } else {
                $flash = "Failed to save uploaded file. Check server permissions.";
            }
        }
    }

    // Online payment simulated (no real payment integration)
    if (isset($_POST['action']) && $_POST['action'] === 'payOnline') {
        $subject = $_POST['subject'] ?? 'ict';
        $month = $_POST['month'] ?? '';
        $cardName = $_POST['cardName'] ?? '';
        $cardNumber = $_POST['cardNumber'] ?? '';
        // Basic validation demo
        if ($month === '' || $cardName === '' || $cardNumber === '') {
            $flash = "Please fill payment info.";
        } else {
            // Simulate payment success and unlock
            if (!isset($_SESSION['unlockedModules'])) {
                $_SESSION['unlockedModules'] = ['ict' => [], 'accounting' => []];
            }
            if (!in_array($month, $_SESSION['unlockedModules'][$subject])) {
                $_SESSION['unlockedModules'][$subject][] = $month;
            }
            $flash = "Payment successful. {$month} unlocked for {$subject} (Demo).";
        }
    }
}

// Helper: is logged in
$isLoggedIn = isset($_SESSION['user']);

// Sample month definitions and sample videos
$months = [
    ['key' => 'january', 'label' => 'January', 'icon' => '❄️', 'lessons' => 8],
    ['key' => 'february', 'label' => 'February', 'icon' => '💝', 'lessons' => 7],
    ['key' => 'march', 'label' => 'March', 'icon' => '🌸', 'lessons' => 9],
    ['key' => 'april', 'label' => 'April', 'icon' => '🌧️', 'lessons' => 8],
    ['key' => 'may', 'label' => 'May', 'icon' => '🌺', 'lessons' => 10],
    ['key' => 'june', 'label' => 'June', 'icon' => '☀️', 'lessons' => 8],
    ['key' => 'july', 'label' => 'July', 'icon' => '🌊', 'lessons' => 9],
    ['key' => 'august', 'label' => 'August', 'icon' => '🎆', 'lessons' => 7],
    ['key' => 'september', 'label' => 'September', 'icon' => '🍂', 'lessons' => 8],
    ['key' => 'october', 'label' => 'October', 'icon' => '🎃', 'lessons' => 9],
    ['key' => 'november', 'label' => 'November', 'icon' => '🍁', 'lessons' => 8],
    ['key' => 'december', 'label' => 'December', 'icon' => '🎄', 'lessons' => 10],
];

// Sample video list (will be repeated as needed). Replace embed IDs with your unlisted YouTube IDs.
$sampleVideos = [
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Introduction to the Topic', 'duration' => '45:23', 'views' => '1.2K'],
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Key Concepts Explained', 'duration' => '38:15', 'views' => '980'],
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Practical Examples', 'duration' => '52:40', 'views' => '1.5K'],
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Problem Solving Session', 'duration' => '41:30', 'views' => '890'],
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Advanced Techniques', 'duration' => '48:20', 'views' => '750'],
    ['id' => 'dQw4w9WgXcQ', 'title' => 'Revision & Summary', 'duration' => '35:45', 'views' => '1.1K']
];

// Which months unlocked in session (structure)
// Example structure:
// $_SESSION['unlockedModules'] = [
//   'ict' => ['january','february','march'],
//   'accounting' => ['january']
// ];

if (!isset($_SESSION['unlockedModules'])) {
    $_SESSION['unlockedModules'] = ['ict' => [], 'commerce' => []];
}
$unlocked = $_SESSION['unlockedModules'];

/* -------------------------
   Render HTML below
   ------------------------- */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Classes - Dulara Hettiarachchi</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/classes.css">
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
                <a href="index.php">Home</a>
                <a href="classes.php" class="active">Classes</a>
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

    <div class="auth">
        <?php if ($isLoggedIn): ?>
            <div class="panel" style="padding:8px 12px;border-radius:12px;display:flex;align-items:center;gap:10px">
                <div style="font-weight:700;color:var(--navy)">
                    <?php echo htmlentities($_SESSION['user']['name']); ?>
                </div>
                <form method="post" style="margin:0">
                    <input type="hidden" name="action" value="logout">
                    <button class="btn btn-ghost" type="submit">Logout</button>
                </form>
            </div>
        <?php else: ?>
            <div class="panel" style="padding:10px;border-radius:12px;">
                <form method="post" class="form-inline" style="margin:0">
                    <input type="hidden" name="action" value="login">
                    <input class="input" name="name" placeholder="Your name">
                    <input class="input" name="email" placeholder="Email">
                    <button class="btn btn-login" type="submit">Login</button>
                </form>
                <div style="margin-top:8px;font-size:0.85rem;color:var(--gray)">Demo login — no password. For
                    production, integrate real auth.</div>
            </div>
        <?php endif; ?>
    </div>


    <?php if ($flash): ?>
        <div class="flash">
            <?php echo htmlentities($flash); ?>
        </div>
    <?php endif; ?>

    <div class="panel">
        <!-- Subject Toggle -->
        <div class="subject-toggle">
            <div class="toggle-wrapper ict" id="subjectToggle">
                <div class="toggle-slider"></div>
                <div class="toggle-options">
                    <div class="toggle-option" data-subject="ict">🖥️ O/L ICT</div>
                    <div class="toggle-option" data-subject="accounting">📊 O/L Accounting</div>
                </div>
            </div>
        </div>

        <!-- ICT Content -->
        <div class="content-section active" id="ict-content">
            <?php if (!$isLoggedIn): ?>
                <div class="login-notice">
                    <div class="icon">⚠️</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Guest Access</div>
                        <div class="small">Please upload your payment slip or make an online payment to unlock lessons.
                        </div>
                        <div class="payment-buttons" style="margin-top:8px">
                            <!-- open upload modal triggers in JS; use forms below for demo non-JS fallback -->
                            <button class="btn btn-primary" onclick="openUploadModal('ict')">📤 Upload Payment
                                Slip</button>
                            <button class="btn btn-ghost" onclick="openPaymentModal('ict')">💳 Online Payment</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="login-notice"
                    style="background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);border-left:5px solid var(--success)">
                    <div class="icon">✅</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Logged in</div>
                        <div class="small">You can access months you've paid for or uploaded slips for.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="months-grid" id="ict-months">
                <?php foreach ($months as $m):
                    $key = $m['key'];
                    $isUnlocked = $isLoggedIn && in_array($key, $unlocked['ict']);
                    $statusClass = $isUnlocked ? 'unlocked' : 'locked';
                    $statusIcon = $isUnlocked ? '✓' : '🔒';
                ?>
                    <div class="month-bar <?php echo $statusClass; ?>" data-month="<?php echo $key; ?>"
                        data-subject="ict">
                        <div class="month-header" onclick="toggleMonth(this)">
                            <div class="month-info">
                                <div class="month-icon">
                                    <?php echo $m['icon']; ?>
                                </div>
                                <div class="month-text">
                                    <h3>
                                        <?php echo $m['label']; ?> 2025
                                    </h3>
                                    <p>
                                        <?php echo $m['lessons']; ?> Lessons Available
                                    </p>
                                </div>
                            </div>
                            <div class="month-status">
                                <span>
                                    <?php echo $statusIcon; ?>
                                </span>
                                <span class="expand-icon">▼</span>
                            </div>
                        </div>
                        <div class="videos-section">
                            <?php if ($isUnlocked): ?>
                                <div class="videos-grid">
                                    <?php
                                    // show up to lessons but sampleVideos limited; repeat if necessary
                                    $count = $m['lessons'];
                                    for ($i = 0; $i < $count; $i++):
                                        $video = $sampleVideos[$i % count($sampleVideos)];
                                    ?>
                                        <div class="video-card">
                                            <div class="video-thumbnail">
                                                <iframe src="https://www.youtube.com/embed/<?php echo $video['id']; ?>?rel=0"
                                                    title="<?php echo htmlentities($video['title']); ?>"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                            <div class="video-info">
                                                <h4>Lesson
                                                    <?php echo ($i + 1); ?>:
                                                    <?php echo htmlentities($video['title']); ?>
                                                </h4>
                                                <div class="video-meta"><span>⏱️
                                                        <?php echo $video['duration']; ?>
                                                    </span><span>👁️
                                                        <?php echo $video['views']; ?>
                                                    </span></div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            <?php else: ?>
                                <div class="locked-overlay">
                                    <div class="locked-icon">🔒</div>
                                    <h4 style="color:var(--navy);margin-bottom:8px">Content Locked</h4>
                                    <p class="muted">Upload your payment slip or make an online payment to access this
                                        month's lessons.</p>
                                    <div class="payment-buttons">
                                        <button class="btn btn-primary"
                                            onclick="openUploadModal('ict','<?php echo $key; ?>')">📤 Upload Slip</button>
                                        <button class="btn btn-ghost"
                                            onclick="openPaymentModal('ict','<?php echo $key; ?>')">💳 Pay Online</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Accounting Content (same structure) -->
        <div class="content-section" id="accounting-content" style="display:none;margin-top:18px">
            <?php if (!$isLoggedIn): ?>
                <div class="login-notice">
                    <div class="icon">⚠️</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Guest Access</div>
                        <div class="small">Please upload your payment slip or make an online payment to unlock lessons.
                        </div>
                        <div class="payment-buttons" style="margin-top:8px">
                            <button class="btn btn-primary" onclick="openUploadModal('accounting')">📤 Upload Payment
                                Slip</button>
                            <button class="btn btn-ghost" onclick="openPaymentModal('accounting')">💳 Online
                                Payment</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="login-notice"
                    style="background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);border-left:5px solid var(--success)">
                    <div class="icon">✅</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Logged in</div>
                        <div class="small">You can access months you've paid for or uploaded slips for.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="months-grid" id="accounting-months">
                <?php foreach ($months as $m):
                    $key = $m['key'];
                    $isUnlocked = $isLoggedIn && in_array($key, $unlocked['accounting']);
                    $statusClass = $isUnlocked ? 'unlocked' : 'locked';
                    $statusIcon = $isUnlocked ? '✓' : '🔒';
                ?>
                    <div class="month-bar <?php echo $statusClass; ?>" data-month="<?php echo $key; ?>"
                        data-subject="accounting">
                        <div class="month-header" onclick="toggleMonth(this)">
                            <div class="month-info">
                                <div class="month-icon">
                                    <?php echo $m['icon']; ?>
                                </div>
                                <div class="month-text">
                                    <h3>
                                        <?php echo $m['label']; ?> 2025
                                    </h3>
                                    <p>
                                        <?php echo $m['lessons']; ?> Lessons Available
                                    </p>
                                </div>
                            </div>
                            <div class="month-status">
                                <span>
                                    <?php echo $statusIcon; ?>
                                </span>
                                <span class="expand-icon">▼</span>
                            </div>
                        </div>
                        <div class="videos-section">
                            <?php if ($isUnlocked): ?>
                                <div class="videos-grid">
                                    <?php
                                    $count = $m['lessons'];
                                    for ($i = 0; $i < $count; $i++):
                                        $video = $sampleVideos[$i % count($sampleVideos)];
                                    ?>
                                        <div class="video-card">
                                            <div class="video-thumbnail">
                                                <iframe src="https://www.youtube.com/embed/<?php echo $video['id']; ?>?rel=0"
                                                    title="<?php echo htmlentities($video['title']); ?>"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                            <div class="video-info">
                                                <h4>Lesson
                                                    <?php echo ($i + 1); ?>:
                                                    <?php echo htmlentities($video['title']); ?>
                                                </h4>
                                                <div class="video-meta"><span>⏱️
                                                        <?php echo $video['duration']; ?>
                                                    </span><span>👁️
                                                        <?php echo $video['views']; ?>
                                                    </span></div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            <?php else: ?>
                                <div class="locked-overlay">
                                    <div class="locked-icon">🔒</div>
                                    <h4 style="color:var(--navy);margin-bottom:8px">Content Locked</h4>
                                    <p class="muted">Upload your payment slip or make an online payment to access this
                                        month's lessons.</p>
                                    <div class="payment-buttons">
                                        <button class="btn btn-primary"
                                            onclick="openUploadModal('accounting','<?php echo $key; ?>')">📤 Upload
                                            Slip</button>
                                        <button class="btn btn-ghost"
                                            onclick="openPaymentModal('accounting','<?php echo $key; ?>')">💳 Pay
                                            Online</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    </div>

    <!-- Hidden forms (server-handled) for upload and payment -->
    <!-- Upload slip form -->
    <form id="uploadFormPC" method="post" enctype="multipart/form-data" style="display:none">
        <input type="hidden" name="action" value="uploadSlip">
        <input type="hidden" name="subject" id="uploadSubject">
        <input type="hidden" name="month" id="uploadMonth">
        <input type="file" name="slip" id="uploadSlipFile">
    </form>

    <!-- Payment form -->
    <form id="payFormPC" method="post" style="display:none">
        <input type="hidden" name="action" value="payOnline">
        <input type="hidden" name="subject" id="paySubject">
        <input type="hidden" name="month" id="payMonth">
        <input type="hidden" name="cardName" id="payCardName">
        <input type="hidden" name="cardNumber" id="payCardNumber">
    </form>

    <!-- Upload Modal / Payment Modal (client-side nicer UI) -->
    <div id="modalRoot"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:2000">
        <div style="background:white;border-radius:12px;padding:18px;min-width:320px;max-width:520px">
            <div id="modalContent"></div>
            <div style="display:flex;gap:8px;margin-top:12px;justify-content:flex-end">
                <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" id="modalPrimaryBtn">Submit</button>
            </div>
        </div>
    </div>

    <script>
        // JS: toggle, expand/collapse, modal actions
        (function() {
            const toggle = document.getElementById('subjectToggle');
            const options = toggle.querySelectorAll('.toggle-option');
            let current = 'ict';
            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const subject = opt.dataset.subject;
                    switchSubject(subject);
                });
            });

            function switchSubject(subject) {
                current = subject;
                toggle.className = 'toggle-wrapper ' + subject;
                document.getElementById('ict-content').style.display = (subject === 'ict') ? 'block' : 'none';
                document.getElementById('accounting-content').style.display = (subject === 'accounting') ? 'block' : 'none';
            }
            window.switchSubject = switchSubject;
        })();

        // Expand/collapse month
        function toggleMonth(header) {
            const monthBar = header.closest('.month-bar');
            // If locked show alert
            if (monthBar.classList.contains('locked')) {
                alert('This month is locked. Please login or make a payment to unlock.');
                // You could open modal automatically
                return;
            }
            // Close other expanded
            document.querySelectorAll('.month-bar.expanded').forEach(bar => {
                if (bar !== monthBar) bar.classList.remove('expanded');
            });
            monthBar.classList.toggle('expanded');
        }

        // Modal helpers (client side)
        function openUploadModal(subject, month = '') {
            // If user is not logged in we still allow upload (guest can upload)
            showModalUpload(subject, month);
        }

        function openPaymentModal(subject, month = '') {
            showModalPayment(subject, month);
        }

        function closeModal() {
            document.getElementById('modalRoot').style.display = 'none';
        }

        function showModalUpload(subject, month) {
            const root = document.getElementById('modalRoot');
            const content = document.getElementById('modalContent');
            content.innerHTML = `
      <h3 style="margin-bottom:8px">Upload Payment Slip — ${subject.toUpperCase()}</h3>
      <div style="font-size:0.95rem;color:#475569;margin-bottom:8px">Choose the month and select payment slip image/PDF.</div>
      <div style="display:grid;gap:8px">
        <label style="font-weight:600">Month</label>
        <select id="modalUploadMonth" style="padding:8px;border:1px solid #e6eef8;border-radius:8px">
          <option value="">Select month...</option>
          <option value="january">January</option><option value="february">February</option><option value="march">March</option>
          <option value="april">April</option><option value="may">May</option><option value="june">June</option>
          <option value="july">July</option><option value="august">August</option><option value="september">September</option>
          <option value="october">October</option><option value="november">November</option><option value="december">December</option>
        </select>
        <label style="font-weight:600">Payment Slip (image or PDF)</label>
        <input id="modalUploadFile" type="file" accept="image/*,.pdf" />
      </div>
    `;
            const primary = document.getElementById('modalPrimaryBtn');
            primary.onclick = function() {
                const m = document.getElementById('modalUploadMonth').value;
                const f = document.getElementById('modalUploadFile').files[0];
                if (!m) {
                    alert('Select a month');
                    return;
                }
                if (!f) {
                    alert('Choose a file');
                    return;
                }
                // submit via hidden form to server
                const uploadForm = document.getElementById('uploadFormPC');
                document.getElementById('uploadSubject').value = subject;
                document.getElementById('uploadMonth').value = m;
                document.getElementById('uploadSlipFile').files = document.getElementById('modalUploadFile').files;
                uploadForm.submit();
            };
            root.style.display = 'flex';
        }

        function showModalPayment(subject, month) {
            const root = document.getElementById('modalRoot');
            const content = document.getElementById('modalContent');
            content.innerHTML = `
      <h3 style="margin-bottom:8px">Online Payment — ${subject.toUpperCase()}</h3>
      <div style="font-size:0.95rem;color:#475569;margin-bottom:8px">This demo simulates an online payment (no real charge).</div>
      <div style="display:grid;gap:8px">
        <label style="font-weight:600">Month</label>
        <select id="modalPayMonth" style="padding:8px;border:1px solid #e6eef8;border-radius:8px">
          <option value="">Select month...</option>
          <option value="january">January</option><option value="february">February</option><option value="march">March</option>
          <option value="april">April</option><option value="may">May</option><option value="june">June</option>
          <option value="july">July</option><option value="august">August</option><option value="september">September</option>
          <option value="october">October</option><option value="november">November</option><option value="december">December</option>
        </select>
        <label style="font-weight:600">Cardholder Name</label>
        <input id="modalCardName" placeholder="Full name on card" style="padding:8px;border:1px solid #e6eef8;border-radius:8px" />
        <label style="font-weight:600">Card Number (demo)</label>
        <input id="modalCardNumber" placeholder="1234 5678 9012 3456" style="padding:8px;border:1px solid #e6eef8;border-radius:8px" />
      </div>
    `;
            const primary = document.getElementById('modalPrimaryBtn');
            primary.onclick = function() {
                const m = document.getElementById('modalPayMonth').value;
                const n = document.getElementById('modalCardName').value;
                const c = document.getElementById('modalCardNumber').value;
                if (!m || !n || !c) {
                    alert('Please complete payment fields');
                    return;
                }
                const payForm = document.getElementById('payFormPC');
                document.getElementById('paySubject').value = subject;
                document.getElementById('payMonth').value = m;
                document.getElementById('payCardName').value = n;
                document.getElementById('payCardNumber').value = c;
                payForm.submit();
            };
            root.style.display = 'flex';
        }

        // Close modal when clicking the overlay
        document.getElementById('modalRoot').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // small enhancement: expand first unlocked month for logged in user
        window.addEventListener('load', function() {
            const firstUnlocked = document.querySelector('.month-bar.unlocked');
            if (firstUnlocked) firstUnlocked.classList.add('expanded');
            // stop pointer events on locked month headers? (we still want alert)
        });
    </script>

    <?php include 'inc/footer.php'; ?>