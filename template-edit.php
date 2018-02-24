<?php
include('connect.php');
if(!empty($_POST)){
    $TemplateName = trim($_POST['TemplateName']);
    $TemplateContent = trim($_POST['editorValue']);
    $TemplateID = trim($_POST['TemplateID']);
    if(empty($TemplateName) || empty($TemplateContent)){
        echo "<script>alert('模板名称、模板内容均不能为空');</script>";    
    }else{
        $date = date('Y-m-d H:i:s',time());
        if(!empty($TemplateID)){ //编辑
            $up = mysql_query("update `template` set `TemplateName`='$TemplateName',`InDate`='$date',`TemplateContent`='$TemplateContent' where `TemplateID`='$TemplateID'");
            if(mysql_affected_rows()>0){    //更新成功
                $get_template = mysql_query("select * from `template` where `TemplateID`='$TemplateID'");
                $info = mysql_fetch_assoc($get_template);       
                $tips = '更新成功';
            }
        }else{  //新增
            $insql = "insert into `template`(`TemplateName`,`TemplateContent`,`InDate`)values('$TemplateName','$TemplateContent','$date')";
            $query = mysql_query($insql);
            if(mysql_affected_rows()>0){    //插入数据成功
                header('location:template-list.php');
            }else{
                $tips = '插入数据失败，请重新尝试提交';
            }    
        }
    }
}
if($_GET['action'] == 'edit'){
    $title = '模板编辑';
    $TemplateID = trim($_GET['TemplateID']);
    $query = mysql_query("select * from `template` where `TemplateID`='$TemplateID'");
    $info = mysql_fetch_assoc($query);
}else{
    $title = '模板添加';
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
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
		<script type="text/javascript" charset="utf-8" src="editor/ueditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="editor/ueditor.all.min.js"> </script>
		<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
		<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
		<script type="text/javascript" charset="utf-8" src="editor/lang/zh-cn/zh-cn.js"></script>
        <form action="<?=$now_script?>" method="post"> 
        <?=empty($info['TemplateID'])?'':"<input type='hidden' name='TemplateID' value='".$info['TemplateID']."' />"?>
                
        <div>
			<h3><?php echo $title?>&nbsp;&nbsp;&nbsp;&nbsp;<?=$tips?></h3>
             <br />
			<h4>模板名称:<input type="text" name="TemplateName" value="<?=$info['TemplateName']?>" style="width: 250px;"/></h4>
            <br />
            <script id="editor" type="text/plain" style="width:1024px;height:400px;"></script>
            
		</div>
		<div id="btns">
			<div>
				<button type="submit" >确认-<?=$title?></button>
            </div>
		</div>
        </form>
        
<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');
     //初始化内容
    ue.addListener("ready", function () {
        ue.setContent('<?=trim(stripcslashes($info['TemplateContent']))?>',true);
    });

    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
</script>



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