/*//////////////////////////
 STRAWBERRY 1.2
 powered by http://strawberry.goodgirl.ru
 version 1.2 support by http://strawberry.su

 Packed by Mr.Miksar
 miksar@mail.ru
 (c) 2011
//////////////////////////*/




/*/////////////////////////// AJAX CLASS //////////////////////////////*/

/* Simple AJAX Code-Kit (SACK) v1.6.1 */
/* ©2005 Gregory Wild-Smith */
/* www.twilightuniverse.com */
/* Software licenced under a modified X11 licence,
   see documentation or authors website for more details */
   
function sack(file) {
	this.xmlhttp = null;
	this.resetData = function() {
		this.method = "POST";
		this.queryStringSeparator = "?";
		this.argumentSeparator = "&";
		this.URLString = "";
		this.encodeURIString = true;
		this.execute = false;
		this.element = null;
		this.elementObj = null;
		this.requestFile = file;
		this.vars = new Object();
		this.responseStatus = new Array(2);
	};

	this.resetFunctions = function() {
		this.onLoading = function() { };
		this.onLoaded = function() { };
		this.onInteractive = function() { };
		this.onCompletion = function() { };
		this.onError = function() { };
		this.onFail = function() { };
	};

	this.reset = function() {
		this.resetFunctions();
		this.resetData();
	};

	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e1) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				this.xmlhttp = null;
			}
		}

		if (! this.xmlhttp) {
			if (typeof XMLHttpRequest != "undefined") {
				this.xmlhttp = new XMLHttpRequest();
			} else {
				this.failed = true;
			}
		}
	};

	this.setVar = function(name, value){
		this.vars[name] = Array(value, false);
	};

	this.encVar = function(name, value, returnvars) {
		if (true == returnvars) {
			return Array(encodeURIComponent(name), encodeURIComponent(value));
		} else {
			this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
		}
	}

	this.processURLString = function(string, encode) {
		encoded = encodeURIComponent(this.argumentSeparator);
		regexp = new RegExp(this.argumentSeparator + "|" + encoded);
		varArray = string.split(regexp);
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split("=");
			if (true == encode){
				this.encVar(urlVars[0], urlVars[1]);
			} else {
				this.setVar(urlVars[0], urlVars[1]);
			}
		}
	}

	this.createURLString = function(urlstring) {
		if (this.encodeURIString && this.URLString.length) {
			this.processURLString(this.URLString, true);
		}

		if (urlstring) {
			if (this.URLString.length) {
				this.URLString += this.argumentSeparator + urlstring;
			} else {
				this.URLString = urlstring;
			}
		}

		// Prevents caching of URLString
		this.setVar("rndval", new Date().getTime());

		urlstringtemp = new Array();
		for (key in this.vars) {
			if (false == this.vars[key][1] && true == this.encodeURIString) {
				encoded = this.encVar(key, this.vars[key][0], true);
				delete this.vars[key];
				this.vars[encoded[0]] = Array(encoded[1], true);
				key = encoded[0];
			}

			urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key][0];
		}
		if (urlstring){
			this.URLString += this.argumentSeparator + urlstringtemp.join(this.argumentSeparator);
		} else {
			this.URLString += urlstringtemp.join(this.argumentSeparator);
		}
	}

	this.runResponse = function() {
		eval(this.response);
	}

	this.runAJAX = function(urlstring) {
		if (this.failed) {
			this.onFail();
		} else {
			this.createURLString(urlstring);
			if (this.element) {
				this.elementObj = document.getElementById(this.element);
			}
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					totalurlstring = this.requestFile + this.queryStringSeparator + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
					try {
						this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
					} catch (e) { }
				}

				this.xmlhttp.onreadystatechange = function() {
					switch (self.xmlhttp.readyState) {
						case 1:
							self.onLoading();
							break;
						case 2:
							self.onLoaded();
							break;
						case 3:
							self.onInteractive();
							break;
						case 4:
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;

							if (self.execute) {
								self.runResponse();
							}

							if (self.elementObj) {
								elemNodeName = self.elementObj.nodeName;
								elemNodeName.toLowerCase();
								if (elemNodeName == "input"
								|| elemNodeName == "select"
								|| elemNodeName == "option"
								|| elemNodeName == "textarea") {
									self.elementObj.value = self.response;
								} else {
									self.elementObj.innerHTML = self.response;
								}
							}
							if (self.responseStatus[0] == "200") {
								self.onCompletion();
							} else {
								self.onError();
							}

							self.URLString = "";
							break;
					}
				};

				this.xmlhttp.send(this.URLString);
			}
		}
	};

	this.reset();
	this.createAJAX();
}





var ajax = new sack();


/*/////////////////////////
Show load img while content is loading
/////////////////////////*/
function wload(obj, way) {
if (! way) {
way = "/";
}
	var e = document.getElementById("ajx"+obj); 
	e.innerHTML = "<div class=\"loading\"><img src=\""+way+"images/loading.gif\" alt=\"Loading...\"/></div>";
}



/*/////////////////////////
Use this, when you use post method
/////////////////////////*/
function pload(ld, obj, way) {
if (! way) {
way = "/";
}
	var form = document.getElementById("form"+obj);
	ajax.setVar("go", form.go.value);
	ajax.setVar("act", form.act.value);
	ajax.setVar("id", form.id.value);
	ajax.setVar("cid", form.cid.value);
	ajax.setVar("typ", form.typ.value);
	ajax.setVar("mod", form.mod.value);
	ajax.setVar("name", form.name.value);
	ajax.setVar("title", form.title.value);
	ajax.setVar("text", form.text.value);
	ajax.setVar("ftext", form.ftext.value);
	ajax.setVar("check", form.check.value);
	ajax.requestFile = way+"active.php";
	ajax.method = form.method.value;
	ajax.element = "ajx"+obj;
	if (ld == '1') {
		ajax.onLoading = wload(obj, way);
	} else {
		ajax.onLoading = "";
	}
	ajax.runAJAX();
}



/*/////////////////////////
Use this, when you use get method
/////////////////////////*/
function gload(ld, obj, go, act, id, cid, tip, mod, text, way) {
if (! way) {
way = "/";
}
	ajax.setVar("go", go);
	ajax.setVar("act", act);
	ajax.setVar("id", id);
	ajax.setVar("cid", cid);
	ajax.setVar("tip", tip);
	ajax.setVar("mod", mod);
	ajax.setVar("text", text);
	ajax.setVar("way", way);
	ajax.requestFile = way+"active.php";
	ajax.method = "GET";
	ajax.element = "ajx"+obj;
	if (ld == '1') {
		ajax.onLoading = wload(obj, way);
	} else {
		ajax.onLoading = "";
	}
	ajax.runAJAX();
}

/*/////////////////////////
Use this, if you don`t wont use ajax for reload captcha
/////////////////////////*/
function wcompleted(obj, tip, way){
if (! way) {
way = "/";
}
var e = document.getElementById('ajx' + obj); 
var string = "<img src=\"" + way + "active.php?go=1&amp;tip=" + tip + "&amp;time=" + new Date().getTime() + "\" border=\"1\" alt=\"security\" style=\"cursor: pointer;\">";
e.innerHTML = string;
}


/*/////////////////////////
Use this, when you use reload captcha by ajax
/////////////////////////*/
function pinload(ld, obj, go, tip, way) {
if (! way) {
way = "/";
}
        ajax.setVar("go", go);
	ajax.setVar("tip", tip);
	ajax.setVar("way", way);
	ajax.requestFile = way+"active.php";
	ajax.method = "GET";
	ajax.element = "ajx"+obj;
	if (ld == '1') {
		ajax.onLoading = wload(obj, way);
	} else {
		ajax.onLoading = "";
	}
	ajax.runAJAX();
}







/*
This function update comment list on page without reload page self
*/

function complete(request, pin){
if (pin) {
wcompleted('comm', 'comm', '');
}
    if (request.status == 200){
        document.forms['comment'].elements['commin_story'].value = '';
        document.forms['comment'].elements['parent'].value = 0;
        quickreply(0);
        Element.hide('error_message');
        $('commentslist').innerHTML = request.responseText;
    } else {
        failure(request);
    }
}



/*
This function send error message for commentetor
*/

function failure(request){
    Element.show('error_message');
    $('error_message').innerHTML = request.responseText;
}




/*
Function for sending comments in base
and update this in page without reload - ajax technology!

modified function
28.08.2009

This is do block and unblock button for sending comments.
This button mast have name 'sendcom'!
document.forms['comment'].elements['sendcom'].disabled = true;
*/


function call_ajax(that, way, auth){
if (! way) {
way = "/system/";
}
document.forms['comment'].elements['sendcom'].disabled = true;
    
  new Ajax.Updater(
      {success: 'commentslist'},
     way+ 'inc/show.add-comment.php',
      {
          insertion: Insertion.Top,
          onComplete: function(request) {
            complete(request, auth);
            document.forms['comment'].elements['sendcom'].disabled = false;
          },
          onFailure: function(request) {failure(request)},
          parameters: Form.serialize(that),
          evalScripts: true
      }
  );

}

