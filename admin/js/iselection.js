//--- Ensight Source Code (ESC)
//--- First Created on Sunday, September 5 2010 by John Ginsberg (still in the lounge)
//--- For Ensight 4

//--- Module Name: iselection.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Auto-complete selection using pictures

//--- Notice:
//--- This code is copyright (C) 2010, Ensight Technologies Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from Ensight Technologies Ltd - http://www.getensight.com

//----------------------------------------------------------------

//------------------------------------------------------------------------------------
//--- Initialise the selection object
//------------------------------------------------------------------------------------

/**
 * Object selection
 * @l obj		:the originating list field
 * @u string	:the callback url that supplies XML results
 * @p array		:additional parameters
 */
function selection (l, u, p) {

	function create () {
	//--- Creates the elements required for a selection

		var boxW = parseInt ((this.parameters['boxW'] ? this.parameters['boxW'] : 180));
		var boxH = parseInt ((this.parameters['boxH'] ? this.parameters['boxH'] : 50));
		var boxesPerRow = (this.parameters['boxesPerRow'] ? this.parameters['boxesPerRow'] : 2);
		var dialogW = (parseInt (boxW * boxesPerRow) + 28 + (19 * boxesPerRow));
		var dialogH = (parseInt (this.parameters['dialogHeight'] ? this.parameters['dialogHeight'] : 300));
		var listFieldParent = this.listField.parentNode;

		//--- Surround the selected object in an outer div (holder) and then add filters and such below
		newDIV = document.createElement ('DIV');
		listFieldParent.insertBefore (newDIV, this.listField);
		newDIV.appendChild (listFieldParent.removeChild (this.listField));

		//--- Add styling
		this.listField.className = 'selectHolder';
		this.listField.style.width  = dialogW + 'px';
		this.listField.style.height = dialogH + 'px';

		//--- Style individual list items
		listEl = this.listField.getElementsByTagName ('li');
		listCount = listEl.length;
		for (var i = 0; i < listEl.length; i++) {
			listEl[i].innerHTML = "<div class=\"item" + (listEl[i].className.indexOf ('selected') != -1 ? " selected" : "") + "\" style=\"width: " + boxW + "px; height: " + boxH + "px\" onclick=\"top.selectionObjects['" + this.listField.id + "'].setActive (); top.lastSelection.click (this)\" onmouseover=\"this.className += ' over'\" onmouseout=\"this.className = this.className.toString ().replace (' over', '')\">" + listEl[i].innerHTML + "</div>";
			if (listEl[i].className.indexOf ('selected') != -1) {
				listEl[i].className = 'li_selected'; this.selectedCount++;
			}
		}

		//--- Add hidden list item (nothing selected)
		newLI = document.createElement ('LI');
		newLI.id = this.listField.id + '_nothing_to_show';
		newLI.style.display = 'none';
		newLI.style.padding = '5px';
		newLI.innerHTML = 'Nothing selected.';
		this.listField.appendChild (newLI);

		//--- Create filter HTML
		var html =
		"<strong>Find</strong> <input type=\"text\" id=\"" + this.listField.id + "_filter\" name=\"" + this.listField.id + "_filter\" onkeyup=\"top.lastSelection.filter (this.value)\" onfocus=\"top.selectionObjects['" + this.listField.id + "'].setActive ()\" autocomplete=\"off\" /><br />" + (this.parameters['hideFilters'] ? "<br />" : "") +  
		"<div class=\"selectFilters\" style=\"width: " + dialogW + "px;" + (this.parameters['hideFilters'] ? " display: none;" : "") + "\">" +
        "<ul class=\"selectFilterTabs\">" + 
		"<li id=\"" + this.listField.id + "_view_all\" class=\"view_on\"><a onclick=\"top.selectionObjects['" + this.listField.id + "'].setActive (); top.lastSelection.tab (this); top.lastSelection.filter ('all'); return false;\" href=\"#\">View All</a></li>" + 
		"<li id=\"" + this.listField.id + "_view_selected\" class=\"\"><a onclick=\"top.selectionObjects['" + this.listField.id + "'].setActive (); top.lastSelection.tab (this); top.lastSelection.filter ('selected'); return false;\" href=\"#\">Selected (<strong id=\"" + this.listField.id + "_view_selected_count\">" + (this.selectedCount ? this.selectedCount : '0') + "</strong>)</a></li>" +
        "<li id=\"" + this.listField.id + "_view_unselected\" class=\"\"><a onclick=\"top.selectionObjects['" + this.listField.id + "'].setActive (); top.lastSelection.tab (this); top.lastSelection.filter ('unselected'); return false;\" href=\"#\">Unselected</a></li>" + 
		"</ul><br style=\"clear: both\" />" +
        "</div>";

		filter = document.createElement ('DIV');
		filter.innerHTML = html;

		//--- Add filter above selection box
		newElement = newDIV.insertBefore (filter, this.listField);

	}

	function tab (link) {
	//--- Changes the filter tab from one option to another

		var filters = link.parentNode.parentNode.getElementsByTagName ('li');
		for (var j = 0; j < filters.length; j++) {
			filters[j].className = '';
		}

		link.parentNode.className = 'view_on';

	}

	function filter (show) {
	//--- Hides or shows list elements based on their status

		var showCount = 0;
		var tab = (_$ (this.listField.id + '_view_all').className == 'view_on' ? 'all' : (_$ (this.listField.id + '_view_selected').className == 'view_on' ? 'selected' : (_$ (this.listField.id + '_view_unselected').className == 'view_on' ? 'unselected' : '')));

		if (show == '') {
			show = tab;
		}

		switch (show) {
			case 'all':		listEl = this.listField.getElementsByTagName ('li');
							listCount = listEl.length;
							for (var i = 0; i < listEl.length; i++) {
								if (listEl[i].id != this.listField.id + '_nothing_to_show') {
									nameField = (listEl[i].getElementsByTagName ('span').length ? listEl[i].getElementsByTagName ('span')[0] : listEl[i].getElementsByTagName ('span'));
									nameValue = (nameField.innerText ? nameField.innerText : nameField.textContent);
									nameField.innerHTML = nameValue;
									listEl[i].style.display = 'block';
									showCount++;
								}
							}
							_$ (this.listField.id + '_filter').value = '';
							break;

			case 'selected':
							listEl = this.listField.getElementsByTagName ('li');
							listCount = listEl.length;
							for (var i = 0; i < listEl.length; i++) {
								if (listEl[i].id != this.listField.id + '_nothing_to_show') {
									nameField = (listEl[i].getElementsByTagName ('span').length ? listEl[i].getElementsByTagName ('span')[0] : listEl[i].getElementsByTagName ('span'));
									nameValue = (nameField.innerText ? nameField.innerText : nameField.textContent);
									nameField.innerHTML = nameValue;
									if (listEl[i].className.indexOf ('li_selected') != -1) {
										listEl[i].style.display = 'block';
										showCount++;
									} else {
										listEl[i].style.display = 'none';
									}
								}
							}
							_$ (this.listField.id + '_filter').value = '';
							break;

			case 'unselected':
							listEl = this.listField.getElementsByTagName ('li');
							listCount = listEl.length;
							for (var i = 0; i < listEl.length; i++) {
								if (listEl[i].id != this.listField.id + '_nothing_to_show') {
									nameField = (listEl[i].getElementsByTagName ('span').length ? listEl[i].getElementsByTagName ('span')[0] : listEl[i].getElementsByTagName ('span'));
									nameValue = (nameField.innerText ? nameField.innerText : nameField.textContent);
									nameField.innerHTML = nameValue;
									if (listEl[i].className.indexOf ('li_selected') != -1) {
										listEl[i].style.display = 'none';
									} else {
										listEl[i].style.display = 'block';
										showCount++;
									}
								}
							}
							_$ (this.listField.id + '_filter').value = '';
							break;

			default:		listEl = this.listField.getElementsByTagName ('li');
							listCount = listEl.length;
							showCompare = show.toLowerCase ();
							for (var i = 0; i < listEl.length; i++) {
								if (listEl[i].id != this.listField.id + '_nothing_to_show') {
									nameField = (listEl[i].getElementsByTagName ('span').length ? listEl[i].getElementsByTagName ('span')[0] : listEl[i].getElementsByTagName ('span'));
									nameValue = (nameField.innerText ? nameField.innerText : nameField.textContent);
									if ((showCompare != '') && (nameValue.toLowerCase ().indexOf (showCompare) != -1)) {
										nameField.innerHTML = nameValue.toString ().replace (new RegExp ('(' + showCompare + ')', "gi"), "<b>$1</b>");
										switch (tab) {
											case 'all':			listEl[i].style.display = 'block'; showCount++; break;
											case 'selected':	if (listEl[i].className.indexOf ('li_selected') != -1) { listEl[i].style.display = 'block'; showCount++; } else { listEl[i].style.display = 'none'; } break;
											case 'unselected':	if (listEl[i].className.indexOf ('li_selected') == -1) { listEl[i].style.display = 'block'; showCount++; } else { listEl[i].style.display = 'none'; } break;
										}
									} else {
										nameField.innerHTML = nameValue;
										listEl[i].style.display = 'none';
									}
								}
							}
							break;

		}

		if (showCount) {
			_$ (this.listField.id + '_nothing_to_show').style.display = 'none';
		} else {
			_$ (this.listField.id + '_nothing_to_show').style.display = '';
		}

		this.selectedFilter = show;

	}

	function click (item) {
	//--- Handles list item clicks

		hiddenField = item.getElementsByTagName ('input')[0];

		if (item.className.indexOf (' selected') != -1) {
			item.className = item.className.toString ().replace (' selected', '');
			item.parentNode.className = '';
			this.selectedCount--;
			hiddenField.checked = false;
		} else {
			if (this.parameters['selectOne']) {
				//--- First de-select all that were previously selected
				var items = this.listField.getElementsByTagName ('div');
				for (var i = 0; i < items.length; i++) {
					if (items[i].className.indexOf ('item selected') != -1) {
						items[i].className = items[i].className.toString ().replace (' selected', '');
						items[i].parentNode.className = '';
						this.selectedCount--;
						items[i].getElementsByTagName ('input')[0].checked = false;
					}
				}
			}
			item.className = 'item selected';
			item.parentNode.className = 'li_selected';
			this.selectedCount++;
			hiddenField.checked = true;
		}

		if (this.selectedFilter != 'all') {
			this.filter (this.selectedFilter);
		}

		_$ (this.listField.id + '_view_selected_count').innerHTML = this.selectedCount;

	}

	function saveSelections (element) {
	//--- Saves the selected entries into a text string for submission via form

		var selections = '';
		var values = _$ (element).getElementsByTagName ('input');
		for (var i = 0; i < values.length; i++) {
			if (values[i].checked == true) {
				selections += ',' + values[i].value;
			}
		}
		return selections;

	}

	function setActive () {
	//--- Sets the selected object to active using its element name

		top.lastSelection = this;

	}

	//--- Methods
	this.create = create;
	this.tab = tab;
	this.filter = filter;
	this.click = click;
	this.saveSelections = saveSelections;
	this.setActive = setActive;

	//--- Properties
	this.listField = l;
	this.callBack = u;
	this.parameters = (p ? p : new Array ());
	this.selectedCount = 0;
	this.selectedFilter = 'all';

	//--- Initialise
	this.create ();

	//--- Globals
	top.lastSelection = this;
	if (typeof (top.selectionObjects) == 'undefined') {
		top.selectionObjects = new Array ();
	}
	top.selectionObjects[this.listField.id] = this;

}