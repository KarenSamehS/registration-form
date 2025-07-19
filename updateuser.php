<!DOCTYPE html>
<html>
<head>
  <title>Update User</title>
  <style>
    .top-heading {
      top: 30px;
      width: 100%;
      text-align: center;
      color: gold;
      font-size: 36px;
      font-family: 'Segoe UI', sans-serif;
      z-index: 2;
      margin-bottom: 20px;
    }

    body {
      margin: 0;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1b2735, #090a0f);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      background-image: 
        radial-gradient(white 1px, transparent 1px),
        radial-gradient(gold 1px, transparent 1px),
        radial-gradient(white 1px, transparent 1px),
        radial-gradient(gold 1px, transparent 1px),
        radial-gradient(#ffd700 1px, transparent 1px),
        radial-gradient(white 1px, transparent 1px);
      background-size: 150px 150px;
      background-position: 
        20px 30px, 
        70px 90px, 
        50px 160px, 
        190px 110px,
        120px 200px,
        300px 80px;
      opacity: 0.4;
    }

    .container {
      background: rgba(27, 39, 53, 0.85);
      padding: 70px 30px;
      border-radius: 20px;
      border: 2px solid gold;
      box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
      width: 100%;
      max-width: 400px;
      box-sizing: border-box;
    }

    h2 {
      text-align: center;
      margin-bottom: 40px;
      color: gold;
      font-size: 36px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-top: 15px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    input[type="checkbox"],
    input[type="radio"] {
    vertical-align: middle;
    margin-right: 8px;
    accent-color: gold; 
    }

    label.inline-label {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }


    input[type="submit"] {
      margin-top: 25px;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background-color: gold;
      color: black;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }
    .success {
      color: #00ff00;
      text-align: center;
      margin-bottom: 10px;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }

    p {
      margin: 6px 0;
    }

    
  </style>
</head>
<body>
<div class="container">


<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
$db = new Database();
$conn = $db->connect();
$userObj = new User($conn);

if(!isset($_GET['id'])){
   echo "❌ No user ID provided!";
   exit;
}
$user_id=$_GET['id'];

if (!$userObj->loadById($user_id)) {
    echo "<p class='error'>❌ User not found!</p>";
    exit;
}

$existing_hobbies = explode(", ", $userObj->hobbies ?? "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$userObj->fullname = $_POST['fullname'];
$userObj->email = $_POST['email'];
$userObj->gender = $_POST['gender'];
$userObj->country = $_POST['country'];
$userObj->hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : "";


    // Validation
    if (!filter_var($userObj->email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error'>❌ Invalid email format.</p>";
    } else {
        if ($userObj->update()) {
            echo "<p class='success'>✔ User updated successfully!</p>";
        } else {
            echo "<p class='error'>❌ Update failed.</p>";
        }
    // Display entered info
    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($userObj->fullname) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($userObj->email) . "</p>";
    echo "<p><strong>Gender:</strong> " . htmlspecialchars($userObj->gender) . "</p>";
    echo "<p><strong>Hobbies:</strong> " . htmlspecialchars($userObj->hobbies ?: "None") . "</p>";
    echo "<p><strong>Country:</strong> " . htmlspecialchars($userObj->country) . "</p>";

    }}
?>
<h2 class="top-heading">Update User</h2>
<form method="POST">
  Full Name: <input type="text" name="fullname" value="<?= htmlspecialchars($userObj->fullname) ?>"><br>
  Email: <input type="email" name="email" value="<?= htmlspecialchars($userObj->email) ?>"><br>
<label>Gender:</label>
<label class="inline-label"><input type="radio" name="gender" value="Male" <?= $userObj->gender == 'Male' ? 'checked' : '' ?>> Male</label>
<label class="inline-label"><input type="radio" name="gender" value="Female" <?= $userObj->gender == 'Female' ? 'checked' : '' ?>> Female</label>

<label>Hobbies:</label>
<label class="inline-label"><input type="checkbox" name="hobbies[]" value="Reading" <?= in_array('Reading', $existing_hobbies) ? 'checked' : '' ?>> Reading</label>
<label class="inline-label"><input type="checkbox" name="hobbies[]" value="Traveling" <?= in_array('Traveling', $existing_hobbies) ? 'checked' : '' ?>> Traveling</label>
<label class="inline-label"><input type="checkbox" name="hobbies[]" value="Sports" <?= in_array('Sports', $existing_hobbies) ? 'checked' : '' ?>> Sports</label>


<label for="country">Country:</label>
   <select name="country" id="country" required>
     <option value="USA" <?= $userObj->country == 'USA' ? 'selected' : '' ?>>USA</option>
     <option value="UK" <?= $userObj->country == 'UK' ? 'selected' : '' ?>>UK</option>
     <option value="Germany" <?= $userObj->country == 'Germany' ? 'selected' : '' ?>>Germany</option>
     <option value="India" <?= $userObj->country == 'India' ? 'selected' : '' ?>>India</option>
     <option value="Egypt" <?= $userObj->country == 'Egypt' ? 'selected' : '' ?>>Egypt</option>
   </select>
<input type="submit" value="Update">
</form>
</body>
</html>