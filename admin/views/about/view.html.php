<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class SphinxSearchViewAbout extends JView
{
    function display($tpl = null)
    {
    	JHTML::_('behavior.mootools');

        parent::display($tpl);
    }
}