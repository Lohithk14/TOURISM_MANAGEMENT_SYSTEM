<?php
session_start();
require_once('includes/config.php');

// Enable proper error reporting (for development, not for production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Redirect to login if not logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
    if ($id) {
        $sql = "DELETE FROM tblissues WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $msg = "Record deleted successfully.";
    } else {
        $error = "Invalid ID.";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Admin Manage Issues</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <link href="css/bootstrap.min.css" rel='stylesheet' />
    <link href="css/style.css" rel='stylesheet' />
    <link rel="stylesheet" href="css/morris.css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="css/table-style.css" />
    <link rel="stylesheet" type="text/css" href="css/basictable.css" />
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/jquery.basictable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table').basictable();
        });
    </script>
    <style>
        .errorWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>
            <div class="clearfix"></div>
        </div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> Manage Issues</li>
        </ol>
        <div class="agile-grids">
            <?php if (isset($error)) { ?>
                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
            <?php } elseif (isset($msg)) { ?>
                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
            <?php } ?>
            <div class="agile-tables">
                <div class="w3l-table-info">
                    <h2>Manage Issues</h2>
                    <table id="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Email</th>
                            <th>Issue</th>
                            <th>Description</th>
                            <th>Posting Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT 
                            tblissues.id AS id,
                            tblusers.FullName AS fname,
                            tblusers.MobileNumber AS mnumber,
                            tblusers.EmailId AS email,
                            tblissues.Issue AS issue,
                            tblissues.Description AS description,
                            tblissues.PostingDate AS postingDate
                            FROM tblissues
                            LEFT JOIN tblusers ON tblusers.EmailId = tblissues.UserEmail";

                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) { ?>
                                <tr>
                                    <td>#00<?php echo htmlentities($result->id); ?></td>
                                    <td><?php echo htmlentities($result->fname); ?></td>
                                    <td><?php echo htmlentities($result->mnumber); ?></td>
                                    <td><?php echo htmlentities($result->email); ?></td>
                                    <td><?php echo htmlentities($result->issue); ?></td>
                                    <td><?php echo htmlentities($result->description); ?></td>
                                    <td><?php echo htmlentities($result->postingDate); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onClick="popUpWindow('updateissue.php?iid=<?php echo $result->id; ?>');" class="btn btn-primary btn-sm">View</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Do you really want to delete?');">
                                            <input type="hidden" name="delete_id" value="<?php echo $result->id; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php include('includes/footer.php'); ?>
    </div>
</div>
<?php include('includes/sidebarmenu.php'); ?>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    var toggle = true;
    $(".sidebar-icon").click(function () {
        if (toggle) {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position": "absolute"});
        } else {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function () {
                $("#menu span").css({"position": "relative"});
            }, 400);
        }
        toggle = !toggle;
    });

    function popUpWindow(URLStr) {
        let popUpWin = window.open(URLStr, 'popUpWin', 'width=600,height=600,scrollbars=yes');
        if (popUpWin && !popUpWin.closed) popUpWin.focus();
    }
</script>
</body>
</html>
