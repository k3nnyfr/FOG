<?php
/*
 *  FOG is a computer imaging solution.
 *  Copyright (C) 2007  Chuck Syperski & Jian Zhang
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 */

//@ini_set( "max_execution_time", 120 );
 
if ( IS_INCLUDED !== true ) die( _("Unable to load system configuration information.") );

require_once( "./lib/ReportMaker.class.php" );

?>
<h2><?php print _("Export Full Inventory for iTalc GlobalConfig.xml file")."  <a href=\"export.php?type=csv\" target=\"_blank\"><img class=\"noBorder\" src=\"images/csv.png\" /></a>"; ?></h2>
<?php

$report = new ReportMaker();
$report->addCSVCell(_("Host Name"));
$report->addCSVCell(_("Host IP"));
$report->addCSVCell(_("Host MAC"));  	
$report->addCSVCell(_("Export iTalc"));		
$report->endCSVLine();												
	
$sql = "SELECT 
		* 
	FROM 
		hosts  
		inner join inventory on ( hosts.hostID = inventory.iHostID )
		left outer join images on ( hostImage = imageID )
		left outer join supportedOS on ( hostOS = osID )";
$res = mysql_query( $sql, $conn ) or die( mysql_error() );

while ( $ar = mysql_fetch_array( $res ) )
{																																					
	$report->addCSVCell($ar["hostName"]);
	$report->addCSVCell($ar["hostIP"]);
	$report->addCSVCell($ar["hostMAC"]);		
	$report->addCSVCell("<client hostname=\"".$ar["hostName"]."\" mac=\"".$ar["hostMAC"]."\" type=\"0\" name=\"".$ar["hostName"]."\"/>");

	$report->endCSVLine();						
}
echo ( "<p>"._("Reporting Complete!")."</p>" );

$_SESSION["foglastreport"] = serialize( $report );
