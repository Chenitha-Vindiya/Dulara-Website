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

    // Upload slip: simulate verification and unlock module immediately
    if (isset($_POST['action']) && $_POST['action'] === 'uploadSlip') {
        $subject = $_POST['subject'] ?? 'ict';
        $module = $_POST['module'] ?? '';
        if ($module === '') {
            $flash = "Please choose a module.";
        } elseif (!isset($_FILES['slip']) || $_FILES['slip']['error'] !== UPLOAD_ERR_OK) {
            $flash = "Please upload a valid file for the payment slip.";
        } else {
            // Save file
            $f = $_FILES['slip'];
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($f['name'], PATHINFO_FILENAME));
            $target = $uploadDir . '/' . $safeName . '_' . time() . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $target)) {
                // For demo: mark module as unlocked (you would verify before unlocking in production)
                if (!isset($_SESSION['unlockedModules'])) {
                    $_SESSION['unlockedModules'] = ['ict' => [], 'commerce' => []];
                }
                if (!in_array($module, $_SESSION['unlockedModules'][$subject])) {
                    $_SESSION['unlockedModules'][$subject][] = $module;
                }
                $flash = "Payment slip uploaded and {$module} module unlocked for {$subject} (Demo: unlocked immediately)";
            } else {
                $flash = "Failed to save uploaded file. Check server permissions.";
            }
        }
    }

    // Online payment simulated (no real payment integration)
    if (isset($_POST['action']) && $_POST['action'] === 'payOnline') {
        $subject = $_POST['subject'] ?? 'ict';
        $module = $_POST['module'] ?? '';
        $cardName = $_POST['cardName'] ?? '';
        $cardNumber = $_POST['cardNumber'] ?? '';
        // Basic validation demo
        if ($module === '' || $cardName === '' || $cardNumber === '') {
            $flash = "Please fill payment info.";
        } else {
            // Simulate payment success and unlock
            if (!isset($_SESSION['unlockedModules'])) {
                $_SESSION['unlockedModules'] = ['ict' => [], 'commerce' => []];
            }
            if (!in_array($module, $_SESSION['unlockedModules'][$subject])) {
                $_SESSION['unlockedModules'][$subject][] = $module;
            }
            $flash = "Payment successful. {$module} unlocked for {$subject} (Demo).";
        }
    }
}

// Helper: is logged in
$isLoggedIn = isset($_SESSION['user']);

// Sample module definitions and sample videos
$modulesICT = [
    ['key' => 'Paper_Discussion', 'label' => 'Paper Discusson | ප්‍රශ්න පත්‍ර සාකච්ඡාව', 'icon' => '❄️', 'lessons' => 5, 'cost' => 5000],
    ['key' => 'Introduction_to_ICT', 'label' => 'Introduction to ICT | තොරතුරු හා සන්නිවේදන තාක්ෂණය හැඳින්වීම', 'icon' => '💝', 'lessons' => 7, 'cost' => 5000],
    ['key' => 'march', 'label' => 'March', 'icon' => '🌸', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'april', 'label' => 'April', 'icon' => '🌧️', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'may', 'label' => 'May', 'icon' => '🌺', 'lessons' => 10, 'cost' => 5000],
    ['key' => 'june', 'label' => 'June', 'icon' => '☀️', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'july', 'label' => 'July', 'icon' => '🌊', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'august', 'label' => 'August', 'icon' => '🎆', 'lessons' => 7, 'cost' => 5000],
    ['key' => 'september', 'label' => 'September', 'icon' => '🍂', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'october', 'label' => 'October', 'icon' => '🎃', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'november', 'label' => 'November', 'icon' => '🍁', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'december', 'label' => 'December', 'icon' => '🎄', 'lessons' => 10, 'cost' => 5000],
];

$modulesCommerce = [
    ['key' => 'Paper_Discussion', 'label' => 'Paper Discusson', 'icon' => '❄️', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'february', 'label' => 'February', 'icon' => '💝', 'lessons' => 7, 'cost' => 5000],
    ['key' => 'march', 'label' => 'March', 'icon' => '🌸', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'april', 'label' => 'April', 'icon' => '🌧️', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'may', 'label' => 'May', 'icon' => '🌺', 'lessons' => 10, 'cost' => 5000],
    ['key' => 'june', 'label' => 'June', 'icon' => '☀️', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'july', 'label' => 'July', 'icon' => '🌊', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'august', 'label' => 'August', 'icon' => '🎆', 'lessons' => 7, 'cost' => 5000],
    ['key' => 'september', 'label' => 'September', 'icon' => '🍂', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'october', 'label' => 'October', 'icon' => '🎃', 'lessons' => 9, 'cost' => 5000],
    ['key' => 'november', 'label' => 'November', 'icon' => '🍁', 'lessons' => 8, 'cost' => 5000],
    ['key' => 'december', 'label' => 'December', 'icon' => '🎄', 'lessons' => 10, 'cost' => 5000],
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

// Which modules unlocked in session (structure)
/* Example structure:
$_SESSION['unlockedModules'] = [
  'ict' => ['january','february'],
  'commerce' => ['january']
];
*/
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - Dulara Hettiarachchi</title>
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/modules.css">
    <script src="assets/js/app.js" defer></script>
</head>

<body>

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
                <a href="classes.php">Classes</a>
                <a href="modules.php" class="active">Modules</a>
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

    <!-- Modules --------------------------------------------------------------------- -->

    <?php if ($flash): ?>
        <div class="notification-container">
            <div class="flash notification-hidden" id="flashNotification">
                <?php echo htmlentities($flash); ?>
                <button class="close-btn" onclick="closeNotification()">×</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="panel">
        <!-- Subject Toggle -->
        <div class="subject-toggle">
            <div class="toggle-wrapper ict" id="subjectToggle">
                <div class="toggle-slider"></div>
                <div class="toggle-options">
                    <div class="toggle-option" data-subject="ict">O/L ICT</div>
                    <div class="toggle-option" data-subject="commerce">O/L Commerce</div>
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
                        <div class="small">You can access modules you've paid for or uploaded slips for.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="module-grid" id="ict-modules">
                <?php foreach ($modulesICT as $m):
                    $key = $m['key'];
                    $isUnlocked = $isLoggedIn && in_array($key, $unlocked['ict']);
                    $statusClass = $isUnlocked ? 'unlocked' : 'locked';
                    $statusIcon = $isUnlocked ? '✓' : '🔒';
                ?>
                    <div class="module-bar <?php echo $statusClass; ?>" data-module="<?php echo $key; ?>"
                        data-subject="ict">
                        <div class="module-header" onclick="toggleModule(this)">
                            <div class="module-info">
                                <div class="module-icon">
                                    <!-- <?php echo $m['icon']; ?> -->
                                </div>
                                <div class="module-text">
                                    <h3>
                                        <?php echo $m['label']; ?>
                                    </h3>
                                    <p>
                                        <?php echo $m['lessons']; ?> Lessons Available
                                    </p>
                                </div>
                            </div>
                            <div class="module-status">
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
                                    <h4 style="color:var(--navy);margin-bottom:8px">Unlock Now!</h4>
                                    <p class="muted" style="font-size:1.1rem;font-weight:700">Price: LKR <?php echo number_format($m['cost']); ?></p>
                                    <p class="muted">Upload your payment slip or make an online payment to access this
                                        module.</p>
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

        <!-- Commerce Content -->
        <div class="content-section" id="commerce-content">
            <?php if (!$isLoggedIn): ?>
                <div class="login-notice">
                    <div class="icon">⚠️</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Guest Access</div>
                        <div class="small">Please upload your payment slip or make an online payment to unlock lessons.
                        </div>
                        <div class="payment-buttons" style="margin-top:8px">
                            <!-- open upload modal triggers in JS; use forms below for demo non-JS fallback -->
                            <button class="btn btn-primary" onclick="openUploadModal('commerce')">📤 Upload Payment
                                Slip</button>
                            <button class="btn btn-ghost" onclick="openPaymentModal('commerce')">💳 Online Payment</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="login-notice"
                    style="background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);border-left:5px solid var(--success)">
                    <div class="icon">✅</div>
                    <div>
                        <div style="font-weight:700;color:var(--navy)">Logged in</div>
                        <div class="small">You can access modules you've paid for or uploaded slips for.</div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="module-grid" id="commerce-modules">
                <?php foreach ($modulesCommerce as $m):
                    $key = $m['key'];
                    $isUnlocked = $isLoggedIn && in_array($key, $unlocked['commerce']);
                    $statusClass = $isUnlocked ? 'unlocked' : 'locked';
                    $statusIcon = $isUnlocked ? '✓' : '🔒';
                ?>
                    <div class="module-bar <?php echo $statusClass; ?>" data-module="<?php echo $key; ?>"
                        data-subject="commerce">
                        <div class="module-header" onclick="toggleModule(this)">
                            <div class="module-info">
                                <div class="module-icon">
                                    <!-- <?php echo $m['icon']; ?> -->
                                </div>
                                <div class="module-text">
                                    <h3>
                                        <?php echo $m['label']; ?>
                                    </h3>
                                    <p>
                                        <?php echo $m['lessons']; ?> Lessons Available
                                    </p>
                                </div>
                            </div>
                            <div class="module-status">
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
                                    <h4 style="color:var(--navy);margin-bottom:8px">Unlock Now!</h4>
                                    <p class="muted" style="font-size:1.1rem;font-weight:700">Price: LKR <?php echo number_format($m['cost']); ?></p>
                                    <p class="muted">Upload your payment slip or make an online payment to access this
                                        module.</p>
                                    <div class="payment-buttons">
                                        <button class="btn btn-primary"
                                            onclick="openUploadModal('commerce','<?php echo $key; ?>')">📤 Upload Slip</button>
                                        <button class="btn btn-ghost"
                                            onclick="openPaymentModal('commerce','<?php echo $key; ?>')">💳 Pay Online</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>






        <!-- Hidden forms (server-handled) for upload and payment -->
        <!-- Upload slip form -->
        <form id="uploadFormPC" method="post" enctype="multipart/form-data" style="display:none">
            <input type="hidden" name="action" value="uploadSlip">
            <input type="hidden" name="subject" id="uploadSubject">
            <input type="hidden" name="module" id="uploadModule">
            <input type="file" name="slip" id="uploadSlipFile">
        </form>

        <!-- Payment form -->
        <form id="payFormPC" method="post" style="display:none">
            <input type="hidden" name="action" value="payOnline">
            <input type="hidden" name="subject" id="paySubject">
            <input type="hidden" name="module" id="payModule">
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
                document.getElementById('commerce-content').style.display = (subject === 'commerce') ? 'block' : 'none';
            }
            window.switchSubject = switchSubject;

            switchSubject(current);

            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const subject = opt.dataset.subject;
                    switchSubject(subject);
                });
            });
        })();

        // Expand/collapse module
        function toggleModule(header) {
            const moduleBar = header.closest('.module-bar');
            // If locked show alert
            if (moduleBar.classList.contains('locked')) {
                alert('This module is locked. Please login or make a payment to unlock.');
                // You could open modal automatically
                return;
            }
            // Close other expanded
            document.querySelectorAll('.module-bar.expanded').forEach(bar => {
                if (bar !== moduleBar) bar.classList.remove('expanded');
            });
            moduleBar.classList.toggle('expanded');
        }




        // Function to show the notification (slide in)
        function showNotification() {
            const notification = document.getElementById('flashNotification');
            if (notification) {
                // 1. Remove the hidden class to start the slide-in transition
                notification.classList.remove('notification-hidden');
                notification.classList.add('notification-visible');

                // 2. Set a timer to close it after 5 seconds (standard duration)
                notification.timer = setTimeout(closeNotification, 5000);
            }
        }

        // Function to close the notification (slide out)
        function closeNotification() {
            const notification = document.getElementById('flashNotification');
            if (notification) {
                // Clear any pending auto-close timer
                clearTimeout(notification.timer);

                // Start the slide-out transition
                notification.classList.remove('notification-visible');
                notification.classList.add('notification-hidden');

                // Optional: Remove the element from DOM after the transition completes
                setTimeout(() => {
                    notification.parentElement.remove(); // Remove the container
                }, 500); // 500ms should be slightly longer than the CSS transition (0.4s)
            }
        }

        // Execute the show function once the page is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Check if the flash notification element exists in the HTML
            if (document.getElementById('flashNotification')) {
                showNotification();
            }
        });
    </script>


    <?php include 'inc/footer.php'; ?>