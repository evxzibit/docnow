<?
$URL = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$Title = "||TITLE||";
$Description = "||DESCRIPTION||";
$TwitterUser = "ensight";
$LinkToDiv = "shareContainer";
//$SpaceOnLeft = "240";
$SpaceOnLeft = "0";

global $Item_ID, $Category_ID;


//if($Item_ID == 6 || $Item_ID == 551) {
  $social = 2;
//}

$Pages = RetrieveContentPages ($Item_ID, None, DefaultLanguage, None, 1, STATUS_PUBLISHED, None, None, None, None);
$ItemDetails = ReadFromDB ($Pages);

if($ItemDetails['Teaser_PIC']){

     $image = ThisURL."/content/".$ItemDetails['Teaser_PIC'];
}else{

     $image = ThisURL."/live/images/logo-fb.jpg";

}

if ($social != 1){
?>
<meta property="og:url" content="<?php echo $URL;?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $Title; ?>" />
<meta property="og:description" content="<?php echo $Description; ?>" />
<meta property="og:image" content="<?php echo $image;?>" />
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1705472673046728";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<link rel="stylesheet" href="/live/pagebuilder/components/shared/social.css?v=1" />
<script type="text/javascript" src="/live/pagebuilder/components/shared/ilib.js"></script>
<script type="text/javascript" src="/live/pagebuilder/components/shared/social.js"></script>
<script type="text/javascript" src="/live/js/jquery-latest.js"></script>
<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<ul id="social-widgets-small" class="social-widgets-small">
	<li class="twitter-widget" style="list-style-type:none;"><a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-counturl="<? echo $URL; ?>" data-lang="en" data-related="<? echo $TwitterUser; ?>" data-text="<? echo $Title; ?>" data-url="<? echo $URL; ?>" data-via="<? echo $TwitterUser; ?>">Tweet</a></li>
	<li class="plusone-widget" style="list-style-type:none;"><g:plusone size="medium"></g:plusone></li>
	<li class="facebook-widget" style="list-style-type:none;"><div class="fb-like" data-href="<? echo $URL; ?>" data-width="100" data-layout="button_count" data-show-faces="false" data-send="false"></div></li>
	<li class="facebook-sh-widget" style="list-style-type:none;"><div class="fb-share-button" data-href="<? echo $URL; ?>" data-width="220" data-type="button_count"></div></li>
	<li class="linkedin-widget" style="list-style-type:none;"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/Share" data-counter="right"></script></li>
</ul>
<script>
_onBodyReady (function () {
	_jsLibLoader (["ilayout.js"], function () { loadSocialWidget (_$ ('<? echo $LinkToDiv; ?>'), <? echo ($SpaceOnLeft ? $SpaceOnLeft : '0'); ?>); });
});
</script>
<? }?>
