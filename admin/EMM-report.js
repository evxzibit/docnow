function newWindow (URL, Title, Width, Height, Parameters) {
//--- Opens a new window with the specifics in the center of the screen

	window.parent.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), (Parameters ? Parameters : window)); return true;

}

function drawGraphsEM () {
//--- Has the page loaded?

	if (!$('#pageLoaded')) {
		setTimeout ('drawGraphsEM ()', 500); // wait and try again
	} else {
		drawGraphsEMCallback ();
	}

}

function drawGraphsEMCallback () {

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
		chartArea: {left: 0, top: 10},
		hAxis: {textPosition: 'in', slantedText: true, maxAlternation: 1, showTextEvery: (growthStackedData.length > 29 ? 5 : (growthStackedData.length > 23 ? 4 : (growthStackedData.length > 17 ? 3 : (growthStackedData.length > 11 ? 2 : 1))))}
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
		chartArea: {left: 0, top: 10},
		hAxis: {textPosition: 'in', slantedText: true, maxAlternation: 1, showTextEvery: (growthOverallData.length > 29 ? 5 : (growthOverallData.length > 23 ? 4 : (growthOverallData.length > 17 ? 3 : (growthOverallData.length > 11 ? 2 : 1))))}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('growth_overall'));
	chart.draw (data, options);

	//--- Top reach and bounced domains

	var data = google.visualization.arrayToDataTable (topReachedDomains);
	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'right', alignment: 'center'},
		chartArea: {left: 15, top: 10, width: '100%', height: '100%'},
		pieSliceText: 'value'
	};

	var chart = new google.visualization.PieChart (document.getElementById ('top_reached_domains'));
	chart.draw (data, options);

	var data = google.visualization.arrayToDataTable (topBouncedDomains);
	var options = {
		is3D: true,
		backgroundColor: 'transparent',
		legend: {position: 'left', alignment: 'center'},
		chartArea: {left: 15, top: 0, width: '225', height: '100%'},
		pieSliceText: 'value'
	};

	var chart = new google.visualization.PieChart (document.getElementById ('top_bounced_domains'));
	chart.draw (data, options);

	//--- Graphs

	var data = google.visualization.arrayToDataTable (interactionsGraph);
	var options = {
		colors: ['#44bbff', '#fc575e', '#66cc99'],
		theme: 'maximized',
		pointSize: 10,
		lineWidth: 3,
		hAxis: {textPosition: 'in', slantedText: true, maxAlternation: 1, showTextEvery: (interactionsGraph.length > 29 ? 5 : (interactionsGraph.length > 23 ? 4 : (interactionsGraph.length > 17 ? 3 : (interactionsGraph.length > 11 ? 2 : 1))))}
	};

	var chart = new google.visualization.AreaChart (document.getElementById ('feedback_stacked'));
	chart.draw (data, options);

	//--- Conversions

	var data = google.visualization.arrayToDataTable (conversions);

	var options = {
		theme: 'maximized',
		pointSize: 5,
		lineWidth: 3,
		hAxis: {textPosition: 'in', slantedText: true, maxAlternation: 1, showTextEvery: (interactionsGraph.length > 29 ? 5 : (interactionsGraph.length > 23 ? 4 : (interactionsGraph.length > 17 ? 3 : (interactionsGraph.length > 11 ? 2 : 1))))}
	};

	var chart = new google.visualization.LineChart (document.getElementById ('conversions'));
	chart.draw (data, options);

	//--- Interactions table

	var data = new google.visualization.arrayToDataTable (interactionsTable);	
	var options = {
		page: 'enable',
		pageSize: 7,
		showRowNumber: false,
		allowHtml: true,
		sortColumn: 1,
		sortAscending: false
	};

	var table = new google.visualization.Table (document.getElementById ('feedback_table'));
	table.draw (data, options);

}

function displayServiceResponse (dateRange, minDate, maxDate) {

	if (reportInterval != null) {
		clearInterval (reportInterval);
	}

	responseBody = document.getElementById ('campaign_report').contentWindow.document.body.innerHTML;

	//--- Place content
	if (responseBody != '') {
		$('#reportBody').html (responseBody);
		$('#reportBodyOuter').css ('display', 'block');
		$('#reportOptions').css ('display', 'block');
	}

	drawGraphsEM ();
	enableCalendar (dateRange, minDate, maxDate);

}

function enableCalendar (dateRange, minDate, maxDate) {
//--- Switches the calendar function on

	cal1 = Calendar.setup ({
		trigger: "calendarSelect",
		selectionType: Calendar.SEL_MULTIPLE,
		selection: new Array (new Array (dateRange[0][0], dateRange[0][1])),
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
							document.getElementById ('calendarInteract').innerHTML = '<input type="button" value="Show selected range" onclick="if (document.location.toString ().indexOf (\'Print=1\') == -1) { showService (selectedId, cal1.selection.getFirstDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (6, 2), cal1.selection.getLastDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getLastDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getLastDate ().toString ().substr (6, 2), session); } else { var isP = document.location.toString ().indexOf (\'Print=1\'); var isT = document.location.toString ().indexOf (\'Trigger=1\'); var isIE = document.location.toString ().indexOf (\'isIE=1\'); document.location = \'EMM-report-select.php?\' + (isP != -1 ? \'Print=1&\' : \'\') + (isT != -1 ? \'Trigger=1&\' : \'\') + (isIE != -1 ? \'isIE=1&\' : \'\') + \'Mail_ID=\' + selectedId + \'&SDate=\' + cal1.selection.getFirstDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getFirstDate ().toString ().substr (6, 2) + \'&EDate=\' + cal1.selection.getLastDate ().toString ().substr (0, 4) + \'-\' + cal1.selection.getLastDate ().toString ().substr (4, 2) + \'-\' + cal1.selection.getLastDate ().toString ().substr (6, 2) + \'&Session_ID=\' + session; } return false;" /><small> or <a href="#" onclick="document.getElementById (\'calendarSelect\').style.display = \'\'; document.getElementById (\'calendarInteract\').parentNode.style.display = \'none\'; document.getElementById (\'calendarInteract\').innerHTML = \'\'; return false;">Cancel</a></small>';
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
	cal1.moveTo (dateRange[0][0]);

}

function showService (ID, sDate, eDate, Session_ID) {
//--- Sends an AJAX call to retrieve the service report	

	//--- If IE, always pop open report in new window
	if (document.all) {
//		window.open ('EMM-report-select.php?isIE=1&Print=1&Mail_ID=' + encodeURI (ID) + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + encodeURI (Session_ID), '_blank'); return;
	}

	selectedId = ID;
	session = Session_ID;

	//--- Load in iframe
	document.getElementById ('campaign_report').src = 'EMM-report-select.php?Mail_ID=' + encodeURI (ID) + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + encodeURI (Session_ID) + '&Rand=' + Math.random (0, 10000);
	document.getElementById ('r_pdf').parentNode.onclick = function () { document.location = 'EMM-report-export.php?Mail_ID=' + document.forms[0].Mail_ID.value + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + document.forms[0].Session_ID.value; return false; }
	document.getElementById ('r_print').parentNode.onclick = function () { window.open ('EMM-report-select.php?Print=1&Trigger=1&Mail_ID=' + document.forms[0].Mail_ID.value + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + document.forms[0].Session_ID.value, '_blank'); return false; }
	document.getElementById ('r_xls').parentNode.onclick = function () { document.location = 'EMM-report-export.php?Format=csv&Mail_ID=' + document.forms[0].Mail_ID.value + '&SDate=' + sDate + '&EDate=' + eDate + '&Session_ID=' + document.forms[0].Session_ID.value; return false; }

}

var reportInterval = null;
var selectedId = null;
var session = null;