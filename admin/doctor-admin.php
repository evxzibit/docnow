<!-- q`1r` --><?php
//--- Disable errors
ini_set("display_errors", "off");

//--- ENVENT Source Code (ESC)
//--- First Created on Wednesday, May 14 2003 by John Ginsberg
//--- For ENSIGHT

//--- Module Name: cache-admin.php
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:f
//--- Administration for Ensight's content caching system

//--- Permissions:
//--- ALLOW_ALL

//----------------------------------------------------------------

if (!defined ("DB_INCLUDED")) 			{ include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED")) 		{ include_once ("../modules/connect.php"); }
if (!defined ("UTILS_INCLUDED")) 		{ include_once ("../modules/utils.php"); }
if (!defined ("DB_FUNCS_INCLUDED")) 	{ include_once ("../modules/db-functions.php"); }
if (!defined ("CATALOG_INCLUDED")) 		{ include_once ("../modules/catalog.php"); }
if (!defined ("DATE_INCLUDED")) 		{ include_once ("../modules/date.php"); }
if (!defined ("PROFILE_INCLUDED")) 		{ include_once ("../modules/profile.php"); }
if (!defined ("SESSION_INCLUDED")) 		{ include_once ("../modules/session.php"); }
if (!defined ("GLOBALS_INCLUDED")) 		{ include_once ("../modules/globals.php"); }
//include_once ("../custom_modules/common.php");

global $Session_ID, $Profile_ID;

define ("ITEMS_PER_PAGE", 10);
define ("HighlightColor", "#CCFFCC");

//--- Get the user's session ID
if (($Session_ID) && (!$Profile_ID)) {
	$Profile_ID = LocateSession ($Session_ID);
}


if (!$Profile_ID) {
	Redirect ("index.php?Dest_URL=main.html?Message=".urlencode ("You have been logged out. Please login again to continue using this system"));
}


function debug($data){
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}


function CorrectStart ($Count) {
//--- Checks to see if the current page is not past the last page in the result set

	global $Start;

	if (($Count) && (RetrieveCatalogCurrentPage ($Start, ITEMS_PER_PAGE) > RetrieveCatalogPageCount ($Count, ITEMS_PER_PAGE))) {
		$Start = (RetrieveCatalogPageCount ($Count, ITEMS_PER_PAGE) - 1) * ITEMS_PER_PAGE;
	}

}
 
function RetrieveDrList ($SortBy, $OrderBy, $Start, $items_per_page,$specialty, $doctorid) {

	$SQL = "SELECT id, CONCAT(tUsers.first_name, ' ', tUsers.last_name) as doctorName, address, speciality_id FROM tUsers WHERE doctor=1";
  $SQL .= ($specialty ? " AND  speciality_id= ".$specialty : "");
  $SQL .= ($doctorid ? " AND  id= ".$doctorid : "");
	$SQL .=" ORDER BY $SortBy $OrderBy ".LimitQueryDB ($Start, $items_per_page);
  //echo $SQL;
	$Query = QueryDB($SQL);

	return $Query;

}

function RetrieveDrCount ($specialty, $doctorid) {
  
	$SQL = "SELECT * FROM tUsers WHERE doctor=1";
  $SQL .= ($specialty ? " AND speciality_id= ".$specialty : "");
  $SQL .= ($doctorid ? " AND  id= ".$doctorid : "");

	$Query = QueryDB($SQL);
	$DrListCount = CountRowsDB ($Query);
	return $DrListCount;

}

function getSpecialities(){

  $SQL = "SELECT * FROM tSpecialty ORDER BY display_order";
  $Query = QueryDB($SQL);
  while($Result = ReadFromDB($Query)){
    $specialities [$Result['id']] = $Result['specialty_name'];

  }

  return $specialities;
}

//--- Set header custom definitions
  $SubTitle = "Cache Administration";
  $HasFocus = "";
  $HeadScript = "";
  $NavBar = array ("Doctors", true);

  //--- Include display functions
  include_once (ADMIN_FILES."/display.php");
  include_once (ADMIN_FILES."/header.php");


  //--- Start layout
  PrintComments ("<p align=\"justify\"><big>Doctors Manager</big><br />Use this page to maintain Doctors information</p>", "", DO_BREAK, NO_BREAK);

  //--- Set defaults
  if (!$Start) {
  	$Start = 0;
  }

  if (!$SortBy) {
  	$SortBy = "doctorName"; 
    $OrderBy = 'ASC';
  }

  $DrList = RetrieveDrList ($SortBy, $OrderBy, $Start, ITEMS_PER_PAGE, $specialty, $doctorid);
  $DrListCount = RetrieveDrCount ($specialty, $doctorid);
  $specialities = getSpecialities();
  //echo $DrListCount;

  //  while ($Doctors = ReadFromDB ($DrList)) {

  //  debug($Doctors);

  // }

  ?>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

  function AltNewWindow (URL, Title, Width, Height) {
    //--- Opens a new window with the specifics in the center of the screen
    window.parent.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), window);
  }

  $(function(){
        $(".search").keyup(function() 
        { 
        var searchid = $(this).val();
        var dataString = 'search='+ searchid;
        if(searchid!=''){
            $.ajax({
              type: "POST",
              url: "/live/admin/doctor-search-ajax.php",
              data: dataString,
              cache: false,
              success: function(html){
                $("#result").html(html).show();
              }
            });
          }
          return false;    
        });
          
      $("#result").bind("click",function(e){
        
        var $clicked = $(e.target).closest("div");
             
              var $name = $clicked.find('.name').html();
              var decoded = $("<div/>").html($name).text();
              $('#searchid').val(decoded);
        
        var $cid = $clicked.find('.cno').html();
        var decoded = $("<div/>").html($cid).text();
        $('#doctorid').val(decoded);
        
        return false;
      });
      
      $(document).bind("click",function(e){
          //jQuery(document).live("click", function(e) { 
              var $clicked = $(e.target);
              if (! $clicked.hasClass("search")){
                jQuery("#result").fadeOut(); 
              }
          });
      
          $('#searchid').click(function(){
              jQuery("#result").fadeIn();
          });
      $('#result').mouseup(function(){
        jQuery("#result").fadeOut();
      });
      
      $('#autoEnrol').click(function(){
        
        if($('#doctorid').val() != ''){
          
          var $pid = $('#doctorid').val();

          
        }else{
          
          alert("Please type in a doctor's name.");
        }
      });
      
    });
  </script>
  <style>
  .buttonFace1{ 
    border: 0px; 
    background-color: transparent; 
    background-image: url(images/add.png);
    background-repeat: no-repeat;
    background-position: left center; 
    padding-left :15px;
    width: 100%; 
    height: 100%; 
    font-family: 
    Verdana; 
    font-size: 11px; 
    margin-top: 1px; 
    cursor: pointer 
  }
  #searchid
    {
      width:300px;
      padding:2px;
    }
    #result
    {
      position:absolute;
      width:500px;
      padding:10px;
      display:none;
      margin-top:-1px;
      border-top:0px;
      overflow:hidden;
      border:1px #CCC solid;
      background-color: white;
    }
    .show
    {
      padding:10px; 
      border-bottom:1px #999 dashed;
      font-size:15px; 
      height:auto;
    }
    .show:hover
    {
      background:#4c66a4;
      color:#FFF;
      cursor:pointer;
    }


  </style>
    <br />
  <table border="0" cellpadding="0" cellspacing="0" width="100%" cols="8" class="toolbar">
    <tr class="toolbarBody" valign="middle">
      <td><table border="0" cellpadding="0" cellspacing="0" style="margin-top: 2px">
          <tr>
            <td style="width: 1px">&nbsp;</td>
            
            <td>                             

              <form name="search" method="post" action="doctor-admin.php" id="form_doctor">
                <label>Doctor Search</label> &nbsp;
                <input type="text" class="search" id="searchid" onclick="this.value='';" onfocus="this.select();" value="<?php //secho ($doctorid ? $doctorid :''); ?>" /><br /> 
                <div id="result">Start typing to find a doctor</div>
                <input type="hidden" id="doctorid" name="doctorid">
                <input type="hidden" value="<? echo $Session_ID;?>" name="Session_ID">
                <input type="hidden" value="course" name="Search">
                <?php

                PrintHiddenField ("SortBy", $SortBy);
                PrintHiddenField ("OrderBy", $OrderBy);
                PrintHiddenField ("Start", $Start);
                PrintHiddenField ("srchf", $srchf); 
                PrintHiddenField ("MAX_FILE_SIZE", "30000");

                ?>
              </form>
            
            </td>
            <td><input type="button" id="autoEnrol" name="Enrol" style="font-size: 12px;font-weight: bold !important;" onclick="$('#form_doctor').submit();" value="Search"></td>
            <td style="width: 8px"><img src="images/divider.gif" width="2" height="21" border="0" hspace="3" /></td>
            <td>
               <form name="search_by_specialty" method="post" action="doctor-admin.php" id="search_by_specialty">
              <select name="specialty" onchange="$('#search_by_specialty').submit();">
                <option value="">--Filter--</option>
                <?php

                 
                  foreach ($specialities as $key => $value) {
                    
                    //$specialities [] = $Result['specialty_name'];

                    echo '<option value="'.$key.'"'.($specialty == $key? 'selected="selected"' : '').' >'.$value.'</option>';

                  }
                ?>            

              </select>

               
                <input type="hidden" value="<? echo $Session_ID;?>" name="Session_ID">
                <input type="hidden" value="course" name="Search">
                <?php

                PrintHiddenField ("SortBy", $SortBy);
                PrintHiddenField ("OrderBy", $OrderBy);
                PrintHiddenField ("Start", $Start);
                PrintHiddenField ("srchf", $srchf); 
                PrintHiddenField ("MAX_FILE_SIZE", "30000");

                ?>
              </form>
            </td>
          </tr>
            
        </table></td>
    </tr>

  </table>

  <table id="Items" border="0" cellpadding="5" cellspacing="1" width="100%" cols="8" class="tbl" style="margin-top: 10px">
      <tr class="tblHead">
        <td style="width: 20px" align="center"><img src="images/check_open.gif" width="11" height="11" onclick="if (this.src.toString ().substr (this.src.toString ().lastIndexOf ('/') + 1) == 'check_open.gif') { this.src = 'images/check_selected.gif'; var imgs = document.getElementById ('Items').getElementsByTagName ('IMG'); for (i = 0; i < imgs.length; i++) { if (imgs[i].src.toString ().substr (imgs[i].src.toString ().lastIndexOf ('/') + 1) == 'check_open.gif') { imgs[i].onclick () } } } else { this.src = 'images/check_open.gif'; var imgs = document.getElementById ('Items').getElementsByTagName ('IMG'); for (i = 0; i < imgs.length; i++) { if (imgs[i].src.toString ().substr (imgs[i].src.toString ().lastIndexOf ('/') + 1) == 'check_selected.gif') { imgs[i].onclick () } } }" /></td>
        <td style="width: expression(columnWidth)"><b>
          <? if ($SortBy == "first_name") { echo "<&nbsp;"; } ?>
          <a href="doctor-admin.php?Start=<? echo $Start; ?>&srchf=<?=$srchf?>&Session_ID=<? echo $Session_ID; ?>&SortBy=<? echo "doctorName"; ?>&OrderBy=<? echo (($SortBy == "first_name") && ($OrderBy == ORDER_ASC) ? ORDER_DESC : ORDER_ASC); ?>&doctorid=<? echo $doctorid;?>&specialty=<? echo $specialty; ?>">Doctor Name</a>
          <? if ($SortBy == "first_name") { echo "&nbsp;>"; } ?>
          </b>
        </td>
        <td><b>Specialty</b></td>
        <td><b>Address</b></td>
        <td style="width: 50px">&nbsp;</td>
      </tr>
      <?
  $i = 0;  
  while ($Doctor = ReadFromDB ($DrList)) {

    $Doctor['doctorName'] = 'Dr.'.str_replace('Dr', '', $Doctor['doctorName']);
    ?>
      <tr class="<? echo ($i % 2 == 0 ? "tblRowL" : "tblRowD"); ?>">
        <td align="center"><img src="images/check_open.gif" width="11" height="11" onclick="if (this.src.toString ().substr (this.src.toString ().lastIndexOf ('/') + 1) == 'check_open.gif') { this.src = 'images/check_selected.gif'; AddToObjectList ('C<? echo $Doctor['id']; ?>'); tRow = FindElement (this, 'TR'); if (tRow != null) { tRow.style.backgroundColor = '<? echo HighlightColor; ?>' } } else { this.src = 'images/check_open.gif'; RemoveFromObjectList ('C<? echo $Doctor['id']; ?>'); tRow = FindElement (this, 'TR'); if (tRow != null) { tRow.style.backgroundColor = '' } }" /></td>
        <td><span style="width: expression(columnWidth); text-overflow: ellipsis; overflow: hidden" title="<? echo htmlspecialchars ($Doctor['doctorName']); ?>"><nobr><a href="custom-store-update.php?doctorid=<? echo $Doctor['id']; ?>&Action=E&Start=<? echo $Start; ?>&srchf=<?=$srchf?>&SortBy=<? echo $SortBy; ?>&OrderBy=<? echo $OrderBy; ?>&srchf=<?=$srchf?>&Session_ID=<? echo $Session_ID; ?>"><? echo $Doctor['doctorName']; ?></a></nobr></span></td>
       <td><?php echo $specialities[$Doctor['speciality_id']];?></td>
       <td><?php echo $Doctor['address'];?></td>
        <td width="70"><a href="custom-store-update.php?id=<? echo $Doctor['id']; ?>&Action=E&Start=<? echo $Start; ?>&srchf=<?=$srchf?>&SortBy=<? echo $SortBy; ?>&OrderBy=<? echo $OrderBy; ?>&srchf=<?=$srchf?>&Session_ID=<? echo $Session_ID; ?>">Edit</a> |
        <a href="custom-store-complete.php?id=<? echo $Doctor['id']; ?>&Action=D&Start=<? echo $Start; ?>&srchf=<?=$srchf?>&SortBy=<? echo $SortBy; ?>&OrderBy=<? echo $OrderBy; ?>&srchf=<?=$srchf?>&Session_ID=<? echo $Session_ID; ?>&GooglePlacesID=<? echo $Doctor['GooglePlacesID']; ?>" onclick="return confirm('Are you sure delete this doctor?')">Delete</a></td>
      </tr>
      <?
    $i++;
  } // end while

  FreeQueryDB ($DrList);
  ?>
  <tr class="tblBlnk">
        <td colspan="9">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" cols="5">
            <tr>
              <td colspan="2"><? $CurrentPage = RetrieveCatalogCurrentPage ($Start, ITEMS_PER_PAGE); $PageCount = RetrieveCatalogPageCount ($DrListCount, ITEMS_PER_PAGE); if ($CurrentPage > 1) { echo "<< ".RetrieveCatalogFirstPageURL ("doctor-admin.php?Start=".$Start."&SortBy=".$SortBy."&OrderBy=".$OrderBy."&id=".$id."&specialty=".$specialty, None, $Session_ID); } if (($CurrentPage > 1) && ($CurrentPage < $PageCount)) { echo "&nbsp;&nbsp;"; } if ($CurrentPage < $PageCount) { echo RetrieveCatalogLastPageURL ("doctor-admin.php?Start=".$Start."&SortBy=".$SortBy."&OrderBy=".$OrderBy."&id=".$id."&specialty=".$specialty, None, $Session_ID, $DrListCount, ITEMS_PER_PAGE)." >>"; } ?></td>
              <td colspan="2" align="right"><? echo RetrieveCatalogPageBar ("doctor-admin.php?Start=".$Start."&SortBy=".$SortBy."&OrderBy=".$OrderBy."&id=".$id."&specialty=".$specialty, None, $Session_ID, $Start, $DrListCount, ITEMS_PER_PAGE, 10); ?></td>
            </tr>
          </table></td>
      </tr>
    </table>

<?
  //--- Include display functions
include_once (ADMIN_FILES."/footer.php");
?>
