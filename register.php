<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Confirmation</title>
  <style>
    body {
      font-family: Arial;
      background: linear-gradient(to bottom right, #e0c3fc, #8ec5fc);
      margin: 0;
      padding: 0;
    }

    .container {
      background-color: #f9f4ff; /* lighter than #f3e9ff */
      max-width: 500px;
      margin: 60px auto;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      color: #333;
    }

    h2 {
      text-align: center;
      color: #4a148c;
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
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Registration Confirmation</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];

    if ($password !== $confirm_password) {
        echo "<p class='error'>❌ Error: Passwords do not match!</p>";
        exit;
    }

    // Connect to database
    $host = "localhost";
    $db = "registration_db";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("<p class='error'>❌ Connection failed: " . $conn->connect_error . "</p>");
    }

    $hobbies = "";
    if (!empty($_POST['hobbies'])) {
        $hobbies = implode(", ", $_POST['hobbies']);
    }

    $sql = "INSERT INTO users (fullname, email, password, gender, hobbies, country)
            VALUES ('$fullname', '$email', '$password', '$gender', '$hobbies', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>✔ Registration successful and data saved to database.</p><br>";
    } else {
        echo "<p class='error'>❌ Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    $conn->close();

    // Display entered info
    echo "<p><strong>Full Name:</strong> " . htmlspecialchars($fullname) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    echo "<p><strong>Gender:</strong> " . htmlspecialchars($gender) . "</p>";
    echo "<p><strong>Hobbies:</strong> " . htmlspecialchars($hobbies ?: "None") . "</p>";
    echo "<p><strong>Country:</strong> " . htmlspecialchars($country) . "</p>";
}
?>

</div>

</body>
</html>
