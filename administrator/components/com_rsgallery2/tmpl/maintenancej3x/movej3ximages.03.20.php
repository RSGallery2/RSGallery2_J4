<?php
// no direct access

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @copyright  (C) 2003-2024 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/moveJ3xImages.css', array('version' => 'auto', 'relative' => true));
// on more use preset ....
$this->document->getWebAssetManager()->useStyle('com_rsgallery2.backend.moveJ3xImages');

// Items exist
// ToDO: Script should not fail when no images ... ==> use web asset preset .. then for both
if ($this->isDoCopyJ3xImages) {
    // HTMLHelper::_('script', 'com_rsgallery2/backend/moveJ3xImages.js', ['version' => 'auto', 'relative' => true]);
    // on more use preset ....
    $this->document->getWebAssetManager()->useScript('com_rsgallery2.backend.moveJ3xImages');
    // $this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.moveJ3xImages');
}

Text::script('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST', true);

// Drag and Drop security id on ajax call.
$script[] = 'var Token = \'' . Session::getFormToken() . '\';';
Factory::getApplication()->getDocument()->addScriptDeclaration(implode("\n", $script));

// $app = Factory::getApplication();

?>

    <form action="<?php
    echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=movej3ximages'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate">
        <div class="d-flex flex-row">
            <?php
            if (!empty($this->sidebar)) : ?>
                <div id="j-sidebar-container" class="">
                    <?php
                    echo $this->sidebar; ?>
                </div>
            <?php
            endif; ?>
            <!--div class="<?php
            echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
            <div class="flex-fill">
                <div id="j-main-container" class="j-main-container">

                    <?php
                    echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', ['active' => 'movej3ximages']); ?>

                    <?php
                    echo HTMLHelper::_(
                        'bootstrap.addTab',
                        'myTab',
                        'movej3ximages',
                        Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES', true),
                    ); ?>

                    <!--legend><strong><?php
                    echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES'); ?></strong></legend-->

                    <?php
                    // all images are moved, no gallery displayed
                    if (!$this->isDoCopyJ3xImages) { ?>

                        <div class="allJ3xMovedArea">
                        <span class="badge bg-success allJ3xMovedText">
                            <?php
                            echo Text::_('COM_RSGALLERY2_J3X_ALL_IMAGES_MOVED'); ?>
                        </span>
                        </div>

                    <?php
                    } else { ?>

                        <p style="max-width:400px">
                            <?php
                            echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_USE') . '.&nbsp'
                                . Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_USE_DESC') . '.&nbsp'
                                . Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_USE_DESC_B');
                            ?>
                        </p>
                        <?php
                        // specify gallery
                        // toDO: change name as used for all
                        echo $this->form->renderFieldset('j3x_gallery');
                        ?>

                        <button id="moveByGallery" type="button" class="btn btn-success btn-rsg2"
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_J3X_IMAGES_MOVE_BY_GALLERY_DESC'); ?>"

                        >
                            <span class="icon-checkbox" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_J3X_IMAGES_MOVE_BY_GALLERY'); ?>
                        </button>
                        <button id="moveByCheckedGalleries" type="button" class="btn btn-success btn-rsg2"
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK_DESC'); ?>"
                                disabled
                        >
                            <span class="icon-out-2" aria-hidden="false"></span>
                            <span class="icon-image" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK'); ?>
                        </button>
                        <!--button id="deSelectGallery" type="button" class="btn btn-success btn-rsg2"
                            title="<?php
                        echo "???" . Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY_DESC'); ?>"
                    >
                        <span class="icon-checkbox-unchecked" aria-hidden="false"></span>
                        <?php
                        echo "???" . Text::_('COM_RSGALLERY2_J3X_IMAGES_DESELECT_BY_GALLERY'); ?>
                    </button-->

                        <button id="moveAllJ3xImjages" type="button" class="btn btn-success btn-rsg2"
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_MOVE_SELECTED_J3X_IMAGES_DESC'); ?>"
                        >
                            <span class="icon-out-2" aria-hidden="false"></span>
                            <span class="icon-images" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_MOVE_ALL_J3X_IMAGES'); ?>
                        </button>

                        <hr>

                        <button id="selectNextGallery" type="button" class="btn btn-info btn-rsg2"
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_GALLERY_DESC'); ?>"

                        >
                            <span class="icon-checkbox" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_GALLERY'); ?>
                        </button>
                        <button id="selectNextGalleries10" type="button" class="btn btn-info btn-rsg2"
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERY_DESC'); ?>"

                        >
                            <span class="icon-checkbox" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_10_GALLERIES'); ?>
                        </button>
                        <button id="selectNextGalleries100" type="button" class="btn btn-info btn-rsg2 "
                                title="<?php
                                echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERY_DESC'); ?>"
                        >
                            <span class="icon-checkbox" aria-hidden="false"></span>
                            <?php
                            echo Text::_('COM_RSGALLERY2_J3X_IMAGES_SELECT_NEXT_100_GALLERIES'); ?>
                        </button>

                        <hr>

                        <!--div id="moveImageArea" >



                            <hr>
                        </div-->


                        <h3><?php
                            echo Text::_('COM_RSGALLERY2_J3X_GALLERIES_MOVE_IMAGES_LIST'); ?></h3>

                        <table class="table table-striped" id="imageList_j3x">

                            <caption id="captionTable" class="sr-only">
                                <?php
                                echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>
                                , <?php
                                echo Text::_('JGLOBAL_SORTED_BY'); ?>
                            </caption>
                            <thead>
                            <tr>
                                <td style="width:1%" class="text-center">
                                    <?php
                                    echo HTMLHelper::_('grid.checkall'); ?>
                                </td>

                                <!--th width="1%" class="text-center">
                                <?php
                                echo Text::_('JSTATUS'); ?>
                            </th-->
                                <th width="1%" class="center">
                                    `gallery_id`
                                </th>
                                <th width="10%" class="center">
                                    `name`
                                </th>
                                <th width="15%" class="center">
                                    `%`
                                </th>
                                <th width="40%" class="center">
                                    `Info`
                                </th>
                            </tr>
                            </thead>

                            <tbody>

                            <?php
                            $FoundNr = 0;
                            foreach ($this->j4x_galleries as $i => $item) {
                                $allMoved = false;
                                if (!in_array($item->id, $this->galleryIds4ImgsToBeMoved)) {
                                    $allMoved = true;

                                    // toDo: two views (a) only unassinged b) all
                                    continue;
                                }

                                $FoundNr += 1;

                                $imgToBeMoved = $this->h4j3xGalleriesData [$item->id]['toBeMoved'];
                                $imgAvailable = $this->h4j3xGalleriesData [$item->id]['count'];

                                // a) Must be transferred b) check

                                //                        $isMerged =in_array ($item->id, $this->j3x_imageIdsMerged);
                                //                        if ($isMerged){
                                //                            $mergedStatusHtml =  isOKIconHtml ('Image is merged');
                                //                        } else {
                                //                            $mergedStatusHtml =  isNotOkIconHtml ('Image is not merged');
                                //                        }

                                if ($allMoved) {
                                    $mergedStatusHtml = isOKIconHtml('Gallery images are merged');
                                } else {
                                    $mergedStatusHtml = isNotOkIconHtml('Gallery images are not merged');
                                }

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php
                                        echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                                    </td>

                                    <td width="1%" class="center">
                                        <?php
                                        $link = Route::_(
                                            "index.php?option=com_rsgallery2&view=image&task=gallery.edit&id=" . $item->id,
                                        );
                                        echo '<a href="' . $link . '"">' . $item->id . '</a>';
                                        ?>
                                    </td>
                                    <td width="1%" class="center">
                                        <?php
                                        $link = Route::_(
                                            "index.php?option=com_rsgallery2&view=image&task=gallery.edit&id=" . $item->id,
                                        );
                                        echo '<a id="galleryId_' . $item->id . '" href="' . $link . '"">' . $item->name . '</a>';
                                        ?>
                                    </td>

                                    <td width="1%" class="text-left">
                                        <?php
                                        echo $mergedStatusHtml; ?>
                                        <!--/td>

                                        <td width="1%" class="center"-->
                                        <span class="badge badge-pill bg-primary">
                                        <i class="icon-move"></i>
                                        <?php
                                        echo $imgToBeMoved; ?>
                                    </span>
                                        <span class="badge badge-pill bg-secondary">
                                        <i class="icon-images"></i>
                                        <?php
                                        //echo ' (' . $imgAvailable . ')'; ?>
                                            <?php
                                            echo $imgAvailable; ?>
                                    </span>
                                    </td>
                                    <td class="left">
                                        <?php
                                        echo createImgFlagsArea($item->id); ?>
                                    </td>
                                    <!--td width="1%" class="center">
                                    <?php
                                    //echo $item->alias; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->descr; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    echo $item->gallery_id; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->title; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->hits; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->date; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->rating; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->votes; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->comments; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->published; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->checked_out; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->checked_out_time; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    echo $item->ordering; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->approved; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->userid; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->params; ?>
                                </td>
                                <td width="1%" class="center">
                                    <?php
                                    //echo $item->asset_id; ?>
                                </td-->
                                </tr>

                                <?php
                            }
                            ?>
                            </tbody>

                        </table>

                        <?php
                        // all images are moved, no gallery displayed
                        if ($FoundNr == 0) { ?>
                            <div class="allJ3xMovedArea">
                            <span class="badge bg-success allJ3xMovedText">
                                <?php
                                echo Text::_('COM_RSGALLERY2_J3X_ALL_IMAGES_MOVED'); ?>
                            </span>
                            </div>
                        <?php
                        } ?>

                    <?php
                    } ?>


                    <?php
                    echo HTMLHelper::_('bootstrap.endTab'); ?>

                    <?php
                    echo HTMLHelper::_('bootstrap.endTabSet'); ?>

                    <!--input type="hidden" name="option" value="com_rsgallery2" />
                    <input type="hidden" name="rsgOption" value="maintenance" /-->

                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="task" value=""/>
                    <?php
                    echo HTMLHelper::_('form.token'); ?>

                </div>
            </div>
        </div>

        <?php
        echo HTMLHelper::_('form.token'); ?>
    </form>


<?php

function isOKIconHtml($title)
{
    $html = <<<EOT
        <div class="btn-group">
            <a class="tbody-icon" href="javascript:void(0);" aria-labelledby="cbpublish1-desc">
                <span class="fas fa-check" aria-hidden="true"/>
            </a>
            <div role="tooltip" id="cbpublish1-desc" style="min-width: 300px max-width: 400% !important;">$title</div>
        </div>
        EOT;

    return $html;
}

function isNotOkIconHtml($title)
{
    $html = <<<EOT
        <div class="btn-group">
            <a class="tbody-icon active" href="javascript:void(0);" aria-labelledby="cbunpublish2-desc">
                <span class="fas fa-times" aria-hidden="true"/>
            </a>
            <div role="tooltip" id="cbpublish1-desc" style="min-width: 100% max-width: 100% !important;">$title</div>
        </div>
        EOT;

    return $html;
}


function createImgFlagsArea($id)
{
    $html = <<<EOT
        <div id="ImgFlagsArea_$id" class="imgFlagArea">
        </div>
        EOT;

    return $html;
}




