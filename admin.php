<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Predefined new password (you can change this)
$new_password = 'lohi123';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = trim($_POST['username']);

    try {
        $sql = "SELECT * FROM admin WHERE UserName = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $admin_username, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Update password
            $updateSQL = "UPDATE admin SET Password = :password WHERE UserName = :username";
            $stmt = $dbh->prepare($updateSQL);
            $stmt->bindParam(':username', $admin_username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();
            $message = "✅ Password for '$admin_username' has been reset to 'lohi123'.";
        } else {
            $message = "❌ Admin user '$admin_username' does not exist.";
        }
    } catch (PDOException $e) {
        $message = "⚠️ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Admin Password</title>
    <style>
        body {
            background-image: url('image/a.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
            padding-top: 100px;
        }
        .form-box {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 30px;
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255,255,255,0.3);
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin: 10px;
            border: none;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Reset Admin Password</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Enter Admin Username" required />
            <br>
            <input type="submit" value="Reset Password" />
        </form>
        <?php if ($message): ?>
            <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
        <?php endif; ?>
    </div>
</body>
</html>
