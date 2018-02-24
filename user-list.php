<?php
include('connect.php');
if(!empty($_GET['action']) && $_GET['action'] == 'add'){
    //获取user_Ready数据
    $inuser = mysql_query("insert into `user`(`Username`,`Email`,`Sex`,`Company`,`Group`) select (`Username`,`Email`,`Sex`,`Company`,`Group`) from `user_ready`");
    //清空临时表
    mysql_query("truncate table `user_ready`");
}
if($_GET['action'] == 'del' && !empty($_GET['UserID'])){
    $UserID = trim($_GET['UserID']);
    $query_del = mysql_query("delete from `user` where `UserID`='$UserID' limit 1");
    if(mysql_affected_rows()){
        echo "<script>alert('删除成功!')</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>用户列表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/compiled/layout.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/elements.css">
    <link rel="stylesheet" type="text/css" href="css/compiled/icons.css">

    <!-- libraries -->
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/user-list.css" type="text/css" media="screen" />

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
                <span class="icon"></span><span class="text">默认</span>
            </a>
            <a href="#" class="skin second_nav" data-file="css/compiled/skins/dark.css">
                <span class="icon"></span><span class="text">黑底</span>
            </a>
        </div>
        
        <div id="pad-wrapper" class="users-list">
            <div class="row header">
                <h3>用户列表</h3>
                <form action="<?=$now_script?>" method="POST">
                <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                    
                        <input type="text" class="col-md-5 search" placeholder="请输入用户名/邮箱..." name="Username" />
                        
                        <select name="GroupID">
                            <option value=''>选择分组</option>
                        <?PHP
                            $group = get_group();
                            if(!empty($group)){
                                foreach($group as $GroupID=>$GroupName){
                        ?>
                        <option value='<?=$GroupID?>'><?=$GroupName?></option>
                        <?php
                                }
                            }
                        ?>
                            
                        </select>
                        <button class="btn-flat small">立即查询</button>
                     
                    
                        <a href="user-edit.php" class="btn-flat success pull-right">
                        <span>&#43;</span>
						添加用户
                    </a>
                </div>
                </form>
            </div>

            <!-- Users table -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-2 sortable">
                                    姓名
                                </th>
                                <th class="col-md-3 sortable">
                                    <span class="line"></span>组别
                                </th>
                                <th class="col-md-2 sortable align-right">
                                    <span class="line"></span>Email
                                </th>
                                <th class="col-md-1 sortable align-right">
                                    <span class="line"></span>性别
                                </th>
                                <th class="col-md-2 sortable align-right">
                                    <span class="line"></span>公司
                                </th>
								<th class="col-md-2 sortable align-right">
                                    <span class="line"></span>操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(empty($_POST['Username'])){
                                $get_user = mysql_query("select * from `user`order by `UserID` desc limit $offset,$pernum ");
                            }else{
                                $Username = addslashes(trim($_POST['Username']));
                                $GroupID = $_POST['GroupID'];
                                if(empty($GroupID)){
                                    $get_user = mysql_query("select * from `user` where `Username` like '%$Username%' or `Email` like '%$Username%' order by `UserID` desc ");
                                
                                }else{
                                    $get_user = mysql_query("select * from `user` where `Group` '%$GroupID%' and  `Username` like '%$Username%' or `Email` like '%$Username%'   order by `UserID` desc ");
                               
                                }
                            }
                            while($info = mysql_fetch_assoc($get_user)){
                                $Group = $info['Group'];
                                $GroupStr = '';
                                $GroupID_arr = str_replace('|',',',$Group);
                                $get_group = mysql_query("select * from `group` where `GroupID` in ($GroupID_arr)");
                                while($group_info = mysql_fetch_assoc($get_group)){
                                    empty($GroupStr)?$GroupStr=$group_info['Groupname']:$GroupStr.='、'.$group_info['Groupname'];    
                                }
                        ?>
                        <!-- row -->
                        <tr class="first">
                            <td>
                                <a href="#" class="name"><?=$info['Username']?></a>
                            </td>
                            <td>
                                <a href="#" class="name"><?=$GroupStr?></a>
                                
                            </td>
                            <td class="align-right">
                                <a href="#"><?=$info['Email']?></a>
                            </td>
							<td class="align-right">
                                <a href="#"><?=$info['Sex']?></a>
                            </td>
                            <td class="align-right">
                                <a href="#"><?=$info['Company']?></a>
                            </td>
							 <td class="align-right">
								
                                <a href="message-edit.php?UserID=<?=$info['UserID']?>&action=send&type=other" title="发信"><i class='icon-envelope-alt'></i></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href='user-edit.php?action=edit&type=noready&UserID=<?=$info['UserID']?>'><i class="table-edit" title='编辑'></i></a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="<?=$now_script?>?action=del&UserID=<?=$info['UserID']?>&page=<?=$page?>" onclick='return confirm("确认删除？")'><i class="table-delete" title='删除' ></i></a>
                                  
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>                
            </div>
            <?php
            if(empty($Username)){
                $ul = get_page('user',$now_script);
                echo $ul;
            }
            ?>
            <!-- end users table -->
        </div>
    </div>
    <!-- end main container -->


	<!-- scripts -->
	<script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>