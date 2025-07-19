<!DOCTYPE>
<html>
<head>
<title>List Users</title>
 <style>
    body {
      margin: 0;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1b2735, #090a0f);
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 60px;
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
    h1 { 
      font-size: 40px;
      color:gold;
    }

    .box {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
      width: 90%;
      max-width: 1100px;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border: 2px solid white
    }

    th, td {
      padding: 15px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
    }

    th {
      background-color: rgba(255, 255, 255, 0.2);
    }

    a {
      color: #66a6ff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .box {
        padding: 20px;
      }

      table {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
<h1> User List </h1>

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
$user = new User($conn);

if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $user->deleteUser($deleteId);
    header("Location: listusers.php");
    exit;
}
if (isset($_GET['delete_all']) && $_GET['delete_all'] === 'true') {
    $user->deleteAllUsers();
    header("Location: listusers.php");
    exit;
}

$users = $user->getAllUsers();
?>
<div style="align-self: flex-end; margin-right: 2%; margin-bottom: 15px;">
  <a href="listusers.php?delete_all=true"
     onclick="return confirm('Are you sure you want to delete ALL users?');"
     style="background-color: red; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">
    Delete All Users
  </a>
</div>




<table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Hobbies</th>
            <th>Country</th>
            <th>Action</th>
        </tr>
<?php foreach ($users as $row): ?>
        <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['fullname']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['gender']) ?></td>
        <td><?= htmlspecialchars($row['hobbies']) ?></td>
        <td><?= htmlspecialchars($row['country']) ?></td>
        <td>
          <a href="updateuser.php?id=<?= $row['id'] ?>" 
   style="background-color: #66a6ff; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-right: 5px;">
  Edit
</a>

<a href="listusers.php?delete=<?= $row['id'] ?>" 
   onclick="return confirm('Are you sure you want to delete this user?');"
   style="background-color: red; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: bold;">
  Delete
</a>

        </td>
    </tr>
<?php endforeach; ?>
</table>
</body>
</html>