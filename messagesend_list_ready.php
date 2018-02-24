<?php
include('connect.php');
set_time_limit(0);
$Date = date('Y-m-d H:i:s',time());
if(!empty($_GET['action']) && $_GET['action'] == 'send'){
    if(!empty($_GET['UserID'])){
        $UserID = trim($_GET['UserID']);
        $insql = "insert into `message_ready`(`ToEmail`) select `Email` from `user` where `UserID`='$UserID''";
        mysql_query($insql);    
    }elseif(!empty($_GET['GroupID'])){
        $GroupID = trim($_GET['GroupID']);
        //获取正在使用的发件人邮箱
        $get_send = mysql_query("select `Email` from `account` where `Status`=1 ");
        $send_info = mysql_fetch_assoc($get_send);
        $send_email = $send_info['Email'];
        if(empty($send_email)){
            echo "<script>alert('当前没有可以使用的发件人配置');</script>";
            header("location:account-list.php");
        }
        $insql = "insert into `message_ready`(`ToEmail`) select `Email` from `user` where `Group` like '%$GroupID%'";
        //echo $insql;
        mysql_query($insql);
        $affect_rows  = mysql_affected_rows();
        $up = mysql_query("update `message_ready` set `GroupID`='$GroupID',`FromEmail`='$send_email',`Date`='$Date' order by `ReadyID` desc limit $affect_rows");
    }
}

if($_GET['action'] == 'del' && !empty($_GET['ReadyID'])){
    $ReadyID = trim($_GET['ReadyID']);
    $query_del = mysql_query("delete from `message_ready` where `ReadyID`='$ReadyID' limit 1");
}

if(!empty($_POST['Update'])){
    
    $Template = $_POST['Template'];
    $Ready = $_POST['Ready'];
    $Status = empty($_POST['Status'])?'':trim($_POST['Status']);
   
    //处理信件内容
    if(!empty($Ready)){
        $TemplateIDAll = 0;
        $name_old = array('[nc]','[xb]','[gsmc]');
        $content = '';
        foreach($Ready as $key=>$ReadyID){
                $TemplateID = $Template[$ReadyID];
                if(!empty($TemplateID)){
                if(empty($content) && $_POST['checkall'] == 1){  //全部统一
                    //获取用户[名字]，[性别],[公司]
                    $get_user = mysql_query("select a.* from `user` as a join `message_ready` as b where a.`Email`=b.`ToEmail` and b.`ReadyID`='$ReadyID' LIMIT 1");
                    $user_info = mysql_fetch_assoc($get_user);
                  
                    $get_name = mysql_query("select `Replace` from `placeholder` where `CNAME`='".$user_info['Sex'] ."' limit 1");
                    $name_info = mysql_fetch_assoc($get_name);
                    $user_info['Sex'] = $name_info['Replace'];
                  
                    
                    $name_new = array($user_info['Username'],$user_info['Sex'],$user_info['Company']);
                    
                    $get_template = mysql_query("select `TemplateContent`,`Title` from `template` where `TemplateID`='$TemplateID' limit 1");
                    $template_info = mysql_fetch_assoc($get_template);
                    
                    $content = $template_info['TemplateContent'];  
                    $title = $template_info['Title'];      
                }elseif($_POST['checkall'] !== 1){
                    //获取用户[名字]，[性别],[公司]
                    $get_user = mysql_query("select a.* from `user` as a join `message_ready` as b where a.`Email`=b.`ToEmail` and b.`ReadyID`='$ReadyID' LIMIT 1");
                    $user_info = mysql_fetch_assoc($get_user);
                    
                    $get_name = mysql_query("select `Replace` from `placeholder` where `CNAME`='".$user_info['Sex'] ."' limit 1");
                    $name_info = mysql_fetch_assoc($get_name);
                    $user_info['Sex'] = $name_info['Replace'];
                  
                    $name_new = array($user_info['Username'],$user_info['Sex'],$user_info['Company']);
                    
                    $get_template = mysql_query("select `TemplateContent`,`Title` from `template` where `TemplateID`='$TemplateID' limit 1");
                    $template_info = mysql_fetch_assoc($get_template);
                    
                    $content = $template_info['TemplateContent']; 
                    $title = $template_info['Title'];      
                }
                 
                $message = str_replace($name_old,$name_new,$content);
                $title = str_replace($name_old,$name_new,$title);
                
                if(!empty($TemplateIDAll)){
                    //获取模板
                    if(empty($template_info)){
                        echo "<script>alert('模板不存在);</script>";
                        break;
                    }else{
                        
                        //$up_ready = mysql_query("update `message_ready` set `Title`='$title',`Status`='$Status',`Content`='$message',`TemplateID`='$TemplateID',`Date`='$Date' where `ReadyID`='$ReadyID'");
                    }
                }
                
                    $up_ready = mysql_query("update `message_ready` set `Title`='$title',`Status`='$Status',`Content`='$message',`TemplateID`='$TemplateID',`Date`='$Date' where `ReadyID`='$ReadyID'");
                  
                }else{
                   
                    $up_ready = mysql_query("update `message_ready` set `Status`='$Status',`Date`='$Date' where `ReadyID`='$ReadyID'");
                      
                }
                
        }
    }
    mysql_query("insert into `message`(`Title`,`ToEmail`,`FromEmail`,`Content`,`Date`) select `Title`,`ToEmail`,`FromEmail`,`Content`,`Date` from `message_ready` where `Status`='OK'");
    mysql_query("delete from `message_ready` where `Status`='OK'");
}

//获取总数
$get_total = mysql_query("select count(`ReadyID`) as total from `message_ready`");
$total_info = mysql_fetch_assoc($get_total);
$total = empty($total_info['total'])?'0':$total_info['total'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>发信预处理页面</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- libraries -->
    <link href="css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">

    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />

    <!-- open sans font -->

    <!-- lato font -->

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

        <div id="pad-wrapper">

            <!-- table sample -->
            <!-- the script for the toggle all checkboxes from header is located in js/theme.js -->
            <div class="table-products section">
                <div class="row head">
                    <div class="col-md-12">
                        <h4>发信预处理<small>页面</small>需处理<?=$total?>封</h4>
                        
                    </div>
                </div>
                
                <form action="<?=$now_script?>" method="post">   
                <div class="row filter-block">
                    <div class="col-md-8 col-md-offset-5">
                        <div class="ui-select">
                            <select name="DataType">
								<option>查找类型</option>
								<option value="User">用户</option>
								<option value="Template">模板</option>
                            </select>
                        </div>
                        <input type="text" class="search" name="Data" />
						<button class="btn-flat small">立即查询</button>
                    </div>
                </div>
                </form>
                
                <div class="row">
                <form action="<?=$now_script?>" method="post">
                <input type="hidden"  name="Update" value="1"/>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-4">
                                    <input type="checkbox" name="checkall" value="1" />
                                    全选【统一使用第一个设置的模板】
                                </th>
                                <th class="col-md-3">
                                    <span class="line"></span>组别
                                </th>
								<th class="col-md-3">
                                    <span class="line"></span>发信模板
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        
                         <?php
                            //获取全部发信模板标题
                            $template_arr = get_template();
                           
                            if(empty($_POST['Data'])){
                                //$get_ready = mysql_query("select * from `message_ready` as a join `user` as b join `group` as c  where a.`ToEmail`=b.`Email`   order by a.`Content` desc  limit $offset,$pernum ");
                                $get_ready = mysql_query("select * from `message_ready` as a join `user` as b   where a.`ToEmail`=b.`Email`  order by a.`ReadyID` desc  limit $offset,$pernum ");
                            
                            }else{
                                $Data = trim($_POST['Data']);
                                $DataType = trim($_POST['DataType']);
                                if($DataType =='User'){
                                   $findsql = "select * from `message_ready` as a join `user` as b  where a.`ToEmail`=b.`Email`   and b.`Email` like '%$Data%' or b.`Username` like '%$Data%' order by a.`ReadyID` desc ";
                                    
                                }elseif($DataType =='Template'){
                                   $findsql = "select * from `message_ready` as a join `user` as b   where a.`ToEmail`=b.`Email`   and b.`Content` like '%$Data%' order by a.`ReadyID` desc ";
                                    
                                }else{
                                    $findsql = "select * from `message_ready` as a join `user` as b   where a.`ToEmail`=b.`Email`   order by a.`ReadyID` desc ";
                                }
                                $InDate = strtotime(trim($_POST['InDate']));
                                $InDate = date('Ymd',$InDate);
                                $get_ready = mysql_query($findsql);
                            }
                            
                            while($info = mysql_fetch_assoc($get_ready)){
                                $GroupID = $info['GroupID'];
                                $ReadyID = $info['ReadyID'];
                                $name = "Template[$ReadyID]";
                                $template_arr_select = array_to_select($template_arr,$name,$GroupID);
                                $Groupname = get_groupname($info['GroupID'])
                         ?>
                        
                            <!-- row -->
                            <tr>
                                <td>
                                    <input type="checkbox" name="Ready[]" value="<?=$info['ReadyID']?>" />
                                    <a href="#"><?=$info['Email']?>--<?=$info['Username']?></a>
                                </td>
						        <td><?=$Groupname?></td> 
                                
                                <td><?=$template_arr_select?></td>
                                <td>
									<span class="label label-info">Standby</span>
                                    <ul class="actions">
                                        <li><a href="message-edit.php?action=edit&type=message_ready&ReadyID=<?=$info['ReadyID']?>"><i class="table-edit" title='编辑'></i></a></li>
                                        <li class="last"><a href="<?=$now_script?>?action=del&ReadyID=<?=$info['ReadyID']?>" onclick='return confirm("确认删除？")'><i class="table-delete" title='删除'></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        
						</tbody>
                    </table>
                    
                </div>
				立即发送<input type="checkbox" name="Status" value='OK' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button class="btn-flat small">提交</button>
                <?php
                if(empty($Data)){
                    $ul = get_page('message_ready',$now_script);
                    echo $ul;    
                }
                ?>
                </form>    
            </div>
            <!-- end table sample -->
        </div>
    </div>


	<!-- scripts -->
    <script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.stack.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/theme.js"></script>

    
</body>
</html>