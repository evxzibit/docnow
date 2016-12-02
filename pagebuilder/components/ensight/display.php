<?
//--- ENVENT Module Source Code (EMSC)
//--- First Created on Saturday, April 14 2001 by John Ginsberg
//--- For ENVENT.co.za

//--- Module Name: Miscellaneous Display Functions (display.php)
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Provides functions for admin display

//--- Permissions:
//--- 

//----------------------------------------------------------------

//--- Definitions
define ("NO_BREAK", 0);
define ("DO_BREAK", 1);

function RetrieveIFrame ($Name, $Headings, $Body) {
//--- Takes a heading and a result set and wraps it in a IFRAME component and returns the HTML

	if ($Name == None) {
		$Name = "myFrame";
	}

	$HTML  = $Headings."\n";
	$HTML .= "<IFRAME stats=\"0\" name=\"$Name\" frameborder=\"1\" width=\"600\" height=\"400\" src=\"\" scrolling=\"yes\"></IFRAME>\n";

	$HTML .= "<SCRIPT>\n";
	$HTML .= "<!--\n";
	$HTML .= "	this.$Name.document.open ();\n";
	$HTML .= "	this.$Name.document.write ('$Body');\n";
	$HTML .= "	this.$Name.document.close ();\n";
	$HTML .= "//-->\n";
	$HTML .= "</SCRIPT>\n";

	return $HTML;

}

function RetrieveIFrameXY ($Name, $Headings, $Body, $X, $Y) {
//--- Takes a heading and a result set and wraps it in a IFRAME component and returns the HTML

	if ($Name == None) {
		$Name = "myFrame";
	}

	$HTML  = $Headings."\n";
	$HTML .= "<IFRAME stats=\"0\" name=\"$Name\" frameborder=\"1\" width=\"$X\" height=\"$Y\" src=\"\" scrolling=\"yes\"></IFRAME>\n";

	$HTML .= "<SCRIPT>\n";
	$HTML .= "<!--\n";
	$HTML .= "	this.$Name.document.open ();\n";
	$HTML .= "	this.$Name.document.write ('$Body');\n";
	$HTML .= "	this.$Name.document.close ();\n";
	$HTML .= "//-->\n";
	$HTML .= "</SCRIPT>\n";

	return $HTML;

}

function RetrieveIFrameURL ($Name, $URL, $X, $Y) {
//--- Takes a heading and a result set and wraps it in a IFRAME component and returns the HTML

	if ($Name == None) {
		$Name = "myFrame";
	}

	$HTML  = "<IFRAME stats=\"0\" name=\"$Name\" frameborder=\"1\" width=\"$X\" height=\"$Y\" src=\"$URL\" scrolling=\"yes\"></IFRAME>\n";
	return $HTML;

}

function PrintHorizontalRule () {
//--- Prints a custom horizontal rule
?>

<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
<TR>
	<TD background="images/eb2.gif"><IMG src="images/eb2.gif" width="7" height="26" align="middle" border="0"></TD>
</TR>
</TABLE>

<?
} // end function

function PrintComments ($Text, $Image, $BreakBefore, $BreakAfter) {
//--- Prints formatted text below a horizontal rule
?>

<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
<?
if ($BreakBefore) {
?>
<TR>
	<TD width="4"><IMG src="images/WhiteSpacer.gif" width="4" height="10"></TD>
	<TD>
	<P>&nbsp;</P>
	</TD>
</TR>
<?
} // end if
?>
<TR>
	<TD width="4"><IMG src="images/WhiteSpacer.gif" width="4" height="10"></TD>
	<TD>
	<?
	if (!$Image) {
		echo $Text."\n";
	} else {
		echo "<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><TR valign=\"middle\"><TD><IMG src=\"$Image\"></TD><TD>$Text</TD></TR></TABLE>\n";
	}
	?>
	</TD>
</TR>
<?
if ($BreakAfter) {
?>
<TR>
	<TD width="4"><IMG src="images/WhiteSpacer.gif" width="4" height="10"></TD>
	<TD>
	<P>&nbsp;</P>
	</TD>
</TR>
<?
} // end if
?>
</TABLE>

<?
} // end function

function PrintHelpBox ($Title, $Text) {
//--- Prints the standard help box on the right hand side
?>

<DIV id="helpbox" align="right">

<TABLE width="151" border="0" cellspacing="0" cellpadding="0" align="right">
<TR>
	<TD width="151"><IMG src="images/WhiteSpacer.gif" width="151" height="10"></TD>
</TR>
<TR> 
	<TD width="151" height="39" background="images/help1.gif"> 

	<TABLE border="0" width="151" cellspacing="0" cellpadding="0">
	<TR>
		<TD width="5">&nbsp;</TD>
		<TD><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"><B><FONT size="2"><? echo $Title; ?></FONT></B></FONT></TD>
	</TR>
	</TABLE>

	</TD>
</TR>
<TR>
	<TD background="images/help1back.gif" valign="top">

	<TABLE width="146" border="0" cellspacing="0" cellpadding="0">
	<TR valign="top"> 
		<TD width="12">&nbsp;</TD>
		<TD><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#666666"><? echo $Text; ?></FONT></TD>
	</TR>
	<TR valign="top"> 
		<TD width="12">&nbsp;</TD>
	</TR>
	</TABLE>

	</TD>
</TR>
<TR>
	<TD valign="top"><IMG src="images/help1bot.gif" width="151" height="54"></TD>
</TR>
</TABLE>

</DIV>

<?
} // end function

//----------------------------------------------------------------
//--- Deprecated functions
//----------------------------------------------------------------

function PrintNewTable ($Width) {
//--- Prints an opening table tag -- deprecated

	echo "<TABLE BORDER=1 BORDERCOLOR=\"#C0C0C0\" CELLPADDING=2 CELLSPACING=2";
	if ($Width != None) {
		echo " WIDTH=\"$Width\"";
	}
	echo ">\n";

}

function PrintEndTable () {
//--- Prints a closing table tag -- deprecated

	echo "</TABLE><BR>\n";

}

function PrintGroupHeading ($Heading, $BGColor, $Colspan) {
//--- Prints a form header -- deprecated

	echo "<TR BGCOLOR=\"$BGColor\">\n";
	echo "<TD COLSPAN=$Colspan><FONT FACE=\"Verdana, Arial, Helvetica\" SIZE=-1>$Heading</FONT></TD>\n";
	echo "</TR>\n";

}
?>
