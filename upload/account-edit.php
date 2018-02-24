<?php
include('connect.php');

if(!empty($_POST)){
 
    extract($_POST);
    if(empty($Email) || empty($Smtp) || empty($Password)){
        echo "<script>alert('Email,Smtp,Password均不能为空');</script>";    
    }else{
        if(!empty($AccountID)){ //编辑
            $up = mysql_query("update `account` set `Email`='$Email',`Smtp`='$Smtp',`Password`='$Password',`Status`='$Status' where `AccountID`='$AccountID'");
            if($up){    //更新成功
                $get_accout = mysql_query("select * from `account` where `AccountID`='$AccountID'");
                $account_info = mysql_fetch_assoc($get_accout);       
                $tips = '更新成功';
            }
        }else{  //新增
            //检查账号是否存在
            $check = mysql_query("select `Email` from `account` where `Email`='$Email' limit 1");
            if(mysql_num_rows($check)>0){
                echo "<script>alert('$Email 账号已经存在')</script>";
                
            }else{
                $insql = "insert into `account`(`Email`,`Smtp`,`Password`,`Status`)values('$Email','$Smtp','$Password','$Status')";
                $query = mysql_query($insql);
                if(mysql_affected_rows()>0){    //插入数据成功
                    if($Status == 1){
                        $upother = mysql_query("update `account` set `Status`='0' where `Email`!='$Email'");
                    }
                    header('location:account-list.php');
                }else{
                    //echo $insql;
                    $tips = '插入数据失败，请重新尝试提交';
                }
            }    
        }
    }
}
if($_GET['action']){
	$action = trim($_GET['action']);
    if($action == 'del'){
        $AccountID = intval($_GET['AccountID']);
        //删除账号
        $query  = mysql_query("delete from `account` where `AccountID`='$AccountID'");
        if(mysql_affected_rows()>0){  //删除成功
            echo "<script>alert('删除成功！');window.location.href='$HTTP_REFERER';</script>";
        }else{
            echo "<script>alert('删除失败！');window.location.href='$HTTP_REFERER';</script>";    
        }
        
    }elseif($action=='edit'){
		$title = '账号编辑';
        $AccountID = intval($_GET['AccountID']);
        $get_accout = mysql_query("select * from `account` where `AccountID`='$AccountID'");
        $account_info = mysql_fetch_assoc($get_accout);
       
	}else{
		$title = '账号添加';
	}
}else{
	$title = '账号添加';
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
        <form action='account-edit.php' method='post'>
        <div id="pad-wrapper" class="form-page">
			<div class="row header">
                <div class="col-md-12">
                    <h3><?=$title?><?=empty($tips)?'':"&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>$tips</font>"?></h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
				<form action='new-user.php' method='post'>
                <?=empty($account_info['AccountID'])?'':"<input type='hidden' name='AccountID' value='".$account_info['AccountID']."' />"?>
                <div class="col-md-8 column">
                    
                        <div class="field-box">
                            <label>Email:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name='Email' value="<?=$account_info['Email']?>"/>
                            </div>                            
                        </div>
                        <div class="field-box">
                            <label>Smtp:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name='Smtp' <?=empty($account_info)?'placeholder="smtp.comecho.de"':"value='".$account_info['Smtp']."'"?>/>
                            </div>                            
                        </div>  
                        <div class="field-box">
                            <label>登录密码:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text"  name='Password' value="<?=empty($account_info['Password'])?'':$account_info['Password']?>" />
                            </div>
                        </div>
                       
						<div class="field-box">
                            <label>是否启用:</label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox1" value="1" name='Status' <?=empty($account_info['Status'])?'':"checked='checked'"?> /> 是 
                            </label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox2" value="0" name='Status' <?=empty($account_info['Status'])?"checked='checked'":''?> /> 否 
                            </label>

                        </div> 
                        
						<div class="col-md-11 field-box actions">
							<button type='submit' class="btn-glow primary">提交数据</button>
						</div>
                    
                </div>
				</form>
			</div>
        </div>
		</form>
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
    <script type="text/javascript">
        $(function () {

            // add uniform plugin styles to html elements
            $("input:checkbox, input:radio").uniform();

            // select2 plugin for select elements
            $(".select2").select2({
                placeholder: "请选择群组"
            });

            // datepicker plugin
            $('.input-datepicker').datepicker().on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
        });
    </script>
</body>
</html>