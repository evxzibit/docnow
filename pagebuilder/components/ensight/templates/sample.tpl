<!--
Tag format:
	#(unique_identifier).attribute
Where:
	"unique_identifier" is the manually-assigned or auto-assigned field ID (case sensitive)
	"attribute" is one of:
		.element   - the field itself
		.caption   - the question
		.mandatory - a marker indicating whether the field is mandatory
		.help      - optional help message
Example:
	#(first_name).element
Rules:
	- Tags are not variables, although they're designed to look like them
	- No spaces are allowed within the tag
	- Quotes may not appear around the unique_identifier
	- The tag must always be preceded by a # sign
	- The tag must never appear inside <script>...</script> tags or inline scriping
	- Rather use document.getElementById ('first_name') or #first_name to reference it via script or CSS
Additional tags:
	{*login*} - the user's login
	{*username*} - the user's user name
	{*profile*} - the user's profile
	{*session*} - the user's session
	{*form*} - a pointer to the form object
	{*captcha*} - display a CAPTCHA test
-->
<table>
<!-- Example 1 -->
<tr valign="top">
	<td>Enter your comments:</td>
	<td>#(response).element</td>
	<td><img src="/live/pagebuilder/components/ensight/images/help.gif" width="15" height="16" title="#(response).help" /></td>
</tr>
<!-- Example 2 -->
<tr valign="top">
	<td><b>#(response_status).caption</b><br />#(response_status).help</td>
	<td colspan="2">#(response_status).element</td>
</tr>
</table>

<p>
Enter the 6-letter code shown:<br />
{*captcha*}
</p>

<input type="submit" name="SubmitButton" value="Submit" />

<!-- Example validation -->
<script>
document.getElementById ('response_status').onchange = function () { if (this.options[this.selectedIndex].text == 'Responded') { alert ('Thanks'); } }
</script>