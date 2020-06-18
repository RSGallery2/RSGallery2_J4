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

// toDo: Checkout https://www.cssscript.com/clean-tree-diagram/

HTMLHelper::_('bootstrap.framework');


    function GalleriesListAsHTML($galleries)
    {
        $html = '';

        try {

            if ( ! empty ($galleries)) {
                // all root galleries and nested ones
                $html = GalleriesOfLevelHTML($galleries, 0, 0);
            }
        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $html;
    }

    function GalleriesOfLevelHTML($galleries, $parentId, $level)
    {
        $html = [];

        try {

            $galleryHTML = [];

            foreach ($galleries as $gallery) {

                if ($gallery->parent_id == $parentId) {

                    // html of this gallery
                    $galleryHTML [] = GalleryHTML($gallery, $level);

                    $subHtml = GalleriesOfLevelHTML($galleries, $gallery->id, $level+1);
                    if (!empty ($subHtml)) {
                        $galleryHTML [] = $subHtml;
                    }
                }
            }

            // surround with <ul>
            if ( ! empty ($galleryHTML)) {

                $lineStart = str_repeat(" ", 3*($parentId));

                array_unshift ($galleryHTML,  $lineStart . '<ul class="list-group">');
                $galleryHTML [] = $lineStart . '</ul>';

                $html = $galleryHTML;
            }

        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }

        return implode($html);
    }

    // ToDo use styling for nested from https://stackoverflow.com/questions/29063244/consistent-styling-for-nested-lists-with-bootstrap


    function GalleryHTML($gallery, $level)
    {
        $html = [];

        $lineStart = str_repeat(" ", 3*($level+1));
        $identHtml = '';
        if ($level > 0) {
            $identHtml = '<span class="text-muted">';
            $identHtml .= str_repeat('⋮&nbsp;&nbsp;&nbsp;', $level - 1);
            $identHtml .= '</span>';
            $identHtml .= '-&nbsp;';
        }

        $id = $gallery->id;
        $parent = $gallery->parent_id;
        $order = $gallery->level;
        $name = $gallery->name;

        try {

            $html = <<<EOT
$lineStart<li class="list-group-item">
$lineStart   $identHtml<span> id: </span><span>$id</span>
$lineStart   <span> parent: </span><span>$parent</span>
$lineStart   <span> order: </span><span>$order</span>
$lineStart   <span> name:</span><span>$name</span>
$lineStart</li>
EOT;

        } catch (RuntimeException $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage());
        }

        return $html;
    }





?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferOldJ3xGalleries'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="col-md-2">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
		<div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'DBTransferOldJ3xGalleries')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DBTransferOldJ3xGalleries', Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE', true)); ?>

                <legend><strong><?php echo Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'); ?></strong></legend>

                <?php

					try
					{
                        if (count ($this->items) == 1) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_IS_EMPTY');
                            echo '   <h2><span class="badge badge-pill badge-success">' . $keyTranslation . '</span></h2>';
                        }

                        if (count ($this->items) == 0) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_TABLE_EMPTY');
                            echo '   <h2><span class="badge badge-pill badge-error">' . $keyTranslation . '</span></h2>';
                        }

                        echo GalleriesListAsHTML($this->items);


                        if (count ($this->items) == 1) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_JUMP_TO_J3x_GALLERIES');
                            $link = Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=DBTransferOldJ3xGalleries');
                            echo  '<a  class="badge badge-pill badge-notice" href="' . $link . '" target="_blank" '
                                . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >' . $keyTranslation . '</a>';;
                        }


//
//                $j4x_galleries = $j3xModel->j4_GalleriesToJ3Form($j3xModel->j4x_galleriesList());
//                $this->j4x_galleriesHtml = $j3xModel->GalleriesListAsHTML($j4x_galleries);

                        echo '<hr>';

					}
					catch (RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= 'Error rawEdit view: "' . 'DBTransferOldJ3xGalleries' . '"<br>';
						$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
					
						$app = Factory::getApplication();
						$app->enqueueMessage($OutTxt, 'error');
					}

				?>

				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value="" />
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


