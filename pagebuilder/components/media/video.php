<?
global $_VideoObjects;

list ($UploadFile, $Width, $Height, $AutoPlay, $AutoBuffer, $BufferTime) = explode ("|", $Parameters);

if (!$UploadFile) {
	return;
}

$W = ($Width ? $Width : "520");
$H = ($Height ? $Height : "330");
$AutoPlay = ($AutoPlay == 'true' ? "true" : "false");
$AutoBuffer = ($AutoBuffer == 'true' ? "true" : "false");
$BufferTime = ($BufferTime == 0 ? '0' : ($BufferTime ? $BufferTime : "15"));
$RandomN = rand (0, 100000);
?>
<!-- Flash display component -->
<?
if ($_VideoObjects == false) {
	$_VideoObjects = 0;
?>
<script type="text/javascript" src="<? echo WWW_ROOT; ?>/live/pagebuilder/components/media/flowplayer-3.1.2.min.js"></script>
<script type="text/javascript" src="<? echo WWW_ROOT; ?>/live/pagebuilder/components/media/flowplayer.embed-3.0.2.min.js"></script>
<?
} // end if
?>

<a href="<? echo ($IncludePath ? ThisURL : "").CONTENT_URL."/".$UploadFile; ?>" style="display:block; width:<? echo $W; ?>px; height:<? echo $H; ?>px" id="player<? echo $RandomN; ?>"></a>
<script>
	$f("player<? echo $RandomN; ?>", "<? echo WWW_ROOT; ?>/live/pagebuilder/components/media/flowplayer-3.1.2.swf", {
		clip: {
			autoPlay: <? echo $AutoPlay; ?>, 
			autoBuffering: <? echo $AutoBuffer; ?>, 
			bufferLength: <? echo $BufferTime; ?>
		}
	});
</script>
<?
$_VideoObjects++;
?>