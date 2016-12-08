<?php
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
 
function RetrieveDrList ($doctorid) {

	$SQL = "SELECT * FROM tUsers WHERE doctor=1 AND id={$doctorid}";

  //echo $SQL;
	$Query = QueryDB($SQL);
  $Result = ReadFromDB($Query);
	return $Result;

}


function getSpecialities(){

  $SQL = "SELECT * FROM tSpecialty ORDER BY display_order";
  $Query = QueryDB($SQL);
  while($Result = ReadFromDB($Query)){
    $specialities [$Result['id']] = $Result['specialty_name'];

  }

  return $specialities;
}

function getLanguages(){

  $SQL = "SELECT * FROM tLanguage ORDER BY id";
  $Query = QueryDB($SQL);
  while($Result = ReadFromDB($Query)){
    $language [$Result['id']] = $Result['language'];

  }

  return $language;
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
  PrintComments ("<p align=\"justify\"><big>Doctors Manager</big><br />Use this page to update Doctors information</p>", "", DO_BREAK, NO_BREAK);

  //--- Set defaults
  if (!$Start) {
  	$Start = 0;
  }

  $profileDetails = RetrieveDrList ($doctorid);
  $specialities = getSpecialities();
  $languages = getLanguages();
  $username = RetrieveProfileDetails ($profileDetails['profile_id']);

  $languagesArray = explode(",", $profileDetails['language']);
  //debug($profileDetails);
  ?>

<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
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
      
      $('.active').bind('click', function(){

        var id = this.id;
        var active = $("#"+id).is(":checked") ? '1' : '0';
         if(id !=''){
          $.ajax({
            type: "POST",
            url: "/live/admin/doctor-update-active-ajax.php",
            data: 'active=' + active + '&id=' + id,
            cache: false,
            success: function(html){
              //$("#result").html(html).show();
            }
          });
        }
      });
    });

   function ValidateForm(){


    if ($('#lat').val() == '' ){ 
      alert('Please find the doctors address on google');
      $('#address').focus();
      return false;
    
    } 
  }

    function initialize() {


    var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(40.802089, -124.163751)
      );

    var input = document.getElementById('address');

    var options = {
      bounds: defaultBounds,
  /*    componentRestrictions: {country: 'sa'}*/  
      };


    var autocomplete = new google.maps.places.Autocomplete(input, options);    

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
          var place = autocomplete.getPlace();
          $('#lat').val(place.geometry.location.lat());
          $('#lng').val(place.geometry.location.lng());
          console.log(place.geometry.location.toJSON());

      });
  }

google.maps.event.addDomListener(window, 'load', initialize);
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

    input[type="text"], input[type="email"] {
      width: 500px;
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
<br>
<?
PrintComments ("<p align=\"justify\"><big>".($Action == 'A' ? "Add" : "Edit")." a Doctor</big></p>", "", NO_BREAK, DO_BREAK);
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td>
      <div class="horizontalRule" style="margin-bottom: 1px;"></div>
    </td>
  </tr>
</table>
  <form action="doctor-admin-complete.php" name="profiledata" id="profiledata" method="post" onsubmit="return ValidateForm()">
    <table border="0" cellspacing="4" cellpadding="6" class="FormBox">
     <tr>
      <td colspan="3" class="toolbar" align="center">
        
        <big>Doctor Details</big>
      </td>
    </tr>       
    <tr>
      <td><label for="first_name">Name:</label></td>
      <td><input type="text" class="form-control" name="first_name" id="first_name" value="<?=$profileDetails['first_name']?>" required></td>
    </tr>
    <tr>
      <td><label for="email">Surname:</label></td>
      <td><input type="text" class="form-control" name="last_name" id="last_name" value="<?=$profileDetails['last_name']?>" required ></td>
    </tr>
    <tr>
      <td><label for="email">Find Doctors Address on Google:</label></td>
      <td><input type="text" alt="Start address" name="address" id="address" placeholder="Address" autocomplete="on" required  value="<?=$profileDetails['address']?>" />
          <input type="hidden" id="lat" name="lat" value="<?=$profileDetails['lat']?>" />
          <input type="hidden" id="lng" name="lng" value="<?=$profileDetails['lng']?>" />
        </td> 
      </tr>
      <tr>
        <td><label for="email">Display Address:</label></td>
        <td><input type="text" name="disply_address" id="display_address" placeholder="Display Address" required  value="<?=$profileDetails['address_1']?>" />
          
        </td> 
      </tr>
      <tr>
        <td><label for="email">Country:</label></td>
        <td><select id="country_id" name="country_id">
              
              <option value="">--Select--</option>
              <? foreach ($GLOBALS['countries'] as $key => $value) :?>

                <option value="<?=$key?>" <?=($key == DefaultRegion ? 'selected="selected"' : '') ?>><?=ucwords(strtolower($value))?></option>
              <? endforeach ?>
            </select>
        </td>
      </tr>
     <tr>
        <td><label for="email">Email address:</label></td>
        <td><input type="email" class="form-control" id="email" name="email" value="<?=$username ['Login_STRING']?>" required> </td>
     </tr>
     <tr>
      <td><label for="cell_phone">Cellphone:</label></td>
      <td><input type="text" class="form-control" id="cell_phone" name="cell_phone" value="<?=$profileDetails['cell_phone']?>" required></td>
    </tr>
    <tr>
      <td><label for="work_number">Work:</label></td>
      <td><input type="text" class="form-control" name="work_number" id="work_number" value="<?=$profileDetails['work_number']?>"></td>
    </tr>
   <tr>
    <td><label for="pwd">Prefered Number:</label></td>
    <td><select name="prefered_number" id="prefered_number" >
        <option value="cellphone">Cellphone</option>
        <option value="work">Work</option>
        <option value="home">Home</option>
      </select>
    </td>
   </tr>
   <!-- <tr>
    <td><label for="birth_date">Date Of Birth:</label></td>
    <td>
        <input readonly type="text" name="birth_date" id="birth_date" value="" />
        <a href="#" onclick="window.parent.modalWindows.openWindow('calendar-select.php?Field=birth_date&StartOn=' + forms[2].birth_date.value + '', 'Select Birth Date', 215, (navigator.userAgent.indexOf ('Macintosh') != -1 ? 240 : 235), window); return false"><img src="images/fff_calendar.gif" width="14" height="14" border="0" align="absmiddle" /></a>
        
      </td>
   </tr> -->
        
   <tr>
      <td><label for="gender">Gender:</label></td>
      <td><select id="gender" name="gender" class="selectpicker">
        <option value="male" <?=($profileDetails['gender']== 'male' ? 'selected="selected"' : '')?>>Male</option>
        <option value="female" <?=($profileDetails['gender']== 'female' ? 'selected="selected"' : '')?>>Female</option>
      </select>
      </td>
    </tr>
        

   <tr>
    <td><label for="comment">Specialties:</label></td>
      <td><span class="">
        <select id="specialty" name="specialty" class="selectpicker">
          <option value="">Specialty</option>
          <?php foreach($specialities as $id => $specialty ):?>
            <option value="<?=$id?>" <?=($id == $profileDetails['speciality_id'] ? 'selected="selected"' : '')?>><?=$specialty?></option>
          <?php endforeach?>
        </select>
      </span>
      </td>
    </tr>
   <tr>
    <td><label for="comment">Language:</label></td>
    <td>
      <span class="">
        <select name="language[]" id="language" multiple>
        <!-- <option>Language</option> -->
        <?php foreach($languages as $id => $language ):?>
          <option value="<?=$language?>" <?=(in_array($language, $languagesArray) ? 'selected="selected"' : '')?>><?=$language?></option>
        <?php endforeach?>
      </select>
      </span>
      </td>
    </tr>
    <tr>
     <td>
        <label for="comment">Education:</label></td>
        <td>
        <textarea class="form-control" name="education" rows="5" id="education"><?=$profileDetails['education']?></textarea>
      </td>
   
      <input type="hidden" name="id" value="<?php echo $profileDetails['id']; ?>">
          <?
          PrintHiddenField ("Profile_ID", $profileDetails['profile_id']);
          PrintHiddenField ("Action", $Action);
          PrintHiddenField ("SortBy", $SortBy);
          PrintHiddenField ("OrderBy", $OrderBy);
          PrintHiddenField ("Start", $Start);
          PrintHiddenField ("srchf", $srchf); 
          PrintHiddenField ("StoreID", $StoreID);
          PrintHiddenField ("MAX_FILE_SIZE", "30000");
          PrintHiddenField ("Session_ID",$Session_ID);
        ?>
     </tr>
     <tr>
     <td>
      <button type="submit" class="btn btn-success">Submit</button>
            &nbsp;&nbsp; or &nbsp;&nbsp;
        <a href="#" style="color:red;" onclick="window.location ='<? echo "doctor-admin.php?Start=$Start&SortBy=$SortBy&srchf=$srchf&OrderBy=$OrderBy&Session_ID=$Session_ID"; ?>'"> CANCEL</a>
      </td>
    </td>
    </tr>
  </table>
</form>
