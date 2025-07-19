<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Confirmation</title>
  <style>
    body {
      font-family: Arial;
      background: linear-gradient(to bottom right,  #476a93, #304a6a);
      margin: 0;
      padding: 0;
      
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
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
      background-color: rgba(27, 39, 53, 0.85); 
      max-width: 500px;
      margin: 60px auto;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(255, 215, 0, 0.3);
      width: 600px;
      height:600px;
      color: white;
    }

    h2 {
      text-align: center;
      color: #ffdb00;
      margin-bottom: 25px;
    }

    p {
      font-size: 16px;
      margin-bottom: 12px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    strong {
      display: inline-block;
      width: 120px;
      color: gold;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Registration Confirmation</h2>

<?php
require_once 'classes/Database.php';
require_once 'classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];
    $hobbies = "";
    if (!empty($_POST['hobbies'])) {
        $hobbies = implode(", ", $_POST['hobbies']);
    }


    if ($password !== $confirm_password) {
        echo "<p class='error'>❌ Error: Passwords do not match!</p>";
        exit;
    }

    // Connect to database
    $db = new Database();
    try{
    $userObj = new User($db->connect());
    $userObj->register($fullname, $email, $password, $gender, $hobbies, $country);
    echo "<p class='success'>✔ Registration successful!</p>";
    }catch (Exception $e) {
    echo "<p class='error'>❌ " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
    }
   

    $success = true;
     $data = [
                'fullname' => $fullname,
                'email' => $email,
                'gender' => $gender,
                'hobbies' => $hobbies,
                'country' => $country
     ];
     
    // Display entered info
    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($data['fullname']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</p>";
    echo "<p><strong>Gender:</strong> " . htmlspecialchars($data['gender']) . "</p>";
    echo "<p><strong>Hobbies:</strong> " . htmlspecialchars($data['hobbies'] ?: "None") . "</p>";
    echo "<p><strong>Country:</strong> " . htmlspecialchars($data['country']) . "</p>";
$db->Connect()->close();
    }
?>

</div>

</body>
</html>
