<?
//--- Theme definition file

//--- Cell
define ("LCellMaxWidth", 150);	//--- Max width for LHS cells
define ("RCellMaxWidth", 150);	//--- Max width for RHS cells

//--- Add a line to this array for each different container type the site
//--- supports. Ensure that the corresponding entries appear in the theme
$ContainerTypes = array (
	1 => array ("Name" => "Container 1 [standard border, standard header]", "Style" => "0", "Border" => "0", "CellPadding" => "5", "CellSpacing" => "0", "ShowTitle" => "1", "ShowFooter" => "0"),
	2 => array ("Name" => "Container 2 [standard border, standard header]", "Style" => 0, "Border" => 0, "CellPadding" => 5, "CellSpacing" => 0, "ShowTitle" => 1, "ShowFooter" => 0),
	3 => array ("Name" => "Container 3 [standard border, standard header]", "Style" => 0, "Border" => 0, "CellPadding" => 5, "CellSpacing" => 0, "ShowTitle" => 1, "ShowFooter" => 0),
	4 => array ("Name" => "Container 4 [standard border, standard header]", "Style" => 0, "Border" => 0, "CellPadding" => 5, "CellSpacing" => 0, "ShowTitle" => 1, "ShowFooter" => 0),
	5 => array ("Name" => "Container 5 [standard border, standard header]", "Style" => "0", "Border" => "0", "CellPadding" => "5", "CellSpacing" => "0", "ShowTitle" => "1", "ShowFooter" => "0"),
	6 => array ("Name" => "No border, no header", "Style" => 0, "Border" => 0, "CellPadding" => 0, "CellSpacing" => 0, "ShowTitle" => 1, "ShowFooter" => 0)
);
?>