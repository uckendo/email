<?php
/**
 * 登陆验证函数
 * @admin 超管名字
 * @password 超管密码
**/
function login($admin,$password){
    $admin = trim($admin);
    if(empty($admin) || empty($password)){
        return array('Ack'=>'Failure','Info'=>'参数错误');
    }
    $password = md5($password);
    $check_sql = "select `AdminID` from `admin` where `Admin`='$admin' and `Password`='$password' limit 1";
  
    $check = mysql_query($check_sql);
    if(mysql_num_rows($check)>0){   //验证超管信息成功
        $_SESSION['Admin'] = $admin;
        return array('Ack'=>'Success','Info'=>$_SESSION);
    }else{
        return array('Ack'=>'Failure','Info'=>'登录失败，用户名或者密码不对！');
    }
}

/**
* @type 数据表
* @action 操作
* @data 传入的数据,键=>值
***/
function make_deal($type,$action,$data){
    if(empty($type) || empty($action) || empty($data) || !is_array($data)){
        return array('Ack'=>'Failure','Info'=>'参数错误');
    }
    if($action == 'Add'){   //添加数据
        
    }
}


/**
 * @url 当前地址
 * @type 数据表名
**/
function get_page($type,$url,$Status=''){
    global $pernum,$page;
    switch($type){
        case 'account':
            $sql = "select count(*) as qty from `account`";
            break;
        case 'excel':
            $sql = "select count(*) as qty from `excel`";
            break;
        case 'group':
            $sql = "select count(*) as qty from `group`";
            break;
        case 'message':
            $sql = "select count(*) as qty from `message`";
            break;
        case 'messagesend':
            $sql = "select count(*) as qty from `message` where `Status`='$Status'";
            break;
        case 'message_ready':
            $sql = "select count(*) as qty from `message_ready`";
            break;
        case 'template':
            $sql = "select count(*) as qty from `template`";
            break;
        case 'user_ready':
            $sql = "select count(*) as qty from `user_ready`";
            break;
        case 'user':
            $sql = "select count(*) as qty from `user`";
            break;
        default:
            break;
    }
    
    if(empty($sql)){
        return array('Ack'=>'Failure','Info'=>'参数错误');
    }
    $query = mysql_query($sql);
    $info = mysql_fetch_assoc($query);
    $total = $info['qty'];
    
    $ul = '<ul class="pagination">';
  
    $pagetotal = ceil($total/$pernum);
    
    if($page>1){
        $perv_page = $page-1;
        $url_href = $url.'?page='.$perv_page;
        $ul .="<li><a href='$url_href'>&laquo;</a></li>";    
    }
   
    
    $now_href = $url.'?page='.$page;
    $ul .="<li><a href='$now_href'>$page</a></li>"; 
    
    if($page<$pagetotal){
        $next_page = $page+1;
        $url_href = $url.'?page='.$next_page;
        $ul .="<li><a href='$url_href'>&raquo;</a></li>"; 
    }
    
    $ul .='</ul>';
    return $ul;
    
    
} 

/**
 * 获取群组
***/ 
function get_group_select($name='GroupID'){
    $query = mysql_query("select * from `group` ");
    $select = "<select name='$name'>";
    while($info = mysql_fetch_assoc($query)){
        $GroupID = $info['GroupID'];
        $GroupName = $info['Groupname'];
        $select .="<option value='$GroupID'>$GroupName</option>";
    }
    $select .='</select>';
    return $select;
}
/**
 * 获取群组
***/ 
function get_group(){
    $query = mysql_query("select * from `group` ");
    $group = array();
    while($info = mysql_fetch_assoc($query)){
        $GroupID = $info['GroupID'];
        $GroupName = $info['Groupname'];
        $group[$GroupID] = $GroupName;
    }
   
    return $group;
}


/**
 * 获取群组
***/ 
function get_groupname($GroupID){
    $query = mysql_query("select * from `group` where `GroupID`='$GroupID' ");
    $info = mysql_fetch_assoc($query);
    $GroupName = $info['Groupname'];
    return $GroupName;
}

/**
 * 获取发信模板
***/
function get_template(){
    $query = mysql_query("select `TemplateID`,`TemplateName` from `template` order by `InDate` desc");
    $template_arr = array();
    while($info = mysql_fetch_assoc($query)){
        $template_arr[$info['TemplateID']] = $info['TemplateName'];
    }
    return $template_arr;
}


/**
 * 数组转换select
 * @arr 数组
 * @name 数组名字
 * @class 类名
 * @selected 选中
**/
function array_to_select($arr,$name,$selected='',$class=''){
    $str = "<select name='$name' class='$class'>";
    $str .="<option value=''>请选择</option>";
    foreach($arr as $key=>$value){
        $str .="<option value='$key'>$value</option>";
    }
    $str .='</select>';
    return $str;
}
?>
