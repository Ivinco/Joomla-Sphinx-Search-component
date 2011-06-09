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
defined( '_JEXEC' ) or die( 'Restricted access' );

$application = JFactory::getApplication("administrator");

$document = JFactory::getDocument();

$document->addScriptDeclaration(
"
var adminOptions = new Object({
	testURL : \"".$application->getSiteURL()."administrator/index.php?option=com_sphinxsearch&task=test&format=raw\"
});
");

$document->addScript($application->getSiteURL() . "media/com_sphinxsearch/js/jsphinxsearch.js");

JToolBarHelper::title(JText::_('SphinxSearch Configuration'), 'config.png');

JToolBarHelper::save();
JToolBarHelper::cancel();
?>

<form autocomplete="off" name="adminForm" method="post" action="index.php">
    <div id="config-document">
        <div id="page-site" style="display: block;">
            <table class="noshow">
            <tbody>
            <?php if (false == $this->sphinxRunning):?>
            <tr>
                <td width="65%">
                    <dl id="system-message">
                    <dt class="error">Error</dt>
                    <dd class="error message fade">
                        <ul><li>Sphinx Search is not running on <?php echo $this->getModel()->getParam("hostname"); ?>.
                            See <a href="http://www.ivinco.com/software/joomla-sphinx-search-component-tutorial/#installation">installation instruction</a>.
                            </li>
                        </ul>
                    </dd>
                    </dl>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td width="65%">
                    <fieldset class="adminform">
                        <legend>Component Settings</legend>

                        <table cellspacing="1" class="admintable">
			<tbody>
			<tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Host name</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam("hostname"); ?>" size="50" id="host" name="hostname" class="text_area"/>
                            </td>
                        </tr>
			<tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Port</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam("port"); ?>" size="50" id="port" name="port" class="text_area"/>
                            </td>
			</tr>
			<tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Index name</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam("index"); ?>" size="50" id="path" name="index" class="text_area"/>
                            </td>
			</tr>
                        </tbody>
			</table>
                    </fieldset>
		</td>
            </tr>
            </tbody>
            </table>
	</div>
    </div>
<div class="clr"></div>
    <input type="hidden" value="com_sphinxsearch" name="option"/>
    <input type="hidden" value="" name="task"/>
    <input type="hidden" value="configuration" name="view"/>
</form>