/*//////////////////////////
STRAWBERRY 1.2
powered by http://strawberry.goodgirl.ru
version 1.2 support by http://strawberry.mgcorp.ru

 Packed by Mr.Miksar
miksar@mail.ru
(c) 2009 - 2010
//////////////////////////*/




/*//////////////////////////
Options for this js file function
//////////////////////////*/
var ie=document.all?1:0;
var ns=document.getElementById&&!document.all?1:0;
var persistmenu="yes";
var persisttype="sitewide";  



/*//////////////////////////
This use in admin panel confings mod
//////////////////////////*/

  function OpenTab(obj,n){
	if (document.getElementById) {
		var el = document.getElementById(obj);
		var ar = document.getElementById("masterdiv").getElementsByTagName("div");
		if (el.style.display != "block") {
			for (var i=0; i<ar.length; i++) {
			if (ar[i].className=="submenu")
			ar[i].style.display = "none";
			}
			el.style.display = "block";
		} else {
			el.style.display = "block";
		}
	}
 }

function sm_bg(obj) {
	if (document.getElementById) {
		var el = document.getElementById(obj);
		var ar = document.getElementById("masterdiv").getElementsByTagName("th");

		if (el.className != "mnon") {
			for (var i=0; i<ar.length; i++) {
			   ar[i].className = "mnoff";
			}
			   el.className = "mnon";
		} else {

			   el.className = "mnon";
		}

	}
	
}




/*//////////////////////////
Get elements
//////////////////////////*/

function _getElementById(id){
  var item = null;
  if (document.getElementById){
    item = document.getElementById(id);
  } else if (document.all){
    item = document.all[id];
  } else if (document.layers){
    item = document.layers[id];
  }
  return item;
}


function _getElementByTag(tag){
  var item = null;
  item = document.getElementsByTagName(tag);
  return item;
}






/*//////////////////////////
look at choose comment for answer
//////////////////////////*/

function quickreply(comment_id){
	var replyForm = _getElementById('comment');
	var oldPosition = _getElementById('comment0');
	var currentComment = _getElementById('comment' + comment_id);
    var parentID = _getElementById('parent');

    if (comment_id == parentID.value || comment_id == 0){
    	replyForm.style.margin = 0;
    	parentID.value = 0;
    	oldPosition.parentNode.insertBefore(replyForm, oldPosition.nextSibling);
    } else {
    	replyForm.style.margin = '0px 0px 0px 50px';
    	parentID.value = comment_id;
    	currentComment.parentNode.insertBefore(replyForm, currentComment.nextSibling);
    }

	return false;
}







/*//////////////////////////
Open window with a help text
//////////////////////////*/

function Help(section) {
  q=window.open('index.php?mod=help&section='+section, 'Help', 'scrollbars=1,resizable=1,width=500,height=400');
}









/*//////////////////////////
Show Or Hide functions. None use now.
//////////////////////////*/

function ShowOrHide(d1, d2) {
  if (d1 != ''){
  	DoDiv(d1);
  }

  if (d2 != ''){
  	DoDiv(d2);
  }
}


function DoDiv(id) {
  var item = _getElementById(id);

  if (!item){
  } else if (item.style){
    if (item.style.display == 'none'){
    	item.style.display = '';
    } else {
    	item.style.display = 'none';
  	}
  } else {
  	item.visibility = 'show';
  }
}








/*//////////////////////////
Make a question
//////////////////////////*/

function confirmDelete(url){
    var agree = confirm('Вы действительно хотите удалить это?');

    if (agree){
        document.location=url;
    }
}







/*//////////////////////////
check and uncheck mass checkboxes
//////////////////////////*/

function ckeck_uncheck_all(area) {

    if (area == "editnews"){frm = document.editnews;}
    else if (area == "comments"){frm = document.comments;}

    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=="checkbox") {
            if(frm.master_box.checked == true){ elmnt.checked=true; }
            else{ elmnt.checked=false; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = true; }
    else{ frm.master_box.checked = false; }
}










/*//////////////////////////
Get preview for content in admin panel
//////////////////////////*/

function preview(mod){
    dd = window.open('', 'prv')
    document.addnews.mod.value = 'preview';
    document.addnews.target = 'prv'
    document.addnews.submit();
    dd.focus()
    setTimeout("document.addnews.mod.value='"+mod+"';document.addnews.target='_self'", 500)
}








/*//////////////////////////
do focus in textarea
//////////////////////////*/

function focus(){
	document.forms[0].title.focus();
}









/*//////////////////////////
show preview
//////////////////////////*/

function showpreview(image,name){
	if (image != ""){
	    document.images[name].src = image;
	} else {
	    document.images[name].src = "admin/images/blank.gif";
	}
}









/*//////////////////////////
Insert tags and smiles in textarea
//////////////////////////*/

function insertext(open, close, area){

//var msgfield = $(area);

    if(area=="short"){msgfield = document.addnews.short_story}
    else if(area=="full"){msgfield = document.addnews.full_story}
    else if(area=="comment"){msgfield = document.addnews.comment}
    else if(area=="reply"){msgfield = document.addnews.reply}
    else if(area=="gbin"){msgfield = document.addnews.gbin_story}
    else if(area=="gbout"){msgfield = document.addnews.gbout_story}
    else if(area=="commin"){msgfield = document.addnews.commin_story}
    else if(area=="commout"){msgfield = document.addnews.commout_story}

    // IE support
    if (document.selection && document.selection.createRange){
        msgfield.focus();
        sel = document.selection.createRange();
        sel.text = open + sel.text + close;
        msgfield.focus();
    }

    // Moz support
    else if (msgfield.selectionStart || msgfield.selectionStart == "0"){
        var startPos = msgfield.selectionStart;
        var endPos = msgfield.selectionEnd;

        msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
        msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
        msgfield.focus();
    }

    // Fallback support for other browsers
    else {
        msgfield.value += open + close;
        msgfield.focus();
    }

    return;
}










/*//////////////////////////
Check field for content
//////////////////////////*/

function process_form(the_form)
{
	var element_names = new Object()
	element_names["username"] 	 = "Логин"
	element_names["password"] 	 = "Пароль"
	element_names["title"]       = "Заголовок"
	element_names["short_story"] = "Краткая новость"
	element_names["commin_story"] = "Комментарий"
	element_names["poster"]      = "Автор"
	element_names["comment"]     = "Комментарий"
	element_names["regusername"] = "Логин"
	element_names["regpassword"] = "Пароль"

	if (document.all || document.getElementById)
	{
		for (i = 0; i < the_form.length; ++i)
		{
			var elem = the_form.elements[i]
            if ((
               elem.name == "short_story"
            || elem.name == "poster"
            || elem.name == "comment"
            || elem.name == "commin_story"
            || elem.name == "username"
            || elem.name == "password"
            || elem.name == "regusername"
            || elem.name == "regpassword"
            )
            && elem.value==''
            )
            {
                alert("\"" + element_names[elem.name] + "\" это поле обязательно для заполнения в этой форме.")
                elem.focus()
                return false
            }
		}
	}

	return true
}











/*//////////////////////////
For comment form (ru)
//////////////////////////*/

function contactsTable(){if(ie){if(document.all.contactsTr.style.display=="none"){document.all.contactsText.innerText="Скрыть форму комментария?";document.all.contactsTr.style.display=''}else{document.all.contactsText.innerText="Вы хотите добавить комментарий? Жмите сюда!";document.all.contactsTr.style.display='none'}}else if(ns){if(document.getElementById("contactsTr").style.display=="none"){document.getElementById("contactsText").innerHTML="Скрыть форму комментария?";document.getElementById("contactsTr").style.display=''}else{document.getElementById("contactsText").innerHTML="Вы хотите добавить комментарий? Жмите сюда!";document.getElementById("contactsTr").style.display='none'}}else alert("Ваш браузер не поддерживается!")}










/*//////////////////////////
for swich elements
sm - havn`t memory
smn - whith memory in cookie
//////////////////////////*/

function sm(obj) {
	if(document.getElementById){
	var el = document.getElementById(obj);
	var ar = document.getElementById("masterfild").getElementsByTagName("div"); 	
	if(el.style.display != "block"){
			for (var i=0; i<ar.length; i++){
				if (ar[i].className=="submenu")
				ar[i].style.display = "none";
			}

			el.style.display = "block";
		}else{
			el.style.display = "none";
		}
	}
}


// For swich elements with remember
function smn(obj) {
	if (document.getElementById) {
		var elt = document.getElementById(obj);
		var art = document.getElementById("masterdiv").getElementsByTagName("div");
		if (elt.style.display != "block") {
			for (var g=0; g<art.length; g++) {
			art[g].style.display = "none";
			}                
			elt.style.display = "block";
		} else {
			elt.style.display = "block";
		}
	}
}

// For swich elements with remember - get_cookie function
function get_cookie(Name) { 
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) { 
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}

// For swich elements with remember - on load function
function onloadfunction(){
if (persistmenu=="yes"){
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=get_cookie(cookiename)
if (cookievalue!="")
document.getElementById(cookievalue).style.display="block"
}
}

// For swich elements with remember - main remember function
function savemenustate(){
var inc=0, blockid=""
while (document.getElementById("sub"+inc))
{
if (document.getElementById("sub"+inc).style.display=="block"){
blockid="sub"+inc
break
}
inc++
}
var cookiename=(persisttype=="sitewide")? "switchmenu" : window.location.pathname
var cookievalue=(persisttype=="sitewide")? blockid+";path=/" : blockid
document.cookie=cookiename+"="+cookievalue
}

if (window.addEventListener)
window.addEventListener("load", onloadfunction, false)
else if (window.attachEvent)
window.attachEvent("onload", onloadfunction)
else if (document.getElementById)
window.onload=onloadfunction

if (persistmenu=="yes" && document.getElementById)
window.onunload=savemenustate














/*//////////////////////////
this for users rating system (mod money)
//////////////////////////*/

function giveMoney(user_id){
  window.open('/system/show_users.php?money=give&user_id=' + user_id, '_Motivation', 'height=360,resizable=yes,scrollbars=yes,width=500');
return false;
}

function takeMoney(user_id){
  window.open('/system/show_users.php?money=take&user_id=' + user_id, '_Motivation', 'height=360,resizable=yes,scrollbars=yes,width=500');
return false;
}

function showMoney(user_id){
  window.open('/system/show_users.php?money=show&user_id=' + user_id, '_Motivation', 'height=360,resizable=yes,scrollbars=yes,width=500');
return false;
}









/*////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/
/*//////////////////////////////////////////|NEW JS FOR STRAWBERRY|\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\*/
/*\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\*/




/*/////////////////////////
PNG fix for IE 5; IE 5.5; IE 6;
/////////////////////////*/
function PNG(element) {
if (/MSIE (5\.5|6).+Win/.test(navigator.userAgent))
{
var src;

if (element.tagName=='IMG')
{
if (/\.png$/.test(element.src))
{
src = element.src;
element.src = "/images/0.gif";
}
}
else
{
src = element.currentStyle.backgroundImage.match(/url\("(.+\.png)"\)/i)
if (src)
{
src = src[1];
element.runtimeStyle.backgroundImage="none";
}
}

if (src) element.runtimeStyle.filter =
"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
}
}



/*/////////////////////////
It can count text in your field. We are can limited it!
/////////////////////////*/
function textCounter(field,counter,maxlimit,linecounter) {
	// text width//
	var fieldWidth =  parseInt(field.offsetWidth);
	var charcnt = field.value.length;
	// trim the extra text
	if (charcnt > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
	// progress bar percentage
	var percentage = parseInt(100 - (( maxlimit - charcnt) * 100)/maxlimit) ;
	document.getElementById(counter).style.width =  parseInt((fieldWidth*percentage)/100)+"px";
	document.getElementById(counter).innerHTML="500"+percentage+"%"
	// color correction on style from CCFFF -> CC0000
	setcolor(document.getElementById(counter),percentage,"background-color");
	}
}



/*//////////////////////////
COLOR SET
//////////////////////////*/
function setcolor(obj,percentage,prop){
	obj.style[prop] = "rgb(80%,"+(100-percentage)+"%,"+(100-percentage)+"%)";
}



/*//////////////////////////
POP_UPer
//////////////////////////*/
function popuper(url, title, x, y) {
	window.open (url, title, "toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=1, copyhistory=0, width="+x+", height="+y+"");
}



/*//////////////////////////
 Sort table function
//////////////////////////*/
addEvent(window, "load", sortables_init);
var SORT_COLUMN_INDEX;
function sortables_init() {
	if (!document.getElementsByTagName) return;
	tbls = document.getElementsByTagName("table");
	for (ti=0;ti<tbls.length;ti++) {
		thisTbl = tbls[ti];
		if (((' '+thisTbl.className+' ').indexOf("sort") != -1) && (thisTbl.id)) {
			ts_makeSortable(thisTbl);
		}
	}
}
function ts_makeSortable(table) {
	if (table.rows && table.rows.length > 0) {
		var firstRow = table.rows[0];
	}
	if (!firstRow) return;
	for (var i=0;i<firstRow.cells.length;i++) {
		var cell = firstRow.cells[i];
		var txt = ts_getInnerText(cell);
		cell.innerHTML = '<a href="#" class="sortheader" onclick="ts_resortTable(this);return false;">'+txt+'<span class="sortarrow">&nbsp;<img src="/images/icons/plus.png" border="0"></span></a>';
	}
}
function ts_getInnerText(el) {
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };
	if (el.innerText) return el.innerText;
	var str = "";
	var cs = el.childNodes;
	var l = cs.length;
	for (var i = 0; i < l; i++) {
		switch (cs[i].nodeType) {
			case 1:
			str += ts_getInnerText(cs[i]);
			break;
			case 3:
			str += cs[i].nodeValue;
			break;
		}
	}
	return str;
}
function ts_resortTable(lnk) {
    var span;
    for (var ci=0;ci<lnk.childNodes.length;ci++) {
        if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') span = lnk.childNodes[ci];
    }
    var spantext = ts_getInnerText(span);
    var td = lnk.parentNode;
    var column = td.cellIndex;
    var table = getParent(td,'TABLE');
    if (table.rows.length <= 1) return;
    var itm = ts_getInnerText(table.rows[1].cells[column]);
    sortfn = ts_sort_caseinsensitive;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^[Ј$]/)) sortfn = ts_sort_currency;
    if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
    SORT_COLUMN_INDEX = column;
    var firstRow = new Array();
    var newRows = new Array();
    for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
    for (j=1;j<table.rows.length;j++) { newRows[j-1] = table.rows[j]; }
	newRows.sort(sortfn);
	if (span.getAttribute("sortdir") == 'down') {
        ARROW = '&nbsp;<img src="/images/icons/up.png" border="0">';
        newRows.reverse();
        span.setAttribute('sortdir','up');
    } else {
        ARROW = '&nbsp;<img src="/images/icons/down.png" border="0">';
        span.setAttribute('sortdir','down');
    }
	for (i=0;i<newRows.length;i++) { if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) table.tBodies[0].appendChild(newRows[i]);}
	for (i=0;i<newRows.length;i++) { if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) table.tBodies[0].appendChild(newRows[i]);}
	var allspans = document.getElementsByTagName("span");
    for (var ci=0;ci<allspans.length;ci++) {
        if (allspans[ci].className == 'sortarrow') {
            if (getParent(allspans[ci],"table") == getParent(lnk,"table")) {
				allspans[ci].innerHTML = '&nbsp;<img src="/images/icons/plus.png" border="0">';
            }
        }
    }
	span.innerHTML = ARROW;
}
function getParent(el, pTagName) {
	if (el == null) {
		return null;
	} else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase()) {
		return el;
	} else {
		return getParent(el.parentNode, pTagName);
	}
}
function ts_sort_date(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa.length == 10) {
        dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
    } else {
        yr = aa.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
    }
    if (bb.length == 10) {
        dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
    } else {
        yr = bb.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
    }
    if (dt1==dt2) return 0;
    if (dt1<dt2) return -1;
    return 1;
}
function ts_sort_currency(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    return parseFloat(aa) - parseFloat(bb);
}
function ts_sort_numeric(a,b) {
    aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
    if (isNaN(bb)) bb = 0;
    return aa-bb;
}
function ts_sort_caseinsensitive(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}
function ts_sort_default(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}
function addEvent(elm, evType, fn, useCapture) {
	if (elm.addEventListener){
		elm.addEventListener(evType, fn, useCapture);
		return true;
	} else if (elm.attachEvent){
		var r = elm.attachEvent("on"+evType, fn);
		return r;
	} else {
		alert("Handler could not be removed");
	}
}





/*//////////////////////////
file_uploader form for admin.
//////////////////////////*/
function file_uploader(which){
if (which < f) return
    f ++
    d = document.getElementById('file_'+f)
    d.innerHTML = '<input type="file" name="file['+f+']" id="file_'+f+'" value="" onchange="file_uploader('+f+');" /><br><span id="file_'+(f+1)+'">'
}

function img_uploader(which){
if (which < p) return
    p ++
    z = document.getElementById('image_'+p)
    z.innerHTML = '<input type="file" name="image['+p+']" id="image_'+p+'" size="70" value="" onchange="img_uploader('+p+');"><br><span id="image_'+(p+1)+'">'
}

