<?
global $Query;
global $Session_ID, $Profile_ID;
?>
<!-- Search box component -->
<form action="<? echo ($IncludePath ? ThisURL : '').SRCH; ?>" method="get">
<input type="text" name="Query" value="<? echo stripslashes ($Query); ?>" size="10" /><br /><input type="submit" value="Search" />
<?
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>
[ <a href="<? echo ($IncludePath ? ThisURL: "").SRCH; ?>?Session_ID=<? echo $Session_ID; ?>">Advanced Search</a> ]