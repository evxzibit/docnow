function newWindow (URL, Title, Width, Height, Parameters) {
//--- Opens a new window with the specifics in the center of the screen

	top.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), Parameters);

}

function showEdit (which) {
//--- Show edit functions

	if (lastEditTimeout != null) {
		clearTimeout (lastEditTimeout);
	}
	if (lastEdit != null) {
		lastEdit.getElementsByTagName('DIV')[0].style.display = 'none';
	}

	which.getElementsByTagName('DIV')[0].style.display = '';
	lastEdit = which;

}

function hideEdit () {
//--- Hide edit functions

	if (lastEditTimeout != null) {
		clearTimeout (lastEditTimeout);
	}
	lastEditTimeout = setTimeout (function () {
		if (lastEdit != null) {
			lastEdit.getElementsByTagName('DIV')[0].style.display = 'none';
		}
	}, 10);

}

function clearIntros () {
//--- Removes intros from all groups that have fields

	var i = 0;
	while  (_$ ('group' + i + 'Intro')) {
		if (_$ ('group' + i + 'FieldsList').getElementsByTagName ('LI').length == 0) {
			_$ ('group' + i + 'Intro').style.display = 'block';
		} else {
			_$ ('group' + i + 'Intro').style.display = 'none';
		}
		i++;
	}

}

function enableSortable () {
//--- Enables sorting functions

	$('.sortable').sortable('destroy');
	$('.sortable').sortable({
		connectWith: '.connected',
		forcePlaceholderSize: true
	});

}

function addField (fieldType, insertAbove) {
//--- Add a field to the form

	if ((insertAbove) && (insertAbove.tagName == 'UL')) {
		 insertAbove.appendChild (_$ ('clone')); // move clone element
	} else {
		_$ ('group0FieldsList').appendChild (_$ ('clone')); // move clone element
	}

	//--- First, find the next available ID
	var i = 0;
	while  (_$ ('field' + i)) {
		i++;
	}

	var id = 'field' + i;

	newField = _cloneChild (_$ ('clone'), 'bottom');
	newField.id = id;

	switch (fieldType) {
		case 'description':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, '').replace (/{Mandatory}/g, '').replace (/{Field}/g, '<div class="descriptionText">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>').replace (/{Help}/g, ''); break;
		case 'text':		newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Text Box').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="text" disabled="disabled" />').replace (/{Help}/g, ''); break;
		case 'email':		newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Email Address').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="email" disabled="disabled" />').replace (/{Help}/g, ''); break;
		case 'tel':			newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Mobile Telephone').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="tel" disabled="disabled" />').replace (/{Help}/g, ''); break;
		case 'password':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Password Box').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="password" disabled="disabled" value="password" />').replace (/{Help}/g, ''); break;
		case 'dropdown':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Drop-Down Menu').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<select size="1" disabled="disabled"><option>---</option><option>Choice 1</option><option>Choice 2</option><option>Choice 3</option></select>').replace (/{Help}/g, ''); break;
		case 'radio':		newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Radio Buttons').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="radio" disabled="disabled" />Choice 1<br /><input type="radio" disabled="disabled" />Choice 2<br /><input type="radio" disabled="disabled" />Choice 3<br />').replace (/{Help}/g, ''); break;
		case 'checkbox':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Checkbox').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="checkbox" disabled="disabled" /> I agree').replace (/{Help}/g, ''); break;
		case 'checkboxgroup':
							newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Checkbox Group').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="checkbox" disabled="disabled" />Choice 1<br /><input type="checkbox" disabled="disabled" />Choice 2<br /><input type="checkbox" disabled="disabled" />Choice 3<br />').replace (/{Help}/g, ''); break;
		case 'multiselect':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Multi-Select Menu').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<select size="5" disabled="disabled"><option>Choice 1</option><option>Choice 2</option><option>Choice 3</option></select>').replace (/{Help}/g, ''); break;
		case 'textarea':	newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Text Area').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<textarea cols="30" rows="4" disabled="disabled"></textarea>').replace (/{Help}/g, ''); break;
		case 'file':		newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled File Upload').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<input type="file" disabled="disabled" />').replace (/{Help}/g, ''); break;
		case 'date':		newField.innerHTML = newField.innerHTML.replace (/{Name}/g, 'Untitled Date Entry Field').replace (/{Mandatory}/g, '*').replace (/{Field}/g, '<select size="1" disabled="disabled"><option>---</option><option>Day</option></select> <select size="1" disabled="disabled"><option>---</option><option>Month</option></select> <select size="1" disabled="disabled"><option>---</option><option>Year</option></select>').replace (/{Help}/g, ''); break;
	}

	if ((insertAbove) && (insertAbove.tagName != 'UL')) {
		 insertAbove.parentNode.insertBefore (newField, insertAbove);
	}

	clearIntros ();
	enableSortable ();

	_$ ('group0Intro').innerHTML = 'Click or drag fields from the left menu.'; // after first field
	_$ ('cloneFieldsList').appendChild (_$ ('clone')); // move it back

	//--- Where has it been inserted
	var index = -1;
	if (insertAbove != null) {
		var items = insertAbove.parentNode.getElementsByTagName ('LI');
		var i = 0;
		var a = 0;
		while ((items[i]) && (index == -1)) {
			if (items[i] == addLI) {
				a = 1;
			}
			if (items[i] == newField) {
				index = (addLI != null ? (i - a) : i);
			}
			i++;
		}
	}

	//--- Get the next available form ID
	_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=A&TempId=' + escape (id) + '&Group=' + escape (((insertAbove != null) && (insertAbove.parentNode.getAttribute ('name')) ? insertAbove.parentNode.getAttribute ('name') : ((insertAbove != null) && (insertAbove.tagName == 'UL') && (insertAbove.getAttribute ('name')) ? insertAbove.getAttribute ('name') : ''))) + '&TempPos=' + index + '&Type=' + escape (fieldType) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
		results = eval('(' + ajaxObj.responseText + ')');
		if (results.temp_id) {
			_$ (results.temp_id).getElementsByTagName ('INPUT')[0].value = results.form_id;
			_$ (results.temp_id).getElementsByTagName ('SPAN')[0].style.display = 'none';
			_$ (results.temp_id).getElementsByTagName ('SPAN')[1].style.display = '';
		}
	}, function (ajaxObj, url) {
		alert ('An error occured and we could not create the field for you. Please try again.');
	});

}

function editField (e, which) {

	var whichField = which.getElementsByTagName ('INPUT')[0];
	var whichValue = whichField.value;

	newWindow ('FMM-create-edit.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Form_ID=' + whichValue + '&Session_ID=' + document.forms[0].Session_ID.value, 'Edit Field', 600, 450, [which, window]);

	if (e.preventDefault) {
		e.preventDefault ();
	}
	if (e.stopPropagation) {
		e.stopPropagation ();
	}
	e.cancelBubble = true;
	return false;

}

function deleteField (e, which) {
//--- Deletes the selected field

	if (which.parentNode.parentNode.id.substr (0, 5) == 'field') {

		ConfirmYesNo ('Are you sure you want to delete this field?<br /><b>Warning: Doing so will delete all data associated with the field as well.</b>', function () {
			_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=D&Form_IDs[]=' + escape (which.parentNode.parentNode.getElementsByTagName ('INPUT')[0].value) + '&TempId=' + escape (which.parentNode.parentNode.id) + '&Group=' + escape ((which.parentNode.parentNode.parentNode.getAttribute ('name') ? which.parentNode.parentNode.parentNode.getAttribute ('name') : '')) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
				results = eval('(' + ajaxObj.responseText + ')');
				if (results.temp_id) {
					_fadeOut (results.temp_id);
					setTimeout (function () {
						_$ (results.temp_id).parentNode.removeChild (_$ (results.temp_id));
						clearIntros ();
					}, 500);
				}
			}, function (ajaxObj, url) {
				alert ('An error occured and we could not delete the field for you. Please try again.');
			});
		}, function () {});

	}

	if (e.preventDefault) {
		e.preventDefault ();
	}
	if (e.stopPropagation) {
		e.stopPropagation ();
	}
	e.cancelBubble = true;
	return false;

}

function cloneField (e, field) {
//--- Add a duplicate field below the current one

	var Form_ID = field.getElementsByTagName ('input')[0].value;

	//--- Get the next available form ID
	_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=F&TempId=' + escape (field.id) + '&Form_ID=' + escape (Form_ID) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
		results = eval('(' + ajaxObj.responseText + ')');
		//--- First, find the next available ID
		var i = 0;
		while  (_$ ('field' + i)) {
			i++;
		}
		var id = 'field' + i;
		newField = _cloneChild (_$ (results.temp_id), 'below');
		newField.id = id;
		newField.getElementsByTagName ('div')[0].style.display = 'none';
		newField.getElementsByTagName ('input')[0].value = results.form_id;
		newField.className = '';
		$(newField).highlightRow ();
		enableSortable ();
	}, function (ajaxObj, url) {
		alert ('An error occured and we could not duplicate the field for you. Please try again.');
	});

	if (e.preventDefault) {
		e.preventDefault ();
	}
	if (e.stopPropagation) {
		e.stopPropagation ();
	}
	e.cancelBubble = true;
	return false;

}

function addGroup (groupName, cloneFrom) {
//--- Adds a new group to the page

	//--- First, find the next available ID
	var i = 0;
	while (_$ ('group' + i + 'Intro')) {
		i++;
	}
	id = 'group' + i;
	newGroupTitle = _cloneChild (_$ ('cloneGroupTitle'), 'bottom');
	newGroupTitle.innerHTML = newGroupTitle.innerHTML.replace (/{Name}/g, groupName).replace (/{ID}/g, id).replace (/cloneGroup/g, id);
	newGroupTitle.id = id + 'Title';
	newGroupOuter = _cloneChild (_$ ('cloneGroupOuter'), 'bottom');
	newGroupOuter.innerHTML = newGroupOuter.innerHTML.replace (/{Name}/g, groupName).replace (/{ID}/g, id).replace (/cloneGroup/g, id);
	newGroupOuter.id = id + 'Outer';
	_$ (id + 'FieldsList').setAttribute ('name', groupName);

	if (cloneFrom == null) {
		enableSortable ();
	} else {
		lastGroupId = id;
		_$ (lastGroupId + 'Intro').innerHTML = 'Loading...';
		_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=C&FromGroup=' + escape (_$ (cloneFrom + 'Name').innerHTML) + '&ToGroup=' + escape (groupName) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
			_$ (lastGroupId + 'FieldsList').innerHTML = ajaxObj.responseText;
			_$ (lastGroupId + 'Intro').innerHTML = 'Click or drag fields from the left in order to design your form. Don\'t need this group any longer? <a href="#" onclick="deleteGroup (\'' + lastGroupId + '\'); return false;">Remove it</a>.';
			_$ (lastGroupId + 'Intro').style.display = 'none';
			newFields = _$ (lastGroupId + 'Outer').getElementsByTagName ('LI');
			for (j = 0; j < newFields.length; j++) {
				var i = 0;
				while (_$ ('field' + i)) {
					i++;
				}
				newFields[j].id = 'field' + i;
			}
			enableSortable ();
		}, function (ajaxObj, url) {
			alert ('An error occured and we could not duplicate the fields for you. Please try again.');
		});
	}

}

function deleteGroup (group) {
//--- Deletes a whole group of fields

	var fieldsInGroup = _$ (group + 'FieldsList').getElementsByTagName ('LI');
	if (fieldsInGroup.length != 0) {
		alert ('There are still fields in this group. Please remove all fields and then try again.'); return false;
	}

	_$ ('groups').removeChild (_$ (group + 'Title'));
	_$ ('groups').removeChild (_$ (group + 'Outer'));

}

function renameGroup (id) {
//--- Asks the user for a new name

	lastGroupId = id;

	Prompt ('What would you like to call the group?', _$ (id + 'Name').innerHTML, 'Rename this Group', 2);

}

function duplicateGroup (id) {
//--- Asks the user for a name for the duplicate group

	lastGroupId = id;

	Prompt ('What would you like to call the new group?', '', 'Duplicate this Group', 3);

}

function fieldDragOver (e) {
//--- Handles the drag over event

	if (e.preventDefault) {
		e.preventDefault ();
	}
	e.dataTransfer.dropEffect = 'copy';
	return false;

}

function fieldDragEnter (e, which) {
//--- Handles the field type drag

	if (isDragging) {
		if (which.tagName == 'UL') {
			if (which.getElementsByTagName ('LI').length == 0) {
				lastDraggedOver = null;
				addLI = document.createElement ('LI');
				addLI.style.height = _getDynamicObjectHeight (which) + 'px';
				addLI.style.border = '1px dashed #666';
				_attachEvent (addLI, 'dragover', function (e) {
					if (e.stopPropagation) {
						e.stopPropagation ();
					}
					if (e.preventDefault) {
						e.preventDefault ();
					}
					e.cancelBubble = true;
					e.dataTransfer.dropEffect = 'copy';
					return false;
				});
				_attachEvent (addLI, 'drop', function (e) {
					if (e.stopPropagation) {
						e.stopPropagation ();
					}
					if (e.preventDefault) {
						e.preventDefault ();
					}
					e.cancelBubble = true;
					addField (isDragging, which); // add at the end
					return false;
				});
				which.appendChild (addLI);
			}
		} else
		if ((which.tagName == 'LI') && (addLI == null)) {
			lastDraggedOver = which;
			addLI = document.createElement ('LI');
			addLI.style.height = _getDynamicObjectHeight (which) + 'px';
			addLI.style.border = '1px dashed #666';
			_attachEvent (addLI, 'dragover', function (e) {
				if (e.stopPropagation) {
					e.stopPropagation ();
				}
				if (e.preventDefault) {
					e.preventDefault ();
				}
				e.cancelBubble = true;
				e.dataTransfer.dropEffect = 'copy';
				return false;
			});
			_attachEvent (addLI, 'drop', function (e) {
				if (e.stopPropagation) {
					e.stopPropagation ();
				}
				if (e.preventDefault) {
					e.preventDefault ();
				}
				e.cancelBubble = true;
				addField (isDragging, lastDraggedOver);
				return false;
			});
		} else
		if (which.tagName == 'LI') {
			lastDraggedOver = which;
			if (_findPrevSibling (which) != addLI) {
				which.parentNode.insertBefore (addLI, which);
			} else {
				which.parentNode.insertBefore (addLI, (_findNextSibling (which) ? _findNextSibling (which) : null));
				lastDraggedOver = _findNextSibling (which);
			}
		}
		if (e.preventDefault) {
			e.preventDefault ();
		}
		if (e.stopPropagation) {
			e.stopPropagation ();
		}
		e.cancelBubble = true;
		return false;
	}

}

function fieldDragStart (e, fieldType) {
//--- Starts the drag, sets the field type

	isDragging = fieldType;
	e.dataTransfer.effectAllowed = 'copy';
	e.dataTransfer.setData ('Text', fieldType); // needed for FF, IE must be 'Text'

}

function fieldDragEnd (e) {
//--- Stops the drag, resets everything

	if (isDragging != null) {
		if (addLI != null) {
			if (addLI.parentNode) {
				addLI.parentNode.removeChild (addLI);
			}
			addLI = null;
			lastDraggedOver = null;
		}
	}
	isDragging = false;

}

function fieldSelectStart (e, which) {
//--- For IE

	if (which.dragDrop) {
		which.dragDrop ();
	}

	return false;

}

function sortDragEnd (e, which) {
//--- Takes action once the sort has completed

	clearIntros ();

	var id = which.id; // which item was moved

	//--- Where has it been inserted
	var index = -1;
	var items = which.parentNode.getElementsByTagName ('LI');
	var i = 0;
	var a = 0;
	while ((items[i]) && (index == -1)) {
		if (items[i].className == 'sortable-placeholder') {
			a = 1;
		}
		if (items[i] == which) {
			index = (i - a);
		}
		i++;
	}

	if (index > -1) {
		_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=R&Form_ID=' + escape (which.getElementsByTagName ('INPUT')[0].value) + '&NewPos=' + index + '&Group=' + escape ((which.parentNode.getAttribute ('name') ? which.parentNode.getAttribute ('name') : '')) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
			results = eval('(' + ajaxObj.responseText + ')'); // nothing else (for now)
		}, function (ajaxObj, url) {
			alert ('An error occured and we could not update the field order. Please reload the page and try again.');
		});
	}

}

function Prompt (Question, Default, Title, ResponseType) {
//--- Replaces the prompt function that doesn't work anymore

	top.modalWindows.openWindow ("prompt-dialog.php?Question=" + escape (Question) + "&Default=" + escape (Default), Title, 460, 130, Array (ResponseType, null), PromptResponse);

}

function PromptResponse (Response) {
//--- Handles the response to a prompt

	switch (Response[0]) {
		case 1:	if (Response[1]) {
					addGroup (Response[1], null);
				}
				break;
		case 2:	if ((lastGroupId != null) && (Response[1])) {
					_ajaxRequest ('FMM-create-complete.php?Item_ID=' + escape (document.forms[0].Item_ID.value) + '&Action=G&TempId=' + lastGroupId + '&OriginalName=' + escape (_$ (lastGroupId + 'Name').innerHTML) + '&NewName=' + escape (Response[1]) + '&Session_ID=' + document.forms[0].Session_ID.value, 'GET', function (ajaxObj, url) {
						results = eval('(' + ajaxObj.responseText + ')');
						_$ (results.group_id + 'Name').innerHTML = results.new_name;
					}, function (ajaxObj, url) {
						alert ('An error occured and we could not update the group name. Please reload the page and try again.');
					});
				}
				break;
		case 3:	if ((lastGroupId != null) && (Response[1])) {
					addGroup (Response[1], lastGroupId);
				}
				break;
	}

}

function ConfirmYesNo (Question, CallOnSubmit, CallOnClose) {
//--- Replaces the confirm function with a custom dialog

	top.modalWindows.openWindow ("yesno-dialog.php?Question=" + escape (Question), 'Confirm', 460, 130, Array (CallOnSubmit), CallOnClose);

}

jQuery.fn.highlightRow = function () {
	$(this).each(function () {
		var el = $(this);
		$("<div/>").width (el.outerWidth ()).height (el.outerHeight ()).css ({
			"position": "absolute",
			"left": el.offset().left,
			"top": el.offset().top,
			"background-color": "#ffff99",
			"opacity": ".7",
			"z-index": "9999999"
		}).appendTo ('body').fadeOut (1000).queue (function () { $(this).remove (); });
	});
}

var lastEditTimeout = null;
var lastEdit = null;
var lastSaveTimeout = null;
var lastGroupId = null;
var isDragging = false;
var lastDraggedOver = null;
var addLI = null;