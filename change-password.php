<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {  
    header('location:index.php');
} else {
    // CSRF Token Generation
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (isset($_POST['submit'])) {
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF validation failed");
        }
        
        $password = $_POST['password'];
        $newpassword = $_POST['newpassword'];
        $username = $_SESSION['alogin'];

        // Fetch stored hash
        $sql = "SELECT Password FROM admin WHERE UserName = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result && password_verify($password, $result->Password)) {
            // Hash new password securely
            $newpasswordHash = password_hash($newpassword, PASSWORD_DEFAULT);

            // Update password in database
            $updateSQL = "UPDATE admin SET Password = :newpassword WHERE UserName = :username";
            $stmt = $dbh->prepare($updateSQL);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':newpassword', $newpasswordHash, PDO::PARAM_STR);
            $stmt->execute();

            $msg = "Your Password successfully changed";
        } else {
            $error = "Your current password is incorrect";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Admin Change Password</title>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<script type="text/javascript">
function valid() {
    if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password do not match!");
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>
</head> 
<body>
<div class="page-container">
    <?php include('includes/header.php'); ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i> Change Password</li>
    </ol>
    <div class="grid-form">
        <div class="grid-form1">
            <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div><?php } 
            else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div><?php } ?>
            <div class="panel-body">
                <form name="chngpwd" method="post" class="form-horizontal" onSubmit="return valid();">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlentities($_SESSION['csrf_token']); ?>">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Current Password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" name="password" class="form-control1" placeholder="Current Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">New Password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" class="form-control1" name="newpassword" placeholder="New Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Confirm Password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="password" class="form-control1" name="confirmpassword" placeholder="Confirm Password" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 col-sm-offset-2">
                        <button type="submit" name="submit" class="btn-primary btn">Submit</button>
                        <button type="reset" class="btn-inverse btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
</div>
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>