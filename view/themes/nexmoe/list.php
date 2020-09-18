<?php view::layout('layout')?>
<?php 
function file_ico($item){
  $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
  if(in_array($ext,['bmp','jpg','jpeg','png','gif'])){
  	return "image";
  }
  if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf'])){
  	return "ondemand_video";
  }
  if(in_array($ext,['ogg','mp3','wav'])){
  	return "audiotrack";
  }
  return "insert_drive_file";
}
?>

<?php view::begin('content');?>

<?php if(is_login()):?>
<div class="mdui-container-fluid" >
	<div class="nexmoe-item">
	<select class="mdui-select" id="file_upload">
		<option value="online_upload">本地上传</option>
		<option value="remote_upload">远程上传</option>
		<option value="offline_upload">aria2上传</option>
  	</select>
	<button class="mdui-btn mdui-ripple" id="pagesearch">过滤</button>
	<button class="mdui-btn mdui-ripple" id="newfolder">新建文件夹</button>
	<!-- <button class="mdui-btn mdui-ripple" id="file_upload">上传文件</button> -->
	<button class="mdui-btn mdui-ripple multiopt" id="deleteall" style="display: none;">删除</button>
	<button class="mdui-btn mdui-ripple multiopt" id="copybtn" onclick="copy()" style="display: none;">复制</button>
	<button class="mdui-btn mdui-ripple multiopt" id="cutbtn" onclick="cut()" style="display: none;">剪切</button>
	<button class="mdui-btn mdui-ripple" id="pastebtn"  onclick="paste()" style="display: none;">粘贴</button>
	<button class="mdui-btn mdui-ripple singleopt" id="rename" style="display: none;">重命名</button>
	<button class="mdui-btn mdui-ripple multiopt" id="sharebtn" style="display: none;">分享</button>
	</div>
</div>
<?php endif;?> 


<div class="mdui-container-fluid">
<?php if($head):?>
<div class="mdui-typo" style="padding: 20px;">
	<?php e($head);?>
</div>
<?php endif;?>
<style>
.thumb .th{
	display: none;
}
.thumb .mdui-text-right{
	display: none;
}
.thumb .mdui-list-item a ,.thumb .mdui-list-item {
	width:217px;
	height: 230px;
	float: left;
	margin: 10px 10px !important;
}

.thumb .mdui-col-xs-12,.thumb .mdui-col-sm-7{
	width:100% !important;
	height:230px;
}

.thumb .mdui-list-item .mdui-icon{
	font-size:100px;
	display: block;
	margin-top: 40px;
	color: #7ab5ef;
}
.thumb .mdui-list-item span{
	float: left;
	display: block;
	text-align: center;
	width:100%;
	position: absolute;
    top: 180px;
}
/*loading动画*/
.simple-spinner {
  height: 100%;
  border: 8px solid rgba(150, 150, 150, 0.2);
  border-radius: 50%;
  border-top-color: rgb(150, 150, 150);
  animation: rotate 1s 0s infinite ease-in-out alternate;
}
@keyframes rotate {
  0%   { transform: rotate(0);      }
  100% { transform: rotate(360deg); }
}
</style>
<div class="nexmoe-item">
	<div class="mdui-row">
		<ul class="mdui-list">
			<li class="mdui-list-item th" id="indexsort">
			<?php if(is_login()):?>
				<label class="mdui-checkbox"><input type="checkbox" value="" id="checkall" onclick="checkall()"><i
						class="mdui-checkbox-icon"></i></label>
				<?php endif;?> 
			<div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="downward">expand_more</i></div>
			<div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="downward">expand_more</i></div>
			<div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="downward">expand_more</i></div>
			</li>
			<?php if($path != '/'):?>
			<li class="mdui-list-item mdui-ripple" id="backtolast">
				<a href="<?php echo get_absolute_path($root.$path.'../');?>">
				<div class="mdui-col-xs-12 mdui-col-sm-7">
					<i class="mdui-icon material-icons">arrow_upward</i>
					..
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"></div>
				<div class="mdui-col-sm-2 mdui-text-right"></div>
				</a>
			</li>
			<?php endif;?>
			
			<li class="mdui-list-item mdui-ripple filter" id="pending" style="display:none;"><div class="simple-spinner" id="loading"></div>文件加载中~~~
			</li>
			<?php foreach((array)$items as $item):?>
				<?php if(!empty($item['folder'])):?>

			<li class="mdui-list-item mdui-ripple filter" data-sort 
						data-sort-name="<?php echo $item['name'] ;?>"
						data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
						data-sort-size="<?php echo $item['size'];?>" 
						id="<?php echo $item["id"] ?>">
				<div class="simple-spinner loading-gif" style="display: none;"></div>
				<?php if(is_login()):?>
				<label class="mdui-checkbox">
					<input type="checkbox" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
					<i class="mdui-checkbox-icon"></i></label>
				<?php endif;?> 		
				<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
				<div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
					<i class="mdui-icon material-icons">folder_open</i>
					<span><?php echo $item['name'];?></span>
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
				<div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
				</a>
			</li>
				<?php else:?>
			<li class="mdui-list-item file mdui-ripple filter" data-sort
						data-sort-name="<?php echo $item['name'];?>"
						data-sort-date="<?php echo $item['lastModifiedDateTime'];?>"
						data-sort-size="<?php echo $item['size'];?>" 
						id="<?php echo $item["id"] ?>">
				<div class="simple-spinner loading-gif" style="display: none;"></div>
						<?php if(is_login()):?>
				<label class="mdui-checkbox">
					<input type="checkbox" value="<?php echo $item["id"] ?>" name="itemid" onclick="onClickHander()">
					<i class="mdui-checkbox-icon"></i></label>
				<?php endif;?> 	
				<a href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
				<div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
					<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
					<span><?php e($item['name']);?></span>
				</div>
				<div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
				<div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
				</a>
			</li>
				<?php endif;?>

				<?php endforeach;?>
		</ul>
	</div>
</div>
<?php if($readme):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; ">
	<div class="mdui-chip">
	  <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
	  <span class="mdui-chip-title">README.md</span>
	</div>
	<?php e($readme);?>
</div>
<?php endif;?>
</div>

<div class="mdui-fab-wrapper" id="myFab">
    <button class="mdui-fab mdui-ripple mdui-color-theme-accent">
      <i class="mdui-icon material-icons">add</i>
      <i class="mdui-icon mdui-fab-opened material-icons">mode_edit</i>
    </button>
    <div class="mdui-fab-dial">
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red" id="search" style="display: <?php if(!is_login()) echo "none" ;else echo "inline" ?>;"><i class="mdui-icon material-icons">&#xe8b6;</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-orange" onclick="location.href='/?/admin'"><i class="mdui-icon material-icons">account_circle</i>
      </button>
      <button class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-blue" onclick="thumb()"><i class="mdui-icon material-icons" id="format_list">format_list_bulleted</i>
      </button>
    </div>
</div>

<div class="mdui-container">
  <div class="mdui-dialog" id="onlineupload-dialog">
    <div class="mdui-dialog-title">文件在线上传</div>
    <div class="mdui-dialog-content">
		<div>最大支持4M文件上传</div>
		<form id="filesubmit" action="?/onlinefileupload" method="post" enctype="multipart/form-data" >
			<input class="mdui-center" type="file" style="margin: 50px 0;" name="onlinefile" />
			<input type="text" style="display: none;" name="uploadurl" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
		</form>
		<div class="mdui-row-xs-3">
			<div class="mdui-col"></div>
				<div class="mdui-col">
					<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" onclick="submitForm()">上传</button>
				</div>
		</div>
	</div>
    <div class="mdui-dialog-actions">
      <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
    </div>
  </div>
</div>

<div class="mdui-container">
  <div class="mdui-dialog" id="remoteupload-dialog">
    <div class="mdui-dialog-title">文件远程上传</div>
    <div class="mdui-dialog-content">
		<div>仅支持Onedrive个人版，企业和学校版无法使用此功能</div>
		<form id="remoteupload" action="?/upload_url" method="post" >
			<div class="mdui-textfield mdui-textfield-floating-label mdui-col-xs-10 mdui-col-offset-xs-1">
				<label class="mdui-textfield-label">远程URL</label>
				<input class="mdui-textfield-input" type="url" name="file_url" id="fileurl" onblur="getRemoteUrl()"/>
			</div>
			<div class="mdui-textfield mdui-textfield-floating-label mdui-col-xs-10 mdui-col-offset-xs-1">
				<label class="mdui-textfield-label">文件名称</label>
				<input class="mdui-textfield-input" type="text" id="filename" name="file_name" />
			</div>
			
			<input type="text" style="display: none;" name="path_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
		</form>
		<div class="mdui-row-xs-3">
			<div class="mdui-col"></div>
				<div class="mdui-col">
					<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" onclick="submitRemoteFile()">提交</button>
				</div>
		</div>
	</div>
    <div class="mdui-dialog-actions">
      <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
    </div>
  </div>
</div>

<div class="mdui-container">
 <div class="mdui-dialog" id="search_form">
    <div class="mdui-dialog-content">
		<form action="?/search" method="post">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">输入关键词</label>
				<input class="mdui-textfield-input" type="text" style="margin: 20px 0;" name="keyword" />
				<div class="mdui-row-xs-3">
				<div class="mdui-col"></div>
					<div class="mdui-col">
						<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple">提交</button>
					</div>
				</div>
			</div>
		</form>
	</div>
  </div>
</div>


<div class="mdui-container">
 <div class="mdui-dialog" id="share">
    <div class="mdui-dialog-content">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">选中的项目链接</label>
				<textarea class="mdui-textfield-input" style="margin: 20px 0;" rows="5" readonly id="sharelinks"></textarea>
			</div>
	</div>
  </div>
</div>

<div class="mdui-container">
	<div class="mdui-dialog" id="progress">
		<div class="mdui-dialog-content">
			<span>文件处理进程</span>
			<div class="mdui-progress" >
				<div class="mdui-progress-determinate" style="width: 0%;" id="progress_width"></div>
			</div>
		</div>
		
	</div>
</div>
<!-- <script src="https://cdn.jsdelivr.net/gh/xieqifei/OneindexN@v1.31/statics/js/nexmoe.js"></script> -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="//cdnjs.loli.net/ajax/libs/mdui/0.4.3/js/mdui.min.js"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/xieqifei/StaticsResources@v1.4/oneindexn/themes/nexmoe/js/nexmoe.js"></script>

<?php view::end('content');?>
