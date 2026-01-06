<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Package List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/animate.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">

    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script> new WOW().init(); </script>

    <style>
        body {
            background-image: url('image/a.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #111;
        }

        .rooms {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .room-bottom h3 {
            text-align: center;
            margin-bottom: 40px;
            color: #222;
            font-weight: bold;
            font-size: 32px;
        }

        .rom-btm {
            margin-bottom: 30px;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .room-left img {
            border-radius: 10px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .room-midle h4,
        .room-midle h6,
        .room-midle p,
        .room-right h5 {
            color: #111;
        }

        .room-right {
            text-align: center;
        }

        .view {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 10px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .view:hover {
            background-color: #555;
        }

        /* Banner heading style */
        .banner-3 h1 {
            text-align: center;
            font-weight: bold;
            color: #222;
            font-size: 36px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!--- banner ---->
<div class="banner-3">
    <div class="container">
        <h1 class="wow zoomIn animated" data-wow-delay=".5s">TMS - Package List</h1>
    </div>
</div>
<!--- /banner ---->

<!--- rooms ---->
<div class="rooms">
    <div class="container">
        <div class="room-bottom">
            <h3>Package List</h3>

            <?php
            $sql = "SELECT * FROM tbltourpackages";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                foreach ($results as $result) {
                    ?>
                    <div class="rom-btm wow fadeInUp" data-wow-delay=".3s">
                        <div class="col-md-3 room-left">
                            <img src="admin/pacakgeimages/<?php echo htmlentities($result->PackageImage); ?>" alt="">
                        </div>
                        <div class="col-md-6 room-midle">
                            <h4>Package Name: <?php echo htmlentities($result->PackageName); ?></h4>
                            <h6>Package Type: <?php echo htmlentities($result->PackageType); ?></h6>
                            <p><b>Location:</b> <?php echo htmlentities($result->PackageLocation); ?></p>
                            <p><b>Features:</b> <?php echo htmlentities($result->PackageFetures); ?></p>
                        </div>
                        <div class="col-md-3 room-right">
                            <h5>USD <?php echo htmlentities($result->PackagePrice); ?></h5>
                            <a href="package-details.php?pkgid=<?php echo htmlentities($result->PackageId); ?>" class="view">Details</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<!--- /rooms ---->

<?php include('includes/footer.php'); ?>
<?php include('includes/signup.php'); ?>
<?php include('includes/signin.php'); ?>
<?php include('includes/write-us.php'); ?>

</body>
</html>
