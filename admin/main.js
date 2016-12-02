newLocation = new String (top.location);

if (newLocation.substr (newLocation.lastIndexOf ('/') + 1) != 'main.html') {
	top.location = newLocation.substr (0, newLocation.lastIndexOf ('/')) + '/main.html';
}
