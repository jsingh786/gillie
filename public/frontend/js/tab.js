<!--
var BROWSER_IE4 = "IE4"
var BROWSER_NN4 = "NN4"
var BROWSER_IE = "IE"
var BROWSER_VER;
BROWSER_VER = checkBrowser()
xMousePos = 0;
yMousePos = 0;
xMousePosMax = 0;
yMousePosMax = 0;

function showSingleLevelTable(tableToShow,position,leftPos,topPos,leftAdj,topAdj){
//alert("|")
if (everythingLoaded == true){

	var lyr = getMyHTMLElement(tableToShow);
	lyr.style.left =(getLeft(position) + leftAdj )+ "px";
	lyr.style.top = (getTop(position) + topAdj) + "px";
	showDisplayTable(tableToShow)
	}
}
function thinkAboutHidingTable(tableToHide){thinkAboutTimeOut = setTimeout("hideDisplayTable('"+tableToHide+"')",1000);}
function forgetAboutHidingTable(tableToHide){if (typeof thinkAboutTimeOut != 'undefined') {clearTimeout(thinkAboutTimeOut)}}



function checkBrowser() {
	var BROWSER_VER;
	if (document.all && !document.getElementById) 
		BROWSER_VER = BROWSER_IE4
	else if (document.layers) 
		BROWSER_VER = BROWSER_NN4
	else if (document.getElementById) 
		BROWSER_VER = BROWSER_IE
	else 
		BROWSER_VER = BROWSER_IE
	return(BROWSER_VER)
}

function OpenWindow(PageName,PageFrame,PageProperties) { //v2.0
  PageProperties= PageProperties +',scrollbars=1,resizable=1'
  MyWindow = window.open (PageName,PageFrame,PageProperties)
}

function showDisplayTable(tableToShow){
	getMyHTMLElement(tableToShow).style.display=''
}

function hideDisplayTable(tableToHide){
	getMyHTMLElement(tableToHide).style.display='none'
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function swapImage(imageName,imageFile){
	var swapImageName = eval('document.' + imageName);
	swapImageName.src = imageFile;
}

function getTop(item){
var top =0;
var mywhere;
mywhere = getMyHTMLElement(item);
	while (mywhere.tagName.toLowerCase() != 'body')
	{
	top += mywhere.offsetTop;
	mywhere = mywhere.offsetParent;
	}
return top;
}

function getLeft(item){
var left =0;
var mywhere;
mywhere = getMyHTMLElement(item);
	while (mywhere.tagName.toLowerCase() != 'body')
	{
	left += mywhere.offsetLeft;
	mywhere = mywhere.offsetParent;
	}
return left;
}

function printPage(){window.print()}

function addToFavorite(urlToAdd,titleToAdd){window.external.AddFavorite(urlToAdd,titleToAdd)}


function bookmarksite(title, url){
if (document.all)
window.external.AddFavorite(url, title);
else if (window.sidebar)
window.sidebar.addPanel(title, url, "")
}


function open_win(url,wname,attr)
{
	if (wname=='undefined') wname = "win"
	if (attr=='undefined') attr = "toolbar=0,location=0,directories=0,status=1,menubar=1,scrollbars=0,resizable=0,width=645,height=540"
	var new_win = window.open(url, wname, attr);
	new_win.focus();
}

function addprod_to_basket(sformname){
var oform = document.forms[sformname]
oform.submit()}

function addprod_to_wishlist(sformname,surl){
var oform = document.forms[sformname]
oform.action = surl;
oform.submit()}


function limittext(field, maxlimit) {
if (field.value.length > maxlimit) 
field.value = field.value.substring(0, maxlimit);
}

function popUp(url) {
	sealWin=window.open(url,"win",'toolbar=0,location=0,directories=0,status=1,menubar=1,scrollbars=1,resizable=1,width=500,height=450');
	self.name = "mainWin"; 
}
			
function popUp2(url,w,h) {
	sealWin=window.open(url,"win",'toolbar=0,location=0,directories=0,status=1,menubar=1,scrollbars=1,resizable=1,width='+w+',height='+h);
	self.name = "mainWin"; 
}

function getMyHTMLElement(sid)
{
	var oelement;
	if(BROWSER_VER==BROWSER_IE4)
		oelement = document.all[sid]
	else if(BROWSER_VER==BROWSER_NN4)
		oelement = document.layers[sid]
	else if(BROWSER_VER==BROWSER_IE)
		oelement = document.getElementById(sid)
	else
		oelement = document.getElementById(sid)
	return(oelement)
}

function LTrim(str)
{
   var whitespace = new String(" \t\n\r");

   var s = new String(str);

   if (whitespace.indexOf(s.charAt(0)) != -1) {
      var j=0, i = s.length;
      while (j < i && whitespace.indexOf(s.charAt(j)) != -1)
         j++;
      s = s.substring(j, i);
   }
   return s;
}

function RTrim(str)
{
   var whitespace = new String(" \t\n\r");

   var s = new String(str);

   if (whitespace.indexOf(s.charAt(s.length-1)) != -1) {

      var i = s.length - 1;       // Get length of string

      while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1)
         i--;

      s = s.substring(0, i+1);
   }

   return s;
}

function Trim(str)
{
   return RTrim(LTrim(str));
}

function replaceString(aSearch, aFind, aReplace)
    {
    result = aSearch;
    if (result != null && result.length > 0)
        {
        a = 0;
        b = 0;
        while (true)
            {
            a = result.indexOf(aFind, b);
            if (a != -1)
                {
                result = result.substring(0, a) + aReplace + result.substring(a + aFind.length);
                b = a + aReplace.length;
            }
            else
            break;
        }
    }
    return result;
}

function submitIfEnterKey(e,d){
     var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox
     if(key == 13){
		startSearch();
     }
}

function startSearch(){
	resetQuickSearch('QuickSearchBox')
	var search_entered = getMyHTMLElement('QuickSearchBox')
	if (search_entered != ""){
	document.QS1.name.value = search_entered.value
	document.QS1.submit()
	}
}
function resetQuickSearch(formStart,valOpt){
	var search_entered = getMyHTMLElement(formStart)
	if (search_entered.value == valOpt){
	search_entered.value = '';
	}
}
function resetQuickSearchBack(formStart,valOpt){
	var search_entered = getMyHTMLElement(formStart)
	if (search_entered.value == ''){
	search_entered.value = valOpt;
	}
}

function isStringANumber(string) {
    if (string.length == 0)
        return false;
    for (var i=0;i < string.length;i++)
        if ((string.substring(i,i+1) < '0') || (string.substring(i,i+1) > '9')){
            return false;
    }
    return true;
}
///*REM -- Alternate images for different colours (#117968) - Murali Kanduri - 03/09/2009 - START*/
function cleanColourValues(alternativeColourSelected){

    alternativeColourSelected = replaceString(alternativeColourSelected,' ','');
    alternativeColourSelected = replaceString(alternativeColourSelected,'-','');
    alternativeColourSelected = replaceString(alternativeColourSelected,'/','');
    alternativeColourSelected = replaceString(alternativeColourSelected,'\\','');
    return alternativeColourSelected;
}
///*REM -- Alternate images for different colours (#117968) - Murali Kanduri - 03/09/2009 - END*/
//-->
