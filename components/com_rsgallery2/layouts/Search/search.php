<?php
/**
 * @package     rsgallery2
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('stylesheet', 'com_rsgallery2/site/rsg2_search.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/site/rsg2_search.js', ['version' => 'auto', 'relative' => true]);

?>

<?php
echo '<span style="color:red">'
    . 'Search tasks: <br>'
    . '* link from extern ??<br>'
    . '* separate searches for gallery / images -> seperate answers<br>'
	. '* Height of button -> BS 5<br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
//	. '* <br>'
. '</span><br><br>';

$link = Route::_('index.php?option=com_rsgallery2&view=searchresult'); // JRoute::_('index.php'); ? >search?Itemid=101
$placeholder = Text::_('COM_RSGALLERY2_SEARCH_GALLERIES_IMAGES');

?>

<form class="rsg2_search js-finder-searchform form-search"
      action="<?php echo $link; ?>"
      method="get"
      role="search">
    <div class="row">
        <div class="container">
            <div class="col-md-4 float-right">
                <div class="input-group ">
                    <span class="input-group-prepend">
                            <div class="input-group-text bg-transparent border-right-0">
                                <i class="fa fa-search"></i>
                            </div>
                        </span>
                    <input class="form-control py-2 border-left-0 border"
                           type="search"
                           value=""
                           placeholder="<?php echo $placeholder;?>"
                           id="example-search-input">
                    <span class="input-group-append">
                        <button class="btn btn-primary" type="submit">
        <!--                    <span class="icon-search icon-white" aria-hidden="true"></span>-->
                            <?php echo Text::_('JSEARCH_FILTER_SUBMIT');?>
                        </button>
                    </span>
                </div>
            </div>
            </div>
    </div>
</form>


<hr>