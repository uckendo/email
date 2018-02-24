<?php
include('connect.php');
if(!empty($_POST)){
    extract($_POST);
    if(empty($Groupname)){
        echo "<script>alert('分组名称不能为空');</script>";    
    }else{
        if(!empty($GroupID)){ //编辑
            $up = mysql_query("update `group` set `Groupname`='$Groupname' where `GroupID`='$GroupID'");
            if($up){    //更新成功
                $get_group = mysql_query("select * from `group` where `GroupID`='$GroupID'");
                $group_info = mysql_fetch_assoc($get_group);       
                $title = '编辑分组';
                $tips = '更新成功';
            }
        }else{  //新增
            //检查分组是否存在
            $check = mysql_query("select `GroupID` from `group` where `Groupname`='$Groupname' limit 1");
            if(mysql_num_rows($check)>0){
                echo "<script>alert('$Groupname 分组已经存在！');</script>";
            }else{
                $insql = "insert into `group`(`Groupname`)values('$Groupname')";
                $query = mysql_query($insql);
                if(mysql_affected_rows()>0){    //插入数据成功
                    header('location:group-list.php');
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
        $GroupID = intval($_GET['GroupID']);
        //删除账号
        $query  = mysql_query("delete from `group` where `GroupID`='$GroupID'");
        if(mysql_affected_rows()>0){  //删除成功
            echo "<script>alert('删除成功！');window.location.href='$HTTP_REFERER';</script>";
        }else{
            echo "<script>alert('删除失败！');window.location.href='$HTTP_REFERER';</script>";    
        }
        
    }elseif($action=='edit'){
		$title = '编辑分组';
        $GroupID = intval($_GET['GroupID']);
        $get_group = mysql_query("select * from `group` where `GroupID`='$GroupID'");
        $group_info = mysql_fetch_assoc($get_group);
        
       
	}else{
		$title = '群组添加';
	}
}else{
    if(empty($title)){
        $title = '群组添加';    
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>用户群组编辑</title>
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
				<form action='group-edit.php' method='post'>
                <?=empty($group_info['GroupID'])?'':"<input type='hidden' name='GroupID' value='".$group_info['GroupID']."' />"?>
                
                <div class="col-md-8 column">
                    
                        <div class="field-box">
                            <label>群组名:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name='Groupname' value="<?=empty($group_info['Groupname'])?'':$group_info['Groupname']?>"/>
                            </div>                            
                        </div>                          
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

</body>
</html>