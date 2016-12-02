<?
include_once ("modules/keyword.php");
include_once ("modules/catalog.php");
include_once ("modules/content.php");
include_once ("modules/fusion.php");

function array_search_all ($needle, $haystack, $strict = false) {
//--- Replacement function for array_search that continues to search for the same word until it reaches the end of the array

	$location = array ();
	$i = 0;

	$found = array_search ($needle, $haystack, $strict);

	while ($found !== false) {
		unset ($haystack[$found]);
		$location[$i] = $found;
		$found = array_search ($needle, $haystack, $strict);
		$i++;
	}

	return $location;

}

function FindShortestDistance ($Current, $Previous, $CheckForNegative = false) {
//--- Finds the lowest positive number of words between $Current and $Previous

	$Lowest = 100000;

	for ($i = 0; $i < count ($Current); $i++) {
		for ($j = 0; $j < count ($Previous); $j++) {
			$Lowest = (abs ($Current[$i] - $Previous[$j]) <= abs ($Lowest) ? abs ($Current[$i] - $Previous[$j]) : $Lowest);
			if (($CheckForNegative) && (($Current[$i] - $Previous[$j]) == -1)) {
				$Lowest = -1;
			}
		}
	}

	return ($Lowest != 100000 ? $Lowest : 0);

}

function RankSingleResult ($SearchFor, $Item) {
//--- Calculates the ranking for a single content item

	global $IgnoreWords;

	$j = 0;

	while (($j < count ($SearchFor))) {

		//--- Store temporary value for processing
		$SearchWord = (trim ($SearchFor[$j]));
		//--- Fix single-word quotes...
		$SearchWord = (substr_count ($SearchWord, "\"") > 1 ? str_replace ("\"", "", $SearchWord) : $SearchWord);

		//--- Look for quotes (backwards to support small words)
		if (substr ($SearchWord, -1, 1) == "\"") {
			$StopQuotes = 1;
		} else
		if (($SearchWord[0] == "\"") || ($SearchWord[1] == "\"")) {
			$TestQuotes = 1;
			$StopQuotes = 0;
			$PrevQuotes = 0;
			$WordsInSequence = 0;
			$AllWordsInQuote = 0;
		}

		//--- Look for an operator
		if (($SearchWord[0] == "+")  || ($SearchWord[0] == "-"))  {
			$IsOperator = $SearchFor[$j][0];
		} else
		if ($TestQuotes == 0) {
			$IsOperator = '';
		}

		//--- Clear out special characters
		$SearchWord = str_replace ("\"", "", $SearchWord);
		$SearchWord = str_replace ("+",  "", $SearchWord);
		$SearchWord = str_replace ("-",  "", $SearchWord);

		if ((!$TestQuotes) && (in_array ($SearchWord." \r\n", $IgnoreWords))) {
			$j++; continue;
		}

		//--- Find the word...
		$CurrPos = array_search_all ($SearchWord, $Item);

		//--- Do we need to disqualify any entries?
		if (($IsOperator == '+') && (!$TestQuotes) && (count ($CurrPos) == 0)) {
			return 0;
		} else
		if (($IsOperator == '-') && (!$TestQuotes) && (count ($CurrPos) != 0)) {
			return 0;
		}
		if ($TestQuotes) {
			$WordsInSequence = $WordsInSequence + (is_array ($PrevQuotes) ? (FindShortestDistance ($CurrPos, $PrevQuotes, true) == 1 ? 1 : 0) : 1);
			$AllWordsInQuote = $AllWordsInQuote + 1;
		}

		//--- Carry out post-quote tests...
		if ($StopQuotes) {
			if (($IsOperator == '-') && ($WordsInSequence == $AllWordsInQuote)) {
				return 0;
			} else
			if (($IsOperator == '+') && ($WordsInSequence != $AllWordsInQuote)) {
				return 0;
			} else
			if (($IsOperator == '' ) && ($WordsInSequence != $AllWordsInQuote)) {
				return 0;
			}
			$TestQuotes = 0;
			$StopQuotes = 0;
			$PrevQuotes = 0;
			$WordsInSequence = 0;
			$AllWordsInQuote = 0;
		} else
		if ($TestQuotes) {
			$PrevQuotes = $CurrPos;
		}

		//--- Rank the word if still remaining in context...
		if (count ($CurrPos)) {
			// get 1 point for the existence of the word
			// get a % of a point for proximity (calculated on smallest distance between words)
			// get a % of half a point for being higher up in the content
			if ($IsOperator == '-') {
				// ignore
			} else {
				$Lowest = FindShortestDistance ($CurrPos, $PrevPos);
				$ResultSet += 1 * RANK_TERM_EXISTS;
				$ResultSet += (isset ($PrevPos) ? (1 / ($Lowest + 1)) : 0) * RANK_TERM_PROXIMITY;
				$ResultSet += (1 / ($CurrPos[0] + 1)) * RANK_TERM_POSITION;
				$PrevPos = $CurrPos;
			}
		}

		$j++;

	}

	return $ResultSet;

}

function HighlightResult ($Text, $Word) {
//--- Highlights every occurance of $Word in $Text

	return preg_replace ("/([.,:;@!?\"()>< ]+)(".$Word.")([.,:;@!?\"()>< ]+)/i", "\\1<b>\\2</b>\\3", $Text);

}

//--- Define ranking algorithm settings
define ("RANK_TERM_PROXIMITY", 1.0);		// how important is proximity
define ("RANK_TERM_EXISTS", 1.0);			// how important is it that the term exists
define ("RANK_TERM_POSITION", 0.5);			// how important is the relative position of the word
define ("SUMMARY_LENGTH", 300);				// how long should the summary text be

global $Session_ID, $Profile_ID;
global $Query, $SearchStart, $ShowResults, $LimitCategory;
global $IgnoreWords;
global $_PAGE_TITLE;

$_PAGE_TITLE = "Search for '".htmlspecialchars ($Query)."'";

?>
<style>
.pageBar {
	margin: 10px 0px 5px 0px;
}
.pageBar .prevPage, .pageBar .nextPage, .pageBar .linkPage {
	border: 1px solid #000000; padding: 5px;
}
.pageBar .thisPage {
	border: 1px solid #000000; padding: 5px; background-color: #000000; color: #ffffff;
}
</style>

<form action="<? echo SRCH; ?>" method="get" style="display: inline">

<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><b>Search for:</b>&nbsp;</td>
	<td><input type="text" name="Query" value="<? echo htmlspecialchars (stripslashes ($Query)); ?>" size="40" /></td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td align="right"><b>Display:</b></td>
	<td>&nbsp;</td>
	<td>
	<select name="ShowResults" onchange="this.form.submit ();">
	<?
	for ($i = 1; $i <= 10; $i++) {
		echo RetrieveDropDownOption ($i * 10, $ShowResults, "- ".($i * 10)." -");
	}
	?>
	</select>&nbsp;
	</td>
	<td>results per page</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td><input type="submit" value="Search" /></td>
</tr>
</table><br />

<?
PrintHiddenField ("Session_ID", $Session_ID);
PrintHiddenField ("LimitCategory", $LimitCategory);
PrintHiddenField ("SearchStart", ""); //--- in case the user resets the display per page
?>
</form>

<?

if (!$Query) {
	echo "<p>Please enter your search criteria in the box above.</p>\n"; return;
}

$STime = microtime (true);

CaptureSearchTerm ($Profile_ID, $Query, RetrieveSessionExpiry ($Session_ID));

if (!$ShowResults) {
	$ShowResults = 10;
}

$IgnoreWords = RetrieveWordList (MODULE_FILES."/dictionaries/noisewords.txt");

$Query = preg_replace ("/[[:punct:]*]/", " ", stripslashes ($Query));

$FindWords = preg_split ("/[ ]+/", trim (strtolower (RetrieveKeywords ($Query))));
$SearchFor = preg_split ("/[ ]+/", trim (strtolower ($Query)));

for ($i = 0; $i < count ($FindWords); $i++) {
	$Add[] = addslashes ($FindWords[$i]);
}

$Items = array ();

$SQL = "SELECT Content_Index.Item_ID, Content_Index.Revision_STRING, Content_Index.Page_NUM, Content_Index.Word_STRING, Content_Index.Position_NUM FROM Catalog, Content_Index, Content WHERE Catalog.Item_ID = Content_Index.Item_ID AND Content_Index.Item_ID = Content.Item_ID AND Content_Index.Revision_STRING = ".ConcatQueryDB (ConcatQueryDB ("Content.ContentLanguage_STRING", "'/'"), "Content.ContentVersion_NUM")." AND Catalog.ItemStatus_NUM = '".STATUS_VISIBLE."' AND Content.Status_NUM = '".STATUS_PUBLISHED."'".($LimitCategory ? " AND Catalog.Category_ID IN (".RetrieveCatalogCategoriesIn ($LimitCategory, None, None).")" : "")." AND Content_Index.Word_STRING IN ('".implode ('\', \'', $Add)."')";
$XQuery = QueryDB ($SQL);
while ($Result = ReadFromDB ($XQuery)) {
	$Items[$Result['Item_ID'].":".$Result['Revision_STRING'].":".$Result['Page_NUM']][$Result['Position_NUM']] = $Result['Word_STRING'];
}

//--- Calculate the number of words that appear in content
$ResultSet = array ();

while (list ($Key, $Item) = each ($Items)) {

	$InclExcl = RankSingleResult ($SearchFor, $Item); // get score
	if ($InclExcl > 0) {
		$ResultSet[$Key] = $InclExcl;
	}

}

$NumResults = count ($ResultSet); arsort ($ResultSet);
$MaxScore = current ($ResultSet); reset  ($ResultSet);

if (!$SearchStart) {
	$SearchStart = 0;
}

$PathsCache = RetrieveCatalogCategoryPaths (2, None, None, 4, CPE, $Session_ID); // not used on-site
$Range = RetrieveCatalogPageRange ($SearchStart, $NumResults, $ShowResults);

for ($i = 0; $i < count ($FindWords); $i++) {
	if (trim (str_replace ($FindWords[$i], $FindWords[$i], $SearchFor[$i])) != '') {
		$LinkedWords[$i] = str_replace ($FindWords[$i], (in_array ($FindWords[$i]." \r\n", $IgnoreWords) ? "<b>".htmlspecialchars ($FindWords[$i])."</b>" : "<b><a href=\"".SRCH."?Query=".htmlspecialchars ($FindWords[$i])."&Session_ID=".$Session_ID."\">".htmlspecialchars ($FindWords[$i])."</a></b>"), htmlspecialchars ($SearchFor[$i]));
	}
}

$ResultSet = array_slice ($ResultSet, $Range[0] - 1, $ShowResults);
$RXCounter = $SearchStart;
?>

<div style="background-color: #cccccc; padding: 5px"><b>Displaying <? echo $Range[0]; ?> - <? echo $Range[1]; ?> of <? echo $NumResults; ?> result(s) for <? echo implode (' ', $LinkedWords); ?></b></div><br />

<?
if ($NumResults == 0) {
	echo "<p>No results found.</p><b>Not finding what you're looking for?</b><br />Perhaps check your spelling, try more generic keywords, or modify your search terms to use one of the following features:<ul><li>Quotes around phrases will ensure that only results with the entire phrase are returned.</li><li>Use the inclusion (+) character before a word or quote to find only results where the word or phrase exists.</li><li>Use the exclusion (-) character before a word or quote to find only results where the word or phrase does not exist.</li></ul>"; return;
}

while (list ($Item, $Score) = each ($ResultSet)) {

	$RXCounter++;

	list ($Item_ID, $Revision, $PageNumber) = explode (':', $Item);
	list ($Language, $Version) = explode ('/', $Revision);
	$ItemDetails = RetrieveCatalogItemByItemID ($Item_ID);
	$Summary = ReadFromDB (RetrieveContentPages ($Item_ID, None, $Language, None, $PageNumber, STATUS_PUBLISHED, None, None, None, None));
	$SummaryHead = stripslashes ($Summary['FullTitle_STRING']);
	$SummaryBody = stripslashes ($Summary['Full_BLOB']);
	$SummaryBody = RemoveHTMLTags ($SummaryBody);
	$SummaryBody = str_replace ("No Image", "", $SummaryBody);
	$SummaryBody = RemoveWhitespace ($SummaryBody);
	$SummaryBody = (trim ($SummaryBody) != '' ? $SummaryBody : "No summary information has been supplied.");
	$SummaryKwds = RetrieveKeywords ($SummaryBody);

	//--- Find the beginning of the sentence featuring the first phrase found
	$FoundFirst = false;
	$FirstWords = strpos (strtolower (' '.$SummaryKwds.' '), strtolower (' '.implode (' ', $Add).' '));
	if ($FirstWords) {
		$FoundFirst = $FirstWords;
	}

	//--- If not found, find the beginning of the sentence featuring any word
	$Look4Words = 0;
	while (($Look4Words < count ($FindWords)) && (!$FoundFirst)) {
		$FirstWords = strpos (strtolower (' '.$SummaryKwds.' '), strtolower (' '.$FindWords[$Look4Words].' '));
		if ($FirstWords) {
			$FoundFirst = $FirstWords;
		}
		$Look4Words++;
	}

	//--- Find the period before the sentence started
	if ($FoundFirst) {
		$LastPeriod = strrpos (substr ($SummaryBody, 0, $FoundFirst), '. ');
		if ($LastPeriod !== false) {
			$LastPeriod += 1;
		}
		if ($FoundFirst - $LastPeriod + strlen ($Query) > SUMMARY_LENGTH) {
			$LastPeriod = strpos ($SummaryBody, ' ', $FoundFirst - floor (SUMMARY_LENGTH / 2));
			$Subsection = 1;
		} else {
			$Subsection = 0;
		}
	} else {
		$LastPeriod = 0;
		$Subsection = 0;
	}

	if (($LastPeriod + SUMMARY_LENGTH) > strlen ($SummaryBody)) {
		$SummaryBody = ($Subsection ? "... " : "").substr ($SummaryBody, $LastPeriod);
	} else {
		$SummaryBody = substr ($SummaryBody, $LastPeriod, SUMMARY_LENGTH);
		$SummaryBody = substr ($SummaryBody, 0, strrpos ($SummaryBody, ' '))." ...";
		$SummaryBody = ($Subsection ? "... " : "").$SummaryBody;
	}

	$SummaryHead = ' '.$SummaryHead.' ';
	$SummaryBody = ' '.$SummaryBody.' ';

	for ($k = 0; $k < count ($FindWords); $k++) {
		$SummaryHead = HighlightResult ($SummaryHead, $FindWords[$k]);
		$SummaryBody = HighlightResult ($SummaryBody, $FindWords[$k]);
	}

	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td><b><a href=\"".($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $ItemDetails['ItemCode_STRING']).($Language != DefaultLanguage ? "&Revision=".$Revision : "").($PageNumber != 1 ? "&Start=".($PageNumber - 1) : "").(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."\">".trim ($SummaryHead)."</a></b><br>".number_format ($Score / $MaxScore * 100)."% - ".nl2br ($SummaryBody)."<br /><br /></td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}

?>

<div align="center" class="pageBar">
Pages <? echo RetrieveCatalogSmartNumbers (FixQueryString (SRCH."&Query=".urlencode ($Query)."&ShowResults=".$ShowResults), None, None, $SearchStart, $NumResults, $ShowResults, 5, " ", "SearchStart", ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false)); ?>
</div>

<?
$ETime = microtime (true);
/*
echo "<p>Search took ".number_format (($ETime - $STime), 2)." seconds</p>";
*/
?>