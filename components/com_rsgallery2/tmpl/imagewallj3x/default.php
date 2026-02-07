<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Tmpl\Rootgalleriesj3x;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;

// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;


$this->document->getWebAssetManager()->usePreset('com_rsgallery2.site.imageWallJ3x');

//$layoutName = 'yyy.default';
//$layout = new FileLayout($layoutName);

//--- 04 ------------------------------------------
// $layoutName = 'ImageWall.overlapping_flex';
//--- 02 ------------------------------------------
// $layoutName = 'ImageWall.overlapping_black';
//--- 03 ------------------------------------------
$layoutName = 'ImageWall.rectangle_plain';


$displayData['images'] = $this->items;

if (!empty($this->isDebugSite)) {
    echo '--- latestImages (3)' . '-------------------------------' . '<br>';
}

// echo $layout->render($displayData);

?>

<form id="rsg2_root_image_wall__form" action="<?php
echo Route::_('index.php?option=com_rsgallery2&view=rootgalleriesj3x'); ?>"
      method="post" class="form-validate form-horizontal well">

    <?php
    $layout = new FileLayout($layoutName);
    echo $layout->render($displayData);
    ?>



    <?php //--- 03 ------------------------------------------
    ?>

<!--    <div class="image-wall">-->
<!---->
<!--        --><?php //foreach ($this->items as $idx => $gallery) : ?>
<!---->
<!--            <img class="overlapping-img"-->
<!--                 src="--><?php //echo $gallery->UrlDisplayFile ?><!--" alt="--><?php //echo $gallery->name; ?><!--">-->
<!---->
<!--        --><?php //endforeach; ?>
<!--    </div>-->
<!---->
    <?php //--- 02 ------------------------------------------
    ?>
    <!---->
    <!--        <div class="imgWall">-->
    <!--    -->
    <!--        --><?php //foreach ($this->items as $idx => $gallery) :
    ?>
    <!---->
    <!--            <img class="overlapping-img"-->
    <!--                 src="--><?php //echo $gallery->UrlDisplayFile
    ?><!--" alt="--><?php //echo $gallery->name;
    ?><!--">-->
    <!---->
    <!--        --><?php //endforeach;
    ?>
    <!--        </div>-->

    <?php //--- 0x ------------------------------------------
    ?>

    <!--        <table class="blend-table">-->
    <!---->
    <!--            --><?php //foreach ($this->items as $idx => $gallery) :
    ?>
    <!---->
    <!--                --><?php
    //                $colIdx = $idx % $maxCols;
    //                $rowIdx = intdiv($idx, $maxCols);
    //
    ?>
    <!---->
    <!--                --><?php //if ($colIdx == 0) :
    ?>
    <!--                    <tr>-->
    <!--                --><?php //endif
    ?>
    <!---->
    <!--                <td>-->
    <!--                    <img class="overlapping-img"-->
    <!--                         src="--><?php //echo $gallery->UrlDisplayFile
    ?><!--" alt="--><?php //echo $gallery->name;
    ?><!--">-->
    <!--                </td>-->
    <!---->
    <!--                --><?php //if ($colIdx == $maxCols - 1) :
    ?>
    <!--                    </tr>-->
    <!--                --><?php //endif
    ?>
    <!---->
    <!--            --><?php //endforeach;
    ?>
    <!---->
    <!--        </table>-->
    <!--    </div>-->

    <?php //--- 01 ------------------------------------------
    ?>

    <!--        <table class="table-imgWall">-->
    <!--            <tbody>-->
    <!---->
    <!--            --><?php //foreach ($this->items as $idx => $gallery) :
    ?>
    <!---->
    <!--                --><?php ////--- image wall classes of element -------------------------
    ?>
    <!---->
    <!--                --><?php
    //                $colIdx = $idx % $maxCols;
    //                $rowIdx = intdiv($idx, $maxCols);
    //
    ?>
    <!---->
    <!--                --><?php
    //
    //                // ToDo: make function above / below
    //                //--- image wall classes of element -------------------------
    //
    //                // left upper edge
    //                if ($colIdx == 0 && $rowIdx == 0) {
    //                    $wallClasses = 'left-col top-row';
    //                } else {
    //                    // left column
    //                    if ($colIdx == 0) {
    //                        $wallClasses = 'left-col';
    //                    } else {
    //                        // upper row
    //                        if ($rowIdx == 0) {
    //                            $wallClasses = 'top-row';
    //                        } else {
    //                            $wallClasses = 'inner-cell';
    //                        }
    //                    }
    //                }
    //
    //
    ?>
    <!---->
    <!---->
    <!--                --><?php ////--- Table parts -------------------------
    ?>
    <!---->
    <!--                --><?php //if ($colIdx == 0) :
    ?>
    <!--                    <tr>-->
    <!--                --><?php //endif
    ?>
    <!---->
    <!--                <td>-->
    <!--                    <img class="--><?php //echo $wallClasses
    ?><!--"-->
    <!--                         src="--><?php //echo $gallery->UrlDisplayFile
    ?><!--" alt="--><?php //echo $gallery->name;
    ?><!--">-->
    <!--                </td>-->
    <!---->
    <!--                --><?php //if ($colIdx == $maxCols - 1) :
    ?>
    <!--                    </tr>-->
    <!--                --><?php //endif
    ?>
    <!---->
    <!--            --><?php //endforeach;
    ?>
    <!---->
    <!--            </tbody>-->
    <!--        </table>-->

    <!--    </div>-->
</form>
