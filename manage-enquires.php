<?php
session_start();
require_once('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

$msg = '';
$error = '';

// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eid'])) {
    $eid = filter_input(INPUT_GET, 'eid', FILTER_VALIDATE_INT);
    if ($eid) {
        $status = 1;
        $sql = "UPDATE tblenquiry SET Status = :status WHERE id = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':eid', $eid, PDO::PARAM_INT);
        $query->execute();
        $msg = "Enquiry marked as read.";
    }
}

// Handle deletion via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }

    $id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
    if ($id) {
        $sql = "DELETE FROM tblenquiry WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $msg = "Enquiry deleted successfully.";
    } else {
        $error = "Invalid enquiry ID.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>TMS | Admin Manage Enquiries</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel='stylesheet'>
    <link href="css/style.css" rel='stylesheet'>
    <link href="css/font-awesome.css" rel="stylesheet"> 
    <link href="css/table-style.css" rel="stylesheet">
    <link href="css/basictable.css" rel="stylesheet">
    <link href="css/icon-font.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
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
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> Manage Enquiries</li>
        </ol>

        <div class="agile-grids">
            <?php if ($error): ?>
                <div class="errorWrap"><strong>ERROR</strong>: <?= htmlentities($error) ?></div>
            <?php elseif ($msg): ?>
                <div class="succWrap"><strong>SUCCESS</strong>: <?= htmlentities($msg) ?></div>
            <?php endif; ?>

            <div class="agile-tables">
                <div class="w3l-table-info">
                    <h2>Manage Enquiries</h2>
                    <table id="table">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Name</th>
                                <th>Mobile No./Email</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Posting Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT * FROM tblenquiry";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        foreach ($results as $result): ?>
                            <tr>
                                <td>#TCKT-<?= htmlentities($result->id) ?></td>
                                <td><?= htmlentities($result->FullName) ?></td>
                                <td><?= htmlentities($result->MobileNumber) ?><br><?= htmlentities($result->EmailId) ?></td>
                                <td><?= htmlentities($result->Subject) ?></td>
                                <td><?= htmlentities($result->Description) ?></td>
                                <td><?= htmlentities($result->PostingDate) ?></td>
                                <td>
                                    <?php if ($result->Status == 1): ?>
                                        Read |
                                    <?php else: ?>
                                        <a href="?eid=<?= $result->id ?>" onclick="return confirm('Mark as read?')">Pending</a> |
                                    <?php endif; ?>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Do you really want to delete this enquiry?');">
                                        <input type="hidden" name="delete_id" value="<?= $result->id ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
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

<?php include('includes/sidebarmenu.php'); ?>

<!-- Scripts -->
<script src="js/scripts.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $(".sidebar-icon").click(function () {
        $(".page-container").toggleClass("sidebar-collapsed sidebar-collapsed-back");
    });
</script>
</body>
</html>
