<?php

error_reporting(0);
if($_GET['action'] == 'quit'){
    
    session_start();
    unset($_SESSION['Admin']);
}

if(!empty($_POST['Admin'])){
 
    include('connect.php');
    
   
    $logincall = login($_POST['Admin'],$_POST['Password']);
    //$logincall['Ack'] == 'Success';
    if($logincall['Ack'] == 'Success'){ //验证成功
        session_start();
        $_SESSION['Admin'] = trim($_POST['Admin']);
        header("location:index.php");
    }else{
        $tips = $logincall['Info'];
        echo "<script>alert('$tips');</script>";
    }
}
?>
<!DOCTYPE html>
<html class="login-bg">
<head>
	<title>邮件发送系统登陆页</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="css/lib/font-awesome.css">
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/signup.css" type="text/css" media="screen" />


    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header">
            <img src="img/logo.png" class="logo" />
    </div>
    <div class="login-wrapper">
        <div class="box">
			<form action='login.php' method='post'>
            <div class="content-wrap">
                <h6>登录框</h6>
                <input class="form-control" type="text" name='Admin' placeholder="管理员名称" />
                <input class="form-control" type="password" name="Password" placeholder="管理员密码" />
                <div class="action">
                    <button class="btn-glow primary signup" >登录</button>
                </div>                
            </div>
			</form>
        </div>

    </div>

	<!-- scripts -->
    <script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>