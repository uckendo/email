<?php
include('connect.php');

if(!empty($_POST['group'])){    //给用户选定分组
    set_time_limit(0);
    $group = $_POST['group'];
    foreach($group as $UserID=>$UserGroup){ 
        $GroupStr = '';
        foreach($UserGroup as $GroupID){
            empty($GroupStr)?$GroupStr=$GroupID:$GroupStr.='|'.$GroupID;
        }
        $up = "update `user_ready` set `Group`='$GroupStr' where `UserID`='$UserID'";
        $query = mysql_query($up);
    }
    
}
if($_GET['action'] == 'del' && !empty($_GET['UserID'])){
    $UserID = trim($_GET['UserID']);
    $query_del = mysql_query("delete from `user_ready` where `UserID`='$UserID' limit 1");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>用户选择分组</title>
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
                <span class="icon"></span><span class="text">默认</span>
            </a>
            <a href="#" class="skin second_nav" data-file="css/compiled/skins/dark.css">
                <span class="icon"></span><span class="text">黑底</span>
            </a>
        </div>
        
        <div id="pad-wrapper" class="users-list">
            <div class="row header">
                <h3>用户选择分组</h3>
                <form action="<?=$now_script?>" method="post">
                <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                    <input type="text" class="col-md-5 search" placeholder="请输入用户名..." name="Username" />
                    <button class="btn-flat" style="margin-top: 10px;">立即查询</button>
                </div>
                </form>
            </div>

            <!-- Users table -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-1 sortable">
                                    姓名
                                </th>
                                <th class="col-md-3 sortable">
                                    <span class="line"></span>组别
                                </th>
                                <th class="col-md-3 sortable align-right">
                                    <span class="line"></span>Email
                                </th>
								<th class="col-md-3 sortable align-right">
                                    <span class="line"></span>操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="<?=$now_script?>" method="post">
                        <?php
                            if(!empty($_POST['Username'])){
                                $Username = TRIM($_POST['Username']);
                                $get_user = mysql_query("select * from `user_ready` where `Username` like '%$Username%' order by `Group` desc   ");
                                
                            }ELSE{
                                $get_user = mysql_query("select * from `user_ready` order by `Group` desc  limit $offset,$pernum ");
                                
                            }
                            $select = get_group();
                            while($info = mysql_fetch_assoc($get_user)){
                                
                                $group_arr = array();
                                if(!empty($info['Group'])){
                                    $group_arr = explode("|",$info['Group']);
                                    
                                }
                                
                        ?>
                        <!-- row -->
                        <tr class="first">
                            <td>
                                <a href="user-edit.php?action=edit&id=1" class="name"><?=$info['Username']?></a>
                            </td>
                            <td>
                            <div class="row form-wrapper ">
                                <div class="col-md-4 column ">
                                    <div class="field-box">
                                    <label>群组:</label>
                                    <select style="width:250px;height:80px;" multiple class="select2" name='group[<?=$info['UserID']?>][]'>
                                        
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
                                </div>

                            </td>
                            <td class="align-right">
                                <?=$info['Email']?>
                            </td>
							
							 <td class="align-right">
									<ul class="actions">
                                        <li><a href='user-edit.php?action=edit&type=ready&UserID=<?=$info['UserID']?>'><i class="table-edit" title='编辑'></i></a></li>
                                        <li class="last"><a href="<?=$now_script?>?action=del&UserID=<?=$info['UserID']?>&page=<?=$page?>" onclick='return confirm("确认删除？")'><i class="table-delete" title='删除' ></i></a></li>
                                    </ul>
                              </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <button type="submit">提交</button> &nbsp;&nbsp;&nbsp;&nbsp;<a href="user-list.php?action=add">数据处理完毕</a>
                        </form>
                        </tbody>
                    </table>
                </div>                
            </div>
            
            <?php
            if(empty($Username)){
                $ul = get_page('user_ready',$now_script);
                echo $ul;
            }
            ?>
            <!-- end users table -->
        </div>
    </div>
    <!-- end main container -->


	<!-- scripts -->
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
                placeholder: ""
            });

            // datepicker plugin
            $('.input-datepicker').datepicker().on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });
        });
    </script>
</body>
</html>