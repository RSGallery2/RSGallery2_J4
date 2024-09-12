<?php // no direct access
/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 * @copyright  (C) 2003-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;


//HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
//HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);
$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

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
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $html;
    }

    function GalleriesOfLevelHTML($galleries, $parentId, $indent)
    {
        $html = [];

        try {

            $galleryHTML = [];

            foreach ($galleries as $gallery) {

                if ($gallery->parent_id == $parentId) {

                    // html of this gallery
                    $galleryHTML [] = GalleryHTML($gallery, $indent);

                    $subHtml = GalleriesOfLevelHTML($galleries, $gallery->id, $indent+1);
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

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return implode($html);
    }

    // ToDo use styling for nested from https://stackoverflow.com/questions/29063244/consistent-styling-for-nested-lists-with-bootstrap


    function GalleryHTML($gallery, $indent)
    {
        $html = [];

        $lineStart = str_repeat(" ", 3*($indent+1));
        $identHtml = '';
        if ($indent > 0) {
            $identHtml = '<span class="text-muted">';
            $identHtml .= str_repeat('⋮&nbsp;&nbsp;&nbsp;', $indent - 1);
            $identHtml .= '</span>';
            $identHtml .= '-&nbsp;';
        }

        $id = $gallery->id;
        $parent = $gallery->parent_id;
        $level = $gallery->level;
        $name = $gallery->name;
        $path = '   [' . $gallery->path . ']';

        try {

            $html = <<<EOT
$lineStart<li class="list-group-item">
$lineStart   $identHtml<span> id: </span><span>$id</span>
$lineStart   <span> parent: </span><span>$parent</span>
$lineStart   <span> level: </span><span>$level</span>
$lineStart   <span> name:</span><span>$name</span>
$lineStart   <span> path: </span><span>$path</span>
$lineStart</li>
EOT;

        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }

        return $html;
    }





?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries'); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="d-flex flex-row">
		<?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
				<?php echo $this->sidebar; ?>
			</div>
		<?php endif; ?>
        <!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
        <div class="flex-fill">
			<div id="j-main-container" class="j-main-container">

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'dbtransferj3xgalleries')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'dbtransferj3xgalleries', Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE', true)); ?>

                <legend><strong><?php echo Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_DESC'); ?></strong></legend>

                <?php

					try
					{
                        if (count ($this->items) == 1) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_IS_EMPTY');
                            echo '   <h2><span class="badge badge-pill bg-success">' . $keyTranslation . '</span></h2>';
                        }

                        if (count ($this->items) == 0) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_TABLE_EMPTY');
                            echo '   <h2><span class="badge badge-pill bg-error">' . $keyTranslation . '</span></h2>';
                        }

                        echo GalleriesListAsHTML($this->items);


                        if (count ($this->items) == 1) {
                            $keyTranslation = Text::_('COM_RSGALLERY2_GALLERIES_AS_TREE_JUMP_TO_J3X_GALLERIES');
                            $link = Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=dbtransferj3xgalleries');
//                            echo  '<br><h3></h3><a  class="badge badge-pill badge-notice" href="' . $link . '" target="_blank" '
//                                . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >' . $keyTranslation . '</a></h3>';;
                            echo  '<br><h3></h3><a  class="badge badge-pill bg-notice" href="' . $link . '" " '
                                . ' title="' . Text::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >' . $keyTranslation . '</a></h3>';;
                        }


//
//                $j4x_galleries = $j3xModel->j4_GalleriesToJ3Form($j3xModel->j4x_galleriesList());
//                $this->j4x_galleriesHtml = $j3xModel->GalleriesListAsHTML($j4x_galleries);

                        echo '<hr>';

					}
					catch (\RuntimeException $e)
					{
						$OutTxt = '';
						$OutTxt .= 'Error rawEdit view: "' . 'dbtransferj3xgalleries' . '"<br>';
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


