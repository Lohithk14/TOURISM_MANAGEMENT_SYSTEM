<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (!isset($_SESSION['alogin']) || strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// DELETE USER if ?del=email is set
if (isset($_GET['del'])) {
    $useremail = $_GET['del'];
    $sql = "DELETE FROM tblusers WHERE EmailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $useremail, PDO::PARAM_STR);
    $query->execute();
    $_SESSION['delmsg'] = "User deleted successfully";
    header('location:manage-users.php');
    exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Admin Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' />
    <link href="css/style.css" rel='stylesheet' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icon-font.min.css" />
    <link rel="stylesheet" href="css/morris.css">
    <link rel="stylesheet" href="css/table-style.css" />
    <link rel="stylesheet" href="css/basictable.css" />

    <!-- JS -->
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table').basictable();
        });
    </script>

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet'>
</head> 
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>
            <div class="clearfix"></div>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="dashboard.php">Home</a><i class="fa fa-angle-right"></i> Manage Users
                </li>
            </ol>

            <div class="agile-grids">	
                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>Manage Users</h2>

                        <?php if(isset($_SESSION['delmsg'])) { ?>
                        <div class="alert alert-success text-center">
                            <?php 
                                echo htmlentities($_SESSION['delmsg']); 
                                unset($_SESSION['delmsg']);
                            ?>
                        </div>
                        <?php } ?>

                        <table id="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile No.</th>
                                    <th>Email ID</th>
                                    <th>Reg Date</th>
                                    <th>Updation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT * FROM tblusers";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo htmlspecialchars($result->FullName); ?></td>
                                    <td><?php echo htmlspecialchars($result->MobileNumber); ?></td>
                                    <td><?php echo htmlspecialchars($result->EmailId); ?></td>
                                    <td><?php echo htmlspecialchars($result->RegDate); ?></td>
                                    <td><?php echo htmlspecialchars($result->UpdationDate); ?></td>
                                    <td>
                                        <a href="user-bookings.php?uid=<?php echo urlencode($result->EmailId); ?>&uname=<?php echo urlencode($result->FullName); ?>" class="btn btn-primary btn-sm">Bookings</a>
                                        <a href="manage-users.php?del=<?php echo urlencode($result->EmailId); ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="btn btn-danger btn-sm" style="margin-left: 5px;">Delete</a>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">No users found.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sticky Header Script -->
                <script>
                    $(document).ready(function () {
                        var navoffeset = $(".header-main").offset().top;
                        $(window).scroll(function () {
                            var scrollpos = $(window).scrollTop();
                            if (scrollpos >= navoffeset) {
                                $(".header-main").addClass("fixed");
                            } else {
                                $(".header-main").removeClass("fixed");
                            }
                        });
                    });
                </script>

                <div class="inner-block"></div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <?php include('includes/sidebarmenu.php'); ?>
    <div class="clearfix"></div>

    <!-- Sidebar Toggle Script -->
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

    <!-- JS Files -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.min.js"></script>
</div>
</body>
</html>
