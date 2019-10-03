
<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

use Joomla\CMS\Language\Text;

//HTMLHelper::_('behavior.formvalidator');
//HTMLHelper::_('behavior.keepalive');

JHtml::_('stylesheet', 'com_rsgallery2/controlPanel.css', array('version' => 'auto', 'relative' => true));

HTMLHelper::_('script', 'mod_quickicon/quickicon.min.js', ['version' => 'auto', 'relative' => true]);


/**


d:\xampp\htdocs\joomla4x\media\com_rsgallery2\images\RSG2_logo.big.png

<div class="rsg2logo-container">

//echo '  <img src="' . JUri::root(true) . '/administrator/components/com_rsgallery2/images/rsg2-logo.png" align="middle" alt="RSGallery2 logo" /> ';
echo '  <img src="' . JUri::root(true) . '/administrator/components/com_rsgallery2/images/RSG2_logoText.svg" align="middle" alt="RSGallery2 logo 2" /> ';
/**/
//--- Logo -----------------------------

function DisplayRSG2Logo()
{
	echo '    <div class="rsg2logo">';
//	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
	             echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
	echo '     </div>';
//	echo '<p class="test">';
//	echo '</p>                                                                                                                                                          ';
}

//--- Control buttons ------------------
//
function DisplayRSG2ControlButtons($buttons)
{

    $html = HTMLHelper::_('icons.buttons', $buttons);
	if (!empty($html))
	{
		//echo '<nav class="quick-icons" aria-label="'
		//    . Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
		echo '    <ul class="nav flex-wrap row-fluid">';
		echo           $html;
		echo '    </ul>';
		//echo '</nav>';
	}

	/** Test standard quick.icons format *
	if (!empty($html))
	{
		echo '<nav class="quick-icons" aria-label="'
		    . Text::_('MOD_QUICKICON_NAV_LABEL') . '">';
		echo '    <ul class="nav flex-wrap row-fluid">';
		echo           $html;
		echo '    </ul>';
		echo '</nav>';
	}
    /**/
}


function DisplayInfoRsgallery2($Rsg2Version)
{
	// First column
//	echo '<div class="container-fluid">';
	echo '<div class="clearfix"></div>';

	echo '<row>';
	//echo '<span class="rsg2logo-container col-md-6">';

	/**/
//    echo '<table class="table table-striped">';
//	echo '<table class="table table-bordered table-striped w-auto text-xsmall table-hover table-light">';
//	echo '<table class="table table-sm w-auto text-xsmall table-hover table-light">';
//	echo '<table class="table w-auto text-xsmall table-hover table-sm table-condensed table-light">';
	echo '<table class="table table-light w-auto table_morecondensed">';
//    echo '<table class="table">';
//    echo '<table>';
//    echo '<table>';
	echo '    <tbody>';
	/**/
	/**/
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_INSTALLED_VERSION') . ': ' . '</td>';
	echo '            <td>';
//	echo '                <a href="' . JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2&layout=ChangeLog') . '"';
//	echo '                   title="' . JText::_('COM_RSGALLERY2_VIEW_CHANGE_LOG') . '""';
//	echo '                   class="modal">' . $Rsg2Version . '</a>';
	echo '                   ' . $Rsg2Version;
	echo '            </td>';
	echo '        </tr>';
	/**/
	// License
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_LICENSE') . ': ' . '</td>';
	echo '            <td>';
	echo '               <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank"'
                        . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_GNU_ORG') . '" >GNU GPL</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Home page
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_HOME_PAGE') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.rsgallery2.org/" target="_blank" '
                         . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Forum
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_FORUM') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.forum.rsgallery2.org/" target="_blank" '
                         . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.forum.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Documentation
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_DOCUMENTATION') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.rsgallery2.org/index.php/documentation" target="_blank"'
                          . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_DOCUMENTATION') . '" >www.rsg.../documentation</a>';
	echo '            </td>';
	echo '        </tr>';

	/**/
	echo '    </tbody>';
	echo '</table>';
	/**/
	//echo '</span>';

	echo '</row>';
//	echo '</div>'; // container fluid

	echo '<br>';

	return;
}

function DisplayInfoGalleryImages ($lastGalleries, $lastImages)
{
	echo '<div class="clearfix"></div>';

	echo '<row>';
	echo '   <div class="card bg-light w-25 data-toggle="collapse">';
	echo '      <div class="card-header">';
	echo '          ' . JText::_('COM_RSGALLERY2_GALLERIES');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         lastGalleries: "' . implode("|", $lastGalleries);
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';

	echo '   <div class="card bg-light w-50 data-toggle="collapse">';
	echo '      <div class="card-header">';
	echo '          ' . JText::_('COM_RSGALLERY2_IMAGES');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         lastImages: "' . implode("|", $lastImages);
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';



}

// Info about supporters of RSGallery 2
function DisplayInfoCredits ($credits)
{
	echo '<div class="clearfix"></div>';

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . JText::_('COM_RSGALLERY2_CREDITS');
   	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         credits: ' . implode("|", $credits);
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';

}

// Info about the change log og RSG3 sources
function DisplayInfoChangeLog ($changeLog)
{
	echo '<div class="clearfix"></div>';

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . JText::_('COM_RSGALLERY2_CHANGELOG');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         changeLog: ' . $changeLog;
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';

}

// Info about
function DisplayInfoExternalLicenses ($externalLicenses)
{
	echo '<div class="clearfix"></div>';

	echo '<row>';
	echo '   <div class="card bg-light w-auto data-toggle="collapse" data-target="#credit-card-body">';
	echo '      <div class="card-header">';
	echo '          ' . JText::_('COM_RSGALLERY2_EXTERNAL_LICENSES');
	echo '      </div>';
	echo '      <div id="credit-card-body" class="card-body">';
	echo '         externalLicenses: ' . implode("|", $externalLicenses);
	echo '      </div>';
//    echo '      <div class="card-footer">Footer</div>';
	echo '   </div>';
	echo '</row>';
}

?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">

	<?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="col-md-2">
			<?php echo $this->sidebar; ?>
        </div>
	<?php endif; ?>
    <div class="<?php if (!empty($this->sidebar)) {echo 'col-md-10'; } else { echo 'col-md-12'; } ?>">
        <div id="j-main-container" class="j-main-container">

            <?php

            //--- Logo -----------------------------

            DisplayRSG2Logo();

            //--- Control buttons ------------------

            DisplayRSG2ControlButtons($this->buttons);

            //--- RSG2 info table -----------------------------

            // DisplayInfoRsgallery2($this->Rsg2Version);
            // ToDo: use real version
            DisplayInfoRsgallery2("5.0.0.1");

            //--- Last galleries and last uploaded images -----------------------------

            // // Info about last created galleries and last uploaded images
            // side by side
            DisplayInfoGalleryImages ($this->lastGalleries, $this->lastImages);

            //--- Change log -----------------------------

            // Info about the change log og RSG3 sources
            DisplayInfoChangeLog ($this->changeLog);

            //--- Credits -----------------------------

            // Info about supporters of RSGallery 2
            DisplayInfoCredits ($this->credits);

            //--- External component licenses -----------------------------

            DisplayInfoExternalLicenses ($this->externalLicenses);





            ?>
        </div>
    </div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>

