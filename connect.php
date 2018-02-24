<?php
/**
 * 连接mysql，选择数据库
***/
header("Content-type: text/html; charset=utf-8"); 

Error_reporting(0);
$connect = mysql_connect('localhost','root','@1990+0926!');
if(!$connect){
    echo mysql_error();die;
}
$select = mysql_select_db('news');
if(!$select){
    echo mysql_error();die;
}
//开启SESSION
session_start();
date_default_timezone_set('Asia/Shanghai');

mysql_query("set names utf8");
$server_root = $_SERVER['SCRIPT_NAME'];

if(!strstr($server_root,'login.php')){   //当前为非登录页面
    if(empty($_SESSION['Admin'])){  //没有登录超管的SESSION，请重新登录
        echo "<script>alert('请重新登录！');window.location.href='login.php';</script>";
        die;
    }    
}
include('function/common_function.php');
//定义常用常量
$pernum = 20;
$offset = 1;
$page = intval($_GET['page'])?intval($_GET['page']):1;
if($page<1){
    $page = 1;
}
$offset = ($page-1)*$pernum;
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];   //上一个页面
$SCRIPT_NAME = $_SERVER['SCRIPT_NAME']; //当前脚本
$script_arr = explode('/',$SCRIPT_NAME);
$now_script = $script_arr[count($script_arr)-1];
//echo $now_script;
$TopDate = date('Y-m-d H:i:s',strtotime('today'));
$query = mysql_query("select `Status`, count(`MessageID`) as total from `message` where `Date`>'$TopDate' group by `Status`");
$status_arr = array();
while($info = mysql_fetch_assoc($query)){
    $TOpstatus_arr[$info['Status']] = $info['total'];
}

?>
