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

//点击复制
function copy(){
    document.cookie="copyitems="+JSON.stringify(check_val);
    document.getElementById('copybtn').style.display="none";
    document.getElementById('cutbtn').style.display="none";
}
//点击剪切
function cut(){
    document.cookie="cutitems="+JSON.stringify(check_val);
    document.getElementById('cutbtn').style.display="none";
    document.getElementById('copybtn').style.display="none";
}

function onClickHander(){
    checkitems = document.getElementsByName("itemid");
    check_val = [];
    for (k in checkitems) {
        if (checkitems[k].checked) check_val.push(checkitems[k].value);
    }
    //alert(check_val);
    console.log(check_val);
    var singleopt = document.getElementsByClassName("singleopt");
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