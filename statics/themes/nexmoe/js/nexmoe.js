$ = mdui.JQ;
$.fn.extend({
    sortElements: function (comparator, getSortable) {
        getSortable = getSortable || function () { return this; };

        var placements = this.map(function () {
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function () {
                parentNode.insertBefore(this, nextSibling);
                parentNode.removeChild(nextSibling);
            };
        });

        return [].sort.call(this, comparator).each(function (i) {
            placements[i].call(getSortable.call(this));
        });
    }
});

function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();
}

function thumb(){
	if($('#format_list').text() == "apps"){
		$('#format_list').text("format_list_bulleted");
		$('.nexmoe-item').removeClass('thumb');
		$('.nexmoe-item .mdui-icon').show();
		$('.nexmoe-item .mdui-list-item').css("background","");
	}else{
		$('#format_list').text("apps");
		$('.nexmoe-item').addClass('thumb');
		$('.mdui-col-xs-12 i.mdui-icon').each(function(){
			if($(this).text() == "image" || $(this).text() == "ondemand_video"){
				var href = $(this).parent().parent().attr('href');
				var thumb =(href.indexOf('?') == -1)?'?t=220':'&t=220';
				$(this).hide();
				$(this).parent().parent().parent().css("background","url("+href+thumb+")  no-repeat center top");
			}
		});
	}

}	


$(function(){
	$('.file a').each(function(){
		$(this).on('click', function () {
			var form = $('<form target=_blank method=post></form>').attr('action', $(this).attr('href')).get(0);
			$(document.body).append(form);
			form.submit();
			$(form).remove();
			return false;
		});
	});

	$('.icon-sort').on('click', function () {
        let sort_type = $(this).attr("data-sort"), sort_order = $(this).attr("data-order");
        let sort_order_to = (sort_order === "less") ? "more" : "less";
        $('li[data-sort]').sortElements(function (a, b) {
            let data_a = $(a).attr("data-sort-" + sort_type), data_b = $(b).attr("data-sort-" + sort_type);
            let rt = data_a.localeCompare(data_b, undefined, {numeric: true});
            return (sort_order === "more") ? 0-rt : rt;
        });
        $(this).attr("data-order", sort_order_to).text("expand_" + sort_order_to);
    });
});
var inst1 = new mdui.Fab('#myFab');

//文件在线上传对话框
var inst2 = new mdui.Dialog('#onlineupload-dialog');
//文件远程上传对话框
var inst6 = new mdui.Dialog('#remoteupload-dialog');
var inst7 = new mdui.Dialog('#progress');
//文件上传方式选择
var inst5 = new mdui.Select('#file_upload');
$('#file_upload').on('closed.mdui.select', function () {
    var  myselect=document.getElementById("file_upload");
    var index=myselect.selectedIndex ;
    var option = myselect.options[index].value;
    if(option == "online_upload"){
        inst2.open();
    }else if(option == "remote_upload"){
        inst6.open();
    }else if(option=="offline_upload"){
        window.open('/?/offline','_blank'); 
    }
  });

//全局搜索
var inst3 = new mdui.Dialog('#search_form');
document.getElementById('search').addEventListener('click', function () {
    inst3.open();
});

//分享链接
var inst4 = new mdui.Dialog('#share');
document.getElementById('sharebtn').addEventListener('click', function () {
    inst4.open();
});
var sharedialog = document.getElementById('share');
sharedialog.addEventListener('open.mdui.dialog', function () {
    var textarea_value=new Array()
    for(var i=0;i<check_val.length;i++){
        textarea_value[i] = window.location.protocol+'//'+window.location.host+document.getElementById(check_val[i]).getElementsByTagName('a')[0].getAttribute('href');
    }
    document.getElementById('sharelinks').value=textarea_value.join('\r\n');
});

//当前页关键词过滤
mdui.JQ('#pagesearch').on('click', function () {
    mdui.prompt('输入过滤的关键词或后缀',
        function (value) {
			var dom_items = document.getElementsByClassName('filter');
			for(var i=0;i<dom_items.length;i++){
				if(dom_items[i].getAttribute('data-sort-name').indexOf(value)==-1){
					dom_items[i].style.display = "none";
				}else{
					dom_items[i].style.display = "";
				}
			}
        },
        function (value) {
        },
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//新建文件夹
mdui.JQ('#newfolder').on('click', function () {
    mdui.prompt('输入文件夹名称',
        function (value) {
            var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
            $('#pending').css('display',null);
            httpRequest.open('POST', '?/create_folder', true); //第二步：打开连接
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query='foldername='+value+'&uploadurl='+window.location.href;
            httpRequest.send(query);
            httpRequest.onreadystatechange = function () {
                if(httpRequest.readyState == 4 && httpRequest.status == 200) {
                    var item = JSON.parse(httpRequest.responseText);
                    if(item.id){
	                    var $folder_domstr = $('<li class="mdui-list-item mdui-ripple filter" data-sort data-sort-name="'+item.name+'" data-sort-date="'+item.lastModifiedDateTime+'" data-sort-size="'+item.size+'" id="'+item.id+'"><label class="mdui-checkbox"><input type="checkbox" value="'+item.id+'" name="itemid" onclick="onClickHander()"><i class="mdui-checkbox-icon"></i></label><a href="'+window.location.href+item.name+'"><div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate"><i class="mdui-icon material-icons">folder_open</i><span>'+item.name+'</span></div><div class="mdui-col-sm-3 mdui-text-right">'+item.lastModifiedDateTime.replace(/[a-zA-Z]/g,' ')+'</div><div class="mdui-col-sm-2 mdui-text-right">'+item.size+'</div></a></li>');
	                    if($('#backtolast').length>0){
	                    	$('#backtolast').after($folder_domstr);
	                    	console.log('存在返回上一级');
	                    }else{
	                		 $('#indexsort').after($folder_domstr);
	                		 console.log('不存在返回上一级');
                        }
                        $('#pending').css('display','none');
	                    console.log('新建文件夹成功');
                    }else if(item.error){
                        $('#pending').css('display','none');
                    	alert('新建文件夹失败,错误代码:'+item.error.message);
                    }
                }
                if(httpRequest.status==502&&httpRequest.readyState==4){
                	alert('服务器无响应！请刷新后查看是否新建成功！');
                	$('#pending').css('display','none');
                }
            };
        },
        function (value) {
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//重命名
mdui.JQ('#rename').on('click', function () {
    mdui.prompt('输入新名称',
        function (value) {
            var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
            httpRequest.open('POST', '?/rename', true); //第二步：打开连接
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query='name='+value+'&itemid='+check_val[0];
            httpRequest.send(query);//发送请求 将情头体写在send中
            var item_dom=document.getElementById(check_val[0]);
            item_dom.getElementsByClassName('loading-gif')[0].style.display='';
           
            /**
             * 获取数据后的处理程序
             */
            httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功
                	item=JSON.parse(httpRequest.responseText);
                    var a_dom = item_dom.getElementsByTagName('a')[0];
                    a_href = item.parentReference.path.replace("/drive/root:","?")+"/"+item.name;
                    item_dom.getElementsByTagName('span')[0].innerHTML=value;
                    item_dom.getElementsByClassName('loading-gif')[0].style.display='none';
                    item_dom.getElementsByTagName('a')[0].setAttribute('href',a_href);
                    item_dom.setAttribute('data-sort-name',value);
                }else{
                    item_dom.getElementsByClassName('loading-gif')[0].style.display='none';
                }
            };
        },
        function (value) {
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});
//删除文件/文件夹
mdui.JQ('#deleteall').on('click', function(){
    mdui.confirm('请确认是否删除选中项目',
        function(){
            for(var i=0;i<check_val.length;i++){
            	document.getElementById(check_val[i]).getElementsByClassName('loading-gif')[0].style.display='';
            }
            var httpRequest = new XMLHttpRequest();
            httpRequest.open('POST', '?/deleteitems', true);
            httpRequest.setRequestHeader("Content-type","application/json");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            var query=JSON.stringify(check_val);
            httpRequest.send(query);
            httpRequest.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {//验证请求是否发送成功
            		 var deleteerror = 0;
            		 var errormessage = '';
                	var resp = JSON.parse(httpRequest.responseText);
                    for(var i=0;i<check_val.length;i++){
                    	if(resp[i]){
                            deleteerror++;
                            document.getElementById(check_val[i]).getElementsByClassName('loading-gif')[0].style.display='none';
                    		errormessage = JSON.parse(resp[i])['error']['message'];
                    	}else{
                    		document.getElementById(check_val[i]).style.display = 'none';
                    	}
                    }
                    if(deleteerror>=1){
                        alert(deleteerror+'个文件删除失败！请重试。错误代码：'+errormessage);
                    }
                }
                if(httpRequest.status==502&&httpRequest.readyState==4){
                    alert('服务器无响应！请刷新后查看是否删除成功！');
                    for(var i=0;i<check_val.length;i++){
                        document.getElementById(check_val[i]).getElementsByClassName('loading-gif')[0].style.display='none';
                    }
                }
            };
        },
        function(){
        }
        ,
        {
            confirmText:'确认',
            cancelText:'取消'
        }
    );
});



//文件选中某个文件后
function onClickHander(){
    checkitems = document.getElementsByName("itemid");
    check_val = [];
    for (k in checkitems) {
        if (checkitems[k].checked) check_val.push(checkitems[k].value);
    }
    //选中一个文件时显示可以重命名按钮
    var singleopt = document.getElementsByClassName("singleopt");
    //选中多个文件时，可以复制移动等
    var multiopt = document.getElementsByClassName("multiopt");
    //单文件操作
    if(check_val.length==1){
        for(var i=0;i<singleopt.length;i++){
            singleopt[i].style.display = "inline";
        }
    }
    else{
        for(var i=0;i<singleopt.length;i++){
            singleopt[i].style.display = "none";
        }
    }
    //多文件操作
    if(check_val.length>=1){
        for(var i=0;i<multiopt.length;i++){
            multiopt[i].style.display = "inline";
        }
    }else{
        for(var i=0;i<multiopt.length;i++){
            multiopt[i].style.display = "none";
        }
    }
}
//选中所有文件
function checkall(){
    var checkall = document.getElementById("checkall");
    var itemsbox = document.getElementsByName("itemid");
    if (checkall.checked == false) {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = false;
        }
    } else {
        for (var i = 0; i < itemsbox.length; i++) {
            itemsbox[i].checked = true;
        }
    }
    onClickHander();
}
//在线上传小文件,需要一个id为filesubmit的表单，有类型为file的input
function submitForm() {
    var formData = new FormData($("#filesubmit")[0]);  //重点：要用这种方法接收表单的参数
    inst2.close();
    $('#pending').css('display',null);
    const req = new XMLHttpRequest();
    req.open('post', '?/onlinefileupload', true);
    req.send(formData);
    req.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
        if (req.readyState == 4 && req.status == 200) {//验证请求是否发送成功
            var item=JSON.parse(req.responseText);
            // console.log(JSON.parse(req.responseText).parentReference.path);
            if(item.id){
            	$('#pending').css('display','none');
                var $dom_items=getListDom(item);
                 if($('#backtolast').length>0){
                    $('#backtolast').after($dom_items);
                    console.log('存在返回上一级');
                }else{
                     $('#indexsort').after($dom_items);
                     console.log('不存在返回上一级');
                }
                console.log('上传成功');
            }else if(item.error){
            	$('#pending').css('display','none');
                alert('新建文件夹失败,错误代码:'+item.error.message);
            }
        }else{
            $('#pending').css('display','none');
        }
    };
}

//远程url上传文件
function submitRemoteFile() {
    var formData = new FormData($("#remoteupload")[0]);  //重点：要用这种方法接收表单的参数
    inst6.close();
    const req = new XMLHttpRequest();
    req.open('post', '?/upload_url', true);
    req.send(formData);
    req.onreadystatechange = function () {//请求后的回调接口，可将请求成功后要执行的程序写在其中
        if (req.readyState == 4 && req.status == 200) {//验证请求是否发送成功
            if(req.responseText=='0'){
                alert('远程上传仅支持Onedrive个人版');
            }else{
                var progress_url = req.responseText;
                showProgress(progress_url);
            }
        }
    };
}

//展示进度条
function showProgress(url){
    inst7.open();
    var num=-1;
    width = 0;
    myInterval = setInterval(function(){
        if (100.0-width <= 1e-5) {
            // console.log('100');
            clearInterval(myInterval);
            inst7.close();
            location.reload();
            alert('加载完成！即将刷新页面。');
        } else {
            num=getProgress(url);
        }
    }, 1000);
    
}
//获取进度并更新。返回获取的进度值。
function getProgress(url){
    var url=url;
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('GET', url, true);
    httpRequest.send();
    httpRequest.onreadystatechange=function(){
        if (httpRequest.readyState == 4 && (httpRequest.status==200|| httpRequest.status == 202||httpRequest.status == 303)) {
            console.log(httpRequest.responseText);
            var data=JSON.parse(httpRequest.responseText);
            if(data.percentageComplete>=width){
                width=data.percentageComplete;
                updateProgress(width);
            }
            return data.percentageComplete;
        }
    };
    return -1;
}
//更新进度条dom
function updateProgress(width){
    var dom = document.getElementById("progress_width");
    dom.style.width = width+'%';
}

//自动填写url上传文件名
function getRemoteUrl(){
	var filename_dom = document.getElementById("filename");
	var fileurl_dom = document.getElementById('fileurl');
	var url = fileurl_dom.value;
	filename = fileNameFromUrl(url);
	filename_dom.value = filename;
}

function fileNameFromUrl(url)
{ 
	
	return url?url.split('/').pop().split('#').shift().split('?').shift():null; 
	
}


//点击复制
function copy(){
    document.cookie="copyitems="+JSON.stringify(check_val);
    document.getElementById('copybtn').style.display="none";
    document.getElementById('pastebtn').style.display="";
    document.getElementById('cutbtn').style.display="none";
}
//点击剪切
function cut(){
    document.cookie="cutitems="+JSON.stringify(check_val);
    document.getElementById('pastebtn').style.display="";
    document.getElementById('cutbtn').style.display="none";
    document.getElementById('copybtn').style.display="none";
}
//判断cookie是否有复制和粘贴
var pastebtn = document.getElementById('pastebtn');
if(!getCookie('cutitems')&&!getCookie('copyitems')){
    pastebtn.style.display="none";
}else{
    pastebtn.style.display="";
}
//点击粘贴
function paste(){
    $('#pending').css('display',null);
    if(getCookie('cutitems')){
    	// var json_data = {cutitems:getCookie('cutitems'),url:window.location.href};
    	var json_data = '{"cutitems":'+getCookie('cutitems')+',"url":"'+window.location.href+'"}';
        var httpRequest = new XMLHttpRequest();
            httpRequest.open('POST', '?/paste', true);
            httpRequest.setRequestHeader("Content-type","application/json");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            // var query=JSON.stringify(json_data);
            httpRequest.send(json_data);
            var itemsid=JSON.parse(getCookie('cutitems'));
            httpRequest.onreadystatechange = function () {
                if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                    var resp_json = JSON.parse(httpRequest.responseText);
                    var resperror = 0;
                    var errormsg ='';
                    for(var i=0;i<resp_json.length;i++){
                    	var item_json=JSON.parse(resp_json[i]);
                    	if(item_json.id){
                    		var $item_domstr = getListDom(item_json);
                    		if($('#backtolast').length>0){
			                    $('#backtolast').after($item_domstr);
			                }else{
			                     $('#indexsort').after($item_domstr);
			                }
                    	}
                    	else if(item_json.error){
                    		resperror = 1;
                    		errormsg = item_json['error']['message'];
                    	}
                    }
                    if(resperror==1){
                    	alert('移动失败，错误代码：'+errormsg);
                    }
                    $('#pending').css('display','none');
                    document.getElementById('pastebtn').style.display='none';
                    document.getElementById('cutbtn').style.display="";
                    document.getElementById('copybtn').style.display="";    
                }
                if(httpRequest.status==502&&httpRequest.readyState==4){
                	$('#pending').css('display','none');
                	
                    document.getElementById('pastebtn').style.display='none';
                    document.getElementById('cutbtn').style.display="";
                    document.getElementById('copybtn').style.display=""; 
                    alert('服务器无响应！');
                }
                document.cookie = "cutitems=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
            };
    }else if(getCookie('copyitems')){
    	// var json_data = {cutitems:getCookie('cutitems'),url:window.location.href};
    	var json_data = '{"copyitems":'+getCookie('copyitems')+',"url":"'+window.location.href+'"}';
        var httpRequest = new XMLHttpRequest();
        httpRequest.open('POST', '?/paste', true);
        httpRequest.setRequestHeader("Content-type","application/json");
        httpRequest.send(json_data);
        var resperror=0;
        var respsuccess=0
        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var resp = JSON.parse(httpRequest.responseText);
                for(var i=0;i<resp.length;i++){
                    var resp_data = resp[i];
                    if(resp_data.http_code!=202){
                        resperror++;
                    }else if(resp_data.http_code==202){
                        respsuccess++;
                    }
                }
                
                document.getElementById('pastebtn').style.display='none';
                document.getElementById('cutbtn').style.display="";
                document.getElementById('copybtn').style.display="";
                $('#pending').css('display','none');
                alert('共'+respsuccess+'个文件复制成功,'+resperror+'个文件复制失败,大文件复制时间较长，请自行刷新页面！');
            }else{
                //需要删除
                $('#pending').css('display','none');
                
                document.getElementById('pastebtn').style.display='none';
                document.getElementById('cutbtn').style.display="";
                document.getElementById('copybtn').style.display="";  
            }
            document.cookie = "copyitems=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
            
        };
    }
    
}
//获取cookie
function getCookie(cname){
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)==0) { 
            return c.substring(name.length,c.length); 
        }
    }
    return false;
}
//获取新的dom
function getListDom(item){
	var $domstr='';
	var path=item.parentReference.path.replace("/drive/root:","?")+"/"+item.name;
	if(item['folder']){
		$domstr='<li class="mdui-list-item mdui-ripple filter" data-sort data-sort-name="'+item.name+'" data-sort-date="'+item.lastModifiedDateTime+'" data-sort-size="'+item.size+'" id="'+item.id+'"><label class="mdui-checkbox"><input type="checkbox" value="'+item.id+'" name="itemid" onclick="onClickHander()"><i class="mdui-checkbox-icon"></i></label><a href="'+path+'"><div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate"><i class="mdui-icon material-icons">folder_open</i><span>'+item.name+'</span></div><div class="mdui-col-sm-3 mdui-text-right">'+item.lastModifiedDateTime.replace(/[a-zA-Z]/g,' ')+'</div><div class="mdui-col-sm-2 mdui-text-right">'+item.size+'</div></a></li>';
	}else{
		$domstr='<li class="mdui-list-item file mdui-ripple filter" data-sort data-sort-name="'+item.name+'" data-sort-date="'+item.lastModifiedDateTime+'" data-sort-size="'+item.size+'" id="'+path+'"><label class="mdui-checkbox"><input type="checkbox" value="'+item.id+'" name="itemid" onclick="onClickHander()"><i class="mdui-checkbox-icon"></i></label><a href="'+path+'" target="_blank"><div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate"><i class="mdui-icon material-icons">insert_drive_file</i><span>'+item.name+'</span></div><div class="mdui-col-sm-3 mdui-text-right">'+item.lastModifiedDateTime.replace(/[a-zA-Z]/g,' ')+'</div><div class="mdui-col-sm-2 mdui-text-right">'+item.size+'</div></a></li>';
	}
	return $domstr;
}


