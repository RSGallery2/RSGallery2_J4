<?php // no direct access
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2021 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// HTMLHelper::_('bootstrap.framework');

HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/imagesProperties.css', array('version' => 'auto', 'relative' => true));
HTMLHelper::_('script', 'com_rsgallery2/backend/imagesProperties.js', ['version' => 'auto', 'relative' => true]);

$ImageLostAndFoundList = $this->oImgRefs->ImageLostAndFoundList;

/**
 * @param ImageReferences $ImageReferences
 * @param $form not used
 *
 * @since 4.3.0
 */
function DisplayImageDataTable($ImageReferences)
{
	$html = [];


//-------------------------------------
// Header
//-------------------------------------
	echo '<br>';

	echo '<table class="table table-striped table-condensed">';
	echo '    <caption><h3>' . JText::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST') . '</h3></caption>';
	echo '    <thead>';
	echo '        <tr>';





	return implode('\n',$html);

}

$LostAndFountHtml = '';
if ( ! empty ($ImageLostAndFoundList))
{
	$LostAndFountHtml = DisplayImageDataTable ($ImageLostAndFoundList);
}
?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintConsolidateDb'); ?>"
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

				<?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'MaintConsolidateDb')); ?>

				<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'MaintConsolidateDb', Text::_('COM_RSGALLERY2_IMAGES_LOST_AND_FOUND_TITLE', true)); ?>

				<div style="max-width: 400px;">
					<strong><?php echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'); ?></strong>
				</div>

				<?php
                // if (count($ImageLostAndFoundList) == 0) :
                    if (empty($ImageLostAndFoundList)) :
                ?>
					<div class="alert alert-no-items">
						<?php
                            // echo JText::_('COM_RSGALLERY2_MAINT_CONSOLDB_NO_MISSING_ITEMS_TXT');
                            echo JText::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE');
                        ?>
					</div>
				<?php else : ?>

					<div class="pull-right">
						<?php
						// Specify parent gallery selection
//						echo $this->form->renderFieldset('maintConsolidateDB');
						?>
					</div>

					<div class="span12">
						<div class="row-fluid">
							<?php
							// Info about lost and found images
							DisplayImageDataTable($this->ImageReferences, $this->form);
							?>
						</div>
					</div>

				<?php endif; ?>

				<div class="form-actions">
					<br>
				</div>

				<fieldset class="refresh">
					<!--legend><?php echo JText::_('COM_RSGALLERY2_REFRESH_TEXT'); ?>XXXXX</legend-->
                    <div class="form-actions">
                        <a class="btn btn-primary"
                           title="<?php echo JText::_('COM_RSGALLERY2_REPEAT_CHECKING_INCONSITENCIES_DESC'); ?>"
                           href="index.php?option=com_rsgallery2&amp;view=maintConsolidateDB">
							<?php echo JText::_('COM_RSGALLERY2_REPEAT_CHECKING'); ?>
                        </a>
                        <a class="btn btn-primary"
                           href="index.php?option=com_rsgallery2&amp;view=maintenance">
		                    <?php echo JText::_('COM_RSGALLERY2_CANCEL'); ?>
                        </a>
                    </div>
 				</fieldset>




				<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

				<?php echo HTMLHelper::_('bootstrap.endTabSet'); ?>

				<!--input type="hidden" name="option" value="com_rsgallery2" />
				<input type="hidden" name="rsgOption" value="maintenance" /-->

				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="ImageReferenceList" value="<?php
                    // $ImageReferenceList = $this->ImageReferences->ImageReferenceList;
                    // $JsonEncoded        = json_encode($ImageReferenceList);
                    // //$JsonEncoded = json_encode($ImageReferenceList, JSON_HEX_QUOT);
                    // //$HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
                    // $HtmlOut = htmlentities($JsonEncoded, ENT_QUOTES, "UTF-8");
                    // echo $HtmlOut;
				?>" />

				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
		</div>
	</div>

	<?php echo HTMLHelper::_('form.token'); ?>
</form>


