<?php
session_start();
include('includes/config.php');

// Redirect to login page if not logged in
if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Fetch dashboard statistics using a single optimized query
$sql = "SELECT 
            (SELECT COUNT(id) FROM tblusers) AS user_count,
            (SELECT COUNT(id) FROM tblissues) AS issues_count,
            (SELECT COUNT(PackageId) FROM tbltourpackages) AS packages_count,
            (SELECT COUNT(id) FROM tblenquiry) AS enquiries_count,
            (SELECT COUNT(id) FROM tblenquiry WHERE Status IS NULL OR Status = '') AS new_enquiries,
            (SELECT COUNT(id) FROM tblenquiry WHERE Status = '1') AS read_enquiries,
            (SELECT COUNT(BookingId) FROM tblbooking) AS total_bookings,
            (SELECT COUNT(BookingId) FROM tblbooking WHERE status IS NULL OR status = '') AS new_bookings,
            (SELECT COUNT(BookingId) FROM tblbooking WHERE status = '2') AS cancelled_bookings,
            (SELECT COUNT(BookingId) FROM tblbooking WHERE status = '1') AS confirmed_bookings";
$query = $dbh->prepare($sql);
$query->execute();
$stats = $query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Dashboard | TMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="js/jquery-2.1.4.min.js"></script>

    <!-- Custom CSS for background -->
    <style>
        body {
            background: url('images/a.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.85);
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card .icon {
            font-size: 28px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.85);
        }

        h4, h5 {
            margin: 0;
        }

        .mb-4 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="left-content">
            <div class="mother-grid-inner">
                <?php include('includes/header.php'); ?>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a> <i class="fa fa-angle-right"></i></li>
                </ol>
                
                <!-- Dashboard Grid -->
                <div class="row">
                    <?php 
                    $sections = [
                        ['Users', 'glyphicon-user', 'manage-users.php', $stats['user_count']],
                        ['Issues Raised', 'glyphicon-folder-open', 'manageissues.php', $stats['issues_count']],
                        ['Total Packages', 'glyphicon-briefcase', 'manage-packages.php', $stats['packages_count']],
                        ['Total Enquiries', 'glyphicon-folder-open', 'manage-enquires.php', $stats['enquiries_count']],
                        ['New Enquiries', 'glyphicon-folder-open', 'manage-enquires.php', $stats['new_enquiries']],
                        ['Read Enquiries', 'glyphicon-folder-open', 'manage-enquires.php', $stats['read_enquiries']],
                        ['Total Bookings', 'glyphicon-list-alt', 'manage-bookings.php', $stats['total_bookings']],
                        ['New Bookings', 'glyphicon-list-alt', 'manage-bookings.php', $stats['new_bookings']],
                        ['Cancelled Bookings', 'glyphicon-list-alt', 'manage-bookings.php', $stats['cancelled_bookings']],
                        ['Confirmed Bookings', 'glyphicon-list-alt', 'manage-bookings.php', $stats['confirmed_bookings']]
                    ];
                    foreach ($sections as $section) {
                        echo "<div class='col-md-4 mb-4'>
                                <a href='{$section[2]}' target='_blank'>
                                    <div class='card p-3 text-center'>
                                        <div class='icon'><i class='glyphicon {$section[1]}'></i></div>
                                        <h4>{$section[0]}</h4>
                                        <h5>{$section[3]}</h5>
                                    </div>
                                </a>
                              </div>";
                    }
                    ?>
                </div>
                <!-- End Dashboard Grid -->

                <div class="inner-block"></div>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <?php include('includes/sidebarmenu.php'); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
