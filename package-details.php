<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['submit2'])) {
    $pid = intval($_GET['pkgid']);
    $useremail = $_SESSION['login'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $comment = $_POST['comment'];
    $status = 0;

    $sql = "INSERT INTO tblbooking(PackageId,UserEmail,FromDate,ToDate,Comment,status) 
            VALUES(:pid, :useremail, :fromdate, :todate, :comment, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pid', $pid, PDO::PARAM_STR);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':todate', $todate, PDO::PARAM_STR);
    $query->bindParam(':comment', $comment, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Booked Successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Package Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />

    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/animate.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/jquery-ui.css" />

    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery-ui.js"></script>

    <script>
        new WOW().init();
        $(function () {
            $("#datepicker, #datepicker1").datepicker();
        });
    </script>

    <style>
        /* Global Text Styling */
        body, h1, h2, h3, h4, h5, h6, p, label, input, button, .btn, .inputLabel, .special, .grand h3, .grand p, ul li {
            color: #111 !important;
            font-weight: bold !important;
        }

        body {
            background-image: url('image/1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Datepicker Styling */
        .ui-datepicker {
            background: #fff !important;
            border: 2px solid #000 !important;
            border-radius: 10px !important;
            z-index: 9999 !important;
        }

        .ui-datepicker-header {
            background: #000 !important;
            color: #fff !important;
            font-weight: bold !important;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        .ui-datepicker-title {
            color: #fff !important;
        }

        .ui-datepicker td a {
            background: #fff !important;
            color: #000 !important;
            padding: 6px !important;
            border-radius: 4px !important;
            text-align: center;
        }

        .ui-datepicker td a:hover {
            background: #e0e0e0 !important;
        }

        .ui-datepicker .ui-state-active {
            background: #000 !important;
            color: #fff !important;
        }

        .ui-datepicker .ui-state-highlight {
            background: #444 !important;
            color: #fff !important;
        }

        .ui-datepicker-prev, .ui-datepicker-next {
            background: #fff !important;
            border: 1px solid #000 !important;
            color: #000 !important;
            padding: 5px !important;
            border-radius: 5px !important;
        }

        .ui-datepicker-prev:hover, .ui-datepicker-next:hover {
            background: #000 !important;
            color: #fff !important;
        }

        /* Page Section Styling */
        .banner-3 {
            padding: 60px 0;
            text-align: center;
            background: rgba(255, 255, 255, 0.75);
        }

        .selectroom_top {
            background-color: rgba(255, 255, 255, 0.92);
            border: 3px solid #333;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .grand {
            background-color: #f8f8f8;
            border: 2px dashed #333;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .btn-primary.btn {
            background-color: #333;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-size: 16px;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-primary.btn:hover {
            background-color: #555;
        }

        .special {
            border: 2px solid #333;
            border-radius: 10px;
            padding: 8px;
            width: 100%;
        }

        label.inputLabel {
            font-weight: bold;
        }

        .errorWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="banner-3">
    <div class="container">
        <h1 class="wow zoomIn animated" data-wow-delay=".5s">TMS - Package Details</h1>
    </div>
</div>

<div class="selectroom">
    <div class="container">

        <?php if ($error) { ?>
            <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
        <?php } else if ($msg) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
        <?php } ?>

        <?php
        $pid = intval($_GET['pkgid']);
        $sql = "SELECT * FROM tbltourpackages WHERE PackageId=:pid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pid', $pid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
        ?>

                <form name="book" method="post">
                    <div class="selectroom_top">
                        <div class="col-md-4 selectroom_left wow fadeInLeft animated" data-wow-delay=".5s">
                            <img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>" class="img-responsive" alt="">
                        </div>
                        <div class="col-md-8 selectroom_right wow fadeInRight animated" data-wow-delay=".5s">
                            <h2><?php echo htmlentities($result->PackageName); ?></h2>
                            <p class="dow">#PKG-<?php echo htmlentities($result->PackageId); ?></p>
                            <p><b>Package Type:</b> <?php echo htmlentities($result->PackageType); ?></p>
                            <p><b>Package Location:</b> <?php echo htmlentities($result->PackageLocation); ?></p>
                            <p><b>Features:</b> <?php echo htmlentities($result->PackageFetures); ?></p>
                            <div class="ban-bottom">
                                <div class="bnr-right">
                                    <label class="inputLabel">From</label>
                                    <input class="date" id="datepicker" type="text" placeholder="dd-mm-yyyy" name="fromdate" required="">
                                </div>
                                <div class="bnr-right">
                                    <label class="inputLabel">To</label>
                                    <input class="date" id="datepicker1" type="text" placeholder="dd-mm-yyyy" name="todate" required="">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="grand">
                                <p>Grand Total</p>
                                <h3>USD. <?php echo htmlentities($result->PackagePrice); ?></h3>
                            </div>
                        </div>

                        <h3>Package Details</h3>
                        <p style="padding-top: 1%"><?php echo htmlentities($result->PackageDetails); ?> </p>
                        <div class="clearfix"></div>
                    </div>

                    <div class="selectroom_top">
                        <h2>Travels</h2>
                        <div class="selectroom-info animated wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="500ms">
                            <ul>
                                <li class="spe">
                                    <label class="inputLabel">Comment</label>
                                    <input class="special" type="text" name="comment" required="">
                                </li>

                                <?php if ($_SESSION['login']) { ?>
                                    <li class="spe" align="center">
                                        <button type="submit" name="submit2" class="btn-primary btn">Book</button>
                                    </li>
                                <?php } else { ?>
                                    <li class="sigi" align="center" style="margin-top: 1%">
                                        <a href="#" data-toggle="modal" data-target="#myModal4" class="btn-primary btn">Book</a>
                                    </li>
                                <?php } ?>
                                <div class="clearfix"></div>
                            </ul>
                        </div>
                    </div>
                </form>

        <?php
            }
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<?php include('includes/signup.php'); ?>
<?php include('includes/signin.php'); ?>
<?php include('includes/write-us.php'); ?>

</body>
</html>
