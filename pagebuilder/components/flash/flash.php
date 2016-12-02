<?
//--- Get file parameters
if ($Parameters) {
	list ($UploadFile, $Width, $Height, $Quality, $BGColor) = explode ('|', $Parameters);
}

if (!$UploadFile) {
	return;
}

$Quality = ($Quality ? $Quality : "high");
$BGColor = ($BGColor ? $BGColor : "#ffffff");
?>
<!-- Flash display component -->
<script type="text/javascript" src="<? echo WWW_ROOT; ?>/live/pagebuilder/components/flash/flashobject.js"></script>
<div id="display_flash">
	<script type="text/javascript">
		var fo = new FlashObject("<? echo ($IncludePath ? ThisURL : "").CONTENT_URL."/".$UploadFile; ?>", "display_flash", "<? echo $Width; ?>", "<? echo $Height; ?>", "8");
		fo.addParam("wmode", "transparent");
		fo.addParam("base", "./");
		fo.addParam("allowScriptAccess", "sameDomain");
		fo.addParam("quality", "<? echo $Quality; ?>");
		fo.addParam("bgcolor", "<? echo $BGColor; ?>");
		fo.write("display_flash");
		var versionOkay = fo.installedVer.versionIsValid (fo.getAttribute ("version"));
	</script>
</div>