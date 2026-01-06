<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
} else {
    if (isset($_REQUEST['bkid'])) {
        $bid = intval($_GET['bkid']);
        $email = $_SESSION['login'];

        $sql = "SELECT FromDate FROM tblbooking WHERE UserEmail=:email AND BookingId=:bid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':bid', $bid, PDO::PARAM_STR);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $fdate = $result->FromDate;
                $cdate = date('Y/m/d');
                $date1 = date_create($cdate);
                $date2 = date_create($fdate);
                $diff = date_diff($date1, $date2);
                $df = $diff->format("%a");

                if ($df > 1) {
                    $status = 2;
                    $cancelby = 'u';

                    $sql = "UPDATE tblbooking 
                            SET status=:status, CancelledBy=:cancelby 
                            WHERE UserEmail=:email AND BookingId=:bid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':status', $status, PDO::PARAM_STR);
                    $query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
                    $query->bindParam(':email', $email, PDO::PARAM_STR);
                    $query->bindParam(':bid', $bid, PDO::PARAM_STR);
                    $query->execute();

                    $msg = "Booking Cancelled successfully";
                } else {
                    $error = "You can't cancel booking before 24 hours";
                }
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <title>Tour History</title>

    <!-- Styles and scripts -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script> new WOW().init(); </script>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet'>

    <style>
        body {
            background: url('image/1.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Open Sans', sans-serif;
            color: #fff;
        }

        .privacy .container {
            background-color: rgba(0, 0, 0, 0.65);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.7);
            max-width: 1000px;
            margin: 40px auto;
        }

        h3 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: 1.2px;
            text-align: center;
        }

        .errorWrap, .succWrap {
            padding: 15px;
            margin-bottom: 20px;
            background: #fff;
            border-left: 6px solid;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }

        .errorWrap {
            border-color: #dd3d36;
            color: #dd3d36;
        }

        .succWrap {
            border-color: #5cb85c;
            color: #4cae4c;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

        table.table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            background-color: rgba(0, 0, 0, 0.3);
        }

        table.table th {
            background-color: rgba(76, 175, 80, 0.9);
            color: #fff;
            font-weight: 700;
            padding: 15px 20px;
            text-transform: uppercase;
            border-bottom: 3px solid #388E3C;
        }

        table.table td {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ddd;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        table.table tr:hover td {
            background-color: rgba(76, 175, 80, 0.35);
            color: #fff;
        }

        table.table a[href*="bkid"] {
            background-color: #4caf50;
            color: white !important;
            padding: 7px 16px;
            border-radius: 8px;
            font-weight: 700;
        }

        table.table a[href*="bkid"]:hover {
            background-color: #357a38;
        }

        @media (max-width: 768px) {
            table.table th, table.table td {
                padding: 10px 8px;
                font-size: 14px;
            }
        }

        .banner-1 {
            padding-top: 80px;
        }
    </style>
</head>
<body>

<!-- Header and Banner -->
<div class="top-header">
    <?php include('includes/header.php'); ?>
    <div class="banner-1">
        <div class="container">
            <h1 class="wow zoomIn animated" data-wow-delay=".5s">.</h1>
        </div>
    </div>
</div>

<!-- Tour History Section -->
<div class="privacy">
    <div class="container">
        <h3 class="wow fadeInDown animated" data-wow-delay=".5s">My Tour History</h3>
        <?php if ($error) { ?>
            <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
        <?php } else if ($msg) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
        <?php } ?>

        <div class="table-responsive">
            <table border="1" class="table">
                <tr align="center">
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Package Name</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Booking Date</th>
                    <th>Action</th>
                </tr>

                <?php
                $uemail = $_SESSION['login'];
                $sql = "SELECT 
                            tblbooking.BookingId as bookid,
                            tblbooking.PackageId as pkgid,
                            tbltourpackages.PackageName as packagename,
                            tblbooking.FromDate as fromdate,
                            tblbooking.ToDate as todate,
                            tblbooking.Comment as comment,
                            tblbooking.status as status,
                            tblbooking.RegDate as regdate,
                            tblbooking.CancelledBy as cancelby,
                            tblbooking.UpdationDate as upddate
                        FROM tblbooking
                        JOIN tbltourpackages ON tbltourpackages.PackageId = tblbooking.PackageId
                        WHERE UserEmail = :uemail";

                $query = $dbh->prepare($sql);
                $query->bindParam(':uemail', $uemail, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                <tr align="center">
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td>#BK<?php echo htmlentities($result->bookid); ?></td>
                    <td><a href="package-details.php?pkgid=<?php echo htmlentities($result->pkgid); ?>"><?php echo htmlentities($result->packagename); ?></a></td>
                    <td><?php echo htmlentities($result->fromdate); ?></td>
                    <td><?php echo htmlentities($result->todate); ?></td>
                    <td><?php echo htmlentities($result->comment); ?></td>
                    <td>
                        <?php 
                        if ($result->status == 0) echo "Pending";
                        elseif ($result->status == 1) echo "Confirmed";
                        elseif ($result->status == 2 && $result->cancelby == 'u') echo "Canceled by you at " . htmlentities($result->upddate);
                        elseif ($result->status == 2 && $result->cancelby == 'a') echo "Canceled by admin at " . htmlentities($result->upddate);
                        ?>
                    </td>
                    <td><?php echo htmlentities($result->regdate); ?></td>
                    <td>
                        <?php if ($result->status == 2) {
                            echo "Cancelled";
                        } else { ?>
                            <a href="tour-history.php?bkid=<?php echo htmlentities($result->bookid); ?>" onclick="return confirm('Do you really want to cancel booking')">Cancel</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php $cnt++; } } ?>
            </table>
        </div>
    </div>
</div>

<!-- Footer & Popups -->
<?php include('includes/footer.php'); ?>
<?php include('includes/signup.php'); ?>
<?php include('includes/signin.php'); ?>
<?php include('includes/write-us.php'); ?>
</body>
</html>
<?php } ?>
