<?php 
define('VIEW_PATH', ROOT.'view/');
class CommonController{
	
	function __construct(){
	}
	//aria2
	function offline(){
		if(config('offline')['offline']||is_login()){
			return view::load('common/offline');
		}
		else{
			return view::load('common/tips')->with('tip','管理员未授权使用');
		}
	}

	//搜索
	function search(){
		if(is_login()){
			if($_POST['keyword']){
				$keyword=$_POST['keyword'];
				$items = onedrive::search($keyword);
				if(!$items){
					return view::load('common/tips')->with('tip','没有找到与“'.$_POST['keyword'].'”有关的内容');
				}
				$searchinfo['keyword']=$keyword;
				$searchinfo['count']=count($items);
				return view::load('common/search')->with('items',$items)->with('searchinfo',$searchinfo);
			}else{
				return '参数错误';
			}
		}
		else{
			return '请登陆后尝试';
		}
	}
	//新建文件夹
	//post参数：uploadurl，当前url的路径
	function create_folder(){
		if(is_login()){
			$urlinfo=parse_url($_POST['uploadurl']);
			if(stristr($_POST['uploadurl'],'?')){
				$paths = explode('/', rawurldecode($urlinfo['query']));
			}else{
				$paths = explode('/', rawurldecode($urlinfo['path']));
			}
			$paths=array_values($paths);
			$remotepath = get_absolute_path(join('/', $paths));
			$data = onedrive::create_folder(str_replace('//','/',config('onedrive_root').$remotepath),$_POST['foldername']);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $data;
		}
		else{
			return '未登录无法新建文件夹';
		}
	}
	//重命名
	//post参数：name：新名称；itemid：itemid
	function rename(){
		if(is_login()){
			$newname=$_POST['name'];
			$itemid=$_POST['itemid'];
			$resp=onedrive::rename($itemid,$newname);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $resp;
		}
		else{
			return '未登录无法重命名';
		}
	}
	//删除
	//传入一个stringfy后的itemid的数组
	function deleteitems(){
		if(is_login()){
			$data = file_get_contents( "php://input" );
			$items = json_decode( $data );
			$resp=onedrive::delete($items);
			oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
			return $resp;
		}
		else{
			return '未登录无法删除';
		}
	}
	//url上传
	function upload_url(){
		if(is_login()){
			if($_POST['file_url']&&$_POST['path_url']){
				$file_url=$_POST['file_url'];
				$path_url=$_POST['path_url'];
				if($_POST['file_name']){
					$file_name = $_POST['file_name'];
				}
				else{
					$file_name = pathinfo(parse_url($file_url,PHP_URL_PATH),PATHINFO_BASENAME);
				}
				$path = str_replace('//','/',$this->url2path($path_url).'/'.$file_name);
				$process_url = onedrive::upload_url($path , $file_url);
				if($process_url){
					return $process_url;
				}
				else{
					return 0;
				}
			}else{
				return '参数错误';
			}
		}else{
			return '未登录';
		}
	}

	//在线上传，大小限制在4M
	//post参数：onlinefile：一个文件；uploadurl：当前url路径
	function onlinefileupload()
	{
		
		if($this->uploadcondition($_FILES["onlinefile"]) ){
			$filename = $_FILES["onlinefile"]['name'];
			$content = file_get_contents( $_FILES["onlinefile"]['tmp_name']);
			//管理员不受上传目录限制
			if(is_login()){
				//获取路径
				$paths = explode('/', rawurldecode($_POST['uploadurl']));
				if(strcmp($paths[1],'?')==0){
					array_shift($paths);
					array_shift($paths);
				}
				//$paths=array_shift($paths);
				$remotepath = get_absolute_path(join('/', $paths));
			}
			//游客只能上传到指定目录
			else{
				$remotepath =  config('offline')['upload_path'];
			}
			$remotefile = $remotepath.$filename;
			$result = onedrive::upload(str_replace('//','/',config('onedrive_root').$remotefile), $content);
			
			if($result){
				$root = get_absolute_path(dirname($_SERVER['SCRIPT_NAME'])).config('root_path');
				$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$url = $_SERVER['HTTP_HOST'].$root.'/'.$remotepath.rawurldecode($filename).((config('root_path') == '?')?'&s':'?s');
				$url = $http_type.str_replace('//','/', $url);
				$result['path']=$url;
				// view::direct($url);
				return json_encode($result);
			}
		}else{
			return '未登录或文件过大';
		}
		
	}
	//上传文件的条件判断
	function uploadcondition($file){
		
		if($file['size'] > 4485760 || $file['size'] == 0){
			return false;
		}
		if(config('offline')['online']==false&&!is_login()){
			return false;
		}

		return true;
	}
	//粘贴
	function paste(){
		if(is_login()){
			$data = file_get_contents( "php://input" );
			$jsondata = json_decode($data);//字符串转对象。
			if($jsondata->cutitems){
				$cutitems=$jsondata->cutitems;
				$url=$jsondata->url;
				return $this->cut($cutitems,$url);
			}
			if($jsondata->copyitems){
				$copyitems=$jsondata->copyitems;
				$url=$jsondata->url;
				return $this->copy($copyitems,$url);
			}
			return '操作失误，请重新尝试！';
		}
		else{
			return '未登录无法重命名';
		}
	}
	//移动或剪切
	function cut($cutitems,$url){
		$itemid=$this->url2id($url);
		$resp=onedrive::move($cutitems,$itemid);
		oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
		return json_encode(json_decode(json_encode($resp)));//decode去掉字符串中的转义字符再
	}
	//复制
	function copy($copyitems,$url){
		$itemid=$this->url2id($url);
		$resp=onedrive::copy($copyitems,$itemid);
		oneindex::refresh_cache(get_absolute_path(config('onedrive_root')));
		return $resp;
	}
	//url转路径
	function url2path($url){
		$paths=array();
		$urlinfo=parse_url($url);
		if(stristr($url,'?')){
			$paths = explode('/', rawurldecode($urlinfo['query']));
		}else{
			$paths = explode('/', rawurldecode($urlinfo['path']));
		}
		if(strcmp($paths[1],'?')==0){
			array_shift($paths);
			array_shift($paths);
		}
		$remotepath = get_absolute_path(join('/', $paths));
		$path = str_replace('//','/',config('onedrive_root').$remotepath);
		return $path;
	}

	//url转id
	function url2id($url){
		$urlinfo=parse_url($url);
		if(stristr($url,'?')){
			$paths = explode('/', rawurldecode($urlinfo['query']));
		}else{
			$paths = explode('/', rawurldecode($urlinfo['path']));
		}
		$paths=array_values($paths);
		$totalpath = str_replace('//','/',config('onedrive_root').get_absolute_path(join('/', $paths)));
		$itemid=onedrive::path2id($totalpath);
		return $itemid;
	}
	
}
