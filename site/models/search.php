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

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

jimport('joomla.error.log');
jimport('joomla.html.pagination');

require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_sphinxsearch".DS."configuration.php");

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class SphinxSearchModelSearch extends JModel
{
    var $query = '';
    var $params = array();
    var $total = 0;
    var $time;
    
    public function __construct()
    {
        parent::__construct();

        $application = JFactory::getApplication("site");

	$config = JFactory::getConfig();

	$this->setState('limit', $application->getUserStateFromRequest('com_sphinxsearch.limit', 'limit', 10, 'int'));
	$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    }

    public function buildQueryURL($params)
    {
        $url = new JURI("index.php");

        $url->setVar("option", "com_sphinxsearch");
	$url->setVar("view", "basic");

	foreach ($params as $key=>$value) {
            if ($value != "com_sphinxsearch") {
                if ($key == "task") {
                    $url->delVar($key);
		} else {
                    $url->setVar($key, $value);
		}
            }
	}
	return JRoute::_($url->toString(), false);
    }

    public function setQueryParams($params)
    {
        $this->query = JArrayHelper::getValue($params, "q", "", "string");

	$this->_setParams($params);
    }    

    public function getQuery()
    {
        return $this->query;
    }
    public function getTotal()
    {
        return $this->total;
    }
    public function getTime()
    {
        return $this->time;
    }

    public function getPagination()
    {
        if (empty($this->pagination)) {
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
	}

	return $this->pagination;
    }

    public function getResults()
    {
        $list = array();
        $log = JLog::getInstance();
	try {
            $configuration = new SphinxSearchConfig();
            $configuration->index;

            $client = new SphinxClient();
            $client->SetServer($configuration->hostname, (int)$configuration->port);
            $client->SetMatchMode(SPH_MATCH_EXTENDED2);
            $client->SetSortMode(SPH_SORT_TIME_SEGMENTS, 'created');
            
            $client->SetLimits($this->getState("limitstart"), $this->getState("limit"));
            
            $result = $client->Query($this->getQuery(), $configuration->index);
            if ( $result === false ) {
                //echo "Query failed: " . $client->GetLastError() . ".\n";exit;
                $log->addEntry(array("c-ip"=>"", "comment"=>$client->GetLastError()));
                return false;
            } else if ( $client->GetLastWarning() ) {
                //echo "WARNING: " . $client->GetLastWarning() . "\n";exit;
                $log->addEntry(array("c-ip"=>"", "comment"=>$client->GetLastWarning()));
                return false;
            }
            if ( empty($result["matches"]) ) {
                return false;
            }
            
            $this->total = $result['total'];
            $this->time = $result['time'];
            $ids = array_keys($result['matches']);

            $db =& JFactory::getDBO();

            $query = 'SELECT id, a.title AS title, a.created AS created, a.metadesc, a.metakey,'
		. ' CONCAT(a.introtext, a.fulltext) AS text,'
		. ' "2" as browsernav, "" AS section'
		. ' FROM #__content AS a'
		. ' WHERE id in ( ' . implode(', ',$ids) . ')'
		. ' AND a.state = 1'
		. " ORDER BY FIELD(id, ". implode(', ', $ids) . ")"
		;

            $db->setQuery($query);
            $results = $db->loadObjectList();

            // get the actual links from Joomla (maintains clean urls but is probably quite slow)
            foreach($results as $key => $item) {
            	$results[$key]->href = ContentHelperRoute::getArticleRoute($item->id);
                if ($results[$key]->created) {
                    $results[$key]->created = $this->_localizeDateTime($results[$key]->created);
                }
                //get text for build snippets
                $texts[] = strip_tags($results[$key]->text);
            }
            
            $textSnippets = $client->BuildExcerpts($texts, $configuration->index, $this->getQuery());

            //load snippets
            $counter = 0;
            foreach($results as $key => $item) {
                $results[$key]->text = $textSnippets[$counter++];
            }

            return $results;
        } catch(Exception $e){            
            $log->addEntry(array("c-ip"=>"", "comment"=>$e->getMessage()));
        }
    }

    private function _setParams($params)
    {
	$this->params = $params;
    }
    private function _localizeDateTime($dateTime)
    {
        $date = JFactory::getDate($dateTime);

	return $date->toFormat(JText::_("DATE_FORMAT_LC2"));
    }
}
