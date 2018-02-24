<?php
include('connect.php');
if(!empty($_GET['action'])){
    $action = trim($_GET['action']);
    $type = trim($_GET['type']);
    if($type == 'other'){       //直接从用户列表添加过来
        $UserID = trim($_GET['UserID']);
        $Date = date('Y-m-d H:i:s',time());
        $user_query = mysql_query("select * from `user` where `UserID`='$UserID'");
        $user_info = mysql_fetch_assoc($user_query);
        $sql = "insert into `message_ready`(`ToEmail`,`Date`)values('".$user_info['Email']."','$Date');";
        //echo $sql;
        mysql_query($sql);
        $ReadyID = mysql_insert_id();
        $type = 'message_ready';
        $sql = "select * from `message_ready` as a join `user` as b where a.`ToEmail`=b.`Email` and  a.`ReadyID`='$ReadyID' limit 1";
        //echo $sql;
    }elseif($type == 'message'){
        $MessageID = trim($_GET['MessageID']);
        $sql = "select * from `message` as a join `user` as b  where a.`ToEmail`=b.`Email` and a.`MessageID`='$MessageID' limit 1";    
    }else{
        $ReadyID = trim($_GET['ReadyID']);
        $sql = "select * from `message_ready` as a join `user` as b where a.`ToEmail`=b.`Email` and  a.`ReadyID`='$ReadyID' limit 1";
    }
    //echo $sql;
    $query = mysql_query($sql);
    $info = mysql_fetch_assoc($query);
}else{
   
}
if(!empty($_POST)){
    $type = trim($_POST['type']);
   
    if($type == 'message_ready'){
        $ReadyID = $_POST['ReadyID'];
        $content = trim($_POST['editorValue']);
        $Status = $_POST['Status'];
        $title = trim($_POST['Title']);
        
        
        $name_old = array('[nc]','[xb]','[gsmc]');
        
        $get_user = mysql_query("select a.* from `user` as a join `message_ready` as b where a.`Email`=b.`ToEmail` and b.`ReadyID`='$ReadyID' LIMIT 1");
        $user_info = mysql_fetch_assoc($get_user);
      
        $get_name = mysql_query("select `Replace` from `placeholder` where `CNAME`='".$user_info['Sex'] ."' limit 1");
        $name_info = mysql_fetch_assoc($get_name);
        $user_info['Sex'] = $name_info['Replace'];
      
        
        $name_new = array($user_info['Username'],$user_info['Sex'],$user_info['Company']);
        
        $content = str_replace($name_old,$name_new,$content);
        
        $title = str_replace($name_old,$name_new,$title);            
        
        $query = mysql_query("update `message_ready` set `Content`='$content',`Status`='$Status',`Title`='$title' where `ReadyID`='$ReadyID'");
        //echo "update `message_ready` set `Content`='$content',`Status`='$Status' where `ReadyID`='$ReadyID'";
        mysql_query("insert into `message`(`Title`,`ToEmail`,`FromEmail`,`Content`) select `Title`,`ToEmail`,`FromEmail`,`Content` from `message_ready` where `Status`='OK'");
        
        mysql_query("delete from `message_ready` where `Status`='OK'");
        header("location:messagesend_list_ready.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>发信内容编辑</title>
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
    
    <script type="text/javascript" charset="utf-8" src="editor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="editor/ueditor.all.min.js"> </script>
	<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	<script type="text/javascript" charset="utf-8" src="editor/lang/zh-cn/zh-cn.js"></script>
        
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
        
        <??>
        <div id="pad-wrapper" class="form-page">
			<div class="row header">
                <div class="col-md-12">
                    <h3>去信内容编辑</h3>
                </div>                
            </div>
            <div class="row form-wrapper">
                <!-- left column -->
				<form action='<?=$now_script?>' method='post'>
                <input type="hidden" name="type" value="<?=$type?>" />
                <input type="hidden" name="ReadyID" value="<?=$ReadyID?>" />
                <div class="col-md-8 column">
                    
                        <div class="field-box">
                            <label>姓名:</label>
                            <div class="col-md-7">
                                <input class="form-control" type="text" name='name' value='<?=$info['Username']?>'/>
                            </div>                            
                        </div>
                                                   
                        <div class="field-box">
                            <label>Email:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text" name='email' value='<?=$info['Email']?>' />
                            </div>
                        </div>
                       
                        <div class="field-box">
                            <label>标题:</label>
                            <div class="col-md-7">
                                <input class="form-control inline-input" type="text" name='Title' value='<?=$info['Title']?>' />
                            </div>
                         </div>
                       
                                                  
                        <div class="field-box">
                            <label>去信:</label>
                            <div class="col-md-7">
                                <script id="editor" type="text/plain" style="width:800px;height:200px;"></script>
                                <!--
                                <textarea class="form-control" rows="4"><?=addslashes($info['Content'])?></textarea>
                                -->
                            </div>
                        </div>
                        
						<div class="field-box">
                            <label>立即发送:</label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox1" value="OK" name='Status'/> 是 
                            </label>
                            <label class="checkbox-inline">
                              <input type="radio" id="inlineCheckbox2" value="" name='Status' /> 否 
                            </label>

                        </div>
						
						<div class="col-md-11 field-box actions">
							<button type='submit' class="btn-glow primary">确&nbsp;&nbsp;&nbsp;&nbsp;认</button>
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

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');
     //初始化内容
    ue.addListener("ready", function () {
        ue.setContent('<?=trim($info['Content'])?>',true);
    });
    </script>
</body>
</html>