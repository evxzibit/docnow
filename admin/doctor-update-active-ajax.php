<?php
include_once ("../modules/DB.php");
include_once ("../modules/connect.php");

extract($_POST);

$SQL = "UPDATE tUsers SET ".$field."={$active} WHERE id={$id}";
echo $SQL;
$Result = UpdateDB ($SQL);

//echo $Result;

?>