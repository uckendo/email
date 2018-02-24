<?php
include('connect.php');

$title = '性别占位符';
if(!empty($_POST)){
    foreach($_POST as $Sex=>$value){
        $value = trim($value);
        mysql_query("update `placeholder` set `Replace`='$value' where `ENAME`='$Sex'");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

    <!-- libraries -->
    <link href="css/lib/uniform.default.css" type="text/css" rel="stylesheet">
    <link href="css/lib/select2.css" type="text/css" rel="stylesheet">
    <link href="css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet">
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/form-showcase.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
	<?php

	
		include('menu.php');

	?>
	<!-- main container -->
    <div class="content">
        
        <!-- settings changer -->
        <div class="skins-nav">
            <a href="#" class="skin first_nav selected">
                <span class="icon"></span><span class="text">默认皮肤</span>
            </a>
            <a href="#" class="skin second_nav" data-file="css/compiled/skins/dark.css">
                <span class="icon"></span><span class="text">深色皮肤</span>
            </a>
        </div>
        
        <div id="pad-wrapper" class="form-page">
			<div class="row header">
                <div class="col-md-12">
                    <h3><?=$title?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$tips?></h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
				<form action='<?=$now_script?>' method='post'>
                <div class="col-md-8 column">
                    <?php
                        $get_place = mysql_query("select * from `placeholder`");
                        while($info = mysql_fetch_assoc($get_place)){
                    ?>
                        <div class="field-box">
                            <label>性别:</label>
                            <label >
                            <?=$info['CNAME']?>
                            </label>
                            <label >
                                <input class="inline-input" style="width: 300px;" type="text"  name='<?=$info['ENAME']?>' value='<?=$info['Replace']?>'/>
                            </label>
                        </div> 
                    <?php
                        }
                    ?>                
    
					<div class="col-md-11 field-box actions">
					<button type='submit' class="btn-glow primary">确认-<?=$title?></button>
					</div>
					
				</div>
				</form>
				
			</div>
        </div>

	</div>
    <!-- end main container -->

	<!-- scripts for this page -->
    <script src="js/wysihtml5-0.3.0.js"></script>
    <script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.datepicker.js"></script>
    <script src="js/jquery.uniform.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
    <!-- call this page plugins -->
</body>
</html>