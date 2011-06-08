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

//no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.registry.registry');
jimport('joomla.filesystem.file');

class SphinxSearchModelConfiguration extends JModel
{
    var $configuration;

    public function __construct()
    {
	parent::__construct();

        require_once($this->getConfig());

	$this->configuration = new SphinxSearchConfig();
    }

    /**
     * Gets the configuration file path.
     *
     * @return The configuration file path.
     */
    public function getConfig()
    {
	return JPATH_ROOT.DS."administrator".DS."components".DS."com_sphinxsearch".DS."configuration.php";
    }

    public function getParam($name)
    {
    	return $this->configuration->$name;
    }

    public function save($array)
    {
	require_once($this->getConfig());

	$config = new JRegistry('sphinxconfig');
	$config_array = array();

	$config_array["hostname"] = JArrayHelper::getValue($array, "hostname");
	$config_array["port"] = JArrayHelper::getValue($array, "port");
	$config_array["index"] = JArrayHelper::getValue($array, "index");
	$config->loadArray($config_array);

	JFile::write($this->getConfig(), $config->toString("PHP", "sphinxconfig", array("class"=>"SphinxSearchConfig")));

	$this->configuration = new SphinxSearchConfig();
    }
}
