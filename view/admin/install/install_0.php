<?php view::layout('install/layout')?>

<?php view::begin('content');?>

<div class="mdui-container-fluid">
	<div class="mdui-typo">
	  <h1>程序安装 <small>环境检测</small></h1>
	</div>

	<div class="mdui-table-fluid">
	  <table class="mdui-table">
	    <thead>
	      <tr>
	        <th>#</th>
	        <th>环境需求</th>
	        <th>当前环境</th>
	      </tr>
	    </thead>
	    <tbody>
	      <tr>
	        <td>1</td>
	        <td>PHP > 5.5</td>
	        <?php if($check['php']): ?>
	        <td><i class="mdui-icon material-icons" style="color:#4caf50;">&#xe5ca;</i></td>
	        <?php else:?>
	        <td><i class="mdui-icon material-icons" style="color:#f44336;">&#xe5cd;</i></td>
	        <?php endif;?>
	      </tr>
	      <tr>
	        <td>2</td>
	        <td>curl 支持</td>
	        <?php if($check['curl']): ?>
	        <td><i class="mdui-icon material-icons" style="color:#4caf50;">&#xe5ca;</i></td>
	        <?php else:?>
	        <td><i class="mdui-icon material-icons" style="color:#f44336;">&#xe5cd;</i></td>
	        <?php endif;?>
	      </tr>
	      <tr>
	        <td>3</td>
	        <td>config/ 目录可读可写</td>
	        <?php if($check['config']): ?>
	        <td><i class="mdui-icon material-icons" style="color:#4caf50;">&#xe5ca;</i></td>
	        <?php else:?>
	        <td><i class="mdui-icon material-icons" style="color:#f44336;">&#xe5cd;</i></td>
	        <?php endif;?>
	      </tr>
	      <tr>
	        <td>4</td>
	        <td>cache/ 目录可读可写</td>
	        <?php if($check['cache']): ?>
	        <td><i class="mdui-icon material-icons" style="color:#4caf50;">&#xe5ca;</i></td>
	        <?php else:?>
	        <td><i class="mdui-icon material-icons" style="color:#f44336;">&#xe5cd;</i></td>
	        <?php endif;?>
	      </tr>
	      <tr>
	        <td>Tips</td>
	        <td rowspan=2>建议配合Mintimate's Blog文章和视频操作：<a href="https://www.mintimate.cn/2020/09/22/oneindex/" target="_blank">博客文章</a>、
	        <a href="https://www.bilibili.com/video/BV1ph41197aa" target="_blank">视频教程</a></td>
	      </tr>
	    </tbody>
	  </table>
	</div>
	<br><br>
	<!--<a class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-left" href="?step=1">上一步</a>-->
	<?php if(array_sum($check) == count($check)):?>
	<a class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" href="?step=1">下一步</a>
	<?php else:?>
	<button class="mdui-btn mdui-btn-raised  mdui-float-right disabled" disabled>下一步</button>
	<?php endif;?>
</div>

<?php view::end('content');?>
