<?php
session_start();
include '../../s/inc/function.php';
is_power('setting_can_do','帐号组',false,'/admin/setting/init.html');
include_once '../../s/inc/conn.php';
if(!empty($_POST['name'])){	
	$out["error"]="";
	$name=$_POST['name'];
	$gdes=$_POST['gdes'];
	$sql="select * from ht_can_do where lower(`name`)=lower('$name')";
	$query=mysql_query($sql);
	$row=mysql_fetch_array($query);
	if($row)
	{
		$out["error"]="账号组已存在！";
	}else{
		$sql='INSERT INTO `ht_can_do` (name,gdes) VALUES("'.$name.'","'.$gdes.'")';
		$query=mysql_query($sql);	
		if($query)
		{			
            $out["error"]="添加成功！";		
		}else{
        	$out["error"]="添加失败！";			
		}
	}
}
$sql="select * from ht_can_do";
$query=mysql_query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="/resource/js/jQuery/jquery-1.9.1.js" type="text/javascript"></script>
<script type="text/javascript" src="/resource/part/newtpl/js/public.js"></script>
<link href="/resource/part/newtpl/css/public.css" rel="stylesheet" type="text/css" />
<title>帐号组</title>
<style>
table {
    background: #ffffff none repeat scroll 0 0;
    border: 0 solid #f3f3f3;
}
.tablewidth {
    background: #efefef none repeat scroll 0 0;
    border: 1px solid #1a5db0;
    margin-bottom: 8px;
    width: 99%;
}
.tablewidth .head, .tablewidth .head td, .tablewidth .head3 td {
    background: #6799b8 url("/resource/images/common/qq_22.gif") repeat scroll 0 0;
    border: 0 solid #449ae8;
    color: #fff;
}
.tablewidth tr {
    background: #fff none repeat scroll 0 0;
}
.tablewidth .head td, .tablewidth .head td a {
    color: #fff;
}
.tablewidth .head td {
    padding-left: 5px;
    padding-top: 5px;
}
.tablewidth .head, .tablewidth .head td, .tablewidth .head3 td {
    background: #6799b8 url("/resource/images/common/qq_22.gif") repeat scroll 0 0;
    border: 0 solid #449ae8;
    color: #fff;
}
</style>
</head>
<body style="background-color:#E0F0FE;">
<div class="admin_main_nr_dbox">
<div align="center">
  <table width="100%" cellspacing="1" cellpadding="3" class="tablewidth">
    <tbody>
      <tr class="head">
        <td colspan="7"><div align="center"><font color="#FFFFFF">用户组后台权限管理</font></div></td>
      </tr>
      <tr bgcolor="#EEEEEE" align="center">
        <td width="6%"><div align="center"><b><font color="#990033">groupid</font></b></div></td>
        <td width="15%"><b><font color="#990033">用户组名称</font></b></td>
        <td width="13%"><b><font color="#990033">用户组性质</font></b></td>
        <td width="16%"><b><font color="#990033">用户组成员</font></b></td>        
        <td width="21%"><b><font color="#990033">后台权限设置</font></b></td>
        <td width="14%"><b><font color="#990033">删除此用户组</font></b></td>
        <td width="15%"><b><font color="#990033">编辑用户组</font></b></td>
      </tr>
      <?php
	  while($row = mysql_fetch_array($query)){
	  ?>
      <tr bgcolor="#FFFFFF">
        <td width="6%" align="center"><?php echo $row['id'];?></td>
        <td width="15%" align="left"><?php echo $row['name'];?></td>
        <td width="13%" align="center"><?php echo $row['gdes'];?></td>
        <td width="16%" align="center"> 
          <a title="点击查看此用户组下的所有会员名单" href="web_guser_list.php?gid=<?php echo $row['id'];?>"><img border="0" src="/resource/images/common/icon_user.gif"></a> 
          </td>        
        <td width="21%" align="center">
          <a onclick="return confirm('提醒!你确实要为＂前台管理员＂这个用户组,开通后台权限吗?')" title="修改后台权限" href="web_power.php?act=assign&gid=<?php echo $row['id'];?>"><img border="0" src="/resource/images/common/button_properties.png"></a> 
          </td>
        <td width="14%" align="center">
        <?php
		if($row['id']==1){
		?>
        	<img border="0" src="/resource/images/common/check_no.gif">
		<?php
		}else{
		?>
        <a onclick="return confirm('警告!删除后不可恢复,是否确定要删除?')" href="web_power.php?act=del&gid=<?php echo $row['id'];?>" title="删除此用户组">
		
        <img border="0" src="/resource/images/common/del_icon2.gif"></a>
        <?php		
		}
        ?>
        </td>
        <td width="14%" align="center">
        <a href="gedit.php?gid=<?php echo $row['id'];?>" title="编辑用户组">
        <img width="16" height="16" border="0" src="/resource/images/common/gedit.png"></a>
        </td>
      </tr> 
      <?php
	  }
	  ?>     
    </tbody>
  </table>
  <form action="?" method="post">
  <table width="100%" cellspacing="1" cellpadding="3" class="tablewidth">  
    <tbody><tr class="head">       
    <td colspan="2"> <font color="#FFFFFF">添加新组:</font></td>
    </tr>
    <?php
		if (strlen($out["error"])>0) {
		?>
        <tr>       
    <td colspan="2"><font color=red><?php echo $out["error"];?></font></td>
    </tr>		
		<?php
		}
		?>   
  <tr>
    <td width="16%" align="right">组 名:</td>
    <td align="left"><input name="name" type="text" /></td>
  </tr>
  <tr>
    <td width="16%" align="right">用户组性质:</td>
    <td align="left"><input name="gdes" type="text" /></td>
  </tr>
    <tr>
    <td></td>
    <td><input type="submit" value="确定" /></td>
  </tr>
</tbody></table>
</form>
</div>
</div>
<div class="admin_frameset" >
  <div class="open_frame" title="全屏" id="open_frame" flag="min"></div>
  <div class="close_frame" title="还原窗口" id="close_frame" flag="min"></div>
</div>
</body>
</html>