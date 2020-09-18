<?php view::layout('themes/'.(config('style')?config('style'):'material').'/layout')?>
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
		<button class="mdui-btn mdui-ripple" id="pagesearch">过滤</button>
		<button class="mdui-btn mdui-ripple singleopt" id="rename" style="display: none;">重命名</button>
		<button class="mdui-btn mdui-ripple multiopt" id="deleteall" style="display: none;">删除</button>
		<button class="mdui-btn mdui-ripple multiopt" id="copybtn" onclick="copy()" style="display: none;">复制</button>
		<button class="mdui-btn mdui-ripple multiopt" id="cutbtn" onclick="cut()" style="display: none;">剪切</button>
		<button class="mdui-btn mdui-ripple multiopt" id="sharebtn" style="display: none;">分享</button>
		</div>
	</div>
<?php endif;?> 


<div class="mdui-container-fluid">
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
				<li class="mdui-list-item th">
					<?php if(is_login()):?>
						<label class="mdui-checkbox"><input type="checkbox" value="" id="checkall" onclick="checkall()"><i
								class="mdui-checkbox-icon"></i></label>
						<?php endif;?> 
					<div class="mdui-col-xs-12 mdui-col-sm-7">文件 <i class="mdui-icon material-icons icon-sort" data-sort="name" data-order="downward">expand_more</i></div>
					<div class="mdui-col-sm-3 mdui-text-right">修改时间 <i class="mdui-icon material-icons icon-sort" data-sort="date" data-order="downward">expand_more</i></div>
					<div class="mdui-col-sm-2 mdui-text-right">大小 <i class="mdui-icon material-icons icon-sort" data-sort="size" data-order="downward">expand_more</i></div>
				</li>
				
				<?php foreach($items as $item):?>
					<?php if(!empty($item['folder'])):?>
						<li class="mdui-list-item mdui-ripple" data-sort 
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
							<a href="<?php echo $item['path'];?>" target="_blank">
							<div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
								<i class="mdui-icon material-icons">folder_open</i>
								<span><?php echo $item['name'];?></span>
							</div>
							<div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
							<div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
							</a>
						</li>
					<?php else:?>
						<li class="mdui-list-item file mdui-ripple" data-sort
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
							<a href="<?php echo $item['path'];?>" target="_blank">
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
</div>


<div class="mdui-container">
 <div class="mdui-dialog" id="search_form">
    <div class="mdui-dialog-content">
		<form action="?/search" method="post">
			<div class="mdui-textfield mdui-textfield-floating-label">
				<label class="mdui-textfield-label">输入关键词</label>
				<input class="mdui-textfield-input" type="text" style="margin: 50px 0;" name="keyword" />
				<div class="mdui-row-xs-3">
				<div class="mdui-col"></div>
					<div class="mdui-col">
						<button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple">提交</button>
					</div>
				</div>
			</div>
		</form>
	</div>

    <div class="mdui-dialog-actions">
      <button class="mdui-btn mdui-ripple" mdui-dialog-cancel>取消</button>
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
<script src="https://cdn.jsdelivr.net/gh/xieqifei/StaticsResources@v1.0/oneindexn/common/search/js/search.js"></script>
<?php view::end('content');?>