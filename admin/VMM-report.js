function newWindow (URL, Title, Width, Height, Parameters) {
//--- Opens a new window with the specifics in the center of the screen

	window.parent.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), (Parameters ? Parameters : window)); return true;

}

function drawGraphsFB () {
//--- Has the page loaded?

	if (!_$ ('pageLoaded')) {
		setTimeout ('drawGraphsFB ()', 500); // wait and try again
	} else {
		drawGraphsFBCallback ();
	}

}

function drawGraphsFBCallback (data) {

	//--- Countries

	var data = google.visualization.arrayToDataTable (countryData);

	var chart = new google.visualization.GeoChart (document.getElementById ('geo_map'));
	chart.draw (data, {colorAxis: {colors: ['lightblue', 'red']}});

	//--- Languages

	var data = google.visualization.arrayToDataTable (languageData);
	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'left', alignment: 'center'},
		chartArea: {left: 0, top: 0, width: '450', height: '200'},
		pieSliceText: 'percentage'
	};

	var chart = new google.visualization.PieChart (document.getElementById ('language_pie'));
	chart.draw (data, options);

	//--- Fan Growth and Loss per Day

	var data = google.visualization.arrayToDataTable (growthStackedData);
	var options = {
		colors: ['#f702ba', '#1a67cc'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3,
		isStacked: false,
		legend: {position: 'none'},
		chartArea: {left: 0, top: 10}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('growth_stacked'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (growthOverallData);
	var options = {
		colors: ['#109618'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3,
		isStacked: false,
		legend: {position: 'none'},
		chartArea: {left: 0, top: 10}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('growth_overall'));
	chart.draw (data, options);

	//--- New and lost fan sources

	var data = google.visualization.arrayToDataTable (newFanSources);
	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'right', alignment: 'center'},
		chartArea: {left: 15, top: 10, width: '100%', height: '100%'},
		pieSliceText: 'value'
	};

	var chart = new google.visualization.PieChart (document.getElementById ('new_fans_sources'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (negativeActions);
	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'left', alignment: 'center'},
		chartArea: {left: 15, top: 0, width: '225', height: '100%'},
		pieSliceText: 'value'
	};

	var chart = new google.visualization.PieChart (document.getElementById ('negative_actions'));
	chart.draw (data, options);

	//--- Interactions table

	var data = new google.visualization.arrayToDataTable (interactionsTable);	
	var options = {
		page: 'enable',
		pageSize: 7,
		showRowNumber: false,
		allowHtml: true,
		sortColumn: 3,
		sortAscending: false
	};

	var table = new google.visualization.Table(document.getElementById ('feedback_table'));
	table.draw (data, options);

	//--- Engagement table

	var data = new google.visualization.arrayToDataTable (engagementsTable);
	var options = {
		page: 'enable',
		pageSize: 7,
		showRowNumber: false,
		allowHtml: true,
		sortColumn: 2,
		sortAscending: false
	};

	var table = new google.visualization.Table(document.getElementById ('clicks_table'));
	table.draw (data, options);

	//--- Graphs

	var data = google.visualization.arrayToDataTable (interactionsGraph);
	var options = {
		colors: ['#66cc99', '#fc575e', '#44bbff'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('feedback_stacked'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (engagementsGraph);
	var options = {
		colors: ['#cec323', '#f15731', '#843df7'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('clicks_stacked'));
	chart.draw (data, options);

};

function drawGraphsTW () {
//--- Has the page loaded?

	if (!_$ ('pageLoaded')) {
		setTimeout ('drawGraphsTW ()', 500); // wait and try again
	} else {
		drawGraphsTWCallback ();
	}

}

function drawGraphsTWCallback () {

	//--- Countries

	var data = google.visualization.arrayToDataTable (countryData);

	var chart = new google.visualization.GeoChart (document.getElementById ('geo_map'));
	chart.draw (data, {colorAxis: {colors: ['lightblue', 'red']}});

	//--- Languages

	var data = google.visualization.arrayToDataTable (languageData);

	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'left', alignment: 'center'},
		chartArea: {left: 0, top: 0, width: '450', height: '200'},
		pieSliceText: 'percentage'
	};

	var chart = new google.visualization.PieChart(document.getElementById ('language_pie'));
	chart.draw(data, options);

	//--- Fan Growth and Loss per Day

	var data = google.visualization.arrayToDataTable (growthStackedData);
	var options = {
		colors: ['#f702ba', '#1a67cc'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3,
		isStacked: false,
		legend: {position: 'none'},
		chartArea: {left: 0, top: 10}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('growth_stacked'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (growthOverallData);
	var options = {
		colors: ['#109618'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3,
		isStacked: false,
		legend: {position: 'none'},
		chartArea: {left: 0, top: 10}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('growth_overall'));
	chart.draw (data, options);

	//--- Graphs

	var data = google.visualization.arrayToDataTable (interactionsGraph);
	var options = {
		colors: ['#66cc99', '#fc575e', '#44bbff'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('feedback_stacked'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (engagementsGraph);
	var options = {
		colors: ['#cec323', '#f15731', '#843df7'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('clicks_stacked'));
	chart.draw (data, options);

	//--- Mentions

	var data = google.visualization.arrayToDataTable (mentions);

	var options = {
		theme: 'maximized',
		pointSize: 5,
		lineWidth: 3,
		hAxis: {textPosition: 'in', slantedText: true, maxAlternation: 1}
	};

	var chart = new google.visualization.LineChart (document.getElementById ('mentions'));
	chart.draw (data, options);

	//--- Interactions table

	var data = new google.visualization.arrayToDataTable (interactionsTable);	
	var options = {
		page: 'enable',
		pageSize: 7,
		showRowNumber: false,
		allowHtml: true,
		sortColumn: 3,
		sortAscending: false
	};

	var table = new google.visualization.Table(document.getElementById ('feedback_table'));
	table.draw (data, options);

	//--- Engagement table

	var data = new google.visualization.arrayToDataTable (engagementsTable);
	var options = {
		page: 'enable',
		pageSize: 7,
		showRowNumber: false,
		allowHtml: true,
		sortColumn: 2,
		sortAscending: false
	};

	var table = new google.visualization.Table(document.getElementById ('clicks_table'));
	table.draw (data, options);

}

function displayServiceResponse (Type, dateRange, minDate, maxDate) {

	if (reportInterval != null) {
		clearInterval (reportInterval);
	}

	responseBody = _$ ('social_post_form').contentWindow.document.body.innerHTML;

	//--- Place content
	if (responseBody != '') {
		_$ ('reportBody').innerHTML = responseBody;
		_$ ('reportBody').className = 'page';
		_$ ('reportBody').style.height = '';
	}

	if (Type == '1') {
		drawGraphsFB ();
	} else {
		drawGraphsTW ();
	}

	enableCalendar (dateRange, minDate, maxDate);

}

function enableCalendar (dateRange, minDate, maxDate) {
//--- Switches the calendar function on

	cal1 = Calendar.setup ({
		trigger: "calendarSelect",
		selectionType: Calendar.SEL_MULTIPLE,
//		selection: new Array (new Array (dateRange[0][0], dateRange[0][1])),
		disabled: function (date) { },
		min: minDate,
		max: maxDate,
		onSelect: function () {
			if ((dateRange[0]) && (!dateRange[1])) {
					dateRange[1] = this.selection.get()[0];
					cal1.selection.clear (true);
					cal1.selection.selectRange (dateRange[0], dateRange[1]);
			} else
			if (!dateRange[0]) {
					dateRange[0] = this.selection.get()[0];
			} else
			if ((dateRange[0]) && (dateRange[1])) {
					//--- First time its called, it's because of selectRange
					//--- Second time we clear it
					if (finaliseSelection == 0) {
						finaliseSelection =  1;
						if (cal1.selection.getFirstDate () != cal1.selection.getLastDate ()) {
							document.getElementById ('calendarSelect').style.display = 'none';
							document.getElementById ('calendarInteract').parentNode.style.display = '';
							document.getElementById ('calendarInteract').innerHTML = '<input type="button" value="Show selected range" onclick="if (document.getElementById (\'services\')) { showService (null, selectedType, selectedId, cal1.selection.getFirstDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (6, 2), cal1.selection.getLastDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getLastDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getLastDate ().toString ().substr (6, 2), session); } else { var isP = document.location.toString ().indexOf (\'Print=1\'); var isT = document.location.toString ().indexOf (\'Trigger=1\'); var isIE = document.location.toString ().indexOf (\'isIE=1\'); document.location = \'VMM-report-select.php?\' + (isP != -1 ? \'Print=1&\' : \'\') + (isT != -1 ? \'Trigger=1&\' : \'\') + (isIE != -1 ? \'isIE=1&\' : \'\') + \'ServiceType=\' + selectedType + \'&ServiceAssigned_ID=\' + selectedId + \'&SDate=\' + cal1.selection.getFirstDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (6, 2) + \'&EDate=\' + cal1.selection.getLastDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getLastDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getLastDate ().toString ().substr (6, 2) + \'&Session_ID=\' + session; } return false;" /><small> or <a href="#" onclick="document.getElementById (\'calendarSelect\').style.display = \'\'; document.getElementById (\'calendarInteract\').parentNode.style.display = \'none\'; document.getElementById (\'calendarInteract\').innerHTML = \'\'; return false;">Cancel</a></small>';
						}
					} else {
						if (this.selection.get().length == 1) {
							if ((this.selection.get()[0].length) && (this.selection.get()[0].length > 1)) {
								//--- The selection has been split at the edges
								var dateClicked = (this.selection.get()[0][0] == dateRange[0] ? dateRange[1] : dateRange[0]);
								dateRange[0] = null;
								dateRange[1] = null;
								finaliseSelection =  0;
								cal1.selection.reset (dateClicked);
							} else {
								dateRange[0] = this.selection.get()[0];
								dateRange[1] = null;
								finaliseSelection =  0;
								cal1.selection.clear (true);
							}
							document.getElementById ('calendarSelect').style.display = 'none';
							document.getElementById ('calendarInteract').parentNode.style.display = '';
							document.getElementById ('calendarInteract').innerHTML = 'Now click on another day...';
						} else {
							//--- The selection has been split in the middle
							var dateClicked = Calendar.dateToInt (Calendar.intToDate ((this.selection.get()[0][1] ? Calendar.dateToInt (this.selection.get()[0][1]) : dateRange[0]) + 1));
							dateRange[0] = null;
							dateRange[1] = null;
							finaliseSelection =  0;
							cal1.selection.reset (dateClicked);
						}
					}
			}
		},
		onBlur: function () {
			setTimeout (function () {
				if (document.getElementById ('calendarSelect')) {
					document.getElementById ('calendarSelect').style.display = '';
					document.getElementById ('calendarInteract').parentNode.style.display = 'none';
					document.getElementById ('calendarInteract').innerHTML = '';
				}
			}, 150);
		}
	});

}

function showService (which, Type, ID, sDate, eDate, Session_ID) {
//--- Sends an AJAX call to retrieve the service report	

	//--- If IE, always pop open report in new window
	if (document.all) {
		window.open ('VMM-report-select.php?isIE=1&Print=1&ServiceType=' + encodeURI (Type) + '&ServiceAssigned_ID=' + encodeURI (ID) + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + encodeURI (Session_ID), '_blank'); return;
	}

	if (which != null) {
		var services = _$ ('services').getElementsByTagName ('DIV');
		for (i = 0; i < services.length; i++) {
			if (services[i].className.indexOf ('serviceSelector') != -1) {
				services[i].className = 'serviceSelector';
			}
		}
		which.className = 'serviceSelector selected';
	}

	_$ ('serviceBar').className = 'hideBottom';

	//--- Loading bar
	_$ ('reportBody').innerHTML = '<div align="center" style="padding: 25px 0;">Please wait while we download data for your report (this may take a minute)<br /><img src="images/ajax-loader.gif" style="margin: 10px 0;" /></div>';
	_$ ('reportBody').className = 'page closed';
	_$ ('reportBody').style.height = '0px';

	reportInterval = setInterval ("if (parseInt (_$ ('reportBody').style.height) < 90) { _$ ('reportBody').style.height = (parseInt (_$ ('reportBody').style.height) + 5) + 'px'; } else { _$ ('reportBody').style.height = '90px'; clearInterval (reportInterval); }", 1);
	selectedId = ID;
	selectedType = Type;
	session = Session_ID;

	//--- Load in iframe
	_$ ('social_post_form').src = 'VMM-report-select.php?ServiceType=' + encodeURI (Type) + '&ServiceAssigned_ID=' + encodeURI (ID) + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + encodeURI (Session_ID);

}

function getAspectRatio (W, H, MaxW, MaxH, BestWeight) {
//--- Works out the best width and height

	//--- Calculate ratio
	var Xratio = MaxW / W;
	var Yratio = MaxH / H;
	var TrueW = 0;
	var TrueH = 0;

	//--- Check that image doesn't already meet our criteria, and if so, do not resize
	if ((W <= MaxW) && (H <= MaxH)) {
		TrueW = W;
		TrueH = H;
	} else
	if (BestWeight == 'W') {
		TrueW = MaxW;
		TrueH = Math.round (Xratio * H);
	} else
	if (BestWeight == 'H') {
		TrueW = Math.round (Yratio * W);
		TrueH = MaxH;
	} else {
		if ((Xratio * H) < MaxH) {
			TrueW = MaxW;
			TrueH = Math.round (Xratio * H);
		} else {
			TrueW = Math.round (Yratio * W);
			TrueH = MaxH;
		}
	}

	return [TrueW, TrueH];

}

function resizeImage (which) {
//--- Resizes the image to fit the photo space

	var ratio = getAspectRatio (which.width, which.height, 190, 190, 'H');
	if (ratio[0] == 0) {
		ratio[0] == 190;
	}
	if (ratio[1] == 0) {
		ratio[1] == 190;
	}

	if ((ratio[0] < 190) && (ratio[1] >= 190) && (which.width >= 190)) {
		which.style.width = '190px';
	} else
	if ((ratio[1] < 190) && (ratio[0] >= 190) && (which.height >= 190)) {
		which.style.height = '190px';
	} else {
		which.style.width = ratio[0] + 'px';
		which.style.height = ratio[1] + 'px';
	}
	if (ratio[0] > 190) {
		which.style.marginLeft = '-' + (Math.round (ratio[0] / 2) - 95) + 'px';
	} else
	if (parseInt (which.style.width) < 190) {
		which.style.marginLeft = (Math.round ((190 - ratio[0]) / 2)) + 'px';
	}

}

var reportInterval = null;
var selectedId = null;
var selectedType = null;
var session = null;