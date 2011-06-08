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

jimport('joomla.application.component.controller');

class SphinxSearchController extends JController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $model = $this->getModel(JRequest::getWord("view"));

	$model->save(JRequest::get("post"));

	$view = $this->getView(JRequest::getWord("view"), JRequest::getWord("format", "html"));
	$view->setModel($model, true);

	$url = new JURI("index.php");
	$url->setVar("option", JRequest::getWord("option"));
	$url->setVar("view", JRequest::getWord("view"));

	$this->setRedirect($url->toString(), JText::_("Configuration successfully saved."));
    }

    function display()
    {
	$viewParam =  JRequest::getWord("view");

        if (empty($viewParam)){
            $viewParam = 'configuration';
        }
        $view = $this->getView($viewParam, JRequest::getWord("format", "html"));

        if ('configuration' == $viewParam) {
            $model = $this->getModel($viewParam);
            $view->setModel($model, true);

            //check sphinx is up
            $view->sphinxRunning = $this->_checkSphinxConnection();
        }
        $view->display();
    }

    function _checkSphinxConnection()
    {
        $configuration = new SphinxSearchConfig();
        $client = new SphinxClient();
        $client->SetServer($configuration->hostname, (int)$configuration->port);
        $client->Open();
        $error = $client->GetLastError();
        $running = false;
        if (empty ($error)){
            $running = true;
        }
        return $running;
    }
}
