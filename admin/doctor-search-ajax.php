<?php
include_once ("../modules/DB.php");
include_once ("../modules/connect.php");

$q = "";
$q = $_POST["search"];
$SQL = "SELECT tUsers.id, CONCAT(tUsers.first_name, ' ', tUsers.last_name) as doctorName, tSpecialty.specialty_name
FROM tUsers 
LEFT JOIN tSpecialty ON tSpecialty.id = tUsers.speciality_id
WHERE tUsers.doctor=1 
AND (tUsers.first_name LIKE '%$q%' OR tUsers.last_name LIKE '%$q%')
ORDER BY tUsers.first_name, tUsers.last_name LIMIT 3";

$Query = QueryDB($SQL);

while ($row = ReadFromDB($Query)) {

	  $row['doctorName'] = 'Dr.'.str_replace('Dr', '', $row['doctorName'])
?>
    <div class="show" align="left">
    	<span class="name"><?php echo $row['doctorName'].'('.$row['specialty_name'].')'; ?></span>&nbsp;<span class="cno" style="font-size: 0px; color: #ffffff;"><?php echo $row['id']; ?></span><br/>
    </div>
<?php 
} ?>