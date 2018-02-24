<?php
include('connect.php');

if(!empty($_POST)){

    extract($_POST);
    
    if(empty($Email) || empty($Username)){
        echo "<script>alert('邮箱和姓名均不能为空');</script>";    
    }else{
        $GroupStr = '';
        if(!empty($group)){
            foreach($group as $GroupID){
                empty($GroupStr)?$GroupStr=$GroupID:$GroupStr.='|'.$GroupID;
            }
        }
        //查重
        if(!empty($UserID)){ 
            
            if($type == 'ready'){//用户临时表
                
                $up = mysql_query("update `user_ready` set `Email`='$Email',`Username`='$Username',`Sex`='$Sex',`Company`='$Company',`Group`='$GroupStr' where `UserID`='$UserID'");
                if($up){    //更新成功
                    $get_user = mysql_query("select * from `user_ready` where `UserID`='$UserID'");
                    $user_info = mysql_fetch_assoc($get_user);       
                    $tips = '更新成功';
                    $title = '编辑用户';
                }       
                
            }else{
                $check = mysql_query("select * from `user` where `Email`='$Email' and `UserID`!='$UserID' limit 1");
                if(mysql_num_rows($check)>0){
                    echo "<script>alert('更换的 $Email 已经存在')</script>";
                    $get_user = mysql_query("select * from `user` where `UserID`='$UserID'");
                    $user_info = mysql_fetch_assoc($get_user);       
                    $tips = '更新失败';
                    $title = '编辑用户';
                }else{
                    $up = mysql_query("update `user` set `Email`='$Email',`Username`='$Username',`Sex`='$Sex',`Company`='$Company',`Group`='$GroupStr' where `UserID`='$UserID'");
                    if($up){    //更新成功
                        $get_user = mysql_query("select * from `user` where `UserID`='$UserID'");
                        $user_info = mysql_fetch_assoc($get_user);       
                        $tips = '更新成功';
                        $title = '编辑用户';
                    } 
                }
            }
            
        }else{  //新增
            //查重
            $check = mysql_query("select * from `user` where `Email`='$Email' limit 1");
            if(mysql_num_rows($check)>0){
                echo "<script>alert('$Email 已经存在')</script>";
            }else{
                $insql = "insert into `user`(`Username`,`Email`,`Sex`,`Company`,`Group`)values('$Username','$Email','$Sex','$Company','$GroupStr')";
                $query = mysql_query($insql);  
                if(mysql_affected_rows()>0){    //插入数据成功
                    header('location:user-list.php');
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
        $type = trim($_GET['type']);
        
		$title = '编辑用户';
        $UserID = intval($_GET['UserID']);
        if($type == 'ready'){
           $sql = "select * from `user_ready` where `UserID`='$UserID'" ;
        }else{
            $sql = "select * from `user` where `UserID`='$UserID'" ;
        }

        $get_user = mysql_query($sql);
        $user_info = mysql_fetch_assoc($get_user);
	}else{
		$title = '添加用户';
	}
}else{
    if(empty($title)){
        $title = '添加用户';    
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
                <input type="hidden" name="type" value="<?=$type?>"/>
                <?=empty($user_info['UserID'])?'':"<input type='hidden' name='UserID' value='".$user_info['UserID']."' />"?>
                <div class="col-md-8 column">
                    
                        <div class="field-box">
                            <label>姓名:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name='Username' value="<?=$user_info['Username']?>"/>
                            </div>                            
                        </div>
                        <div class="field-box">
                            <label>性别:</label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox1" value="男" name='Sex' <?=$user_info['Sex'] =='男'?"checked='checked'":''?> /> 男 
                            </label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox2" value="女" name='Sex'  <?=$user_info['Sex'] =='男'?'':"checked='checked'"?>/> 女 
                            </label>

                        </div>                           
                        <div class="field-box">
                            <label>Email:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text"  name='Email' value='<?=$user_info['Email']?>'/>
                            </div>
                        </div>
                       
                       <div class="field-box">
                            <label>公司:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text"  name='Company' value="<?=$user_info['Company']?>" />
                            </div>
                        </div>
                        
                    
                </div>
                <?php
                    $select = get_group();
                    $group_arr = array();
                    if(!empty($user_info['Group'])){
                        $group_arr = explode("|",$user_info['Group']);
                        
                    }
                ?>
                <!-- right column -->
                        <div class="col-md-4 column pull-right">
                    
                        <div class="field-box">
                            <label>分组:</label>
                            <select style="width:250px;height:80px;" multiple class="select2" name='group[]'>
                            <?php
                            foreach($select as $GroupID=>$Groupname){
                                
                                 if(in_array($GroupID,$group_arr)){
                            ?>
                                <option value="<?=$GroupID?>" selected="selected"><?=$Groupname?></option>
                                    
                            <?php
                                    }else{
                            ?>
                            <option value="<?=$GroupID?>"><?=$Groupname?></option>
                            <?php
                                    }        
                                }
                            ?>
                               
                            </select>
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
    <!-- call this page plugins -->
    <script type="text/javascript">
        $(function () {

            // add uniform plugin styles to html elements
            $("input:checkbox, input:radio").uniform();

            // select2 plugin for select elements
            $(".select2").select2({
                placeholder: "请选择分组"
            });

            // datepicker plugin
            $('.input-datepicker').datepicker().on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
        });
    </script>
</body>
</html>