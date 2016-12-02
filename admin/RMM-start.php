<?
//--- ENVENT Module Source Code (EMSC)
//--- First Created on Saturday, September 22 2007 by John Ginsberg
//--- For Ensight

//--- Module Name: RMM-start.php
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Provides an interface into the available reports

//--- Permissions:
//--- ALLOW_REPORT_VIEW

//----------------------------------------------------------------

if (!defined ("DB_INCLUDED")) 			{ include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED")) 		{ include_once ("../modules/connect.php"); }
if (!defined ("PROFILE_INCLUDED")) 		{ include_once ("../modules/profile.php"); }
if (!defined ("SESSION_INCLUDED")) 		{ include_once ("../modules/session.php"); }
if (!defined ("CATALOG_INCLUDED")) 		{ include_once ("../modules/catalog.php"); }

//--- Get the user's session ID
if (($Session_ID) && (!$Profile_ID)) {
	$Profile_ID = LocateSession ($Session_ID);
}

if (!$Profile_ID) {
	Redirect ("index.php?Dest_URL=main.html?Message=".urlencode ("You have been logged out. Please login again to continue using this system"));
}

//--- Check permissions
$Allowed = CheckPermission ($Profile_ID, GROUP_DEFAULT, None, ALLOW_REPORT_VIEW, ACCESS_ON);
if (!$Allowed) {
	Redirect ("access-denied.php?Session_ID=$Session_ID&Src_URL=".GetVar ("HTTP_REFERER"));
}

//--- Set header custom definitions
$SubTitle = "Reporting Center";
$HasFocus = "";
$HeadScript = "";
$LoadScript = "";
$NavBar = array ("Reporting Center", true);

//--- Include display functions
include_once (ADMIN_FILES."/display.php");
include_once (ADMIN_FILES."/header.php");
?>

<?
PrintComments ("<p align=\"justify\"><big>Reporting Center</big><br />The following is a list of standard and custom reports configured within Ensight. Click on a link to browse further.</p>", "", DO_BREAK, NO_BREAK);
?>
<form action="RMM-start.php" method="post" style="display: inline">

<br />
<center>
<table border="0" cellpadding="0" cellspacing="0" width="872">
<tr valign="top">
	<td style="width: 428px">

	<table border="0" cellpadding="0" cellspacing="1" width="100%" style="background-color: #8a89a6">
	<tr>
		<td style="background-color: white">

		<table border="0" cellpadding="5" cellspacing="5" width="100%" height="140">
		<tr valign="top">
			<td><img src="images/report-1.png" vspace="2" width="32" height="32" align="left" /></td>
			<td>
			<p><big class="grayHeader">User Statistics</big><br />Provides an analysis of user and browser information gathered through profiling.</p>
			<p>
			<a class="lineSpacer" href="RMM-summary.php?Session_ID=<? echo $Session_ID; ?>">View an executive summary (overview) report</a><br />
			<a class="lineSpacer" href="RMM-activities.php?Session_ID=<? echo $Session_ID; ?>">View user access trends</a><br />
			<a class="lineSpacer" href="RMM-user-statistics.php?Session_ID=<? echo $Session_ID; ?>">Review common browser statistics</a><br />
			</p>
			</td>
		</tr>
		</table>

		</td>
	</tr>
	</table>

	</td>
	<td style="width: 16px">&nbsp;</td>
	<td style="width: 428px">

	<table border="0" cellpadding="0" cellspacing="1" width="100%" style="background-color: #8a89a6">
	<tr>
		<td style="background-color: white">

		<table border="0" cellpadding="5" cellspacing="5" width="100%" height="140">
		<tr valign="top">
			<td><img src="images/report-2.png" vspace="2" width="32" height="32" align="left" /></td>
			<td>
			<p><big class="grayHeader">Search and Geographics</big><br />Details site and search engine referrals and geographic location information for visitors.</p>
			<p>
			<a class="lineSpacer" href="RMM-user-statistics-details.php?Session_ID=<? echo $Session_ID; ?>">Learn about the sites that are referring users</a><br />
			<a class="lineSpacer" href="RMM-engine-visits.php?Session_ID=<? echo $Session_ID; ?>">See how often your site is indexed by the search engines</a><br />
			<a class="lineSpacer" href="RMM-geo-locations.php?Session_ID=<? echo $Session_ID; ?>">View user access by geography</a><br />
			</p>
			</td>
		</tr>
		</table>

		</td>
	</tr>
	</table>

	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr valign="top">
	<td style="width: 428px">

	<table border="0" cellpadding="0" cellspacing="1" width="100%" style="background-color: #8a89a6">
	<tr>
		<td style="background-color: white">

		<table border="0" cellpadding="5" cellspacing="5" width="100%" height="140">
		<tr valign="top">
			<td><img src="images/report-3.png" vspace="2" width="32" height="32" align="left" /></td>
			<td>
			<p><big class="grayHeader">Content and Click Paths</big><br />Displays information relating to how a user moves around the site.</p>
			<p>
			<a class="lineSpacer" href="RMM-impressions.php?Report=3&Session_ID=<? echo $Session_ID; ?>">Identify popular on-site search terms</a><br />
			<a class="lineSpacer" href="RMM-impressions.php?Session_ID=<? echo $Session_ID; ?>">Identify top content and user click-paths through the site</a><br />
			<a class="lineSpacer" href="RMM-clicks.php?Session_ID=<? echo $Session_ID; ?>">Review links clicked from pages and e-mails</a><br />
			</p>
			</td>
		</tr>
		</table>

		</td>
	</tr>
	</table>

	</td>
	<td style="width: 16px">&nbsp;</td>
	<td style="width: 428px">

	<table border="0" cellpadding="0" cellspacing="1" width="100%" style="background-color: #8a89a6">
	<tr>
		<td style="background-color: white">

		<table border="0" cellpadding="5" cellspacing="5" width="100%" height="140">
		<tr valign="top">
			<td><img src="images/report-4.png" vspace="2" width="32" height="32" align="left" /></td>
			<td>
			<p><big class="grayHeader">Custom Reports</big><br />Details a list of custom reports created for this install of Ensight.</p>
			<p>
			<select name="Reports" style="width: 270px; margin-top: 5px" onchange="where = this.form.Reports.options[this.form.Reports.selectedIndex].value; windowSettings = where.split (','); displayX = ((screen.width - windowSettings[0]) / 2); displayY = ((screen.height - windowSettings[1]) / 2); if (where != 0) { if (windowSettings[0] == 0) { window.location = windowSettings[2]; } else if (where != '') { window.open (windowSettings[2], 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=' + windowSettings[0] + ',height=' + windowSettings[1] + ',screenX=' + displayX + ',screenY=' + displayY + ',left=' + displayX + ',top=' + displayY); } }">
			<option value="">-- Additional Reports --</option>
			<option value="0">- - - - - -</option>
			<?
			//--- Custom options
			if (is_array ($CustomReportingLinks)) {
				for ($i = 0; $i < count ($CustomReportingLinks); $i++) {
					echo "<option value=\"".$CustomReportingLinks[$i]['width'].",".$CustomReportingLinks[$i]['height'].",".$CustomReportingLinks[$i]['url']."&Session_ID=$Session_ID\">".$CustomReportingLinks[$i]['text']."</option>\n";
				} // end for
			}
			//--- Custom options
			?>
			</select>
			</p>
			</td>
		</tr>
		</table>

		</td>
	</tr>
	</table>

	</td>
</tr>
</table>
<br /></center>

<?
PrintHiddenField ("Session_ID", $Session_ID);
?>
</form>

<?
//--- Include display functions
include_once (ADMIN_FILES."/footer.php");
?>