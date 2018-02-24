<!-- navbar -->
<?php
error_reporting(0);
?>
    <header class="navbar navbar-inverse" role="banner">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" id="menu-toggler">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="img/logo.png"></a>
        </div>
        <ul class="nav navbar-nav pull-right hidden-xs">
            <li class="hidden-xs hidden-sm">
                <input class="search" type="text" />
            </li>
            <li class="notification-dropdown hidden-xs hidden-sm">
                <a href="messagesend_list.php" class="trigger">
                    <i class="icon-warning-sign"></i>
                    <span class="count"><?=count($TOpstatus_arr)?></span>
                </a>
                <div class="pop-dialog">
                    <div class="pointer right">
                        <div class="arrow"></div>
                        <div class="arrow_border"></div>
                    </div>
                    <div class="body">
                        <a href="#" class="close-icon"><i class="icon-remove-sign"></i></a>
                        <div class="notifications">
                            <h3>你有<?=count($TOpstatus_arr)?>条信息</h3>
                            <?php
                            if(!empty($TOpstatus_arr)){
                                foreach($TOpstatus_arr as $Status=>$total){
                                   if($Status == 'SendSuccess'){
                            ?>
                            <a href="messagesended_list.php?Status=<?=$Status?>" class="item">
                                <i class="icon-signin"></i> 已发出信件
                                <span class="time"><i class="icon-time"></i><?=$total?></span>
                            </a>
                            <?php        
                                   }elseif($Status=='SendFailure'){
                            ?>
                            <a href="messagesended_list.php?Status=<?=$Status?>" class="item">
                                <i class="icon-envelope-alt"></i> 发送失败
                                <span class="time"><i class="icon-time"></i><?=$total?></span>
                            </a>
                            <?php 
                                   }elseif($Status =='WaitSend'){
                            ?>
                            <a href="messagesended_list.php?Status=<?=$Status?>" class="item">
                                <i class="icon-signin"></i> 未发出信件
                                <span class="time"><i class="icon-time"></i><?=$total?></span>
                            </a>
                            <?php
                                   } 
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </li>
          
            <li class="dropdown">
                <a href="#" class="dropdown-toggle hidden-xs hidden-sm" data-toggle="dropdown">
                    你的账号
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="account-list.php">账号设置</a></li>
                </ul>
            </li>
            <li class="settings hidden-xs hidden-sm">
                <a href="account-list.php" role="button">
                    <i class="icon-cog"></i>
                </a>
            </li>
            <li class="settings hidden-xs hidden-sm">
                <a href="login.php?action=quit" role="button">
                    <i class="icon-share-alt"></i>
                </a>
            </li>
        </ul>
    </header>
    <!-- end navbar -->

    <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
            <li <?=$now_script=='index.php'?"class='active'":''?>>
                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>
                <a href="index.php">
                    <i class="icon-home"></i>
                    <span>首页</span>
                </a>
            </li>  
                      
            <li <?php
            if(strstr($now_script,'user') || strstr($now_script,'group')){
                echo 'class="active"';
                $linetype = 'user';
            }
            ?>>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-group"></i>
                    <span>用户</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul <?=$linetype=='user'?"class='active submenu'":'class="submenu"'?> >
                    <li <?=$now_script=='user-list.php'?"class='active'":'"'?>><a href="user-list.php">用户列表</a></li>
                    <li <?=$now_script=='group-list.php'?"class='active'":'"'?>><a href="group-list.php">用户分组</a></li>
                    <li <?=$now_script=='user-list-ready.php'?"class='active'":'"'?>><a href="user-list-ready.php">用户选组</a></li>
                    <li <?=$now_script=='user-edit.php'?"class='active'":'"'?>><a href="user-edit.php">添加用户</a></li>
                </ul>
            </li>
            
            <li <?php
            if(strstr($now_script,'messagesend') || strstr($now_script,'messagesend')){
                echo 'class="active"';
                $linetype = 'message';
            }
            ?>>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-edit"></i>
                    <span>发信</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul <?=$linetype=='message'?"class='active submenu'":'class="submenu"'?>>
                    <li <?=$now_script=='messagesend_list.php'?"class='active'":'"'?>><a href="messagesend_list.php">发信列表</a></li>
					<li <?=$now_script=='messagesend_list_ready.php'?"class='active'":'"'?>><a href="messagesend_list_ready.php">发信预处理</a></li>
					<li <?=$now_script=='messagesend_now.php'?"class='active'":'"'?>><a href="messagesend_now.php">确认发信</a></li>
                    <li <?=$now_script=='messagesended_list.php'?"class='active'":'"'?>><a href="messagesended_list.php">历史发信</a></li>
                </ul>
            </li>
            <li <?php
            if(strstr($now_script,'template') || strstr($now_script,'template')){
                echo 'class="active"';
                $linetype = 'template';
            }
            ?>>
                <a class="dropdown-toggle"  href="#">                    
                    <i class="icon-calendar-empty"></i>
                    <span>模板</span>
					<i class="icon-chevron-down"></i>
                </a>
				<ul <?=$linetype=='template'?"class='active submenu'":'class="submenu"'?>>
                    <li <?=$now_script=='template-list.php'?"class='active'":'"'?>><a href="template-list.php">模板列表</a></li>
                    <li <?=$now_script=='template-edit.php'?"class='active'":'"'?>><a href="template-edit.php?action=add">模板添加</a></li>
                    <li <?=$now_script=='placeholder-edit.php'?"class='active'":'"'?>><a href="placeholder-edit.php">性别设定</a></li>
                
                </ul>
            </li>
            <li <?php
            if(strstr($now_script,'excel') || strstr($now_script,'excel')){
                echo 'class="active"';
                $linetype = 'excel';
            }
            ?>>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-th-large"></i>
                    <span>数据处理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul <?=$linetype=='excel'?"class='active submenu'":'class="submenu"'?>>
                    <li <?=$now_script=='excel.php'?"class='active'":'"'?>><a href="excel.php">数据导入</a></li>
                    <li <?=$now_script=='excel-list.php'?"class='active'":'"'?>><a href="excel-list.php">Excel列表</a></li>
                </ul>
            </li>
            <li>
                <a  href="account-list.php">
                    <i class="icon-cog"></i>
                    <span>账号管理</span>
                </a>
            </li>
            <li>
                <a href="login.php">
                    <i class="icon-share-alt"></i>
                    <span>登出</span>
                    
                </a>
            </li>
        </ul>
    </div>
    <!-- end sidebar -->

