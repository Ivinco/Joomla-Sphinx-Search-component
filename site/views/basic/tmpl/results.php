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
$this->assignRef("results", $this->get("Results"));
?>
<form action="<?php echo JRoute::_("index.php?option=com_sphinxsearch&task=search"); ?>" method="post" name="adminForm" class="sphinx-search-result-form">
	<div class="sphinx-query">
		<input type="text" name="q" id="q" value="<?php echo htmlspecialchars($this->get("Query")); ?>" class="sphinx-result-query"/>
                <button type="submit" class="sphinx-search-button">Search</button>
	</div>
</form>

	<?php if ($this->get("Total") > 0) : ?>
	<div class="sphinx-total-results"><?php echo JText::sprintf("About %s results found in %s seconds.", $this->get("Total"), $this->get("Time")); ?></div>
	<?php endif; ?>

	<?php if ($this->get("Total") == 0) : ?>
            <div class="sphinx-no-results"><?php echo JText::_("No results found."); ?></div>
        <?php else:?>
            <div class="sphinx-results">
            <?php foreach ($this->results as $item) : ?>
                    <div class="sphinx-result">
                            <div class="sphinx-result-title"><a href="<?php echo $item->href; ?>"><?php echo $item->title; ?></a></div>

                            <?php if ($item->created) : ?>
                            <div class="sphinx-result-date">
                                    <span class="sphinx-date-label"><?php echo JText::_("Created"); ?>:</span><?php echo $item->created; ?>
                            </div>
                            <?php endif; ?>

                            <div class="sphinx-result-description"><?php echo $item->text; ?></div>
                    </div>
            <?php endforeach; ?>
            </div>

        <?php endif; ?>


<div class="sphinx-pagination">
	<?php echo $this->get("Pagination")->getPagesLinks(); ?>
</div>