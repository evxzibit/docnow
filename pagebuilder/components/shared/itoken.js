//--- Ensight Source Code (ESC)
//--- First Created on Monday, May 3 2010 by John Ginsberg (in the lounge)
//--- For Ensight 4

//--- Module Name: itoken.js
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Tokenized text entry

//--- Notice:
//--- This code is copyright (C) 2010, Ensight Technologies Ltd. All rights
//--- are reserved. Using this code in any shape or form requires permission
//--- from Ensight Technologies Ltd - http://www.getensight.com

//----------------------------------------------------------------

//------------------------------------------------------------------------------------
//--- Initialise the tokenizer object
//------------------------------------------------------------------------------------

/**
 * Object tokenizer
 * @f obj		:the originating select field
 * @u string	:the callback url that supplies XML results
 * @p array		:additional parameters
 */
function tokenizer (f, u, p) {

	function create () {
	//--- Creates the elements required for a tokenizer

		newUL = document.createElement ('UL');
		newUL.id = this.holderList;
		newUL.className = 'tokenHolder';

		//--- Overwrite default 500px width
		if (this.formField.style.width) {
			newUL.style.width = parseInt (this.formField.style.width) + 'px';
		}

		//--- For each existing option, create a new list element
		for (i = 0; i < this.formField.options.length; i++) {
			newLI = document.createElement ('LI');
			newLI.className = 'box';
			newLI.innerHTML = "<span>" + this.formField.options[i].text + "</span><a class=\"closebutton\" onclick=\"top.lastTokenizer.removeEntry (this.parentNode); return false\">&nbsp;</a>";
			newUL.appendChild (newLI);
			this.currentSelection += this.formField.options[i].value + (i < this.formField.options.length - 1 ? ',' : '');
		}

		newLI = document.createElement ('LI');
		newLI.id = this.cloneEntry;
		newLI.className = 'box';
		newLI.style.display = 'none';
		newLI.innerHTML = "<span></span><a class=\"closebutton\" onclick=\"top.lastTokenizer.removeEntry (this.parentNode); return false\">&nbsp;</a>";
		newUL.appendChild (newLI);

		newLI = document.createElement ('LI');
		newLI.className = "box-input";
		newLI.innerHTML = "<input size=\"1\" name=\"" + this.inputField + "\" id=\"" + this.inputField + "\" value=\"\" autocomplete=\"off\" onkeydown=\"top.lastTokenizer.suggestionLayer.keyDown (event); return top.lastTokenizer.keyDown (event)\" onkeyup=\"top.lastTokenizer.suggestionLayer.lookupWord (event)\" onfocus=\"if (top.suggestionsTimeout != null) { clearTimeout (top.suggestionsTimeout); } top.tokenizerInstances[" + this.tokenInstance + "].setActive (); top.lastTokenizer.suggestionLayer.setActive (); top.lastTokenizer.suggestionLayer.showSuggestionMessage ('" + this.holderList + "', 'Start typing...')\" onblur=\"top.suggestionsTimeout = setTimeout ('top.lastTokenizer.suggestionLayer.hideSuggestions ()', 500)\" />";
		newUL.appendChild (newLI);

		this.formField.style.display = 'none';

		newUL = this.formField.parentNode.insertBefore (newUL, this.formField);
		newUL.onclick = function () { _$ (this.id.replace ('_tokens', '_tokens_input')).focus (); }

		//--- Create suggestion list object (using isuggest.js)
		this.suggestionLayer = new suggestionList (_$ (this.inputField), this.callBack.replace (/{field}/g, this.holderList).replace (/{selection}/g, 2), 1, {'topTip': false, 'shadow': false, 'customStyle': 'border: 1px solid #999; border-top: 0px; background-color: #ffffff;' + (this.parameters ? ' ' + this.parameters : '')});

	}

	function keyDown (e) {
	//--- Handles key presses (this must be called after the suggestionLayer's keyDown event)

		var key = (e.which ? e.which : e.keyCode);

		if ((key != 13) && (key != 9)) {
			_$ (this.inputField).size = (_$ (this.inputField).value.length ? _$ (this.inputField).value.length + 2 : 1);
		}

		if ((key == 13)) {
			return false;
		}

		previous = _findPrevSibling (_findPrevSibling (_$ (this.inputField).parentNode));
		if (previous == null) {
			return;
		}

		if ((key == 8) && (_$ (this.inputField).value == '')) {
			if (previous.className.indexOf ('box-focus') == -1) {
				previous.className = 'box box-focus'; this.suggestionLayer.hideSuggestions (); return false;
			} else {
				this.removeEntry (previous); return false;
			}
		} else {
			if (previous.className.indexOf ('box-focus') != -1) {
				previous.className = 'box';
			}
		}

	}

	function click (element, text, value) {
	//--- handles the selection (called by isuggest.js after an ajax request)

		hiddenNode = _findPrevSibling (_$ (element).parentNode);

		newLI = _cloneChild (hiddenNode, 'above');
		newLI.firstChild.innerHTML = text;
		_$ (element).value = '';

		var found = false;
		var selectField = this.formField;

		//--- Replace in original select field
		for (i = 0; i < selectField.options.length; i++) {
			if (selectField.options[i].value == value) {
				found = true;
			}
		}

		if (!found) {
			selectField.options[selectField.options.length] = new Option (text, value);
		}

		//--- Remove this item from the ajax callback
		this.replaceCallBack ();

	}

	function removeEntry (whichObject) {
	//--- Removes a node from the unordered list and select list

		var selectField = this.formField;
		var selectValue = whichObject.firstChild.innerHTML;

		//--- Replace in original select field
		for (i = 0; i < selectField.options.length; i++) {
			if (selectField.options[i].text == selectValue) {
				selectField.options[i] = null;
			}
		}

		//--- Remove the list item
		whichObject.parentNode.removeChild (whichObject);

		//--- Remove this item from the ajax callback
		this.replaceCallBack ();

		_$ (this.inputField).value = '';
		_$ (this.inputField).focus ();

	}

	function replaceCallBack () {
	//--- Replaces the callback with an updated list

		this.currentSelection = '';

		//--- For each existing option, create a new list element
		for (i = 0; i < this.formField.options.length; i++) {
			this.currentSelection += this.formField.options[i].value + (i < this.formField.options.length - 1 ? ',' : '');
		}

		this.suggestionLayer.callBack = this.callBack.replace (/{field}/g, this.holderList).replace (/{selection}/g, this.currentSelection);

	}

	function setActive () {
	//--- Sets the selected object to active

		top.lastTokenizer = this;

	}

	function prepareSubmit () {
	//--- Prepares the selection field for a form submit

		for (var i = 0; i < this.formField.options.length; i++) {
			this.formField.options[i].selected = true;
		}

		//--- Prevent submit of temp field
		document.getElementById (this.inputField).disabled = true;

	}

	//--- Methods
	this.create = create;
	this.keyDown = keyDown;
	this.click = click;
	this.removeEntry = removeEntry;
	this.replaceCallBack = replaceCallBack;
	this.setActive = setActive;
	this.prepareSubmit = prepareSubmit;

	//--- Properties
	this.formField = f;
	this.callBack = u;
	this.parameters = p;
	this.suggestionLayer = null;
	this.holderList = f.id + '_tokens';
	this.cloneEntry = f.id + '_tokens_clone';
	this.inputField = f.id + '_tokens_input';
	this.currentSelection = '';
	this.tokenInstance = top.tokenizerInstances.length;

	//--- Initialise
	this.create ();

	//--- Globals
	top.lastTokenizer = this;
	top.suggestionsTimeout = null;
	top.tokenizerInstances[this.tokenInstance] = this;

}

top.tokenizerInstances = new Array ();