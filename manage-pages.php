<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{	
    header('location:index.php');
}
else {
    if($_POST['submit']=="Update") {
        $pagetype = $_GET['type'];
        $pagedetails = $_POST['pgedetails'];
        $sql = "UPDATE tblpages SET detail=:pagedetails WHERE type=:pagetype";
        $query = $dbh->prepare($sql);
        $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
        $query->bindParam(':pagedetails', $pagedetails, PDO::PARAM_STR);
        $query->execute();
        $msg = "Page data updated successfully";
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Admin Package Creation</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pooled Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> 
    addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); 
    function hideURLbar(){ window.scrollTo(0,1); } 
</script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="css/morris.css" type="text/css"/>
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-2.1.4.min.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />

<style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    }
    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    }

    /* Added styles for About Us and Contact Us pages */
    .styled-page-content {
        border: 2px solid #333; /* dark border */
        padding: 15px;
        color: #222; /* dark text */
        background-color: #f9f9f9; /* light background */
        border-radius: 5px;
        font-family: 'Roboto', sans-serif;
        font-size: 15px;
        line-height: 1.5;
    }
</style>

<script type="text/JavaScript">
// JavaScript validation and functions omitted for brevity (keep yours here)
</script>
<script type="text/javascript" src="nicEdit.js"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>		

</head> 
<body>
   <div class="page-container">
   <!--/content-inner-->
   <div class="left-content">
       <div class="mother-grid-inner">
           <!--header start here-->
           <?php include('includes/header.php');?>
           <div class="clearfix"></div>	
       </div>
       <!--header end here-->

       <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="index.html">Home</a><i class="fa fa-angle-right"></i>Update Page Data </li>
       </ol>

       <!--grid-->
       <div class="grid-form">
           <div class="grid-form1">
               <h3>Update Page Data</h3>
               <?php if($error){?>
                   <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
               <?php } else if($msg){?>
                   <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
               <?php }?>

               <div class="tab-content">
                   <div class="tab-pane active" id="horizontal-form">
                       <form class="form-horizontal" name="package" method="post" enctype="multipart/form-data">
                           <div class="form-group">
                               <label for="focusedinput" class="col-sm-2 control-label">Select page</label>
                               <div class="col-sm-8">
                                   <select name="menu1" onChange="MM_jumpMenu('parent',this,0)" class="form-control">
                                       <option value="" selected="selected">***Select One***</option>
                                       <option value="manage-pages.php?type=terms">Terms and Conditions</option>
                                       <option value="manage-pages.php?type=privacy">Privacy and Policy</option>
                                       <option value="manage-pages.php?type=aboutus">About Us</option> 
                                       <option value="manage-pages.php?type=contact">Contact Us</option>
                                   </select>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="focusedinput" class="col-sm-2 control-label">Selected Page</label>
                               <div class="col-sm-8">
                                   <?php
                                   switch($_GET['type']) {
                                       case "terms": echo "Terms and Conditions"; break;
                                       case "privacy": echo "Privacy And Policy"; break;
                                       case "aboutus": echo "About Us"; break;
                                       case "software": echo "Offers"; break;	
                                       case "aspnet": echo "Vision And Mission"; break;		
                                       case "objectives": echo "Objectives"; break;						
                                       case "disclaimer": echo "Disclaimer"; break;
                                       case "vbnet": echo "Partner With Us"; break;
                                       case "candc": echo "Super Brand"; break;
                                       case "contact": echo "Contact Us"; break;
                                       default: echo "";
                                   }
                                   ?>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="focusedinput" class="col-sm-2 control-label">Page Details</label>
                               <div class="col-sm-8">

                                   <?php 
                                   $pagetype = $_GET['type'];
                                   $applyStyle = ($pagetype == 'aboutus' || $pagetype == 'contact') ? 'styled-page-content' : '';
                                   ?>

                                   <div class="<?php echo $applyStyle; ?>">
                                       <textarea class="form-control" rows="10" cols="50" name="pgedetails" id="pgedetails" placeholder="Page Details" required><?php 
                                       $sql = "SELECT detail from tblpages where type=:pagetype";
                                       $query = $dbh->prepare($sql);
                                       $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
                                       $query->execute();
                                       $results = $query->fetchAll(PDO::FETCH_OBJ);
                                       if($query->rowCount() > 0) {
                                           foreach($results as $result) {
                                               echo htmlentities($result->detail);
                                           }
                                       }
                                       ?></textarea>
                                   </div>
                               </div>
                           </div>															

                           <div class="row">
                               <div class="col-sm-8 col-sm-offset-2">
                                   <button type="submit" name="submit" value="Update" id="submit" class="btn-primary btn">Update</button>
                               </div>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
       <!--//grid-->

       <!-- script-for sticky-nav -->
       <script>
           $(document).ready(function() {
               var navoffeset=$(".header-main").offset().top;
               $(window).scroll(function(){
                   var scrollpos=$(window).scrollTop(); 
                   if(scrollpos >=navoffeset){
                       $(".header-main").addClass("fixed");
                   } else {
                       $(".header-main").removeClass("fixed");
                   }
               });
           });
       </script>
       <!-- /script-for sticky-nav -->

       <!--inner block start here-->
       <div class="inner-block"></div>
       <!--inner block end here-->

       <!--copy rights start here-->
       <?php include('includes/footer.php');?>
       <!--COPY rights end here-->
   </div>
   </div>
   <!--//content-inner-->

   <!--/sidebar-menu-->
   <?php include('includes/sidebarmenu.php');?>
   <div class="clearfix"></div>		
</div>

<script>
    var toggle = true;							
    $(".sidebar-icon").click(function() {                
        if (toggle) {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
        } else {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
                $("#menu span").css({"position":"relative"});
            }, 400);
        }
        toggle = !toggle;
    });
</script>

<!--js -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>
</html>
<?php } ?>
