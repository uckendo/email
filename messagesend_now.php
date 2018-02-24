<?php
include('connect.php');

require 'function/phpmailer/class.phpmailer.php';

$Status_arr = array('SendSuccess'=>'发送成功','WaitSend'=>'等待发信','SendFailure'=>'发信失败');

error_reporting(E_ALL);
$query = mysql_query("select * from `account` where `Status`=1");
if(mysql_num_rows($query)>0){
 
    $sender = mysql_fetch_assoc($query);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>发送进行中</title>
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
                        <h4>发信中<small></small></h4>
                    </div>
                </div>

                <div class="row">
                    <table class="table table-hover">
                        <thead>
                            <tr>

                                <th class="col-md-3">
                                    <span class="line"></span>Email
                                </th>

								<th class="col-md-3">
                                    <span class="line"></span>发送时间
                                </th>
                                <th class="col-md-3">
                                    <span class="line"></span>Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(empty($sender)){
                            echo "<script>alert('没有使用中的账号');</script>";
                            header("location:account-list.php");
                        }else{
                            $get_message_sql = "select * from `message` where `Status`='WaitSend' ";
                            
                            $get_query = mysql_query($get_message_sql);
                            while($info = mysql_fetch_assoc($get_query)){
                                $date = date('Y-m-d H:i:s',time());
                                $MessageID = $info['MessageID'];
                                try {
                                	$mail = new PHPMailer(true); 
                                	$mail->IsSMTP();
                                	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                                	$mail->SMTPAuth   = true;                  //开启认证
                                	$mail->Port       = 25;                    
                                	$mail->Host       = trim($sender['Smtp']); 
                                	$mail->Username   = trim($sender['Email']);    
                                	$mail->Password   = trim($sender['Password']);            
                                	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                                	$mail->AddReplyTo(trim($sender['Email']),"mckee");//回复地址
                                	$mail->From       = trim($sender['Email']);
                                	$mail->FromName   = trim($sender['Name']);
                                	$to = trim($info['ToEmail']);
                                	$mail->AddAddress($to);
                                	$mail->Subject  = $info['Title'];
                                    $content = file_get_contents('http://uckendo.com/');
                                	$mail->Body = $content;
                                	$mail->AltBody    = $content; //当邮件不支持html时备用显示，可以省略
                                	
                                	//$mail->AddAttachment("f:/test.png");  //可以添加附件
                                	$mail->IsHTML(true); 
                                	$mail->Send();
                                    $Status = 'SendSuccess';
                                }catch (phpmailerException $e) {
                                    $Status = 'SendFailure';
                               	    $error = $e->errorMessage();
                                }
                                mysql_query("update `message` set `Status`='$Status',`Date`='$date',`FromEmail`='".$sender['Email']."'  where `MessageID`='$MessageID'");
                                
                        ?>
                            <tr>
                                <td>
                                    <?=$info['ToEmail']?>
                                </td>
                                <td class="description">
                                    <?=$date?>
                                </td>
					
                                <td>
									<span class="label label-info"><?=$Status_arr[$Status]?></span>
                                </td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
        
							 
						</tbody>
                    </table>
                </div>
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