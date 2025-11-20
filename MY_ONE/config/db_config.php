<?php
session_start();

/* ------------------------------
   DATABASE CONNECTION
--------------------------------*/
$host = "localhost";
$user = "root";
$pass = "";
$db = "dulara_hettiarachchi";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ------------------------------
   REGISTER PROCESS
--------------------------------*/
$register_msg = "";

if (isset($_POST['register'])) {

    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $dob = $_POST['dob'];
    $nic = trim($_POST['nic']);
    $school = trim($_POST['school']);
    $ol_year = $_POST['ol_year'];
    $district = $_POST['district'];
    $address = trim($_POST['address']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $register_msg = "Passwords do not match!";
    } else {

        // Check if mobile exists
        $check = $conn->prepare("SELECT id FROM users WHERE mobile=?");
        $check->bind_param("s", $mobile);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $register_msg = "This mobile number is already registered!";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("
                INSERT INTO users(first_name, last_name, dob, nic, school, ol_year, district, address, mobile, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssssssss",
                $first,
                $last,
                $dob,
                $nic,
                $school,
                $ol_year,
                $district,
                $address,
                $mobile,
                $hashed
            );

            if ($stmt->execute()) {
                $register_msg = "Registration successful! You can now log in.";
            } else {
                $register_msg = "Error during registration!";
            }
        }
    }
}
?>