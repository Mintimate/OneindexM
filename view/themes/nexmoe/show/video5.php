<?php view::layout('layout')?>

<?php 
$item['thumb'] = onedrive::thumbnail($item['path']);
?>

<?php view::begin('content');?>
<div class="mdui-container-fluid">
	<div class="nexmoe-item">
	<video class="mdui-video-fluid mdui-center" preload controls poster="<?php @e($item['thumb']);?>">
	  <source src="<?php e($item['downloadUrl']);?>" type="video/mp4">
	</video>
	<div class="mdui-p-t-5 ">
		<ul class="mdui-menu" id="menu">
			<li class="mdui-menu-item">
			<a href="intent:<?php e($url);?>;end" class="mdui-ripple">MXPlayer(FREE)</a>
			</li>
			<li class="mdui-menu-item">
			<a href="vlc://<?php e($url);?>" class="mdui-ripple">VLC</a>
			</li>
			<li class="mdui-menu-item">
			<a href="potplayer://<?php e($url);?>" class="mdui-ripple">PotPlayer</a>
			</li>
			<li class="mdui-menu-item">
			<a href="nplayer-<?php e($url);?>" class="mdui-ripple">PotPlayer</a>
			</li>
			
		</ul>

		<button id="appplayers" class="mdui-btn mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">&#xe039;</i>外部播放器播放<i class="mdui-icon material-icons">&#xe313;</i></button>
	</div>
	<!-- 固定标签 -->
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">下载地址</label>
	  <input class="mdui-textfield-input" type="text" value="<?php e($url);?>"/>
	</div>
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">引用地址</label>
	  <textarea class="mdui-textfield-input"><video><source src="<?php e($url);?>" type="video/mp4"></video></textarea>
	</div>
	</div>
</div>
<script>
	var inst = new mdui.Menu('#appplayers', '#menu');

// method
document.getElementById('appplayers').addEventListener('click', function () {
  inst.open();
});
</script>
<a href="<?php e($url);?>" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">file_download</i></a>
<?php view::end('content');?>