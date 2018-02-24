<?php
include('connect.php');
header("Content-type: text/html; charset=utf-8"); 
if(!empty($_FILES)){
    include('function/phpexcel/PHPExcel/IOFactory.php');
    if($_POST['Type'] == 'In'){
        $DataType = trim($_POST['DataType']);
        
        error_reporting(E_ALL);
        
        $month = date('Y_m',time());
        $Indate = date('Y-m-d H:i:s',time());
        
        echo date('Y-m-d H:i:s',time());
        $file_name = date('Ymd_H_i_s',time()).'_'.$DataType.'.xls';
        $new_file_name = 'upload/'.$month.'/'.$file_name;
        if($_FILES['Excel']){
            if(!file_exists('upload/'.$month)){
                mkdir('upload/'.$month);
                chmod('upload/'.$month,777);
            }
            $path = $new_file_name;
            move_uploaded_file($_FILES['Excel']['tmp_name'],$path);
            
            
            
            $inputFileName = $path; //'example2.xls';
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            
            
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true); 
            foreach($sheetData as $key=>$eachline){
                $insertsql = '';
                if($key>1){
                    if($DataType == 'User'){
                        $Email = trim($eachline['A']);
                        $Username = trim($eachline['B']) ;
                        $Sex = trim($eachline['C']);
                        $Company = trim($eachline['D']);
                        $check_exist = mysql_query("select `Email` from `user` where `Email`='$Email'");
                        if(mysql_num_rows($check_exist)>0){ //该用户已经存在
                            
                        }else{
                            $insertsql = "insert into `user_ready`(`Username`,`Sex`,`Email`,`Company`)values('$Username','$Sex','$Email','$Company');";
                        }   
                    }else{
                        $TemplateName = trim($eachline['A']);
                        $Title =  trim($eachline['B']);
                        $TemplateContent= trim($eachline['C']);
                        if(!empty($TemplateName) && !empty($TemplateContent)){
                            $insertsql = "insert into `template`(`Title`,`TemplateName`,`TemplateContent`,`InDate`)values('$Title','$TemplateName','$TemplateContent','$Indate');";
                        }
                    }
                    if(!empty($insertsql)){
                        mysql_query($insertsql);     
                    }
                }
            }
            $query = mysql_query("insert into `excel`(`ExcelName`,`Floder`,`InDate`)values('$file_name','$month','$Indate');");
        }
    }else{
        
    }
    if($DataType =='User'){
        $gotourl = 'user-list-ready.php';    
    }else{
        $gotourl = 'template-list.php';
    }
    header("location:$gotourl");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Excel数据</title>
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
    <link rel="stylesheet" href="css/compiled/form-wizard.css" type="text/css" media="screen" />

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
        
        <div id="pad-wrapper">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div id="fuelux-wizard" class="wizard row">
                        <ul class="wizard-steps">
                            <li data-target="#step1">
                                <span class="step">1</span>
                                <span class="title">Excel <br> 批量导入</span>
                            </li>
                            <li data-target="#step2">
                                <span class="step">2</span>
                                <span class="title">Excel <br> 导出数据</span>
                            </li>
                        </ul>                            
                    </div>
                    <div class="step-content">
                        <div class="step-pane active" id="step1">
                            <div class="row form-wrapper">
                                <div class="col-md-8">
                                    <form action="<?=$now_script?>" method='post' enctype="multipart/form-data">
                                        <input type="hidden" name="Type" value="In" />
                                        <div class="field-box">
                                            <div class="field-box">
												<a href="upload/user.xls" target="_blank"><label>用户EXCEL</label></a>
												<a href="upload/template.xls" target="_blank"><label>模板EXCEL</label></a>
												
											</div>
                                        </div>
                                        <div class="field-box">
                                            <div class="field-box">
												<label>Excel文件路径:</label>
												<input type="file" name="Excel"/>
											</div>
                                        </div>
                                        
                                        <div class="field-box">
                                            <label>数据类型：</label>
                                            <select name="DataType">
                                                <option value="User">用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;户</option>
                                                <option value="Template">模&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;板</option>
                                            </select>
                                        </div>
										<div class="field-box error">
                                            <button class="btn-glow primary btn-next">提&nbsp;&nbsp;&nbsp;交</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="step-pane" id="step2">
                            <div class="row form-wrapper payment-info">
                                <div class="col-md-8">
                                    <form action="excel-out.php" method="post" target="_blank">
                                        <input type="hidden" name="Type" value="Out" />
                                        <div class="field-box">
                                            <label>数据类型：</label>
                                            <select id="plan" name="DataType">
                                                <option value="User">用户数据</option>
                                                <option value="Template">模板数据</option>
                                            </select>
                                        </div>
                                        <div class="field-box">
                                            <label>帅选条件:</label>
                                            <select id="plan" name="Nums">
                                                <option value="All">全部</option>
                                                <option value="1000">1000条</option>
                                                <option value="500">500条</option>
                                            </select>
                                        </div>
									   <div class="field-box">
                                            <label>选择顺序:</label>
                                            <select id="plan" name="Order">
                                                <option value="desc">最新</option>
                                                <option value="asc">最旧</option>
                                                
                                            </select>
                                        </div>
                                        <div class="field-box error">
                                            <button class="btn-glow">提&nbsp;&nbsp;&nbsp;交</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-actions">
                        <button type="button" disabled class="btn-glow primary btn-prev"> 
                            <i class="icon-chevron-left"></i>上一步
                        </button>
                        <button type="button" class="btn-glow primary btn-next" data-last="Finish">
                            下一步 <i class="icon-chevron-right"></i>
                        </button>
                        <button type="button" class="btn-glow success btn-finish">
                            Setup your account!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
	</div>
    <!-- end main container -->

	<!-- scripts for this page -->
	<script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/fuelux.wizard.js"></script>

    <script type="text/javascript">
        $(function () {
            var $wizard = $('#fuelux-wizard'),
                $btnPrev = $('.wizard-actions .btn-prev'),
                $btnNext = $('.wizard-actions .btn-next'),
                $btnFinish = $(".wizard-actions .btn-finish");

            $wizard.wizard().on('finished', function(e) {
                // wizard complete code
            }).on("changed", function(e) {
                var step = $wizard.wizard("selectedItem");
                // reset states
                $btnNext.removeAttr("disabled");
                $btnPrev.removeAttr("disabled");
                $btnNext.show();
                $btnFinish.hide();

                if (step.step === 1) {
                    $btnPrev.attr("disabled", "disabled");
                } else if (step.step === 4) {
                    $btnNext.hide();
                    $btnFinish.show();
                }
            });

            $btnPrev.on('click', function() {
                $wizard.wizard('previous');
            });
            $btnNext.on('click', function() {
                $wizard.wizard('next');
            });
        });
    </script>
</body>
</html>