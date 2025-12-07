<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Layouts\Search;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;


// ToDo: getWebAssetManager Move to all display php files or include in common *.scss file
HTMLHelper::_('stylesheet', 'com_rsgallery2/site/rsg2_search.css', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('script', 'com_rsgallery2/site/rsg2_search.js', ['version' => 'auto', 'relative' => true]);
// on more use preset ....
//$this->document->getWebAssetManager()->useStyle('com_rsgallery2.site.rsg2_search');

?>

<?php
if (!empty($isDevelopSite)) {
    echo '<span style="color:red">'
        . 'Tasks:  layout search<br>'
        . '* html aria-label ... <br>'
        . '* HTML 5 layout, bootstrap * <br>'
        . 'Search tasks: <br>'
        . '* link from extern ??<br>'
        . '* separate searches for gallery / images -> separate answers<br>'
        . '* Height of button -> BS 5<br>'
        //  . '* <br>'
        //  . '* <br>'
        //  . '* <br>'
        //  . '* <br>'
        . '</span><br><br>';
}

$link = Route::_('index.php?option=com_rsgallery2&view=searchresult'); // \Joomla\CMS\Router\Route::_('index.php'); ? >search?Itemid=101
$placeholder = Text::_('COM_RSGALLERY2_SEARCH_GALLERIES_IMAGES');

/* 2024.12.09 Deprecated: Creation of dynamic property
   Joomla\CMS\Layout\FileLayout::$filterForm is deprecated in
  /homepages/4/d92360456/htdocs/rsgallery2_J4x_upgrade/components/com_rsgallery2/layouts/Search/search.php
  on line 48
**
**
$this->filterForm    = $this->get('FilterForm');
$this->activeFilters = $this->get('ActiveFilters');
/**/
?>

<!--<fields name="filter">-->
<!--    <field-->
<!--            name="search"-->
<!--            type="text"-->
<!--            inputmode="search"-->
<!--            label=""-->
<!--            hint="JSEARCH_FILTER"-->
<!--            class="js-tools-search-string"-->
<!--    />-->


<form class="rsg2_search"
      action="<?php echo $link; ?>"
      method="get"
      role="search">
    <div class="container">

        <!--                <hr>-->
        <!--                <h4>(2) rectangles</h4>-->

        <div class="input-group input-group-sm mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <input class="form-control "
                           type="search"
                           value=""
                           placeholder="<?php echo $placeholder;?>"
                           id="rsg2_search_input"
                    >
                    <span class="input-group-append">
                                    <button class="btn btn-outline-secondary border  ms-n5" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                </div>
            </div>
        </div>

        <!--                <hr>-->
        <!--                <h4>(3) round</h4>-->
        <!---->
        <!--                <div class="input-group input-group-sm mb-3">-->
        <!--                    <div class="col-md-5">-->
        <!--                            <div class="input-group">-->
        <!--                                <input class="form-control rounded-pill"-->
        <!--                                       type="search"-->
        <!--                                       value=""-->
<!--                                       placeholder="--><?php //echo $placeholder;?><!--"-->
        <!--                                       id="rsg2_search_input"-->
        <!--                                >-->
        <!--                                <span class="input-group-append">-->
        <!--                                    <button class="btn btn-primary border-bottom-0 border rounded-pill ms-n5" type="submit">-->
        <!--                                        <i class="fa fa-search"></i>-->
        <!--                                    </button>-->
        <!--                                </span>-->
        <!--                            </div>-->
        <!--                    </div>-->
        <!--                </div>-->

        <!--                <hr>-->
        <!--                <h4>(X) old Version)</h4>-->

        <!--            <div class="col-md-5 float-right">-->
        <!--                <div class="input-group ">-->
        <!--                    <span class="input-group-prepend">-->
        <!--                            <div class="input-group-text bg-transparent border-right-0">-->
        <!--                                <i class="fa fa-search"></i>-->
        <!--                            </div>-->
        <!--                        </span>-->
        <!--                    <input class="form-control py-2 border-left-0 border"-->
        <!--                           type="search"-->
        <!--                           value=""-->
<!--                           placeholder="--><?php //echo $placeholder;?><!--"-->
        <!--                           id="rsg2_search_input">-->
        <!--                    >-->
        <!--                    <span class="input-group-append">-->
        <!--                        <button class="btn btn-primary" type="submit">-->
<!--                            --><?php //echo Text::_('JSEARCH_FILTER_SUBMIT');?>
        <!--                        </button>-->
        <!--                    </span>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--            </div>-->

        <!--                <hr>-->

    </div>
</form>

<?php if (!empty($isDebugSite)) : ?>
    <hr>
<?php endif; ?>
