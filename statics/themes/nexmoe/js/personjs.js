var documentWidth=window.screen.availWidth;
var documentHeight=window.screen.availHeight;
if(documentWidth>documentHeight){
    document.getElementsByTagName("body")[0].setAttribute("style","background-size:100% auto")
}else{
    document.getElementsByTagName("body")[0].setAttribute("style","background-size:auto 100%")
}