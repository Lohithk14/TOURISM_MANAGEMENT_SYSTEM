<?php
session_start();
error_reporting(0);
include('includes/config.php');

define('STATUS_PENDING', 0);
define('STATUS_CONFIRMED', 1);
define('STATUS_CANCELLED', 2);

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

$msg = $error = '';

// Handle Booking Actions via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'];

    if ($bookingId && in_array($action, ['cancel', 'confirm'])) {
        if ($action === 'cancel') {
            $status = STATUS_CANCELLED;
            $cancelby = 'a';
            $sql = "UPDATE tblbooking SET status=:status, CancelledBy=:cancelby, UpdationDate=NOW() WHERE BookingId=:bid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':cancelby', $cancelby, PDO::PARAM_STR);
            $query->bindParam(':bid', $bookingId, PDO::PARAM_INT);
            $query->execute();
            $msg = "Booking cancelled successfully";
        } elseif ($action === 'confirm') {
            $status = STATUS_CONFIRMED;
            $sql = "UPDATE tblbooking SET status=:status, UpdationDate=NOW() WHERE BookingId=:bid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':bid', $bookingId, PDO::PARAM_INT);
            $query->execute();
            $msg = "Booking confirmed successfully";
        }
    } else {
        $error = "Invalid booking ID or action.";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Admin Manage Bookings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/morris.css" rel="stylesheet">
    <link href="css/table-style.css" rel="stylesheet">
    <link href="css/basictable.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icon-font.min.css">
    <link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table').basictable();
        });
    </script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <div class="page-container">
        <?php include('includes/sidebarmenu.php'); ?>
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>
                <div class="clearfix"></div>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i>Manage Bookings</li>
                </ol>

                <div class="agile-grids">
                    <?php if ($error): ?>
                        <div class="errorWrap"><strong>ERROR</strong>: <?= htmlentities($error) ?></div>
                    <?php elseif ($msg): ?>
                        <div class="succWrap"><strong>SUCCESS</strong>: <?= htmlentities($msg) ?></div>
                    <?php endif; ?>

                    <div class="agile-tables">
                        <div class="w3l-table-info">
                            <h2>Manage Bookings</h2>
                            <table id="table">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Package</th>
                                        <th>From / To</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql = "SELECT tblbooking.BookingId as bookid, tblusers.FullName as fname, tblusers.MobileNumber as mnumber, tblusers.EmailId as email, tbltourpackages.PackageName as pckname, tblbooking.PackageId as pid, tblbooking.FromDate as fdate, tblbooking.ToDate as tdate, tblbooking.Comment as comment, tblbooking.status as status, tblbooking.CancelledBy as cancelby, tblbooking.UpdationDate as upddate 
                                            FROM tblbooking 
                                            LEFT JOIN tblusers ON tblbooking.UserEmail = tblusers.EmailId 
                                            LEFT JOIN tbltourpackages ON tbltourpackages.PackageId = tblbooking.PackageId";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($results as $result): ?>
                                        <tr>
                                            <td>#BK-<?= htmlentities($result->bookid) ?></td>
                                            <td><?= htmlentities($result->fname) ?></td>
                                            <td><?= htmlentities($result->mnumber) ?></td>
                                            <td><?= htmlentities($result->email) ?></td>
                                            <td><a href="update-package.php?pid=<?= htmlentities($result->pid) ?>"><?= htmlentities($result->pckname) ?></a></td>
                                            <td><?= date("d M Y", strtotime($result->fdate)) ?> to <?= date("d M Y", strtotime($result->tdate)) ?></td>
                                            <td><?= htmlentities($result->comment) ?></td>
                                            <td>
                                                <?php 
                                                if ($result->status == STATUS_PENDING) echo "Pending";
                                                elseif ($result->status == STATUS_CONFIRMED) echo "Confirmed";
                                                elseif ($result->status == STATUS_CANCELLED && $result->cancelby == 'a') echo "Cancelled by Admin on " . htmlentities($result->upddate);
                                                elseif ($result->status == STATUS_CANCELLED && $result->cancelby == 'u') echo "Cancelled by User on " . htmlentities($result->upddate);
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($result->status == STATUS_PENDING): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="booking_id" value="<?= $result->bookid ?>">
                                                        <input type="hidden" name="action" value="confirm">
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm this booking?')">Confirm</button>
                                                    </form>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="booking_id" value="<?= $result->bookid ?>">
                                                        <input type="hidden" name="action" value="cancel">
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this booking?')">Cancel</button>
                                                    </form>
                                                <?php elseif ($result->status == STATUS_CONFIRMED): ?>
                                                    <span class="text-success">Confirmed</span>
                                                <?php elseif ($result->status == STATUS_CANCELLED): ?>
                                                    <span class="text-danger">Cancelled</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- Sidebar toggle script -->
    <script>
        var toggle = true;
        $(".sidebar-icon").click(function () {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({ "position": "absolute" });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function () {
                    $("#menu span").css({ "position": "relative" });
                }, 400);
            }
            toggle = !toggle;
        });
    </script>

    <!-- Other JS -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
