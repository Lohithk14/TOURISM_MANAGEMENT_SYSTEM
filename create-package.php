<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {    
    header('location:index.php');
} else {
    if(isset($_POST['submit'])) {
        $pname = htmlspecialchars($_POST['packagename']);
        $ptype = htmlspecialchars($_POST['packagetype']);    
        $plocation = htmlspecialchars($_POST['packagelocation']);
        $pprice = filter_var($_POST['packageprice'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);    
        $pfeatures = htmlspecialchars($_POST['packagefeatures']);
        $pdetails = htmlspecialchars($_POST['packagedetails']);    
        $pimage = $_FILES["packageimage"]["name"];
        $target_dir = "packageimages/";
        $target_file = $target_dir . basename($pimage);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
        if (!in_array($imageFileType, $allowed_extensions)) {
            $error = "Invalid file format. Only JPG, JPEG, PNG, and GIF allowed.";
        } else {
            move_uploaded_file($_FILES["packageimage"]["tmp_name"], $target_file);

            $sql = "INSERT INTO tbltourpackages (PackageName, PackageType, PackageLocation, PackagePrice, PackageFetures, PackageDetails, PackageImage) 
                    VALUES (:pname, :ptype, :plocation, :pprice, :pfeatures, :pdetails, :pimage)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pname', $pname, PDO::PARAM_STR);
            $query->bindParam(':ptype', $ptype, PDO::PARAM_STR);
            $query->bindParam(':plocation', $plocation, PDO::PARAM_STR);
            $query->bindParam(':pprice', $pprice, PDO::PARAM_STR);
            $query->bindParam(':pfeatures', $pfeatures, PDO::PARAM_STR);
            $query->bindParam(':pdetails', $pdetails, PDO::PARAM_STR);
            $query->bindParam(':pimage', $pimage, PDO::PARAM_STR);
            
            try {
                $query->execute();
                $msg = "Package Created Successfully";
            } catch (PDOException $e) {
                $error = "Something went wrong: " . $e->getMessage();
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Admin Package Creation</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome.css" type="text/css"/>
    <script src="js/jquery-2.1.4.min.js"></script>
    <style>
        .errorWrap { padding: 10px; margin: 20px 0; background: #fff; border-left: 4px solid #dd3d36; box-shadow: 0 1px 1px rgba(0,0,0,.1); }
        .succWrap { padding: 10px; margin: 20px 0; background: #fff; border-left: 4px solid #5cb85c; box-shadow: 0 1px 1px rgba(0,0,0,.1); }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a> <i class="fa fa-angle-right"></i> Create Package</li>
                </ol>
                <div class="grid-form">
                    <div class="grid-form1">
                        <h3>Create Package</h3>
                        <?php if($error) { echo '<div class="errorWrap"><strong>ERROR</strong>: '.htmlentities($error).'</div>'; } ?>
                        <?php if($msg) { echo '<div class="succWrap"><strong>SUCCESS</strong>: '.htmlentities($msg).'</div>'; } ?>
                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control1" name="packagename" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Type</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control1" name="packagetype" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Location</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control1" name="packagelocation" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Price (USD)</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control1" name="packageprice" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Features</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control1" name="packagefeatures" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Details</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" rows="5" name="packagedetails" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Image</label>
                                <div class="col-sm-8">
                                    <input type="file" name="packageimage" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <button type="submit" name="submit" class="btn-primary btn">Create</button>
                                    <button type="reset" class="btn-inverse btn">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php } ?>