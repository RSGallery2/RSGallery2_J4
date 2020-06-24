<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

//HTMLHelper::_('bootstrap.framework');


?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DbTransferOldJ3xImages'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
    <div class="row">
        <?php if (!empty($this->sidebar)) : ?>
            <div id="j-sidebar-container" class="col-md-2">
                <?php echo $this->sidebar; ?>
            </div>
        <?php endif; ?>
        <div class="<?php if (!empty($this->sidebar)) {
            echo 'col-md-10';
        } else {
            echo 'col-md-12';
        } ?>">
            <div id="j-main-container" class="j-main-container">

                <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DbTransferOldJ3xImages')); ?>

                <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbTransferOldJ3xImages', Text::_('COM_RSGALLERY2_TRANSFER_J3X_IMAGES', true)); ?>

                <legend><strong><?php echo Text::_('COM_RSGALLERY2_TRANSFER_J3X_IMAGES'); ?></strong></legend>

                <h3><?php echo Text::_('COM_RSGALLERY2_J3X_IMAGES_LIST'); ?></h3>

                <table class="table table-striped" id="galleryList">

                    <caption id="captionTable" class="sr-only">
                        <?php echo Text::_('COM_CATEGORICOM_RSGALLERY2_TABLE_CAPTIONES_TABLE_CAPTION'); ?>
                        , <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
                    </caption>
                    <thead>
                    <tr>
                        <td style="width:1%" class="text-center">
                            <?php echo HTMLHelper::_('grid.checkall'); ?>
                        </td>



					</tr>
                    </thead>

                    <tbody>

                    <?php
                    foreach ($this->j3x_images as $i => $item) {
                        $identHtml = str_repeat('⋮&nbsp;&nbsp;&nbsp;', $item->level);

                        ?>
                        <tr class="row<?php echo $i % 2; ?>">

                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>

                            <td class="text-center">
                                <?php echo $item->id; ?>
                            </td>


                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>

                </table>


                <?php

                try {


                    echo '<hr>';
                } catch (RuntimeException $e) {
                    $OutTxt = '';
                    $OutTxt .= 'Error rawEdit view: "' . 'DbTransferOldJ3xImages' . '"<br>';
                    $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

                    $app = Factory::getApplication();
                    $app->enqueueMessage($OutTxt, 'error');
                }

                ?>

                <?php echo HTMLHelper::_('bootstrap.endTab'); ?>

                <?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

                <!--input type="hidden" name="option" value="com_rsgallery2" />
                <input type="hidden" name="rsgOption" value="maintenance" /-->

                <input type="hidden" name="task" value=""/>
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>


