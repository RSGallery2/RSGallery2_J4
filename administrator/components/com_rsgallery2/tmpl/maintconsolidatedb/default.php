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
use Joomla\Component\Finder\Administrator\Indexer\Parser\Html;

// HTMLHelper::_('bootstrap.framework');

HTMLHelper::_('stylesheet', 'com_rsgallery2/backend/maintConsolidateDB.css', array('version' => 'auto', 'relative' => true));
// HTMLHelper::_('script', 'com_rsgallery2/backend/maintConsolidateDB.js', ['version' => 'auto', 'relative' => true]);

$ImageLostAndFoundList = $this->oImgRefs->ImageLostAndFoundList;

/**
 * @param $ImageLostAndFoundList $ImageReferences
 *
 * @since 4.3.0
 */
function DisplayImageDataTable($ImageLostAndFoundList)
{
	$html = [];


//-------------------------------------
// Header
//-------------------------------------
    $html[] = '<br>';

    $html[] = '<table class="table table-striped table-condensed  caption-top">';
    $html[] = '    <caption><h3>' . Text::_('COM_RSGALLERY2_MISSING_IMAGE_REFERENCES_LIST') . '</h3></caption>';
    $html[] = '    <thead>';
    $html[] = '        <tr>';

     // Counter
    $html[] = '<th>';
    //$html[] = '';
    $html[] = Text::_('COM_RSGALLERY2_NUM');
    $html[] = '</th>';
    

    /*
    $html = array (); // Check all empty
    $html[] = '<th class="center" width="1%">';
    //$html[] = '2'; // empty
    $html[] = '</th>';
    
    /**/
     // Check all
    $html[] = '<th>';
    //$html[] = '';
    $html[] = HtmlHelper::_('grid.checkall');
    $html[] = '</th>';
    

     // filename
    $html[] = '<th class="align-left" width="20%">';
    // $html[] =  '3';
    $html[] = Text::_('COM_RSGALLERY2_FILENAME');
    $html[] = '</th>';
    

     // In Database
    $html[] = '<th class="center">';
    //$html[] = '4';
    $html[] = Text::_('COM_RSGALLERY2_IN_BR_DATABASE');
    $html[] = '</th>';
    

     // display
    $html[] = '<th class="center">';
    //$html[] = '5';
    $html[] = Text::_('COM_RSGALLERY2_DISPLAY_BR_FOLDER');
    $html[] = '</th>';
    

     // In original
    $html[] = '<th class="center">';
    //$html[] =  '6';
    $html[] = Text::_('COM_RSGALLERY2_ORIGINAL_BR_FOLDER');
    $html[] = '</th>';
    

     // thumb
    $html[] = '<th class="center">';
    //$html[] = '7';
    $html[] = Text::_('COM_RSGALLERY2_THUMB_BR_FOLDER');
    $html[] = '</th>';
    

//    // watermarked
//    if ($ImageReferences->UseWatermarked)
//    {
//         // watermarked
//        $html[] = '<th class="center">';
//        //$html[] =  '8';
//        $html[] = Text::_('COM_RSGALLERY2_WATERMARK_BR_FOLDER');
//        $html[] = '</th>';
//        
//    }

    // thumb
    $html[] = '<th class="center sizes_column" >';
    //$html[] = '7';
    $html[] = Text::_('COM_RSGALLERY2_SIZES_BR_FOLDERS');
    $html[] = '</th>';


    // action
    $html[] = '<th class="center">';
    //$html[] =  '9';
    $html[] = Text::_('COM_RSGALLERY2_ACTION');
    $html[] = '</th>';
    

     // parent gallery
    $html[] = '<th class="center" width="20%">';
    //$html[] = '10';
    $html[] = Text::_('COM_RSGALLERY2_GALLERY'); // COM_RSGALLERY2_PARENT_BR_GALLERY
    $html[] = '</th>';
    

     // image
    $html[] = '<th class="center" width="10%">';
    //$html[] = '11';
    $html[] = Text::_('COM_RSGALLERY2_IMAGE');
    $html[] = '</th>';
    
    
    
    
    
    
    
    
    


    $html[] = '        </tr>'; // end of row
    $html[] = '    </thead>';


//-------------------------------------
// table body
//-------------------------------------

    $html[] = '    <tbody>';

    $Idx = -1;
    foreach ($ImageLostAndFoundList as $ImageReference) {
        $Idx += 1;

    //-------------------------------------
    // Next data row
    //-------------------------------------

        $html[] = '        <tr>'; // start of row

        // row index
        $html[] = '<td>';
        $html[] = '' . (string) $Idx;
        $html[] = '</td>';

        // check boxes ToDo: see other lists
        $html[] = '<td>';
        $html[] = '' . HTMLHelper::_('grid.id', '' . (string) $Idx, $Idx);
        $html[] = '</td>';

        // filename
        $html[] = '<td>';
        $html[] = $ImageReference->imageName;
        $html[] = '</td>';

        // database entry found
        if ($ImageReference->IsImageInDatabase)
        {
            $html[] = '<td class="center">';
            $html[] = '    <i class="icon-ok hasTooltip" data-original-title="database entry found" ';
            $html[] = '      title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_DATABASE_ENTRY_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
        }
        else
        {
            // Not found -> button
            $html[] = '<td class="center">';
            $html[] = '    <a class="btn btn-danger btn-small jgrid data-bs-toggle="tooltip" data-bs-placement="top" db_missing inside_button" ';
            $html[] = '         title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_CREATE_DATABASE_ENTRY') . '" ';
            $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createImageDbItems\')" ';
            $html[] = '         href="javascript:void(0);"';
            $html[] = '     >';
            $html[] = '         <span class="icon-database"></span>';
            $html[] = '     </a>';
            $html[] = '</td>';
        }

        // display entry found
        if ($ImageReference->IsDisplayImageFound)
        {
            $html[] = '<td class="center">';
            //$html[] = '5';
            $html[] = '    <i class="icon-ok hasTooltip" data-original-title="display image found" ';
            $html[] = '      title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
        }
        else
        {
            $html[] = '<td class="center">';
            //$html[] = '5';
            $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="display image not found" ';
            $html[] = '      title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_DISPLAY_IMAGE_NOT_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
        }

        // original image found
        if ($ImageReference->IsOriginalImageFound)
        {
            
            $html[] = '<td class="center">';
            //$html[] = '6';
            $html[] = '    <i class="icon-ok hasTooltip" data-original-title="original image found" ';
            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
            
        }
        else
        {
             // database
            $html[] = '<td class="center">';
            //$html[] = '6';
            $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_ORIGINAL_IMAGE_NOT_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
            
        }

        // thumb image found
        if ($ImageReference->IsThumbImageFound)
        {
            
            $html[] = '<td class="center">';
            //$html[] = ' 7;';
            $html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
            
        }
        else
        {
             // database
            $html[] = '<td class="center">';
            //$html[] = ' 7';
            $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_THUMB_IMAGE_NOT_FOUND') . '" ';
            $html[] = '    />';
            $html[] = '</td>';
            
        }

//        // Watermark
//        if ($ImageReference->IsWatermarkedImageFound)
//        {
//
//            $html[] = '<td class="center">';
//            //$html[] = '8';
//            $html[] = '    <i class="icon-ok hasTooltip" data-original-title="thumb image found" ';
//            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_FOUND') . '" ';
//            $html[] = '    />';
//            $html[] = '</td>';
//
//        }
//        else
//        {
//             // database
//            $html[] = '<td class="center">';
//            //$html[] = '8';
//            $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="original image not found" ';
//            $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_WATERMARK_IMAGE_NOT_FOUND') . '" ';
//            $html[] = '    />';
//            $html[] = '</td>';
//
//        }


        // sizes
        $html[] = '<td class="center">';

        foreach ($ImageReference->sizeFilePaths as $size => $sizePath) {

            $isSizeFound = $ImageReference->IsSizes_ImageFound [$size];

            $html[] = '<div class="img_sizes">';
            //$html[] = '<span>' . $size . ': </span>';
            $html[] = $size . ': ';

            if ($isSizeFound) {

                $html[] = '    <i class="icon-ok hasTooltip" data-original-title="size image found" ';
                $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_SIZE_IMAGE_FOUND') . '" ';
                $html[] = '    ></i>';

            }
            else {

                $html[] = '    <i class="icon-cancel hasTooltip" data-original-title="size image not found" ';
                $html[] = '      title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_SIZE_IMAGE_NOT_FOUND') . '" ';
                $html[] = '    ></i>';

            }
            $html[] = '</div>';

        }




        $html[] = '</td>';





        // action
        $html[] = '<td class="center">';
        // if ($ImageReference->IsMainImageMissing(ImageReference::dontCareForWatermarked))
        {
            //$html[] = '9';
            $html[] = '    <a class="btn btn-primary btn-small jgrid hasTooltip inside_button" ';
            $html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_CREATE_MISSING_IMAGES_IN_ROW') . '" ';
            $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.createMissingImages\')" ';
            $html[] = '         href="javascript:void(0);"';
            $html[] = '     >';
            $html[] = '         <span class="icon-image"></span>';
            $html[] = '     </a>';
        }
        // if($ImageReferences->)
        {
            $html[] = '     <a class="btn btn-secondary btn-small jgrid hasTooltip inside_button" ';
            $html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_ASSIGN_GALLLERY_IN_ROW') . '" ';
            $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.assignParentGallery\')" ';
            $html[] = '         href="javascript:void(0);"';
            $html[] = '     >';
            $html[] = '         <span class="icon-images"></span>';
            $html[] = '     </a>';
        }
        //if($ImageReferences->)
        {
            $html[] = '     <a class="btn btn-success btn-small jgrid hasTooltip inside_button" ';
            $html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_REPAIR_ISSUES_IN_ROW') . '" ';
            $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.repairAllIssuesItems\')" ';
            $html[] = '         href="javascript:void(0);"';
            $html[] = '     >';
            $html[] = '         <span class="icon-refresh"></span>';
            $html[] = '     </a>';
        }
        //if($ImageReferences->)
        {
            $html[] = '     <a class="btn btn-danger btn-small jgrid hasTooltip inside_button" ';
            $html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_RSGALLERY2_DELETE_SUPERFLOUS_ITEMS_IN_ROW') . '" ';
            $html[] = '         onclick="return listItemTask(\'cb' . $Idx . '\',\'MaintConsolidateDb.deleteRowItems\')" ';
            $html[] = '         href="javascript:void(0);"';
            $html[] = '     >';
            $html[] = '         <span class="icon-delete"></span>';
            $html[] = '     </a>';
        }
        $html[] = '</td>';
        

         //  parent gallery
        $html[] = '<td class="center">';
        //$html[] = '    10';

        // google (1) joomla formfield array
        // google (2) joomla display array of form fields

//		$field = $form->getFieldset('maintConsolidateDB');
//	    if ($ImageReference->ParentGalleryId > -1) {
        if ($ImageReference->parentGalleryId)
        {

            //$html[] = '' . $ImageReference->ParentGalleryId . ' ';
            $html[] = '' . $ImageReference->parentGalleryId . ' ';
        }
        else
        {
            $html[] = '<span class="icon-cancel">';
        }

        //$html[] = $form->renderFieldset('maintConsolidateDB');
//	    $field = $form->getFieldset('maintConsolidateDB');
//	    $html[] = $field->input;

        $html[] = '</td>';
        

         // image

        // Image is defined
        if ($ImageReference->imagePath !== '')
        {
            $html[] = '   <td class="center">';
            $html[] = '       <div class="img_border">';
            //$html[] =         '11';
            $html[] = '       <img  class="img_thumb" alt="' . $ImageReference->imageName . '" '
                . 'name="image" src="' . JUri::root(false) . $ImageReference->imagePath . '">';
            $html[] = '       </div>';
            $html[] = '   </td>';
        }
        else
        {
            $html[] = '   <td class="center">';
            //$html[] =         '11';
            $html[] = '        <span class="icon-cancel">';
            $html[] = '   </td>';
        }


        $html[] = '        </tr>';
    }

    /**/
    $html[] = '    </tbody>';

    //--- footer ----------------------------------
    $html[] = '</table>';

	return implode(' ',$html);

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
					<strong><?php echo Text::_('COM_RSGALLERY2_MAINT_CONSOLDB_TXT'); ?></strong>
				</div>

				<?php
                // if (count($ImageLostAndFoundList) == 0) :
                    if (empty($ImageLostAndFoundList)) :
                ?>
					<div class="alert alert-no-items">
						<?php
                            // echo Text::_('COM_RSGALLERY2_MAINT_CONSOLDB_NO_MISSING_ITEMS_TXT');
                            echo Text::_('COM_RSGALLERY2_NO_INCONSISTENCIES_IN_DATABASE');
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
							// DisplayImageDataTable();
                            echo $LostAndFountHtml;

                            ?>
						</div>
					</div>

				<?php endif; ?>

				<div class="form-actions">
					<br>
				</div>

				<fieldset class="refresh">
					<!--legend><?php echo Text::_('COM_RSGALLERY2_REFRESH_TEXT'); ?>XXXXX</legend-->
                    <div class="form-actions">
                        <a class="btn btn-primary jgrid "
                           title="<?php echo Text::_('COM_RSGALLERY2_REPEAT_CHECKING_INCONSITENCIES_DESC'); ?>"
                           href="index.php?option=com_rsgallery2&amp;view=maintConsolidateDB">
							<?php echo Text::_('COM_RSGALLERY2_REPEAT_CHECKING'); ?>
                        </a>
                        <a class="btn btn-primary jgrid"
                           href="index.php?option=com_rsgallery2&amp;view=maintenance">
		                    <?php echo Text::_('COM_RSGALLERY2_CANCEL'); ?>
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


