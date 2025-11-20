<?php
/* ------------------------------
   LOGIN PROCESS
--------------------------------*/
$login_msg = "";

if (isset($_POST['login'])) {

  $mobile = trim($_POST['mobile']);
  $password = $_POST['password'];

  // Check mobile number
  $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM users WHERE mobile=?");
  $stmt->bind_param("s", $mobile);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {

      $_SESSION['user_id'] = $row['id'];
      $_SESSION['fullname'] = $row['first_name'] . " " . $row['last_name'];

      header("Location: modules.php");
      exit;
    } else {
      $login_msg = "Incorrect password!";
    }
  } else {
    $login_msg = "Mobile number not found!";
  }
}
?>

<div class="auth">
  <?php if ($isLoggedIn): ?>
    <div class="panel" style="padding:8px 12px;border-radius:12px;display:flex;align-items:center;gap:10px">
      <div style="font-weight:700;color:var(--navy)">
        <?php echo htmlentities($_SESSION['user']['name']); ?>
      </div>
      <form method="post" style="margin:0" action="config\db_config.php">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-ghost" type="submit">Logout</button>
      </form>
    </div>
  <?php else: ?>


    <div class="container-form">
      <!-- LOGIN BOX -->
      <div id="login-box">
        <h2>Login</h2>
        <div class="msg"><?= $login_msg ?></div>

        <form method="POST">
          <input type="text" name="mobile" placeholder="Mobile Number" required>
          <input type="password" name="password" placeholder="Password" required>

          <button class="submit" type="submit" name="login">Login</button>
        </form>

        <div class="toggle" onclick="showRegister()">Don't have an account? Register</div>
      </div>

      <!-- REGISTER BOX -->
      <div id="register-box" style="display:none;">
        <h2>Register</h2>
        <div class="msg"><?= $register_msg ?></div>

        <form method="POST">

          <input type="text" name="first_name" placeholder="First Name" required>

          <input type="text" name="last_name" placeholder="Last Name" required>

          <label>Date of Birth</label>
          <input type="date" name="dob" required>

          <input type="text" name="nic" placeholder="NIC Number" required>

          <input type="text" name="school" placeholder="School" required>

          <label>O/L Year</label>
          <select name="ol_year" required>
            <option value="">Select O/L Year</option>
            <option value="2026">2026 O/L</option>
            <option value="2027">2027 O/L</option>
          </select>

          <label>District</label>
          <select name="district" required>
            <option value="">Select District</option>
            <option>Colombo</option>
            <option>Gampaha</option>
            <option>Kalutara</option>
            <option>Kandy</option>
            <option>Matale</option>
            <option>Nuwara Eliya</option>
            <option>Galle</option>
            <option>Matara</option>
            <option>Hambantota</option>
            <option>Jaffna</option>
            <option>Killinochchi</option>
            <option>Mannar</option>
            <option>Vavuniya</option>
            <option>Mullaitivu</option>
            <option>Batticaloa</option>
            <option>Ampara</option>
            <option>Trincomalee</option>
            <option>Kurunegala</option>
            <option>Puttalam</option>
            <option>Anuradhapura</option>
            <option>Polonnaruwa</option>
            <option>Badulla</option>
            <option>Monaragala</option>
            <option>Ratnapura</option>
            <option>Kegalle</option>
          </select>

          <textarea name="address" placeholder="Address" required></textarea>

          <input type="text" name="mobile" placeholder="Mobile Number" required>

          <input type="password" name="password" placeholder="Password" required>

          <input type="password" name="confirm" placeholder="Confirm Password" required>

          <button class="submit" type="submit" name="register">Register</button>
        </form>

        <div class="toggle" onclick="showLogin()">Already have an account? Login</div>
      </div>

    </div>

  <?php endif; ?>
</div>



<script>
  function showRegister() {
    document.getElementById("login-box").style.display = "none";
    document.getElementById("register-box").style.display = "block";
  }

  function showLogin() {
    document.getElementById("login-box").style.display = "block";
    document.getElementById("register-box").style.display = "none";
  }
</script>