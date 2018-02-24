<?php
include('connect.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>历史发信列表</title>
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
                        <h4>历史发信<small>列表</small></h4>
                    </div>
                </div>
                <?php
                $Status_arr = array('SendSuccess'=>'发送成功','WaitSend'=>'等待发信','SendFailure'=>'发信失败');
                ?>
                <form action="<?=$now_script?>" method="post">
                <div class="row filter-block">
                    <div class="col-md-8 col-md-offset-5">
                        <div class="ui-select">
                            <select name="DataType">
								<option value="">查找类型</option>
								<option value="ToEmail">用户</option>
								<option value="Content">内容</option>
								<option value="SendSuccess">已发信件</option>
								<option value="WaitSend">待发信件</option>
								<option value="SendFailure">发送失败</option>
                            </select>
                        </div>
                        <input type="text" class="search" name="Data" />
						<button class="btn-flat small">立即查询</button>
                    </div>
                </div>
                </form>
                <div class="row">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                
                                <th class="col-md-3">
                                    <span class="line"></span>邮箱
                                </th>
                                <th class="col-md-4">
                                    <span class="line"></span>发信内容
                                </th>
								<th class="col-md-3">
                                    <span class="line"></span>发信时间
                                </th>
                                <th class="col-md-2">
                                    <span class="line"></span>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                                if(empty($_POST['Data']) && empty($_POST['DataType'])){
                                    if(!empty($_GET['Status'])){
                                        $Status = trim($_GET['Status']);
                                       
                                        $get_message = mysql_query("select * from `message` where `Status`='$Status' order by `MessageID` desc ");
                                    }ELSE{
                                        $get_message = mysql_query("select * from `message` where `Status`='SendSuccess' limit $offset,$pernum ");
                                        
                                    }
                                        
                                }else{
                                   
                                    $DataType = $_POST['DataType'];
                                    $Data= addslashes(trim($_POST['Data']));
                                    if(empty($DataType)){
                                       
                                        
                                            $get_message = mysql_query("select * from `message` where `ToEmail` like '%$Data%' or `Content` like '%$Data%' order by `MessageID` desc ");
                                        
                                        
                                        
                                    }else{
                                        if(empty($Data)){
                                            $like ='';
                                        }else{
                                            $like =" `ToEmail` like '%$Data%' or `Content` like '%$Data%' and ";
                                        }
                                        switch($DataType){
                                            case 'ToEmail':
                                                $sql = "select * from `message` where `ToEmail` like '%$Data%' order by `MessageID` desc ";
                                                break;
                                            case 'Content':
                                                $sql = "select * from `message` where `Content` like '%$Data%' order by `MessageID` desc ";
                                                break;
                                            case 'SendSuccess':
                                                $sql = "select * from `message` where $like `Status`='SendSuccess'  order by `MessageID` desc ";
                                                break;
                                            case 'SendFailure':
                                            
                                                $sql = "select * from `message` where $like `Status`='SendFailure'  order by `MessageID` desc ";
                                                break;
                                            case 'WaitSend':
                                                $sql = "select * from `message` where  $like `Status`='WaitSend'  order by `MessageID` desc ";
                                                
                                                break;
                                            defalut:
                                                break;
                                        }
                                        //echo $sql;
                                        $get_message = mysql_query($sql);
                                    
                                    }
                                }
                                while($info = mysql_fetch_assoc($get_message)){
                                    
                            ?>
                            <tr>
                                <td>
                                   
                                    <a href="#"><?=$info['ToEmail']?></a>
                                </td>
                                <td class="description">
                                    <?=$info['Content']?>
                                </td>
								<td class="description">
                                    <?=$info['Date']?>
                                </td>
                                <td>
									<span class="label label-info"><?=$Status_arr[$info['Status']]?></span>
                                </td>
                            </tr>
                        <?php
                        
                        }
                        ?>
                           
						</tbody>
                    </table>
                </div>
                <?php
            
                $ul = get_page('messagesend',$now_script,$Status='SendSuccess');
                echo $ul;
                
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

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();



            // jQuery UI Sliders
            $(".slider-sample1").slider({
                value: 100,
                min: 1,
                max: 500
            });
            $(".slider-sample2").slider({
                range: "min",
                value: 130,
                min: 1,
                max: 500
            });
            $(".slider-sample3").slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 40, 170 ],
            });

            

            // jQuery Flot Chart
            var visits = [[1, 50], [2, 40], [3, 45], [4, 23],[5, 55],[6, 65],[7, 61],[8, 70],[9, 65],[10, 75],[11, 57],[12, 59]];
            var visitors = [[1, 25], [2, 50], [3, 23], [4, 48],[5, 38],[6, 40],[7, 47],[8, 55],[9, 43],[10,50],[11,47],[12, 39]];

            var plot = $.plot($("#statsChart"),
                [ { data: visits, label: "Signups"},
                 { data: visitors, label: "Visits" }], {
                    series: {
                        lines: { show: true,
                                lineWidth: 1,
                                fill: true, 
                                fillColor: { colors: [ { opacity: 0.1 }, { opacity: 0.13 } ] }
                             },
                        points: { show: true, 
                                 lineWidth: 2,
                                 radius: 3
                             },
                        shadowSize: 0,
                        stack: true
                    },
                    grid: { hoverable: true, 
                           clickable: true, 
                           tickColor: "#f9f9f9",
                           borderWidth: 0
                        },
                    legend: {
                            // show: false
                            labelBoxBorderColor: "#fff"
                        },  
                    colors: ["#a7b5c5", "#30a0eb"],
                    xaxis: {
                        ticks: [[1, "JAN"], [2, "FEB"], [3, "MAR"], [4,"APR"], [5,"MAY"], [6,"JUN"], 
                               [7,"JUL"], [8,"AUG"], [9,"SEP"], [10,"OCT"], [11,"NOV"], [12,"DEC"]],
                        font: {
                            size: 12,
                            family: "Open Sans, Arial",
                            variant: "small-caps",
                            color: "#697695"
                        }
                    },
                    yaxis: {
                        ticks:3, 
                        tickDecimals: 0,
                        font: {size:12, color: "#9da3a9"}
                    }
                 });

            function showTooltip(x, y, contents) {
                $('<div id="tooltip">' + contents + '</div>').css( {
                    position: 'absolute',
                    display: 'none',
                    top: y - 30,
                    left: x - 50,
                    color: "#fff",
                    padding: '2px 5px',
                    'border-radius': '6px',
                    'background-color': '#000',
                    opacity: 0.80
                }).appendTo("body").fadeIn(200);
            }

            var previousPoint = null;
            $("#statsChart").bind("plothover", function (event, pos, item) {
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(0),
                            y = item.datapoint[1].toFixed(0);

                        var month = item.series.xaxis.ticks[item.dataIndex].label;

                        showTooltip(item.pageX, item.pageY,
                                    item.series.label + " of " + month + ": " + y);
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        });
    </script>
</body>
</html>