<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JHtml::_('behavior.core');

//$doTask  = $displayData['doTask'];
// echo "layout classic J25: search: <br>";
/**
<!--button onclick="<?php echo $doTask; ?>" class="btn btn-small" data-toggle="collapse" data-target="#collapse-<?php echo $name; ?>"<?php echo $onClose; ?>>
	<span class="icon-cog" aria-hidden="true"></span>
	<?php echo $text; ?>
</button--> 
/**/

/*
$document = JFactory::getDocument();
if ($document->getType() == 'html')
{
	$document->addStyleSheet(JURI_SITE . "/components/com_rsgallery2/lib/rsgsearch/rsgsearch.css");
}

/**/
/**/

/**/
?>

<div align="right">
    <form name="rsg2_search" method="post" action="<?php echo JRoute::_('index.php'); ?>">
        <?php echo JText::_('COM_RSGALLERY2_SEARCH'); ?>
        <input type="text" name="searchtext" class="searchbox"
               onblur="if(this.value=='') this.value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>';"
               onfocus="if(this.value=='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>') this.value='';" value='<?php echo JText::_('COM_RSGALLERY2_KEYWORDS'); ?>' />
        <input type="hidden" name="option" value="com_rsgallery2" />
        <input type="hidden" name="rsgOption" value="search" />
        <input type="hidden" name="task" value="showResults" />
    </form>
</div>

<div class="main-top card " aria-labelledby="mod-112">
    <h3 class="card-header " id="mod-112">smart search</h3>
    <div class="card-body">
<!--        <form class="mod-finder js-finder-searchform form-search" action="/joomla4x/index.php/component/finder/search?Itemid=101" method="get" role="search">-->
        <form class="rsg2_search js-finder-searchform form-search" action="<?php echo JRoute::_('index.php'); ?>search?Itemid=101" method="get" role="search">
            <label for="mod-finder-searchword112" class="finder">Search</label>
            <div class="mod-finder__search input-group"><div class="awesomplete">
                    <input type="text" name="q" id="mod-finder-searchword112"
                           class="js-finder-search-query form-control"
                           value="" placeholder="Search ..." autocomplete="off"
                           aria-expanded="false" aria-owns="awesomplete_list_1"
                           role="combobox">
                    <ul hidden="" role="listbox" id="awesomplete_list_1"></ul>
                    <span class="visually-hidden" role="status" aria-live="assertive" aria-atomic="true">Type 2 or more characters for results.</span>
                </div>
                <span class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <span class="icon-search icon-white" aria-hidden="true"></span>
                        Search
                    </button>
                </span>
            </div>
            <input type="hidden" name="Itemid" value="101">
            <input type="hidden" name="Itemid" value="120">
        </form>
    </div>
</div>
