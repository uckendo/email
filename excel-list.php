<?php
include('connect.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Excel列表</title>
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
                <h3>Excel列表</h3>
                <div class="col-md-10 col-sm-12 col-xs-12 pull-right">
                    <form action="<?=$now_script?>" method="post">
                        <label>上传日期:</label>       
                        <input type="text" value="<?=date('m/d/Y',time())?>" name="InDate" class="form-control input-datepicker" style="width:180px;display:inline;" />
                        
                        <button class="btn-flat small" style='display:inline;'>立即查询</button>
                    </form>
                </div>
            </div>

            <!-- Users table -->
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-4 sortable">
                                    排序
                                </th>
                                <th class="col-md-3 sortable">
                                    <span class="line"></span>文件名
                                </th>
                                <th class="col-md-2 sortable">
                                    <span class="line"></span>上传日期
                                </th>
								<th class="col-md-2 sortable">
                                    <span class="line"></span>下载
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(empty($_POST['InDate'])){
                                $get_excel = mysql_query("select * from `excel` limit $offset,$pernum ");
                            }else{
                                $InDate = strtotime(trim($_POST['InDate']));
                                $InDate = date('Ymd',$InDate);
                                $get_excel = mysql_query("select * from `excel` where `ExcelName` like '%$InDate%' order by `ExcelID` desc ");
                            }
                            $order = $offset+1;
                            while($info = mysql_fetch_assoc($get_excel)){
                        ?>
                        <!-- row -->
                        <tr class="first">
                            <td>
                                <a href="#" class="name"><?=$order?></a>
                            
                            </td>
                            <td>
                                <?=$info['ExcelName']?>
                            </td>
                            <td>
                                <?=$info['InDate']?>
                            </td>
                            <td >
								<a href="upload/<?=date('Y_m',strtotime($info['InDate']))?>/<?=$info['ExcelName']?>" target="_blank">
                                <button class="btn">
									    <i class="icon-download-alt"></i>
								</button>
								</a>
                            </td>
                        </tr>
                        <?php
                            $order++;
                        }
                        ?>
               
                        </tbody>
                    </table>
                </div>                
            </div>
            <?php
            if(empty($ExcelName)){
                $ul = get_page('excel',$now_script);
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
	<script src="js/bootstrap.datepicker.js"></script>
	<script src="js/jquery.uniform.min.js"></script>
	<script src="js/select2.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>
<script type="text/javascript">
	$(function () {

		// add uniform plugin styles to html elements
		$("input:checkbox, input:radio").uniform();

		// select2 plugin for select elements
		$(".select2").select2({
			placeholder: "Select a State"
		});

		// datepicker plugin
		$('.input-datepicker').datepicker().on('changeDate', function (ev) {
			$(this).datepicker('hide');
		});
	});
</script>