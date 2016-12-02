function NewWindow (URL, Title, Width, Height, callOnClose) {
//--- Opens a new window with the specifics in the center of the screen

	top.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), window, callOnClose);

}

function number_format (number, decimals, dec_point, thousands_sep) {

	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};

	//--- Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);

}

function is_numeric (n) {

	return !isNaN (parseFloat (n)) && isFinite (n);

}

function selectDropDown (element, value) {
//--- Selects a drop down element by value

	for (var i = 0; i < element.length; i++) {
		if (element[i].value == value) {
			element.selectedIndex = i;
		}
	}

}

function getRadioButtonValue (element) {
//--- Gets the value from a radio button

	for (var i = 0; i < element.length; i++) {
		if (element[i].checked) {
			return element[i].value;
		}
	}

}

function charsLeft (service) {
//--- Works out the correct length based on a number of factors

	var orig = document.forms[0].elements['Comment'].value;
	var origMaxLength = 0;
	var linkLen = 20;
	switch (service) {
		case 'fb':	linkLen = 20; origMaxLength = 2500; break;
		case 'tw':	linkLen = 22; origMaxLength = 140; break;
		default:	linkLen = 20; origMaxLength = 2500; break;
	}
	var linkTrimmed = '';
	var linkPadding = Array (linkLen).join ('*');
	var linkMatches = orig.match (urlMatchGlobal);
	if (!linkMatches) {
		return origMaxLength;
	}
	for (var i = 0; i < linkMatches.length; i++) {
		linkTrimmed = linkMatches[i].replace(/^\\s*/, '').replace(/\\s*$/, '').replace(/^ */, '').replace(/ *$/, '');
		if (linkTrimmed.length < linkLen) {
			origMaxLength = origMaxLength - (linkLen - linkTrimmed.length);
		}
		if (linkTrimmed.length > linkLen) {
			origMaxLength = origMaxLength + (linkTrimmed.length - linkLen);
		}
	}

	return origMaxLength;

}

function countChars (which, maxChars) {
//--- Counts the characters and returns a message if too big

	return (which.value.length ? (which.value.length > maxChars ? '<span style=\'color: red\'>' + (maxChars - which.value.length) + '</span>' : (maxChars - which.value.length)) : maxChars);

}

function SelectService (whichService, id) {
//--- Selects or deselects a service

	if (whichService.className.indexOf (' selected') != -1) {
		whichService.className = whichService.className.replace (' selected', ' deselected');
		document.forms[0].elements['Services'].value = document.forms[0].elements['Services'].value.replace (';' + id, '');
	} else {
		whichService.className = whichService.className.replace (' deselected', ' selected');
		document.forms[0].elements['Services'].value = document.forms[0].elements['Services'].value + ';' + id;
	}

}

function animateProgressBar (targetProgress, speed, callOnComplete) {
//--- Instead of just changing, it animates to the new position

	if (lastProgress < targetProgress) {
		lastProgress += 0.01;
		progress.setProgress (lastProgress);
		setTimeout ("animateProgressBar (" + targetProgress + ", " + speed + ", \"" + callOnComplete + "\")", speed);
	} else {
		eval (callOnComplete); // sorry
	}

}

function showNewRow () {
//--- Displays the new item once selected

	if (document.forms[0].TempItem_ID.value) {
		//--- Before we start, check if the item already appears on the page
		var f = null;
		$('.items .row').each (function () {
			if ($(this).attr ('data-id') == document.forms[0].TempItem_ID.value) {
				f = $(this);
			}
		});
		if (f != null) {
			f.animate ({ backgroundColor: '#ffff9C' }, 100).animate({ backgroundColor: '#ffffff' }, { duration: 1000, complete: function () { $(this).css ('background-color', ''); } });
			return;
		}
		var Icon = 'create-form.png';
		switch (document.forms[0].TempPath.value.split (/\//)[0]) {
			case 'Web':				var Icon = 'create-web.png'; break;
			case 'Landing Pages':	var Icon = 'create-landing.png'; break;
			case 'Facebook':		var Icon = 'create-facebook.png'; break;
		}
		if (($('.newrow').css ('display') == 'none') && ($('.items .row').length > 4)) {
			//--- Hide the filter if I am below the bottom row
			if ((document.forms[0].ViewDefinition) && ($('#filterSelect').prev ('.row').is ('.items .row:last'))) {
				hideFilters ($('#filterSelect'), function () {});
				$('.items .row:last').removeClass ('highlighted');
			}
			$('.items .row:last').slideUp (400, function () { $(this).remove (); });
		}
		$('.newrow .rowTitle').html (document.forms[0].TempItemCode.value);
		$('.newrow a').attr ('href', 'IMM-form.php?Item_ID=' + encodeURIComponent (document.forms[0].TempItem_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value));
		$('.newrow .rowFolderPath').html ('Saved in: ' + document.forms[0].TempPath.value);
		$('.newrow').attr ('data-id', document.forms[0].TempItem_ID.value);
		$('.newrow .rowItem').css ('background', 'url(images/' + Icon + ') no-repeat 0 50%').css ('backgroundSize', '32px 32px');
		$('.newrow').slideDown ().animate ({ backgroundColor: '#ffff9C' }, 100).animate({ backgroundColor: '#ffffff' }, { duration: 1000, complete: function () { $(this).css ('background-color', ''); } });
		fixTargetRowWidth ();
		$.ajax ({
			url: 'EMM-target-count-xml.php?Profile_ID=' + document.forms[0].Profile.value + '&Mail_ID=' + document.forms[0].Mail_ID.value + '&ReturnAs=target&RunTestOn=filter&Item_ID=' + document.forms[0].TempItem_ID.value + '&ViewDefinition=0&Rand=' + Math.random (10000),
			complete: function (jqXHR, textStatus) {
				var c = jqXHR.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("recipients")[0].firstChild.nodeValue.toString ();
				var t = jqXHR.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("total")[0].firstChild.nodeValue.toString ();
				$('.newrow input').off ('click').on ('click', function () { selectList (this, document.forms[0].TempItem_ID.value, t); return false; });
				$('.newrow button').off ('click').on ('click', function () { showFilters (this, document.forms[0].TempItem_ID.value, ''); return false; });
				$('.newrow .buttons a').html (number_format (t) + (t == 1 ? ' contact<span style="color: #e2f9e3;">s</span>' : ' contacts'));
				if (parseInt (t) > 0) {
					$('.newrow .buttons input, .newrow .buttons button').removeAttr ('disabled').removeClass ('disabled').addClass ('button');
				} else {
					$('.newrow .buttons input, .newrow .buttons button').attr ('disabled', true).removeClass ('button').addClass ('disabled');
				}
			}
		});
	}

}

function disableButtons () {
//--- Disable all buttons across all steps; remember those that were previously disabled so we don't re-enable them

	lastButtonsEnabled = $('#step3 .button, #step3_ready .button');
	lastFormObjEnabled = $('#step3 input:not(.disabled, .keepDisabled), #step3 select, #step3 textarea, #step3_ready input:not(.disabled, .keepDisabled)');
	$('#step3 .button, #step3_ready .button').removeClass ('button').addClass ('disabled').attr ('disabled', true);
	$('#step3 input:not(.disabled, .keepDisabled), #step3 select, #step3 textarea, #step3_ready input:not(.disabled, .keepDisabled)').attr ('disabled', true).css ('background-color', '#eee');

}

function enableButtons () {
//--- Enable all buttons across all steps

	lastButtonsEnabled.removeClass ('disabled').addClass ('button').removeAttr ('disabled');
	lastFormObjEnabled.removeAttr ('disabled').css ('background-color', '');
	lastButtonsEnabled = null;
	lastFormObjEnabled = null;

}

function fixTargetRowWidth () {
//--- Fixes the width of target item rows

	var rowWidth = $('#step2').width () - $('.items .row:not(.newrow):not(.highlighted) .buttons').width () - 135;

	$('.items .row:not(.highlighted) .rowInstructions, .items .row:not(.highlighted) .bold').css ('width', rowWidth);
	$('.items .row.highlighted .rowInstructions, .items .row.highlighted .bold').css ('width', rowWidth - 80);

}

function setSchedule (which) {
//--- Saves the schedule

	var f = document.forms[0];

	switch (which) {
		case 1:	if ((f.DateScheduled.value == '')) {
					alert ('Please select a date and try that again.'); return false;
				}
				var url = 'EMM-target-complete.php?Action=S&Campaign_ID=' + encodeURIComponent (f.Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (f.Mail_ID.value) + '&Item_ID=' + encodeURIComponent (f.Item_ID.value) + '&Year=' + encodeURIComponent (f.DateScheduled.value.substr (0, 4)) + '&Month=' + encodeURIComponent (f.DateScheduled.value.substr (4, 2)) + '&Day=' + encodeURIComponent (f.DateScheduled.value.substr (6, 2)) + '&Hour=' + encodeURIComponent (f.ScheduledHour.options[f.ScheduledHour.selectedIndex].value) + '&Minute=' + encodeURIComponent (f.ScheduledMinute.options[f.ScheduledMinute.selectedIndex].value) + '&RemoveAfter=1&Session_ID=' + encodeURIComponent (f.Session_ID.value) + '&Rand=' + Math.random (10000);
				break;
		case 2:	var url = 'EMM-target-complete.php?Action=S&Campaign_ID=' + encodeURIComponent (f.Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (f.Mail_ID.value) + '&Item_ID=' + encodeURIComponent (f.Item_ID.value) + '&Year=' + encodeURIComponent (f.Year.options[f.Year.selectedIndex].value) + '&Month=' + encodeURIComponent (f.Month.options[f.Month.selectedIndex].value) + '&Day=' + encodeURIComponent (f.Day.options[f.Day.selectedIndex].value) + '&Hour=' + encodeURIComponent (f.Hour.options[f.Hour.selectedIndex].value) + '&Minute=' + encodeURIComponent (f.Minute.options[f.Minute.selectedIndex].value) + '&RemoveAfter=' + encodeURIComponent (f.RemoveAfter.value) + '&Session_ID=' + encodeURIComponent (f.Session_ID.value) + '&Rand=' + Math.random (10000);
				break;
	}

	$.ajax ({
		url: url,
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText == '') {
				alert ('We could not save your selection. Please go back a page and try again.'); return false;
			} else {
				var f = document.forms[0];
				switch (which) {
					case 1:	selectDropDown (f.Year, f.DateScheduled.value.substr (0, 4));
							selectDropDown (f.Month, f.DateScheduled.value.substr (4, 2).replace (/^0+/, ''));
							selectDropDown (f.Day, f.DateScheduled.value.substr (6, 2).replace (/^0+/, ''));
							selectDropDown (f.Hour, f.ScheduledHour.options[f.ScheduledHour.selectedIndex].value.replace (/^0+/, ''));
							selectDropDown (f.Minute, f.ScheduledMinute.options[f.ScheduledMinute.selectedIndex].value.replace (/^0+/, ''));
							f.RemoveAfter.value = '1';
							$('#frequencyText').html ('once');
							$('#frequency').find ('a.linkSelected').removeClass ('linkSelected');
							$('#frequency').find ('a[data-val="1"]').addClass ('linkSelected');
							break;
					case 2:	if ((is_numeric (f.Year.options[f.Year.selectedIndex].value)) && (is_numeric (f.Month.options[f.Month.selectedIndex].value)) && (is_numeric (f.Day.options[f.Day.selectedIndex].value)) && (is_numeric (f.Hour.options[f.Hour.selectedIndex].value)) && (is_numeric (f.Minute.options[f.Minute.selectedIndex].value))) {
								tYear = f.Year.options[f.Year.selectedIndex].value;
								tMonth = (parseInt (f.Month.options[f.Month.selectedIndex].value) < 10 ? '0' : '') + parseInt (f.Month.options[f.Month.selectedIndex].value);
								tDay = (parseInt (f.Day.options[f.Day.selectedIndex].value) < 10 ? '0' : '') + parseInt (f.Day.options[f.Day.selectedIndex].value);
								tHour = parseInt (f.Hour.options[f.Hour.selectedIndex].value);
								tMinute = parseInt (f.Minute.options[f.Minute.selectedIndex].value);
								f.DateScheduled.value = f.Year.options[f.Year.selectedIndex].value + tMonth + tDay;
								selectDropDown (f.ScheduledHour, tHour);
								selectDropDown (f.ScheduledMinute, tMinute);
								cal.selection.set (f.DateScheduled.value);
								cal.moveTo (tYear + '-' + tMonth + '-' + tDay);
							} else {
								f.DateScheduled.value = '';
								f.ScheduledHour.selectedIndex = 0;
								f.ScheduledMinute.selectedIndex = 0;
								cal.selection.clear (false);
							}
							break;
				}
				$('#ready_normal').css ('display', 'none');
				$('#ready_scheduled').css ('display', 'block');
				$('#cancelButton').fadeIn (500);
				$('#splitRow .button').removeClass ('button').addClass ('keepDisabled').attr ('disabled', true);
				$('#scheduleText a').html ('Sending ' + jqXHR.responseText);
				$('#scheduleText').delay (500).animate ({backgroundColor: "#FFFF9C"}, 100).animate({ backgroundColor: "transparent"}, 1500);
				$('#scheduleTextPostSend').html ('Sending ' + jqXHR.responseText);
				$('#scheduleTextPostSend').closest ('.row').delay (500).animate ({backgroundColor: "#FFFF9C"}, 100).animate({ backgroundColor: "transparent"}, 1500, 'swing', function () { $(this).removeAttr ('style'); });
				hideSubPage ();
			}
		}
	});

}

function removeSchedule () {
//--- Removes the schedule

	var f = document.forms[0];

	$.ajax ({
		url: 'EMM-target-complete.php?Action=R&Campaign_ID=' + encodeURIComponent (f.Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (f.Mail_ID.value) + '&Session_ID=' + encodeURIComponent (f.Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We could not save your selection. Please go back a page and try again.'); return false;
			} else {
				$('#ready_scheduled').css ('display', 'none');
				$('#ready_normal').fadeIn ();
				$('#cancelButton').css ('display', 'none');
				$('#splitRow .keepDisabled').removeClass ('keepDisabled').addClass ('button').attr ('disabled', false);
				$('#scheduleTextPostSend').closest ('.row').css ('display', 'none');
				$('#outer').css ('height', 'auto');
				f.Year.selectedIndex = 0;
				f.Month.selectedIndex = 0;
				f.Day.selectedIndex = 0;
				f.Hour.selectedIndex = 0;
				f.Minute.selectedIndex = 0;
				f.RemoveAfter.value = '-1';
				$('#frequencyText').html ('ongoing');
				$('#frequency').find ('a.linkSelected').removeClass ('linkSelected');
				$('#frequency').find ('a[data-val="-1"]').addClass ('linkSelected');
				f.DateScheduled.value = '';
				f.ScheduledHour.selectedIndex = 0;
				f.ScheduledMinute.selectedIndex = 0;
				cal.selection.clear (false);
				hideSubPage ();
			}
		}
	});

}

function resizeReportBoxes () {
//--- Once reports are rendered, this should be enabled

	if (lastProgress > 1) {
		var w = parseInt ($('#outer').width () / 7);
		$('#statistics').css ('width', 'auto');
		$('#statistics_pie').css ('width', 'auto');
		$('#metrics').css ('width', $('#outer').width () - (w * 2) - 10).css ('left', (w * 2));
		$('#moremetrics').css ('width', (w * 2) - 15);
		//--- Resize boxes
		$('#statistics').css ('width', ($('#outer').width () * 3 / 4) - 25);
		$('#statisticsBorder').css ('width', ($('#outer').width () * 1 / 4) - 20);
		if (google) {
			try {
				statsOptions.width = ($('#outer').width () * 3 / 4) - 25;
				statsChart.draw (statsData, statsOptions);
			} catch (e) {}
			try {
				pieOptions.width = ($('#outer').width () * 1 / 4) - 20;
				pieChart.draw (pieData, pieOptions);
			} catch (e) {}
		}
	}

}

function switchTo (direction) {
//--- Switches from one step to another

	var prevStep, nextStep;

	if (typeof pageLoaded == 'undefined') {
		return; // page not loaded
	}

	switch (direction) {
		case 1:		prevStep = currStep - 1;
					nextStep = currStep + 1;
					break;
		case -1:	prevStep = currStep + 1;
					nextStep = currStep - 1;
					break;
	}

	if (prevStep < 2) {
		prevStep = 2;
	}
	if (nextStep > 4) {
		nextStep = 4;
	}

	if (nextStep == 3) {
		$('#step3_ready').fadeIn ((direction > 0 ? 200 : 200));
	}
	if (currStep == 3) {
		$('#step3_ready').fadeOut ((direction > 0 ? 200 : 200));
	}

	$('#step' + currStep).hide ('slide', { direction: (direction > 0 ? 'left' : 'right') }, (direction > 0 ? 200 : 200));
	$('#step' + nextStep).delay (200).show ('slide', { direction: (direction > 0 ? 'right' : 'left') }, (direction > 0 ? 400 : 400));
	$('#outer').animate ({ height: $('#step' + nextStep).height () }, (direction > 0 ? 500 : 500));
	setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);

	switch (nextStep) {
		case 2:		$('#title').html ('<b>Step 2</b> - Select a contact list or form'); $('#targeting').hide ('slide', { direction: 'right' }); break;
		case 3:		$('#title').html ('<b>Step 3</b> - Test and schedule this campaign'); $('#targeting').show ('slide', { direction: 'right' }); break;
		case 4:		$('#title').html ('<b>Step 4</b> - Sending...'); break;
	}

	if (direction > 0) {
		$('#step' + currStep + '_no').addClass ('done').removeClass ('active');
		$('#step' + nextStep + '_no').addClass ('active');
	} else {
		$('#step' + currStep + '_no').removeClass ('active');
		$('#step' + prevStep + '_no').removeClass ('active');
		$('#step' + nextStep + '_no').addClass ('active').removeClass ('done');
	}

	if (nextStep == 2) {
		setTimeout (function () {
			fixTargetRowWidth (); $(window).bind ('resize', fixTargetRowWidth);
		}, 200);
	} else
	if (nextStep == 3) {
		$(window).unbind ('resize', fixTargetRowWidth);
	}
	if (nextStep == 4) {
		$('.edit').fadeOut ();
		$('#pause').show ();
		//--- Reset pie
		var statsPie = [
			['Action', 'Count'],
			['Not Opened', parseInt (document.forms[0].Recipients.value)],
			['Opened Only', 0],
			['Engaged (Opened + Clicked/Shared)', 0],
			['Bounced', 0],
			['Invalid', 0],
			['Unsubscribed', 0]
		];
		if (google) {
			try {
				pieData = google.visualization.arrayToDataTable (statsPie);
				pieChart.draw (pieData, pieOptions);
			} catch (e) {}
		}
		scheduleStatusUpdates (); // Start checking now
	}

	currStep = nextStep;

}

function selectRow (which) {
//--- Highlights a row

	$(which).closest ('.row').switchClass ('', 'highlighted');

}

function deselectRow () {
//--- Removes highlighting from a row

	$('#step2 .highlighted').removeClass ('highlighted');

}

function showGraphs (title) {
//--- Increase the progress indicator

	lastProgress = 1.01;

	var w = parseInt ($('#outer').width () / 7);
	$('#message').fadeOut (500, function () { $(this).css ('display', 'none'); });
	$('#progress').fadeOut (500, function () { $(this).css ('display', 'none'); });
	$('#pauseResume').fadeOut (500, function () { $(this).css ('display', 'none'); });
	$('#metrics').delay (100).animate ({ left: (w * 2), top: '10', width: ($('#outer').width () - 10 - (w * 2)) });
	$('#moremetrics').delay (300).css ('display', 'table').hide ().animate ({ display: 'table', left: '10', top: '10', width: ((w * 2) - 15) }).show ('slide', { direction: 'up' }, 500);
	$('#statistics').css ('width', ($('#outer').width () * 3 / 4) - 25);
	$('#statisticsBorder').css ('width', ($('#outer').width () * 1 / 4) - 20);
	$('#statisticsBox').delay (550).show ('slide', { direction: 'left' }, 500);
	$('#title').html (title);
	$('#step4_no').addClass ('done').removeClass ('active');
	$('#step5_no').addClass ('active');
	$(window).bind ('resize', resizeReportBoxes);

}

function showCompletedStats () {
//--- Animates the stats in

	lastProgress = 1.01;

	var w = parseInt ($('#outer').width () / 7);
	$('#metrics').delay (200).css ('display', 'table').hide ().animate ({ display: 'table', left: (w * 2), top: '10', width: ($('#outer').width () - 10 - (w * 2)) }).show ('slide', { direction: 'right' }, 300);
	$('#moremetrics').css ('display', 'table').hide ().animate ({ display: 'table', left: '10', top: '10', width: ((w * 2) - 15) }).show ('slide', { direction: 'up' }, 300);
	$('#statistics').css ('width', ($('#outer').width () * 3 / 4) - 25);
	$('#statisticsBorder').css ('width', ($('#outer').width () * 1 / 4) - 20);
	$('#statistics_pie').css ('width', ($('#outer').width () * 1 / 4) - 20);
	$('#statistics').css ('width', ($('#outer').width () * 3 / 4) - 25).css ('border', '1px solid #ccc');
	$('#statisticsBox').show ();
	$(window).bind ('resize', resizeReportBoxes);

	//--- Kick off scheduled updates
	scheduleStatusUpdates ();

}

function roundValue (value) {
//--- Rounds the number to K or M

	return (value > 1000000 ? '<span class="over" val="' + number_format (value) + '">' + number_format (value / 1000000, 1) + 'm</span>' : (value > 1000 ? '<span class="over" val="' + number_format (value) + '">' + number_format (value / 1000, 1) + 'k</span>' : value));

}

function checkStatus () {
//--- Calls the server

	$.ajax ({
		url: 'EMM-status.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Action=I&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		success: function (data, textStatus, jqXHR) {
			if (!jqXHR.responseXML) {
				return; // safety
			}
			var status = jqXHR.responseXML.getElementsByTagName ('status')[0];
			if (status) {
				var progressPc = parseFloat (status.getElementsByTagName ('progress')[0].firstChild.nodeValue);
				if ($('#progress').css ('display') != 'none') {
					animateProgressBar (progressPc, parseInt (500 / ((progressPc - lastProgress) * 100)), "if (arguments[0] > 1) { showGraphs ('<b>Send completed</b>'); }");
				}
				if (parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue)) {
					$('#stats_sent').html (roundValue (parseInt (status.getElementsByTagName ('sent')[0].firstChild.nodeValue)));
					$('#stats_invalid').html (roundValue (status.getElementsByTagName ('invalid')[0].firstChild.nodeValue.toString ()));
					$('#stats_opened').html (roundValue (status.getElementsByTagName ('opened')[0].firstChild.nodeValue.toString ()));
					$('#stats_clicked').html (roundValue (status.getElementsByTagName ('clicked')[0].firstChild.nodeValue.toString ()));
					$('#stats_shared').html (roundValue (status.getElementsByTagName ('shared')[0].firstChild.nodeValue.toString ()));
					$('#stats_bounced').html (roundValue (status.getElementsByTagName ('bounced')[0].firstChild.nodeValue.toString ()));
					$('#stats_unsubscribed').html (roundValue (status.getElementsByTagName ('unsubscribed')[0].firstChild.nodeValue.toString ()));
					$('#stats_sent').closest ('.count').find ('.percent').html ((parseInt (status.getElementsByTagName ('sent')[0].firstChild.nodeValue) != parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) ? ' (' + number_format ((parseInt (status.getElementsByTagName ('sent')[0].firstChild.nodeValue)) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)' : ''));
					$('#stats_invalid').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('invalid')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					$('#stats_opened').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('opened')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					$('#stats_clicked').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('clicked')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					$('#stats_shared').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('shared')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					$('#stats_bounced').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('bounced')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					$('#stats_unsubscribed').closest ('.count').find ('.percent').html (' (' + number_format (parseInt (status.getElementsByTagName ('unsubscribed')[0].firstChild.nodeValue) * 100 / (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue ? parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) : 1)) + '%)');
					//--- Check for hour, day or week tags
					statsOverTime = [
						['', 'Bounced', 'Shared', 'Clicked', 'Opened'],
						['Start', 0, 0, 0, 0]
					];
					if ((status.getElementsByTagName ('time')) && ((status.getElementsByTagName ('time').length > 0))) {
						var period = status.getElementsByTagName ('time')[0].getElementsByTagName ('period');
						for (var i = 0; i < period.length; i++) {
							statsOverTime[i + 2] = [period[i].firstChild.nodeValue.toString (), parseInt (period[i].getAttribute ('bounced')), parseInt (period[i].getAttribute ('shared')), parseInt (period[i].getAttribute ('clicked')), parseInt (period[i].getAttribute ('opened'))];
						}
					}
					if (google) {
						try {
							statsData = google.visualization.arrayToDataTable (statsOverTime);
							statsOptions.hAxis = { showTextEvery: (statsOverTime.length > 29 ? 5 : (statsOverTime.length > 23 ? 4 : (statsOverTime.length > 17 ? 3 : (statsOverTime.length > 11 ? 2 : 1)))) }
							statsChart.draw (statsData, statsOptions);
						} catch (e) {}
					}
					//--- Pie chart
					statsPie = [
						['Action', 'Count'],
						['Not Opened', parseInt (status.getElementsByTagName ('targeted')[0].firstChild.nodeValue) - parseInt (status.getElementsByTagName ('invalid')[0].firstChild.nodeValue) - parseInt (status.getElementsByTagName ('opened')[0].firstChild.nodeValue)],
						['Opened Only', parseInt (status.getElementsByTagName ('opened')[0].firstChild.nodeValue) - parseInt (status.getElementsByTagName ('engaged')[0].firstChild.nodeValue)],
						['Engaged (Opened + Clicked/Shared)', parseInt (status.getElementsByTagName ('engaged')[0].firstChild.nodeValue)],
						['Bounced', parseInt (status.getElementsByTagName ('bounced')[0].firstChild.nodeValue)],
						['Invalid', parseInt (status.getElementsByTagName ('invalid')[0].firstChild.nodeValue)],
						['Unsubscribed', parseInt (status.getElementsByTagName ('unsubscribed')[0].firstChild.nodeValue)]
					];
					if (google) {
						try {
							pieData = google.visualization.arrayToDataTable (statsPie);
							pieChart.draw (pieData, pieOptions);
						} catch (e) {}
					}
					if ((status.getElementsByTagName ('progress').length) && (status.getElementsByTagName ('progress')[0].getAttribute ('completed'))) {
						document.forms[0].DateCompleted.value = status.getElementsByTagName ('progress')[0].getAttribute ('completed');
						$('#title').html ('<b>Send completed</b> - ' + document.forms[0].DateCompleted.value);
					}
					//--- If the schedule is still active...
					var scheduleTag = status.getElementsByTagName ("schedule")[0];
					if (scheduleTag) {
						var sText = scheduleTag.firstChild.nodeValue.toString ();
						var sYear = scheduleTag.getAttribute ('year');
						var sMonth = scheduleTag.getAttribute ('month');
						var sDay = scheduleTag.getAttribute ('day');
						var sHour = scheduleTag.getAttribute ('hour');
						var sMinute = scheduleTag.getAttribute ('minute');
						var sRemoveAfter = scheduleTag.getAttribute ('removeAfter');
						var sType = scheduleTag.getAttribute ('type');
					}
					if (($('#scheduleTextPostSend').closest ('.row').css ('display') != 'none') && ((sText == 'none') || (sText.substr (0, 7) == '0 times'))) {
						 $('#scheduleTextPostSend').closest ('.row').fadeOut ();
						 $('#outer').css ('height', 'auto');
					} else
					if (($('#scheduleTextPostSend').closest ('.row').css ('display') != 'none') && ($('#scheduleTextPostSend').html () != 'Sending ' + sText)) {
						f = document.forms[0];
						if ((is_numeric (sYear)) && (is_numeric (sMonth)) && (is_numeric (sDay)) && (is_numeric (sHour)) && (is_numeric (sMinute))) {
							f.DateScheduled.value = sYear + (sMonth.length < 2 ? '0' : '') + sMonth + (sDay.length < 2 ? '0' : '') + sDay;
							selectDropDown (f.ScheduledHour, (sHour != '0' ? sHour.replace (/^0+/, '') : '0'));
							selectDropDown (f.ScheduledMinute, (sMinute != '0' ? sMinute.replace (/^0+/, '') : '0'));
							cal.selection.set (f.DateScheduled.value);
							cal.moveTo (sYear + '-' + (sMonth.length < 2 ? '0' : '') + sMonth + '-' + (sDay.length < 2 ? '0' : '') + sDay);
						} else {
							f.DateScheduled.value = '';
							f.ScheduledHour.selectedIndex = 0;
							f.ScheduledMinute.selectedIndex = 0;
							cal.selection.clear (false);
						}
						selectDropDown (f.Year, sYear);
						selectDropDown (f.Month, sMonth.replace (/^0+/, ''));
						selectDropDown (f.Day, sDay.replace (/^0+/, ''));
						selectDropDown (f.Hour, (sHour != '0' ? sHour.replace (/^0+/, '') : '0'));
						selectDropDown (f.Minute, (sMinute != '0' ? sMinute.replace (/^0+/, '') : '0'));
						f.RemoveAfter.value = sRemoveAfter;
						$('#frequencyText').html ((sRemoveAfter == '-1' ? 'ongoing' : (sRemoveAfter == '1' ? 'once' : (sRemoveAfter == '2' ? 'twice' : sRemoveAfter + ' times'))));
						$('#frequency').find ('a.linkSelected').removeClass ('linkSelected');
						$('#frequency').find ('a[data-val="' + sRemoveAfter + '"]').addClass ('linkSelected');
						$('#scheduleText a').html ('Sending ' + sText);
						$('#scheduleTextPostSend').html ('Sending ' + sText);
						$('#scheduleTextPostSend').closest ('.row').animate ({backgroundColor: "#FFFF9C"}, 100).animate({ backgroundColor: "transparent"}, 1500, 'swing', function () { $(this).removeAttr ('style'); });
					}
				}
			}
		}
	});

}

function scheduleStatusUpdates () {
//--- Kick off status update checks

	lastStatus = setInterval (function () {
		checkStatus ();
	}, 10000);

}

function showSubPage (page_id) {
//--- Retains all other settings, but just shows a new sub page

	if (typeof pageLoaded == 'undefined') {
		return; // page not loaded
	}

	if (currStep == 3) {
		$('#step3_ready').fadeOut (200);
	}

	$('#step' + currStep).hide ('slide', { direction: 'left' }, 200);
	$('#' + page_id).delay (200).show ('slide', { direction: 'right' }, 400);
	$('#outer').animate ({ height: $('#' + page_id).height () }, 500);
	setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
	lastSubPage = page_id;

}

function hideSubPage () {
//--- Restores the previous page

	if (currStep == 3) {
		$('#step3_ready').fadeIn ();
	} else
	if (currStep == 4) {
		resizeReportBoxes ();
	}

	$('#' + lastSubPage).hide ('slide', { direction: 'right' }, 200);
	$('#step' + currStep).delay (200).show ('slide', { direction: 'left' }, 400);
	$('#outer').animate ({ height: $('#step' + currStep).height () }, 500);
	setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
	lastSubPage = null;

}

function showSubSubPage (page_id) {
//--- Retains all other settings, but just shows a new sub-sub page

	if (typeof pageLoaded == 'undefined') {
		return; // page not loaded
	}

	if (page_id == 'reportClicks') {
		$('#clicks').html ('<img src="images/loader-small.gif" align="absmiddle" /> &nbsp;Loading...');
		$.ajax ({
			url: 'EMM-report-clicks.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
			success: function (data, textStatus, jqXHR) {
				$('#clicks').html (jqXHR.responseText);
				$('#' + lastSubPage).hide ('slide', { direction: 'left' }, 200);
				$('#reportClicks').delay (200).show ('slide', { direction: 'right' }, 400);
				$('#outer').animate ({ height: $('#reportClicks').height () }, 500);
				setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
				lastSubSubPage = 'reportClicks';
			},
			error: function (jqXHR, textStatus, errorMessage) {
				alert ('There was a problem loading your report. Please try again.');
			}
		});
	} else
	if (page_id == 'reportSplitTest') {
		$('#splitTestResults').html ('<img src="images/loader-small.gif" align="absmiddle" /> &nbsp;Loading...');
		$.ajax ({
			url: 'EMM-report-split-test.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
			success: function (data, textStatus, jqXHR) {
				$('#splitTestResults').html (jqXHR.responseText);
				drawSplitTestGraphs ();
				$('#' + lastSubPage).hide ('slide', { direction: 'left' }, 200);
				$('#reportSplitTest').delay (200).show ('slide', { direction: 'right' }, 400);
				$('#outer').animate ({ height: $('#reportSplitTest').height () }, 500);
				setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
				lastSubSubPage = 'reportSplitTest';
				$(window).bind ('resize', resizeSplitTestReportBoxes);
			},
			error: function (jqXHR, textStatus, errorMessage) {
				alert ('There was a problem loading your report. Please try again.');
			}
		});
	} else {
		$('#' + lastSubPage).hide ('slide', { direction: 'left' }, 200);
		$('#' + page_id).delay (200).show ('slide', { direction: 'right' }, 400);
		$('#outer').animate ({ height: $('#' + page_id).height () }, 500);
		setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
		lastSubSubPage = page_id;
	}

}

function hideSubSubPage () {
//--- Restores the previous sub-page

	if (lastSubSubPage == 'reportClicks') {
		$('#clicks').html ('');
	} else
	if (lastSubSubPage == 'reportSplitTest') {
		$('#splitTestResults').html ('');
		$(window).unbind ('resize', resizeSplitTestReportBoxes);
	}

	$('#' + lastSubSubPage).hide ('slide', { direction: 'right' }, 200);
	$('#' + lastSubPage).delay (200).show ('slide', { direction: 'left' }, 400);
	$('#outer').animate ({ height: $('#' + lastSubPage).height () }, 500);
	setTimeout (function () { $('#outer').css ('height', 'auto'); }, 500);
	lastSubSubPage = null;

}

function pauseCampaign () {
//--- Pause a campaign

	//--- Run a background request
	$.ajax ({
		url: 'EMM-status.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Action=S&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText == 'OK') {
				$('#title').html ('<b>Send paused</b> - ' + document.forms[0].DateCompleted.value);
				$('#pause').css ('display', 'none');
				$('#resume').fadeIn ();
			} else {
				alert ('We couldn\'t pause the campaign. Please try again in 30 seconds.');
			}
		}
	});

}

function resumeCampaign (stop) {
//--- Pause or stop a campaign

	//--- Run a background request
	$.ajax ({
		url: 'EMM-status.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Action=R&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText == 'OK') {
				$('#title').html ('<b>Step 4</b> - Sending...');
				$('#resume').css ('display', 'none');
				$('#pause').fadeIn ();
			} else {
				alert ('We couldn\'t resume the campaign. Please try again in 30 seconds.');
			}
		}
	});

}

function stopCampaign () {
//--- Stops the campaign and prevents it from restarting

	$.ajax ({
		url: 'EMM-status.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Action=C&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			//--- Reload stats immediately (don't wait)
			if (jqXHR.responseText == 'OK') {
				checkStatus ();
			} else {
				alert ('We couldn\'t stop the campaign. Please try again in 30 seconds.');
			}
		}
	});

	$('#stats_invalid').closest ('.mbox').find ('.metric').html ('Not Sent');

	showGraphs ('<b>Send stopped</b> - ' + document.forms[0].DateCompleted.value);

}

function buttonToLoader (button) {
//--- Changes a button to a loader

	$(button).css ('display', 'none');
	$('<img class="loader" src="images/loader-small.gif" />').insertBefore ($(button));
	lastLoaderButton = button;

}

function loaderToButton () {
//--- Changes a button to a loader

	if (lastLoaderButton != null) {
		$(lastLoaderButton).css ('display', '');
		$(lastLoaderButton).prev ('img.loader').remove ();
		lastLoaderButton = null;
	}

}

function selectList (which, Item_ID, contacts) {
//--- Selects a list and proceeds

	document.forms[0].Item_ID.value = Item_ID;

	$('#contactCount').html (number_format (contacts)).off ('click').on ('click', function () { return contactListClick (); });
	$('#contactList').html ($(which).closest ('.row').find ('.bold').html ()).off ('click').on ('click', function () { return contactListClick (); });
	$('#filterSelected').html ('No filter applied');
	$('#sendCount').html (number_format (contacts));
	document.forms[0].Recipients.value = contacts;
	if ((contacts > 0) && (!isSending)) {
		$('#ready_normal').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
		$('#ready_scheduled').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
	}
	if (document.forms[0].ViewDefinition) {
		document.forms[0].ViewDefinition.selectedIndex = 0;
		if (!$(which).closest ('.row').hasClass ('highlighted')) {
			deselectRow ();
		}
		selectRow (which);
		hideFilters ($('#filterSelect'), function () {
			saveTargeting ();
			switchTo (1);
		});
	} else {
		if (!$(which).closest ('.row').hasClass ('highlighted')) {
			deselectRow ();
		}
		selectRow (which);
		saveTargeting ();
		switchTo (1);
	}

}

function showFilters (which, Item_ID, ViewDefinition) {
//--- Show a filter selection block

	if (($('#filterSelect')) && ($('#filterSelect').css ('display') == 'block')) {
		if (!$(which).closest ('.row').hasClass ('highlighted')) {
			deselectRow ();
		}
		hideFilters ($('#filterSelect'), function () { showFilters (which, Item_ID, ViewDefinition); }); return;
	}

	document.forms[0].Item_ID.value = Item_ID;

	$('#outer').css ('height', 'auto');
	$('#contactList').html ($(which).closest ('.row').find ('.bold').html ()).off ('click').on ('click', function () { return contactListClick (); });

	var newFilter = $(which).closest ('.items').find ('.expanded').clone ().insertAfter ($(which).closest ('.row'));
	newFilter.attr ('id', 'filterSelect');
	newFilter.find ('select').attr ('name', 'ViewDefinition');
	newFilter.find ('strong').html ('&nbsp;<img src="images/indicator.gif" align="absmiddle" />&nbsp;');
	newFilter.find ('a.editFilters').bind ('click', function () {
		NewWindow ('FMM-filter.php?Item_ID=' + encodeURIComponent (document.forms[0].Item_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value), 'Create or Modify Filters', 900, 500, function () { loadFilters (document.forms[0].Item_ID.value, true); }); return false;
	})
	newFilter.slideDown (200);

	$(which).closest ('.row').find ('.buttons').fadeOut (400, function () { $(this).css ('display', 'none'); });
	if (!$(which).closest ('.row').hasClass ('highlighted')) {
		deselectRow ();
	}
	selectRow (which);

	lastFilter = newFilter.find ('select');

	loadFilters (Item_ID, false, ViewDefinition); 

}

function hideFilters (which, onClose) {
//--- Hide a filter selection block

	$('#outer').css ('height', 'auto');
	$(which).closest ('.expanded').prev ('.row').find ('.buttons').fadeIn ();
	$(which).closest ('.expanded').slideUp (200);
	setTimeout (function () {
		$('#filterSelect').remove ();
		onClose ();
	}, 210);


}

function changeFilter (which) {
//--- Handles a change event

	$('#filterSelect strong').html ('&nbsp;<img src="images/indicator.gif" align="absmiddle" />&nbsp;');
	$('#filterSelected').html ((lastFilter[0].options[lastFilter[0].selectedIndex].value != '' ? lastFilter[0].options[lastFilter[0].selectedIndex].text : 'No filter applied'));
	lastFilter.closest ('.expanded').find ('.button').addClass ('disabled').removeClass ('button').attr ('disabled', true);

	var request = $.ajax ({
		url: 'EMM-target-count-xml.php?Profile_ID=' + document.forms[0].Profile.value + '&Mail_ID=' + document.forms[0].Mail_ID.value + '&ReturnAs=target&RunTestOn=filter&Item_ID=' + document.forms[0].Item_ID.value + '&ViewDefinition=' + lastFilter[0].options[lastFilter[0].selectedIndex].value + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			var c = jqXHR.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("recipients")[0].firstChild.nodeValue.toString ();
			var t = jqXHR.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("total")[0].firstChild.nodeValue.toString ();
			$('#filterSelect strong').html (number_format (c) + (c == 1 ? ' contact' : ' contacts') + ' selected' + (t != c ? ' (out of ' + (t ? number_format (t) : 0) + ')' : ''));
			if (parseInt (c) == 0 ) {
				$('#filterSelect').append('<br /><br /><span class="zeroWarning">NB: The filter selected currently has no recipients.</span>');
				$('.filterCount').css ('background-color','#FFFFCC');
				$('.filterCount').css ('border-color','#FFB08A');
			} else {
				$('.filterCount').css ('background-color','#E2F9E3');
				$('.filterCount').css ('border-color','#99CC99');
				$('.zeroWarning').hide();
			}
			$('#contactCount').html (number_format (c)).off ('click').on ('click', function () { return contactListClick (); });
			$('#sendCount').html (number_format (c));
			if (!isSending) {
				$('#ready_normal').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
				$('#ready_scheduled').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
			}
			document.forms[0].Recipients.value = c;
			//if (parseInt (c) > 0) {
				lastFilter.closest ('.expanded').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
			//} else {
			//	lastFilter.closest ('.expanded').find ('.button').addClass ('disabled').removeClass ('button').attr ('disabled', true);
			//}
			if (currStep == 3) {
				$('#targeting').show ('slide', { direction: 'right' });
			}
		}
	});

}

function loadFilters (Item_ID, Reload, Select) {
//--- Loads or reloads the filter drop down list

	//--- Save last selection (or selected filter)
	lastFilterSelected = (Select ? Select : ((typeof (top.modalWindows.windowArguments) == 'object') && (Reload) && (top.modalWindows.windowArguments[0] > 0) ? top.modalWindows.windowArguments[0] : lastFilter[0].options[lastFilter[0].options.selectedIndex].value));
	//--- Clear the filter
	while (lastFilter[0].options[0]) {
		lastFilter[0].options[0] = null;
	}
	var request = $.ajax ({
		url: 'EMM-target-select-list-xml.php?Profile_ID=' + encodeURIComponent (document.forms[0].Profile.value) + '&Select=' + encodeURIComponent (lastFilterSelected) + '&ReturnList=filters&Field=' + encodeURIComponent (lastFilter[0].name) + '&Item_ID=' + encodeURIComponent (Item_ID) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			var listOptions = jqXHR.responseXML.getElementsByTagName ("list")[0].getElementsByTagName ("option");
			var listElement = jqXHR.responseXML.getElementsByTagName ("list")[0].getAttribute ("for");
			var whichFormElement = document.forms[0].elements[listElement];
			if (listOptions.length) {
				for (i = 0; i < listOptions.length; i++) {
					optionTxt = (listOptions[i].firstChild ? listOptions[i].firstChild.nodeValue.toString () : '');
					optionVal = (listOptions[i].getAttribute ('value'));
					optionSel = (listOptions[i].getAttribute ('selected'));
					isSelected = (lastFilterSelected ? (optionVal == lastFilterSelected) : (optionSel == 'selected'));
					whichFormElement[i] = new Option (optionTxt, optionVal, false, isSelected);
				}
				whichFormElement.onchange ();
			}
		}
	});

}

function saveTargeting () {
//--- Saves targeting in the background

	$.ajax ({
		url: 'EMM-target-complete.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Item_ID=' + encodeURIComponent (document.forms[0].Item_ID.value) + '&ViewDefinition=' + encodeURIComponent ((document.forms[0].ViewDefinition ? document.forms[0].ViewDefinition.options[document.forms[0].ViewDefinition.selectedIndex].value : '0')) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We could not save your selection. Please go back a page and try again.'); return false;
			}
		}
	});

}

function sendSample (which, sampleOption) {
//--- Sends a sample message via Ajax

	switch (sampleOption) {
		case 1:		//--- Send a personalised message
					if (document.forms[0].Recipient1.value == '') {
						alert ('Please enter one or more email addresses to receive a personalised copy of this campaign.');
						document.forms[0].Recipient1.focus ();
						return false;
					}
					buttonToLoader ($(which));
					disableButtons ();
					var url = 'EMM-create-complete.php?SampleOption=1&Recipient=' + encodeURIComponent (document.forms[0].Recipient1.value) + '&Campaign_ID=' + encodeURIComponent (document.forms[0].Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&SampleType=' + ($('#sampleVersion').css ('display') != 'none' ? getRadioButtonValue (document.forms[0].SampleVersion) : 0) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000);
					break;
		case 2:		//--- Send me a copy of the first X messages
					if (document.forms[0].Recipient2.value == '') {
						alert ('Please enter an email address to receive ' + document.forms[0].SampleSize.options[document.forms[0].SampleSize.selectedIndex].text + ' of this campaign.');
						document.forms[0].Recipient2.focus ();
						return false;
					}
					buttonToLoader ($(which));
					disableButtons ();
					var url = 'EMM-create-complete.php?SampleOption=2&Recipient=' + encodeURIComponent (document.forms[0].Recipient2.value) + '&SampleSize=' + encodeURIComponent (document.forms[0].SampleSize.options[document.forms[0].SampleSize.selectedIndex].value) + '&SampleType=0&Campaign_ID=' + encodeURIComponent (document.forms[0].Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000);
					break;
	}

	if (lastStatusTest != null) {
		clearTimeout (lastStatusTest);
	}

	$.ajax ({
		url: url,
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We had a problem sending your sample email. Please reload the page and try again.');
				loaderToButton (); enableButtons ();
			} else {
				//--- Flag it so we check
				isSending = 1;
			}
		}
	});

	//--- Start again after 5 seconds
	setTimeout (function () { waitForSending (); }, 5000);

}

function sendCampaign (which) {
//--- Kicks off the campaign

	buttonToLoader ($(which));
	disableButtons ();

	$.ajax ({
		url: 'EMM-create-complete.php?Campaign_ID=' + encodeURIComponent (document.forms[0].Campaign_ID.value) + '&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&SendVolume=' + encodeURIComponent (parseInt (document.forms[0].Recipients.value)) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We had a problem sending your email out. Please reload the page and try again.');
				loaderToButton (); enableButtons ();
			} else {
				switchTo (1);
				setTimeout (function () { animateProgressBar (0.05, 500, ''); }, 1000); // fake it, just so we see something
			}
		}
	});

}

function waitForSending () {
//--- Starts the wait for a status update

	testForStatus (function (xml) {
		try {
			if (!xml.getElementsByTagName ("status").length) {
				return 0;
			} else
			if (!xml.getElementsByTagName ("status")[0].getElementsByTagName ("statusText").length) {
				return 0;
			}
			if (!xml.getElementsByTagName ("status")[0].getElementsByTagName ("schedule").length) {
				return 0;
			}
			var unsent = xml.getElementsByTagName ("status")[0].getElementsByTagName ("unsent")[0].firstChild.nodeValue.toString ();
			var status = xml.getElementsByTagName ("status")[0].getElementsByTagName ("statusText")[0].firstChild.nodeValue.toString ();
		} catch (e) {
			return 0;
		}
		if (isSending) {
			if ((unsent == 0) && (status == 'pending')) {
				if (parseInt ($('#contactCount').html ()) > 0) {
					$('#ready_normal').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
					$('#ready_scheduled').find ('.disabled').addClass ('button').removeClass ('disabled').attr ('disabled', false);
				}
				loaderToButton (); enableButtons ();
				isSending = 0;
			}
			return 0;
		}
		switch (status) {
			case 'queuing':
			case 'sending':
			case 'paused':
			case 'stopped':
			case 'incomplete':
			case 'completed':
				if (currStep < 4) {
					if (currStep == 2) {
						$('#step' + currStep).hide ('slide', { direction: 'left' }, 200);
						$('#step' + currStep + '_no').addClass ('done').removeClass ('active');
						$('#targeting').show ('slide', { direction: 'right' });
						currStep++;
					}
					var sText = xml.getElementsByTagName ("status")[0].getElementsByTagName ("schedule")[0].firstChild.nodeValue.toString ();
					if (($('#scheduleTextPostSend').closest ('.row').css ('display') != 'none') && ((sText == 'none') || (sText.substr (0, 7) == '0 times'))) {
						 $('#scheduleTextPostSend').closest ('.row').css ('display', 'none');
						 $('#outer').css ('height', 'auto');
					}
					disableButtons ();
					switchTo (1);
					setTimeout (function () { animateProgressBar (0.05, 500, ''); }, 1000); // fake it, just so we see something
				}
				return 1;
			default:
				if (currStep < 4) {
					//--- Check for changes
					var scheduleTag = xml.getElementsByTagName ("status")[0].getElementsByTagName ("schedule")[0];
					if (scheduleTag) {
						var sText = scheduleTag.firstChild.nodeValue.toString ();
						var sYear = scheduleTag.getAttribute ('year');
						var sMonth = scheduleTag.getAttribute ('month');
						var sDay = scheduleTag.getAttribute ('day');
						var sHour = scheduleTag.getAttribute ('hour');
						var sMinute = scheduleTag.getAttribute ('minute');
						var sRemoveAfter = scheduleTag.getAttribute ('removeAfter');
						var sType = scheduleTag.getAttribute ('type');
					}
					if (sText.substr (0, 7) == '0 times') {
						sText = 'none';
					}
					if ((sText != '') && (sText != 'none')) {
						if ($('#ready_scheduled').css ('display') == 'none') {
							$('#ready_normal').css ('display', 'none');
							$('#ready_scheduled').css ('display', 'block');
							$('#cancelButton').fadeIn (500);
						}
						if ($('#scheduleText a').html () != 'Sending ' + sText) {
							f = document.forms[0];
							if ((is_numeric (sYear)) && (is_numeric (sMonth)) && (is_numeric (sDay)) && (is_numeric (sHour)) && (is_numeric (sMinute))) {
								f.DateScheduled.value = sYear + (sMonth.length < 2 ? '0' : '') + sMonth + (sDay.length < 2 ? '0' : '') + sDay;
								selectDropDown (f.ScheduledHour, (sHour != '0' ? sHour.replace (/^0+/, '') : '0'));
								selectDropDown (f.ScheduledMinute, (sMinute != '0' ? sMinute.replace (/^0+/, '') : '0'));
								cal.selection.set (f.DateScheduled.value);
								cal.moveTo (sYear + '-' + (sMonth.length < 2 ? '0' : '') + sMonth + '-' + (sDay.length < 2 ? '0' : '') + sDay);
							} else {
								f.DateScheduled.value = '';
								f.ScheduledHour.selectedIndex = 0;
								f.ScheduledMinute.selectedIndex = 0;
								cal.selection.clear (false);
							}
							selectDropDown (f.Year, sYear);
							selectDropDown (f.Month, sMonth.replace (/^0+/, ''));
							selectDropDown (f.Day, sDay.replace (/^0+/, ''));
							selectDropDown (f.Hour, (sHour != '0' ? sHour.replace (/^0+/, '') : '0'));
							selectDropDown (f.Minute, (sMinute != '0' ? sMinute.replace (/^0+/, '') : '0'));
							f.RemoveAfter.value = sRemoveAfter;
							$('#frequencyText').html ((sRemoveAfter == '-1' ? 'ongoing' : (sRemoveAfter == '1' ? 'once' : (sRemoveAfter == '2' ? 'twice' : sRemoveAfter + ' times'))));
							$('#frequency').find ('a.linkSelected').removeClass ('linkSelected');
							$('#frequency').find ('a[data-val="' + sRemoveAfter + '"]').addClass ('linkSelected');
							$('#scheduleText a').html ('Sending ' + sText);
							$('#scheduleText').animate ({backgroundColor: "#FFFF9C"}, 100).animate({ backgroundColor: "transparent"}, 1500);
							$('#scheduleTextPostSend').html ('Sending ' + sText);
						}
					} else
					if ((sText == 'none') && ($('#ready_scheduled').css ('display') != 'none')) {
						f = document.forms[0];
						f.DateScheduled.value = '';
						f.ScheduledHour.selectedIndex = 0;
						f.ScheduledMinute.selectedIndex = 0;
						cal.selection.clear (false);
						f.Year.selectedIndex = 0;
						f.Month.selectedIndex = 0;
						f.Day.selectedIndex = 0;
						f.Hour.selectedIndex = 0;
						f.Minute.selectedIndex = 0;
						f.RemoveAfter.value = '-1';
						$('#frequencyText').html ('once');
						$('#frequency').find ('a.linkSelected').removeClass ('linkSelected');
						$('#frequency').find ('a[data-val="-1"]').addClass ('linkSelected');
						$('#cancelButton').css ('display', 'none');
						$('#ready_scheduled').css ('display', 'none');
						$('#ready_normal').fadeIn ();
					}
					return 0; // try again
				}
				return 1;
		}
	}, 'status', 5000);

}

function testForStatus (callback, limit, wait) {
//--- Keeps checking until the status meets a specific goal, then executes callback

	$.ajax ({
		url: 'EMM-status.php?Action=I&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&LimitTo=' + limit + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (0, 10000),
		callback: callback,
		complete: function (jqXHR, textStatus) {
			if (!this.callback (jqXHR.responseXML)) {
				lastStatusTest = setTimeout (function () { testForStatus (callback, limit, wait) }, wait);
			}
		}
	})

}

function saveSplitTest (which) {
//--- Save split test settings

	buttonToLoader ($(which).closest ('div'));

	$('#splitRow').addClass ('highlighted');
	$('#scheduleButton').removeClass ('button').addClass ('keepDisabled').attr ('disabled', true);
	$.ajax ({
		url: 'EMM-target-complete.php?Action=T&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&NameFrom=' + encodeURIComponent (document.forms[0].NameFrom.value) + '&EmlFrom=' + encodeURIComponent (document.forms[0].EmlFrom.value) + '&Subject=' + encodeURIComponent (document.forms[0].Subject.value) + '&WinnerBasedOn=' + encodeURIComponent (document.forms[0].WinnerBasedOn.options[document.forms[0].WinnerBasedOn.selectedIndex].value) + '&WaitPeriod=' + encodeURIComponent (document.forms[0].WaitPeriod.value) + '&TestGroupSize=' + encodeURIComponent (document.forms[0].TestGroupSize.value) + '&DefaultTo=' + encodeURIComponent (document.forms[0].DefaultTo.options[document.forms[0].DefaultTo.selectedIndex].value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We had a problem saving your test settings. Please reload the page and try again.');
			} else {
				hideSubPage ();
				$('#sampleVersion').show ();
				$('#unsplitButton').show ();
				$('#scheduleTextPostSend').closest ('.row').show ();
				$('#scheduleTextPostSend').closest ('.row').find ('.bold').html ('We will pick the winning A/B version at:');
				$('#scheduleTextPostSend').html ('Waiting for test schedule...');
				$('#splitTestLink').addClass ('clickable').removeClass ('hidden').on ('click', function () { showSubSubPage ('reportSplitTest'); return false; });
			}
			loaderToButton ();
		}
	});

}

function clearSplitTest () {
//--- Removes split test settings

	$.ajax ({
		url: 'EMM-target-complete.php?Action=C&Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText != 'OK') {
				alert ('We had a problem saving your test settings. Please reload the page and try again.');
			} else {
				$('#splitRow').removeClass ('highlighted');
				$('#sampleVersion').hide ();
				$('#unsplitButton').hide ();
				$('#scheduleTextPostSend').closest ('.row').hide ();
				$('#scheduleTextPostSend').closest ('.row').find ('.bold').html ('Edit or remove this schedule:');
				$('#scheduleTextPostSend').html ('Sending on demand');
				$('#splitTestLink').removeClass ('clickable').addClass ('hidden').on ('click', function () { return false; });
				$('#scheduleButton').removeClass ('keepDisabled').addClass ('button').attr ('disabled', false);
				hideSubPage ();
			}
		}
	});

}

function drawSplitTestGraphs () {
//--- Draws a new graph

	if (google) {

		//--- Main graphs
		splitStatsData = google.visualization.arrayToDataTable (splitStatsOverTime);
		splitStatsOptions = {
			colors: ['#ffb2b2', '#add3ea'],
			theme: 'maximized',
			pointSize: 10,
			fontName: 'Arial',
			fontSize: 11,
			lineWidth: 3,
			width: ($('#outer').width () - 20),
			height: '140',
			legend: { position: 'none' }, 
			hAxis: { showTextEvery: (splitStatsOverTime.length > 29 ? 5 : (splitStatsOverTime.length > 23 ? 4 : (splitStatsOverTime.length > 17 ? 3 : (splitStatsOverTime.length > 11 ? 2 : 1)))) }, 
			animation: { duration: 1000, easing: 'out' }
		};

		splitStatsChart = new google.visualization.AreaChart (document.getElementById ('splitStatistics'));
		splitStatsChart.draw (splitStatsData, splitStatsOptions);

	}

}

function resizeSplitTestReportBoxes () {
//--- Resizes the report boxes

	if (google) {
		try {
			splitStatsOptions.width = ($('#outer').width () - 20);
			splitStatsChart.draw (splitStatsData, splitStatsOptions);
		} catch (e) {}
	}

}

function drawGraphs () {

	$('#statistics').css ('width', ($('#outer').width () * 3 / 4) - 25);
	$('#statisticsBorder').css ('width', ($('#outer').width () * 1 / 4) - 20);

	if (google) {

		//--- Main graphs
		statsData = google.visualization.arrayToDataTable (statsOverTime);
		statsOptions = {
			colors: ['#edcb21', '#4e79d8', '#fc575e', '#66cc99'],
			theme: 'maximized',
			pointSize: 10,
			fontName: 'Arial',
			fontSize: 11,
			lineWidth: 3,
			width: ($('#outer').width () * 3 / 4) - 25,
			height: '140',
			legend: { position: 'none' }, 
			hAxis: { showTextEvery: (statsOverTime.length > 29 ? 5 : (statsOverTime.length > 23 ? 4 : (statsOverTime.length > 17 ? 3 : (statsOverTime.length > 11 ? 2 : 1)))) }, 
			animation: { duration: 1000, easing: 'out' }
		};

		statsChart = new google.visualization.AreaChart (document.getElementById ('statistics'));
		statsChart.draw (statsData, statsOptions);

		$('#statistics').css ('border-top', '0px');

		pieData = google.visualization.arrayToDataTable (statsPie);
		pieOptions = {
			is3D: true,
			backgroundColor: 'transparent',
			legend: { position: 'none' },
			chartArea: {left: 0, top: 0, width: '100%', height: '100%'},
			pieSliceText: 'percentage',
			slices: [{color: '#999'}, {color: '#66cc99'}, {color: '#fc575e'}, {color: '#edcb21'}, {}, {color: '#7fa7ff'}],
			width: ($('#outer').width () * 1 / 4) - 20,
			height: '130', 
			animation: { duration: 1000, easing: 'out' }
		};

		pieChart = new google.visualization.PieChart (document.getElementById ('statistics_pie'));
		pieChart.draw (pieData, pieOptions);

	}

}

function hideInfographic () {
//--- Slides the infographic out

	$('#reportOptions').fadeOut (100);
	$('#reportBodyOuter').hide ('slide', { direction: 'up' }, 300);
	$('#body').css ('height', '').delay (300).show ('slide', { direction: 'up' }, 500, function () {
		$('#reportBody').html ('<div align="center" style="padding: 20px 0 10px 0;">Please wait while we download data for your report (this may take a minute)<br /><img src="images/ajax-loader.gif" style="margin: 10px 0;" /></div>');
	});

}

function downloadRecipients () {
//--- Calls the recipient/status report with the selected options

	var runBackground = (document.forms[0].RunBackground.checked ? 1 : 0);
	var includeNumbers = (document.forms[0].IncludeNumbers.checked ? 1 : 0);
	var includeDates = (document.forms[0].IncludeDates.checked ? 1 : 0);

	if (runBackground) {
		$.ajax ({
			url: 'EMM-delivery-recipients.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&RunBackground=1&IncludeNumbers=' + encodeURIComponent (includeNumbers) + '&IncludeDates=' + encodeURIComponent (includeDates) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
			complete: function (jqXHR, textStatus) {
				if (jqXHR.responseText != 'OK') {
					alert ('There was a problem requesting your report. Please try again. If the problem persists, please refresh your screen.');
					loaderToButton ();
				} else {
					loaderToButton ();
					hideSubSubPage ();
				}
			}
		});
	} else {
		//--- This should start the download
		document.location = 'EMM-delivery-recipients.php?Mail_ID=' + encodeURIComponent (document.forms[0].Mail_ID.value) + '&IncludeNumbers=' + encodeURIComponent (includeNumbers) + '&IncludeDates=' + encodeURIComponent (includeDates) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000);
		loaderToButton ();
		hideSubSubPage ();
	}

}

function postToSocial () {
//--- Sends the specified post to the selected social media accounts

	if (document.forms[0].Comment.value == '') {
		alert ('Please enter a comment'); document.forms[0].Comment.focus (); loaderToButton (); return false;
	}
	if (document.forms[0].Services.value == '') {
		alert ('Please select at least one service.'); loaderToButton (); return false;
	}

	var textLength = document.forms[0].Comment.value.length;

	//--- Check for length problems before submit
	var promptOnFacebook = false;
	var promptOnTwitter = false;
	var promptOnLinkedIn = false;
	var promptOnYouTube = false;
	var Services = document.forms[0].Services.value.substr (1, document.forms[0].Services.value.length - 1).split (';');
	for (var i = 0; i < Services.length; i++) {
		TService = Services[i].split (':');
		switch (TService[0]) {
			case '1':	promptOnFacebook = (textLength > charsLeft ('fb') ? true : promptOnFacebook); break;
			case '2':	promptOnTwitter = (textLength > charsLeft ('tw') ? true : promptOnTwitter); break;
			case '3':	promptOnLinkedIn = false; break;
			case '4':	promptOnYouTube = false; break;
		}
	}

	if (promptOnFacebook) {
		if (!confirm ('Your post is over 2,500 characters long, and part of the message may be cut off by Facebook. Continue?')) {
			loaderToButton (); return false;
		}
	}
	if (promptOnTwitter) {
		if (!confirm ('Your post is over 140 characters long and may be rejected by Twitter. Continue?')) {
			loaderToButton (); return false;
		}
	}

	$.ajax ({
		url: 'VMM-post.php?Action=addPost&FromPostTo=1&Comment=' + encodeURIComponent (document.forms[0].Comment.value) + '&Services=' + encodeURIComponent (document.forms[0].Services.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000),
		complete: function (jqXHR, textStatus) {
			if (jqXHR.responseText.substr (0, 8) != '<script>') {
				alert ('There was a problem posting your content. Please try again. If the problem persists, please refresh your screen.');
				loaderToButton ();
			} else {
				loaderToButton ();
				hideSubPage ();
			}
		}
	});

}

function contactListClick () {
//--- Handler function

	if (currStep == 3) {
		switchTo (-1);
	} else {
		document.location = 'IMM-form.php?Item_ID=' + encodeURIComponent (document.forms[0].Item_ID.value) + '&Session_ID=' + encodeURIComponent (document.forms[0].Session_ID.value) + '&Rand=' + Math.random (10000);
	}

	return false;

}

var urlMatchGlobal = new RegExp ("https?://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=#+@]+($| )", 'g');
var lastProgress = 0;
var lastSubPage = null;
var lastSubSubPage = null;
var lastLoaderButton = null;
var lastFilter = null;
var lastFilterSelected = null;
var lastButtonsEnabled = null;
var lastFormObjEnabled = null;
var lastStatus = null;
var lastStatusTest = null;
var statsChart = null;
var statsOptions = [];
var statsData = null;
var splitStatsOverTime = null;
var splitStatsChart = null;
var splitStatsOptions = [];
var splitStatsData = null;
var pieChart = null;
var pieOptions = [];
var pieData = null;

var isIE = document.all;
var isChrome = navigator.userAgent.toLowerCase ().indexOf ('chrome') != -1;
var isSafari = navigator.userAgent.toLowerCase ().indexOf ('safari') != -1;
var isFirefox = ((!isIE) && (!isChrome));
var isMac = (navigator.userAgent.indexOf ('Macintosh') != -1);
