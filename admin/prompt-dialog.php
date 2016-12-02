<?
if (!defined ("DB_INCLUDED"))           { include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED"))      { include_once ("../modules/connect.php"); }
if (!defined ("UTILS_INCLUDED"))        { include_once ("../modules/utils.php"); }

$IsMacintosh = (strpos ($_SERVER['HTTP_USER_AGENT'], "Macintosh") !== false);
$Fixed_Width = ($IsMacintosh ? 49 : 47);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
        <title><? echo ($Title ? stripslashes ($Title) : "Ensight"); ?> - Prompt</title>
        <script type="text/javascript" language="JavaScript">document.write ("<link rel=\"stylesheet\" type=\"text/css\" href=\"estyle.css\" media=\"screen\" />");</script>
        <script type="text/javascript" language="JavaScript">document.write ("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + (navigator.userAgent.indexOf ('MSIE') != -1 ? "estyle-ie.css" : (navigator.userAgent.toLowerCase ().indexOf ('chrome') != -1 ? "estyle-ch.css" : "estyle-ff.css")) + "\" media=\"screen\" />");</script>
        <script type="text/javascript" language="JavaScript">document.write ((document.all ? "<meta http-equiv=\"MSThemeCompatible\" content=\"No\" />" : ""));</script>
</head>

<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" marginwidth="0" marginheight="0" onload="document.forms[0].elements[0].select (); document.forms[0].elements[0].focus ();">

<script>
top.modalWindows.attachKeyHandler (document); // assign ESCape key
</script>

<form onsubmit="top.lastModalWindow.windowArguments[1] = this.Response.value; top.modalWindows.closeWindow (); return false">

<table border="0" cellpadding="7" cellspacing="1" bgcolor="#F9F8F7" width="100%" height="100%">
<tr valign="top">
        <td>
        <span style="font-family: Microsoft Sans Serif, Arial, Verdana; font-size: 11px">
        <? echo stripslashes ($Question); ?><br />
        <input type="text" name="Response" size="<? echo $Fixed_Width; ?>" value="<? echo str_replace ("\"", "&quot;", stripslashes ($Default)); ?>" />&nbsp;
        <input type="submit" value="Okay" />&nbsp;<input type="button" value="Cancel" onclick="top.modalWindows.closeWindow (); return false" />
        <p>Enter a value in the space above, and then press Okay to confirm or Cancel to return to the previous window.</p>
        </span>
        </td>
</tr>
</table>

</form>

</body>

