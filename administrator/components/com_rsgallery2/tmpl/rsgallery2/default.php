
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

/**
<?php
$html = HTMLHelper::_('icons.buttons', $this->buttons);
?>
<?php if (!empty($html)) : ?>
<nav  class="quick-icons" aria-label="<?php echo Text::_('MOD_QUICKICON_NAV_LABEL'); ?>">
<ul>
<?php echo $html; ?>
</ul>
</nav>
<?php endif; ?>


d:\xampp\htdocs\joomla4x\media\com_rsgallery2\images\RSG2_logo.big.png

<div class="rsg2logo-container">

//echo '  <img src="' . JUri::root(true) . '/administrator/components/com_rsgallery2/images/rsg2-logo.png" align="middle" alt="RSGallery2 logo" /> ';
echo '  <img src="' . JUri::root(true) . '/administrator/components/com_rsgallery2/images/RSG2_logoText.svg" align="middle" alt="RSGallery2 logo 2" /> ';
/**/

function DisplayInfoRsgallery2($Rsg2Version)
{
	// Logo
	echo '<row>';
	echo '<div class="rsg2logo-container">';
	/**/
//    echo '<table class="table table-striped">';
	echo '<table class="table table-striped table-condensed">';
//    echo '<table class="table">';
//    echo '<table>';
//    echo '<table>';
	echo '    <tbody>';
	/**/
	/**/
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_INSTALLED_VERSION') . ': ' . '</td>';
	echo '            <td>';
	echo '                <a href="' . JRoute::_('index.php?option=com_rsgallery2&view=rsgallery2&layout=ChangeLog') . '"';
	echo '                   title="' . JText::_('COM_RSGALLERY2_VIEW_CHANGE_LOG') . '""';
	echo '                   class="modal">' . $Rsg2Version . '</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// License
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_LICENSE') . ': ' . '</td>';
	echo '            <td>';
	echo '               <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank" title="';
	echo JText::_('COM_RSGALLERY2_JUMP_TO_GNU_ORG') . '" >GNU GPL</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Home page
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_HOME_PAGE') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.rsgallery2.org/" target="_blank" ' . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Forum
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_FORUM') . '</td>';
	echo '            <td>';
	echo '                <a href="http://www.forum.rsgallery2.org/" target="_blank" ' . ' title="' . JText::_('COM_RSGALLERY2_JUMP_TO_FORUM') . '" >www.forum.rsgallery2.org</a>';
	echo '            </td>';
	echo '        </tr>';
	/**/
	// Documentation
	echo '        <tr>';
	echo '            <td>' . JText::_('COM_RSGALLERY2_DOCUMENTATION') . '</td>';
	echo '            <td>';
//	echo '                <a href="http://joomlacode.org/gf/project/rsgallery2/frs/?action=FrsReleaseBrowse&frs_package_id=6273" target="_blank" ';
	echo '                <a href="http://www.rsgallery2.org/documentation/" target="_blank" ';
	echo '                    title="' . JText::_('COM_RSGALLERY2_JUMP_TO_DOCUMENTATION') . '" >www.rsgallery2.org/documentation</a>';
	echo '            </td>';
	echo '        </tr>';

	/**/
	echo '    </tbody>';
	echo '</table>';
	/**/
	echo '</div>';

	echo '</row>';

	echo '<br>';

	return;
}







?>
<form action="<?php echo Route::_('index.php?option=com_rsgallery2'); ?>"
      method="post" name="adminForm" id="rsgallery2-main" class="form-validate">

    <p class="test">
        <div class="rsg2logo">
            <?php
            // echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
            echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_RSGALLERY2_MAIN_LOGO_ALT_TEXT'), null, true);
            ?>
        </div>
    </p>

    <?php
    $html = HTMLHelper::_('icons.buttons', $this->buttons);
    ?>
    <?php if (!empty($html)) : ?>
    <nav  class="quick-icons" aria-label="<?php echo Text::_('MOD_QUICKICON_NAV_LABEL'); ?>">
        <ul>
            <?php echo $html; ?>
        </ul>
    </nav>
	<?php endif; ?>

    <div class="span12">
        <div class="row-fluid">
            <div class="span6">
				<?php
				// DisplayInfoRsgallery2($this->Rsg2Version);
				DisplayInfoRsgallery2("Rsg2Version");
				?>
            </div>
        </div>
    </div>







    <?php echo HTMLHelper::_('form.token'); ?>
</form>

