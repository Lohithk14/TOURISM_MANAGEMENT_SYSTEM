<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $uname = trim($_POST['username']);

    $sql = "SELECT UserName FROM admin WHERE UserName = :uname";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $uname;
        echo "<script>window.location.href = 'dashboard.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid username');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TMS | Admin Login (No Password)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        body {
            background: url('images/a.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 40px 30px;
            max-width: 400px;
            margin: 100px auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
        }

        label {
            margin-top: 10px;
            color: #fff;
        }

        .form-control {
            border-radius: 5px;
            border: none;
            padding: 10px;
        }

        .btn-primary {
            width: 100%;
            margin-top: 20px;
            background-color: #00c6ff;
            border: none;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #009acd;
        }

        .extra-links {
            text-align: center;
            margin-top: 15px;
        }

        .extra-links a {
            color: #fff;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter username">
            </div>

            <button type="submit" name="login" class="btn btn-primary">Sign In</button>

            <div class="extra-links">
                <a href="../index.php">Back to Home</a>
            </div>
        </form>
    </div>

</body>
</html>
