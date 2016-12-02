var page = require('webpage').create(), system = require('system'), URL, output, viewport;

if (system.args.length < 3 || system.args.length > 4) {
	console.log('Usage: render.js URL Filename [ViewportWidth*ViewportHeight]');
	console.log('  example: render.js "http://www.getensight.com" "getensight.png" "800*600"');
	phantom.exit(1);
} else {
	URL = system.args[1];
	output = system.args[2];
	if (system.args[3]) {
		viewport = system.args[3].split('*');;
	}
}
page.viewportSize = { width: (viewport ? viewport[0] : 800), height: (viewport ? viewport[1] : 600) };
page.open(URL, function () {
	page.render(output);
	phantom.exit();
});