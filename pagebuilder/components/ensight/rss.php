<?
include_once ("modules/db-functions.php");
include_once ("modules/content.php");
include_once ("modules/catalog.php");

$Body = RetrieveCacheByURL ($Parameters);

//--- No content
if (!$Body) {
	return "Sorry! The specified content is not available.";
}

$RDOM = ParseXMLStream ($Body);
if (!is_array ($RDOM)) {
	echo "Not a valid XML file"; return;
}
$Text = '';

if ($RDOM['rss']) {

	$ItemCount = ($RDOM['rss']['channel']['item']['_lastTag_'] ? $RDOM['rss']['channel']['item']['_lastTag_'] + 1 : is_array ($RDOM['rss']['channel']['item']));

	for ($i = 0; $i < $ItemCount; $i++) {
		$Item  = ($ItemCount == 1 ? $RDOM['rss']['channel']['item'] : $RDOM['rss']['channel']['item'][$i]);
		$Text .= "<tr valign=\"top\">\n";
		$Text .= "<td>".DefaultBullet."</td><td><a href=\"".$Item['link']['_content_']."\" target=\"_blank\">".$Item['title']['_content_']."</a>".($Item['description']['_content_'] ? "<br />".$Item['description']['_content_']."<br />" : '')."</td>\n";
		$Text .= "</tr>\n";
	}

} else
if ($RDOM['rdf:RDF']) {

	$ItemCount = ($RDOM['rdf:RDF']['item']['_lastTag_'] ? $RDOM['rdf:RDF']['item']['_lastTag_'] + 1 : is_array ($RDOM['rdf:RDF']['item']));

	for ($i = 0; $i < $ItemCount; $i++) {
		$Item  = ($ItemCount == 1 ? $RDOM['rdf:RDF']['item'] : $RDOM['rdf:RDF']['item'][$i]);
		$Text .= "<tr valign=\"top\">\n";
		$Text .= "<td>".DefaultBullet."</td><td><a href=\"".$Item['link']['_content_']."\" target=\"_blank\">".$Item['title']['_content_']."</a>".($Item['description']['_content_'] ? "<br />".$Item['description']['_content_']."<br />" : '')."</td>\n";
		$Text .= "</tr>\n";
	}

} else
if ($RDOM['feed']) {
	//--- Atom

	$ItemCount = ($RDOM['feed']['entry']['_lastTag_'] ? $RDOM['feed']['entry']['_lastTag_'] + 1 : is_array ($RDOM['feed']['entry']));

	for ($i = 0; $i < $ItemCount; $i++) {
		$Item = ($ItemCount == 1 ? $RDOM['feed']['entry'] : $RDOM['feed']['entry'][$i]);
		$Cont = (is_array ($Item['content']) ? $Item['content'] : (is_array ($Item['summary'] ? $Item['summary'] : array ())));
		//--- Find correct link
		$LinkCount = ($Item['link']['_lastTag_'] ? $Item['link']['_lastTag_'] + 1 : is_array ($Item['link']));
		for ($j = 0; $j < $LinkCount; $j++) {
			$Link = ($LinkCount == 1 ? $Item['link'] : $Item['link'][$j]); if ($Link['_attributes_']['rel'] == 'alternate') { $HREF = $Link['_attributes_']['href']; }
		}
		$Text .= "<tr valign=\"top\">\n";
		$Text .= "<td>".DefaultBullet."</td><td><a href=\"".$HREF."\" target=\"_blank\">".($Item['title']['_attributes_']['mode'] == 'base64' ? base64_decode ($Item['title']['_content_']) : $Item['title']['_content_'])."</a>".($Cont['_content_'] ? "<br />".($Cont['_attributes_']['type'] == 'text/plain' ? nl2br (($Cont['_attributes_']['mode'] == 'base64' ? base64_decode ($Cont['_content_']) : $Cont['_content_'])) : ($Cont['_attributes_']['mode'] == 'base64' ? base64_decode ($Cont['_content_']) : $Cont['_content_']))."<br />" : '')."</td>\n";
		$Text .= "</tr>\n";
	}

}
?>
<!-- RSS/RDF rendering component -->
<table cellpadding="2" cellspacing="0" border="0">
<?
echo $Text;
?>
</table>
