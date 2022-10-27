<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no" />
		<title><?php e($title.' - '.config('site_name'));?></title>
		<link rel="stylesheet" href="/statics/themes/nexmoe/css/forkGh.css" />
		<!--Safari浏览器-->
		<link rel="apple-touch-icon" sizes="64x64" href="/statics/themes/nexmoe/IMG/OneIndexM.svg">
		<!--Chrome等浏览器-->
		<link rel="icon" href="/statics/themes/nexmoe/img/OneIndexM.svg">
        <!-- 视频Flv加载插件 -->
        <script crossorigin="anonymous" integrity="sha512-49OFf+8jaHx4Vb7iFNb46Loq1pIxXlEYeVOQRIx0KLBRF4KSV6E7QK2Vw5r416TQDyBJW+DFh2CyTV7+gCWd6g==" src="https://lib.baomitu.com/flv.js/1.6.2/flv.min.js"></script>
        <!-- 视频播放器加载插件 -->
        <link crossorigin="anonymous" integrity="sha384-tLMkTWh2pfXNWGFlUS0w1TFtRG5xZ9lPWFOooj+vDDLIL+xBGQU/voDBY5XE2lVh" href="https://lib.baomitu.com/aplayer/1.10.1/APlayer.min.css" rel="stylesheet">
        <script crossorigin="anonymous" integrity="sha384-gdGYZwHnfJM54evoZhpO0s6ZF5BQiybkiyW7VXr+h5UfruuRL/aORyw+5+HZoU6e" src="https://lib.baomitu.com/aplayer/1.10.1/APlayer.min.js"></script>
        <!-- 音乐加载插件 -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/meting@2/dist/Meting.min.js"></script> -->
        <script src="/statics/themes/nexmoe/js/Meting.min.js"></script>
        <link crossorigin="anonymous" href="https://lib.baomitu.com/mdui/0.4.3/css/mdui.min.css" rel="stylesheet">
		<style>
			body {
				background-color: #f2f5fa;
				padding-bottom: 3rem;
				background-position: center bottom;
				background-repeat: no-repeat;
				background-attachment: fixed
			}

			.nexmoe-item {
				margin: 20px -8px 0 !important;
				padding: 15px !important;
				border-radius: 5px;
				background-color: #fff;
				-webkit-box-shadow: 0 .5em 3em rgba(161, 177, 204, .4);
				box-shadow: 0 .5em 3em rgba(161, 177, 204, .4);
				background-color: #fff
			}

			.mdui-img-fluid,
			.mdui-video-fluid {
				border-radius: 5px;
				border: 1px solid #eee
			}

			.mdui-list {
				padding: 0
			}

			.mdui-list-item {
				margin: 0 !important;
				border-radius: 5px;
				padding: 0 10px 0 5px !important;
				border: 1px solid #eee;
				margin-bottom: 10px !important
			}

			.mdui-list-item:last-child {
				margin-bottom: 0 !important
			}

			.mdui-list-item:first-child {
				border: none
			}

			.mdui-toolbar {
				width: auto;
				margin-top: 1rem !important
			}

			.mdui-appbar .mdui-toolbar {
				height: 1rem;
				font-size: 16px
			}

			.mdui-toolbar>* {
				padding: 0 6px;
				margin: 0 2px;
				opacity: .5
			}

			.mdui-toolbar>.mdui-typo-headline {
				padding: 0 16px 0 0
			}

			.mdui-toolbar>i {
				padding: 0
			}

			.mdui-toolbar>a:hover,
			a.mdui-typo-headline,
			a.active {
				opacity: 1
			}

			.mdui-container {
				max-width: 980px
			}

			.mdui-list>.th {
				background-color: initial
			}

			.mdui-list-item>a {
				width: 100%;
				line-height: 48px
			}

			.mdui-toolbar>a {
				padding: 0 16px;
				line-height: 30px;
				border-radius: 30px;
				border: 1px solid #eee
			}

			.mdui-toolbar>a:last-child {
				opacity: 1;
				background-color: #1e89f2;
				color: #ffff
			}

			@media screen and (max-width:980px) {
				.mdui-list-item .mdui-text-right {
					display: none
				}

				.mdui-container {
					width: 100% !important;
					margin: 0
				}

				.mdui-toolbar>* {
					display: none
				}

				.mdui-toolbar>a:last-child,
				.mdui-toolbar>.mdui-typo-headline,
				.mdui-toolbar>i:first-child {
					display: block
				}
			}
		</style>
		<!-- <script src="https://cdn.jsdelivr.net/gh/xieqifei/StaticsResources/oneindexn/mdui/mdui.min.js"></script> -->
        <script crossorigin="anonymous" integrity="sha384-GXQQyAWEQJOklZd/6CWH3BbffdVqZ85WoDiENXxSqLpjrdWzpX15CKmya8HIdM4r" src="https://lib.baomitu.com/mdui/0.4.3/js/mdui.min.js"></script>
	</head>
	<body class="mdui-theme-primary-blue-grey mdui-theme-accent-blue">
		<a class="github-fork-ribbon" href="https://github.com/Mintimate/OneindexM" data-ribbon="Fork me on GitHub"
			title="Fork me on GitHub">Fork me on GitHub</a>
		<div class="mdui-container">
			<div class="mdui-container-fluid">
				<div class="mdui-toolbar nexmoe-item">
					<a href="/"><?php e(config('site_name'));?></a>
					<?php foreach((array)$navs as $n=>$l):?>
					<i class="mdui-icon material-icons mdui-icon-dark" style="margin:0;">chevron_right</i>
					<a href="<?php e($l);?>"><?php e($n);?></a>
					<?php endforeach;?>
					<!--<a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">refresh</i></a>-->
				</div>
			</div>
			<?php view::section('content');?>
		</div>
		<meting-js server="netease" type="playlist" id="2485662712" fixed="true">
		</meting-js>
		<!-- id改成自己的歌单号 -->
		<script src="/statics/themes/nexmoe/js/personjs.js">
		</script>
		<script src="/statics/themes/nexmoe/js/nexmoe.js"></script>

	</body>
</html>
