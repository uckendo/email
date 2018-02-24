<?php
include('connect.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>群组列表</title>
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
                <span class="icon"></span><span class="text">默认皮肤</span>
            </a>
            <a href="#" class="skin second_nav" data-file="css/compiled/skins/dark.css">
                <span class="icon"></span><span class="text">深色皮肤</span>
            </a>
        </div>
        
        <div id="pad-wrapper" class="users-list">
            <div class="row header">
                <h3>用户分组</h3>
                <form action="<?=$now_script?>" method="post">
                <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                    <input type="text" class="col-md-5 search" placeholder="请输入群组名..." name='Groupname'/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn-flat small">立即查询</button>

                    <a href="group-edit.php?action=add" class="btn-flat success pull-right">
                        <span>&#43;</span>
						添加群组
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
                                <th class="col-md-4 sortable">
                                    组别
                                </th>
                                <th class="col-md-3 sortable">
                                    <span class="line"></span>成员数量
                                </th>
                                <th class="col-md-2 sortable">
                                    <span class="line"></span>已发信息
                                </th>
                                <th class="col-md-3 sortable align-right">
                                    <span class="line"></span>操作
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(empty($_POST['Groupname'])){
                                $get_group = mysql_query("select * from `group` limit $offset,$pernum ");
                            }else{
                                $Groupname = addslashes(trim($_POST['Groupname']));
                                $get_group = mysql_query("select * from `group` where `Groupname` like '%$Groupname%' order by `GroupID` desc ");
                            }
                            while($info = mysql_fetch_assoc($get_group)){
                                $GroupID = $info['GroupID'];
                                $get_num = mysql_query("select count(*) as total from `user` where `Group` like '%$GroupID%'");
                                $get_num_result = mysql_fetch_assoc($get_num);
                                
                        ?>
                        <!-- row -->
                        <tr class="first">
                            <td>
                                <a href="#" class="name"><?=$info['Groupname']?></a>
                            </td>
                            <td>
                                当前分组:<?=empty($get_num_result['total'])?'0':$get_num_result['total']?>个用户
                            </td>
                            <td>
                                
                            </td>
                            <td class="align-right">
                                <a href="messagesend_list_ready.php?GroupID=<?=$info['GroupID']?>&action=send" title="发信"><i class='icon-envelope-alt'></i></a>
                                &nbsp;&nbsp;&nbsp;
                                <a href='group-edit.php?action=edit&GroupID=<?=$info['GroupID']?>'><i class="table-edit" title='编辑'></i></a>
                                &nbsp;&nbsp;&nbsp;
                                <a href='group-edit.php?action=del&GroupID=<?=$info['GroupID']?>' onclick='return confirm("确认删除？")'><i class="table-delete" title='删除'></i></a>
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
            if(empty($Groupname)){
                $ul = get_page('group',$now_script);
                echo $ul;    
            }
            
            ?>
            <!-- end users table -->
        </div>
    </div>
    <!-- end main container -->


	<!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>