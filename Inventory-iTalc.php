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

class italcexport extends FOGBase
{
        public function __construct()
        {
                parent::__construct();
                $this->makeReport();
        }
        private function makeReport()
        {?>
		<h2><?php print _("Export Full Inventory for iTalc GlobalConfig.xml file")."&nbsp;&nbsp;&nbsp;<a href=\"export.php?type=csv\" target=\"_blank\"><img class=\"noBorder\" src=\"images/csv.png\" /></a>&nbsp;&nbsp;&nbsp;<a href=\"export.php?type=pdf\" target=\"_blank\"><img class=\"noBorder\" src=\"images/pdf.png\" /></a>"; ?></h2><?php
	
		$Hosts = $this->FOGCore->getClass('HostManager')->find();
		
		$report = new ReportMaker();
		$report->addCSVCell(_("Host Name"));
		$report->addCSVCell(_("Host IP"));
		$report->addCSVCell(_("Host MAC"));  	
		$report->addCSVCell(_("Export iTalc"));		
		$report->endCSVLine();												
		
		$report->appendHTML('<table cellpadding="0" cellspacing="0" border="0" width="100%">');
		$report->appendHTML('<tr bgcolor="#BDBDBD"><td><b>Hostname</b></td><td><b>MAC</b></td><td><b>iTalc-Export</b></td></tr>');
		
		$cnt = 0;
		foreach ($Hosts AS $Host)
		{	
			$bg = ($cnt++ % 2 == 0 ? "#E7E7E7" : '');	
			$report->addCSVCell($Host->get('name'));
			$report->addCSVCell($Host->get('ip'));
			$report->addCSVCell($Host->get('mac'));
			$report->addCSVCell("<client hostname=\"".$Host->get('name')."\" mac=\"".$Host->get('mac')."\" type=\"0\" name=\"".$Host->get('name')."\"/>");
			$report->endCSVLine();
		
			$report->appendHTML('<tr bgcolor="'.$bg.'"><td>'.$Host->get('name').'</td><td>'.$Host->get('mac').'</td><td><span style="font-size:10px;">'.'"&lt;client hostname="'.$Host->get('name').'" mac="'.$Host->get('mac').'" type="0" name="'.$Host->get('name').'"/&gt;"</span></td></tr>');
		}
		
		$report->appendHTML('</table>');
		$report->outputReport(ReportMaker::FOG_REPORT_HTML);

		$_SESSION["foglastreport"] = serialize( $report );
		
		echo ( "<p>"._("Reporting Complete!")."</p>" );
	}
}
$italcexport = new italcexport();
