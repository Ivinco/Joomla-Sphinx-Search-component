<?php
/**
 *
 *
 * @author		Ivinco LTD.
 * @package		Ivinco
 * @subpackage	SphinxSearch
 * @copyright	Copyright (C) 2011 Ivinco Ltd. All rights reserved.
 * @license     This file is part of the SphinxSearch component for Joomla!.

   The SphinxSearch component for Joomla! is free software: you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation, either version 3 of the License,
   or (at your option) any later version.

   The SphinxSearch component for Joomla! is distributed in the hope that it will be
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the SphinxSearch component for Joomla!.  If not, see
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have
 * contributed any source code changes.
 * Name							Email
 * Ivinco					<opensource@ivinco.com>
 *
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.helper');

function com_install()
{
	$installer = new SphinxSearchInstaller();
	$installer->install();
}

class SphinxSearchInstaller
{
	const COM_SPHINXSEARCH = "com_sphinxsearch";

	public function __construct()
	{

	}

	public function install()
	{
		$installer = new JInstaller();
		$installer->_overwrite = true;

		$pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.self::COM_SPHINXSEARCH.DS.'extensions'.DS;

		if ($handle = opendir($pkg_path)) {
			while ($pkg = readdir($handle)) {
				if ($pkg != "." && $pkg != "..") {
					$package = JInstallerHelper::unpack($pkg_path.$pkg);

					if ($installer->install($package['dir'])) {
						$msgcolor = "#E0FFE0";
						$msgtext  = "$pkg successfully installed.";
					} else {
						$msgcolor = "#FFD0D0";
						$msgtext  = "ERROR: Could not install $pkg. Please install manually.";
					}
					?>

					<table bgcolor="<?php echo $msgcolor; ?>" width ="100%">
						<tr style="height:30px">
							<td width="50px"><img src="/administrator/images/tick.png" height="20px" width="20px"></td>
							<td><font size="2"><b><?php echo $msgtext; ?></b></font></td>
						</tr>
					</table>

					<?php
					JInstallerHelper::cleanupInstall($pkg_path.$pkg, $package['dir']);
				}
			}
		}
	}
}