<?
global $Mail_ID;

//--- Pick up target
$TargetBy = RetrieveMailTargetingCriteria ($Mail_ID);
if ($TargetBy == 'custom') {
	$Item_ID = $Callback ("mail", RetrieveMailTargetRecipients ($Mail_ID), "target", $Mail_ID);
} else
if ($TargetBy == 'filter') {
	$MFilter = RetrieveMailTargetFilter ($Mail_ID);
	$Item_ID = ($MFilter ? $MFilter['Item_ID'] : USER_PROFILE);
} else
if ($TargetBy == 'segment') {
	$Item_ID = RetrieveMailTargetSegmentDataSource ($Mail_ID);
} else
if ($TargetBy == 'sql') {
	$Item_ID = USER_PROFILE;
}

if ($Item_ID) {
	$Item = RetrieveFusionCachedItem ($Item_ID);
}

if (basename ($_SERVER['PHP_SELF']) == 'sendmail.php') {
?>
<div align="center">This email was forwarded to you by <? echo FriendlyName; ?> at the request of the sender. Please <a href="<? echo ThisURL.ROOT_URL; ?>/click.php?u=pagebuilder/components/ensight/forward_mail.php?Mail_ID=<? echo $Mail_ID; ?>&o=<? echo $Tag['origin']; ?>&r=<? echo $ThisReference_ID; ?>" target="_blank">forward this message to other friends</a> who may be interested in receiving it.</div>
<div align="center" style="font-size: smaller; color: #999999; text-align: center">
You have not been added to our mailing list. To subscribe yourself to receive this newsletter in future, simply <a href="<? echo ThisURL.ROOT_URL."/click.php?u=".($Item_ID ? RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $Item['ItemCode_STRING'], $Tag['device']) : PEM); ?>&o=<? echo $Tag['origin']; ?>&r=<? echo $ThisReference_ID; ?>" target="_blank">click here</a>.<br />
<? echo $Parameters; ?>
</div>
<?
	return;
}

if ($Item_ID) {
	$PEmailTo = RetrieveFormProfileValue (RetrieveFormFieldByType ($Item_ID, 'E'), $Profile_ID, $ThisReference_ID);
} else
if (LoginFlag == 'E') {
	$PProfile = RetrieveProfileDetails ($Profile_ID);
	$PEmailTo = $PProfile['Login_STRING'];
}

?>
<div align="center">You are receiving this newsletter<? echo ($PEmailTo ? " at ".$PEmailTo : ""); ?> as part of your subscription. Please <a href="<? echo ThisURL.ROOT_URL; ?>/click.php?u=pagebuilder/components/ensight/forward_mail.php?Mail_ID=<? echo $Mail_ID; ?>&o=<? echo $Tag['origin']; ?>&r=<? echo $ThisReference_ID; ?>" target="_blank">forward this message to friends</a> who may be interested in receiving it.</div>
<div align="center" style="font-size: smaller; color: #999999; text-align: center">
To change your mail preferences, visit the <a href="<? echo ThisURL.ROOT_URL."/click.php?u=".($Item_ID ? RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $Item['ItemCode_STRING'], $Tag['device']) : PEM); ?>&o=<? echo $Tag['origin']; ?>&r=<? echo $ThisReference_ID; ?>" target="_blank">preference centre</a> on our website. To leave our mailing list, simply <a href="<? echo ThisURL.ROOT_URL."/click.php?u=".($Item_ID ? RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $Item['ItemCode_STRING'], $Tag['device']) : PEM); ?>&o=<? echo $Tag['origin']; ?>&r=<? echo $ThisReference_ID; ?>" target="_blank">click here</a>.<br />
<? echo $Parameters; ?>
</div>