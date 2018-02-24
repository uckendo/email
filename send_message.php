<?php
include('connect.php');
//获取当前发信配置账号
$query = mysql_query("select * from `account` where `Status`=1");
if(mysql_num_rows($query)>0){
    require 'phpmailer/class.phpmailer.php';
    $info = mysql_fetch_assoc($query);
    $get_message_sql = "select * from `message` where `Stauts`='WaitSend' limit 1";
    send_start:
    $get_send = mysql_query();
    while($in)
    try {
    	$mail = new PHPMailer(true); 
    	$mail->IsSMTP();
    	$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
    	$mail->SMTPAuth   = true;                  //开启认证
    	$mail->Port       = 25;                    
    	$mail->Host       = "$"; 
    	$mail->Username   = "info@comecho.de";    
    	$mail->Password   = "comecho.2015";            
    	//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
    	$mail->AddReplyTo("info@comecho.de","mckee");//回复地址
    	$mail->From       = "info@comecho.de";
    	$mail->FromName   = "test";
    	$to = "455019825@qq.com";
    	$mail->AddAddress($to);
    	$mail->Subject  = "phpmailer测试标题";
    	$mail->Body = "<h1>phpmail演示</h1>这是php点点通（<font color=red>www.phpddt.com</font>）对phpmailer的测试内容";
    	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
    	$mail->WordWrap   = 80; // 设置每行字符串的长度
    	//$mail->AddAttachment("f:/test.png");  //可以添加附件
    	$mail->IsHTML(true); 
    	$mail->Send();
    	echo '邮件已发送';
    } catch (phpmailerException $e) {
    	echo "邮件发送失败：".$e->errorMessage();
    }    
}else{
    echo '没有使用中的账号';
}

?>