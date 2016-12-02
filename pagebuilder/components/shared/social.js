(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=128680590480310";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function repositionSocialWidget (nextToObject, space) {
//--- Sets up the appropriate widget
    
	tPos = _getDynamicObjectPosition (nextToObject);
    
	if (parseInt (_getDynamicObjectWidth (_$ ('social-widgets-large'))) + space < parseInt (tPos[0])) {
		_$ ('social-widgets-small').style.display = 'none';
		targetObject = new Object ();
		targetObject.target = nextToObject;
		_showAtDynamicPosition (targetObject, _$ ('social-widgets-large'), 'lefttop', -space, 95);
	} else {
		_$ ('social-widgets-large').style.display = 'none';
		_$ ('social-widgets-small').style.display = 'block';
	}

}

function scrollSocialWidget (nextToObject) {
//--- For the large widget only, keeps it above the page view

	if (parseInt (_getScrollTop ()) > originalTop) {
		_$ ('social-widgets-large').style.top = parseInt (_getScrollTop ()) + 10 + 'px';
	} else {
		_$ ('social-widgets-large').style.top = originalTop + 'px';
	}

}

function loadSocialWidget (nextToObject, space) {
//--- Loads a social widget either below an object or to the left of it depending on screen space

	nextToObject.appendChild (_$ ('social-widgets-small'));
	repositionSocialWidget (nextToObject, space);
    
	//--- Set an event
	window.onresize = function () { repositionSocialWidget (nextToObject, space); }
	window.onscroll = function () { tPos = _getDynamicObjectPosition (nextToObject); originalTop = parseInt (tPos[1]); scrollSocialWidget (nextToObject); }

}

var originalTop = 0;