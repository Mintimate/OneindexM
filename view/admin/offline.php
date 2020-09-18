<?php view::layout('layout')?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">

	<div class="mdui-typo">
	  <h1> 上传管理<small>游客前台离线和在线上传</small></h1>
  </div>
  <form action="" method="post">
		<div class="mdui-textfield">
		  <h4>允许游客离线上传<small>（需安装aria2和rclone，输入远程url即可上传）</small></h4>
		  <label class="mdui-textfield-label"></label>
		  <label class="mdui-switch">
			  <input type="checkbox" name="offline" value="1" <?php echo ($config['offline']==false)?'':'checked';?>/>
			  <i class="mdui-switch-icon"></i>
		  </label>
		</div>
	   <Br>
	   <div class="mdui-textfield">
		  <h4>允许游客在线上传<small> （上传游客计算机里的文件）</small></h4>
		  <label class="mdui-textfield-label"></label>
		  <label class="mdui-switch">
			  <input type="checkbox" name="online" value="1" <?php echo ($config['online']==false)?'':'checked';?>/>
			  <i class="mdui-switch-icon"></i>
		  </label>
		</div>
		<br>
		<div class="mdui-textfield">
		  <h4>游客在线上传目录限制<small>（注意：管理员不受此限制）</small></h4>
		  <input class="mdui-textfield-input" type="text" name="upload_path" value="<?php echo $config['upload_path'];?>"/>
		  <small>此目录下的readme index head文件无法被渲染</small>
		</div>
	   <button type="submit" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right">
	   	<i class="mdui-icon material-icons">&#xe161;</i> 保存
     </button>
	</form>
</div>
<?php view::end('content');?>