<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/resource/part/bootstrap/css/bootstrap3.min.css" rel="stylesheet" type="text/css" />
<script src="/resource/js/jQuery/jquery-1.11.1.min.js"></script>
<title>处理流程</title>
</head>
<body>
    <?php echo ($nav); ?>
<div class="container">
    <div class="row">
        <?php if(is_array($form_data)): $i = 0; $__LIST__ = $form_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($vo); endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <form action="<?php echo U('/Flow/run/flowsave');?>" method="post">
            <?php echo ($form_hidden); ?>
            <?php echo ($design_content); ?>
            <?php echo ($form_sumit); ?>
        </form>
    </div>
</div>
<script src="/resource/part/bootstrap/js/bootstrap3.min.js"></script>
</body>
</html>