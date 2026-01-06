<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['submit1']))
{
    $fname=$_POST['fname'];
    $email=$_POST['email'];	
    $mobile=$_POST['mobileno'];
    $subject=$_POST['subject'];	
    $description=$_POST['description'];
    $sql="INSERT INTO  tblenquiry(FullName,EmailId,MobileNumber,Subject,Description) VALUES(:fname,:email,:mobile,:subject,:description)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
    $query->bindParam(':subject',$subject,PDO::PARAM_STR);
    $query->bindParam(':description',$description,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
        $msg="Enquiry Successfully submitted";
    }
    else 
    {
        $error="Something went wrong. Please try again";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>TMS | Tourism Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8" />
<meta name="keywords" content="Tourism Management System In PHP" />
</head>
<body style="
  background: url('image/1.jpg') no-repeat center center fixed; 
  background-size: cover; 
  font-family: Arial, sans-serif;
  ">


<div style="max-width:400px; margin:30px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.15);">

    <h3 style="color:#222; text-align:center; margin-bottom:25px;">Enquiry Form</h3>

    <?php if($error){?>
        <div style="padding:12px; margin-bottom:20px; background:#ffdddd; border-left:5px solid #dd3d36; color:#a33; font-weight:bold;">
            ERROR: <?php echo htmlentities($error); ?>
        </div>
    <?php } else if($msg){?>
        <div style="padding:12px; margin-bottom:20px; background:#ddffdd; border-left:5px solid #4CAF50; color:#2a662a; font-weight:bold;">
            SUCCESS: <?php echo htmlentities($msg); ?>
        </div>
    <?php }?>

    <form name="enquiry" method="post" style="display:flex; flex-direction:column; gap:15px;">

        <label for="fname" style="color:#222; font-weight:bold;">Full Name</label>
        <input type="text" name="fname" id="fname" placeholder="Full Name" required=""
            style="padding:10px; border:2px solid #444; border-radius:8px; font-size:15px; color:#222; outline:none;
            transition: border-color 0.3s ease;"
            onfocus="this.style.borderColor='#007BFF';" onblur="this.style.borderColor='#444';"
        />

        <label for="email" style="color:#222; font-weight:bold;">Email</label>
        <input type="email" name="email" id="email" placeholder="Valid Email id" required=""
            style="padding:10px; border:2px solid #444; border-radius:8px; font-size:15px; color:#222; outline:none;"
            onfocus="this.style.borderColor='#007BFF';" onblur="this.style.borderColor='#444';"
        />

        <label for="mobileno" style="color:#222; font-weight:bold;">Mobile No</label>
        <input type="text" name="mobileno" id="mobileno" maxlength="10" placeholder="10 Digit mobile No" required=""
            style="padding:10px; border:2px solid #444; border-radius:8px; font-size:15px; color:#222; outline:none;"
            onfocus="this.style.borderColor='#007BFF';" onblur="this.style.borderColor='#444';"
        />

        <label for="subject" style="color:#222; font-weight:bold;">Subject</label>
        <input type="text" name="subject" id="subject" placeholder="Subject" required=""
            style="padding:10px; border:2px solid #444; border-radius:8px; font-size:15px; color:#222; outline:none;"
            onfocus="this.style.borderColor='#007BFF';" onblur="this.style.borderColor='#444';"
        />

        <label for="description" style="color:#222; font-weight:bold;">Description</label>
        <textarea name="description" id="description" rows="6" placeholder="Description" required=""
            style="padding:10px; border:2px solid #444; border-radius:8px; font-size:15px; color:#222; resize:none; outline:none;"
            onfocus="this.style.borderColor='#007BFF';" onblur="this.style.borderColor='#444';"
        ></textarea>

        <button type="submit" name="submit1" 
            style="padding:12px; background:#007BFF; border:none; border-radius:8px; color:#fff; font-weight:bold; font-size:16px; cursor:pointer; transition: background-color 0.3s ease;">
            Submit
        </button>

    </form>
</div>

</body>
</html>
