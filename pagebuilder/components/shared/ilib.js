//--- Ensight Source Code (ESC)
//--- First Created on Friday, January 8 2010 by John Ginsberg (snowed in)
//--- For Ensight 4

//--- Module Name: ilib.js
//--- Contains: v1.0

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Loads other Javascript functions, ensures no duplication

//--- Notice:
//--- This code is copyright (C) 2010, Ensight Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from Ensight Ltd - http://www.getensight.com

//----------------------------------------------------------------

var _jsLibLoaded = new Array ();
var _jsLibLoadedCount = 0;
var _jsLibDefaultPath = "/live/pagebuilder/components/shared/";

function _$ (element) {
//--- Unique iLib shortcut, loaded here for speed

	return document.getElementById (element);

}

function _jsLibObject () {

	this.object = null;
	this.called = false;
	this.loaded = false;

}

function _jsLibLoader (urls, callOnComplete) {
//--- Load one or more external javascript libraries; always pass in an array

	for (var i = 0; i < urls.length; i++) {
		if (_jsLibLoaded[urls[i]] instanceof _jsLibObject == false) {
			_jsLibLoaded[urls[i]] = new _jsLibObject ();
		}
		if (_jsLibLoaded[urls[i]].called) {
			continue; // don't load more than once
		}
		_jsLibLoaded[urls[i]].object = document.createElement ("script");
		_jsLibLoaded[urls[i]].object.type = "text/javascript";
		_jsLibLoaded[urls[i]].object.src = (urls[i].indexOf ('/') == -1 ? _jsLibDefaultPath : '') + urls[i];
		document.getElementsByTagName("head")[0].appendChild (_jsLibLoaded[urls[i]].object);
		if (callOnComplete) {
			if (_jsLibLoaded[urls[i]].object.readyState) { //IE
	        	_jsLibLoaded[urls[i]].object.onreadystatechange = function () {
	            	if ((this.readyState == "loaded") || (this.readyState == "complete")) {
	                	 this.onreadystatechange = null;
	                	 for (var j = 0; j < urls.length; j++) {
	                		 if (_jsLibLoaded[urls[j]].object == this) {
	                			 _jsLibLoaded[urls[j]].loaded  = true;
	                			 _jsLibLoadedCount++;
                			 }
                		 }
                		 if (_jsLibLoadedCount == urls.length) {
							 callOnComplete (); // only after all urls are loaded
						 }
	            	}
	            }
	        } else {
	        	_jsLibLoaded[urls[i]].object.onload = function () {
                	 for (var j = 0; j < urls.length; j++) {
                		 if (_jsLibLoaded[urls[j]].object == this) {
                			 _jsLibLoaded[urls[j]].loaded  = true;
                			 _jsLibLoadedCount++;
            			 }
            		 }
            		 if (_jsLibLoadedCount == urls.length) {
						 callOnComplete (); // only after all urls are loaded
					 }
				}
			}
		}
		_jsLibLoaded[urls[i]].called = true;
	}

}

function _onBodyReady (fPointer) {
//--- Sets the specified function to run when the body is ready (before onload)

	if (document.addEventListener) {
		document.addEventListener ('DOMContentLoaded', fPointer, false);
	}

	(function() {
		/*@cc_on
		try {
			document.body.doScroll ('up');
			return fPointer ();
		} catch(e) {}
		/*@if (false) @*/
		if (/loaded|complete/.test (document.readyState)) return fPointer ();
		/*@end @*/
		if (!fPointer.done) setTimeout (arguments.callee, 30);
	})();

	if (window.addEventListener) {
		window.addEventListener ('load', fPointer, false);
	} else
	if (window.attachEvent) {
		window.attachEvent ('onload', fPointer);
	}

}

function _ajaxRequest (url, method, callOnSuccess, callOnFailure) {
//--- Generic ajax request handler, with different handlers on success (200) and failure (any other status)
//--- Each handler function is sent two arguments, the handler object and the original URL 

	var ajaxObj;

	if (window.XMLHttpRequest) {
		ajaxObj = new XMLHttpRequest ();
	} else
	if (window.ActiveXObject) {
		ajaxObj = new ActiveXObject ("Microsoft.XMLHTTP");
	}

	ajaxObj.onreadystatechange = function () { if (ajaxObj.readyState != 4) { return; } if (ajaxObj.status == 200) { callOnSuccess (ajaxObj, url); } else { callOnFailure (ajaxObj, url); } }
	if (method.toLowerCase () == 'get') {
		ajaxObj.open (method, url, true);
		ajaxObj.send (null);
	} else
	if (method.toLowerCase () == 'post') {
		parameters = url.substr (url.indexOf ('?'));
		url = url.substr (0, url.indexOf ('?'));
		ajaxObj.open (method, url, true);
		ajaxObj.setRequestHeader ("Content-type", "application/x-www-form-urlencoded");
		ajaxObj.setRequestHeader ("Content-length", parameters.length);
		ajaxObj.setRequestHeader ("Connection", "close");
		ajaxObj.send (parameters);
	}

	return ajaxObj;

}