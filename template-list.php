<?php
include('connect.php');
if($_GET['action'] == 'del' && !empty($_GET['TemplateID'])){
    $TemplateID = trim($_GET['TemplateID']);
    $query_del = mysql_query("delete from `template` where `TemplateID`='$TemplateID' limit 1");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>模板列表</title>
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
                        <h4>模板<small>列表</small></h4>
                    </div>
                </div>

                <div class="row filter-block">
                    <div class="col-md-8 col-md-offset-5">
                        <form action="<?=$now_script?>" method='post'">
                            <input type="text" class="search" name='TemplateName' placeholder="请输入关键字" />
    						<button class="btn-flat small">立即查询</button>
                        </form>
                        
                    </div>
                    <a href="template-edit.php?action=add" class="btn-flat success pull-right">
                        <span>&#43;</span>
						添加模板
                        </a>
                </div>

                <div class="row">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-3">
                                    排序
                                </th>
                                <th class="col-md-3">
                                    <span class="line"></span>标题
                                </th>
                                <th class="col-md-3">
                                    <span class="line"></span>上传日期
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(empty($_POST['TemplateName'])){
                                $get_template = mysql_query("select * from `template` limit $offset,$pernum ");
                            }else{
                                $TemplateName = trim($_POST['TemplateName']);
                                $get_template = mysql_query("select * from `template` where `TemplateName` like '%$TemplateName%' or `TemplateContent` like '%$TemplateName%' order by `InDate` desc ");
                            }
                            $order = 1;
                            while($info = mysql_fetch_assoc($get_template)){
                                
                        ?>    
                            <!-- row -->
                            <tr>
                                <td>
                                    <?=$order?>
                                </td>
                                <td class="description">
                                    <?=$info['TemplateName']?>
                                </td>
                                <td>
									<span class="label label-info"><?=$info['InDate']?></span>
                                    <ul class="actions">
                                        <li><a href='template-edit.php?action=edit&TemplateID=<?=$info['TemplateID']?>'><i class="table-edit" title='编辑'></i></a></li>
                                        <li class="last"><a href="template-list.php?action=del&TemplateID=<?=$info['TemplateID']?>&page=<?=$page?>" onclick='return confirm("确认删除？")'><i class="table-delete" title='删除' ></i></a></li>
                                    </ul>
                                </td>
                            </tr>
						  <?php
                            $order++;
                          }
                          ?>	
						</tbody>
                    </table>
                </div>
                <?php
                if(empty($TemplateName)){
                    $ul = get_page('template',$now_script);
                    echo $ul;    
                }
                
                ?>
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