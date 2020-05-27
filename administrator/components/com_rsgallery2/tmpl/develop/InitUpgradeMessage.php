<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;

/**
require_once(JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php');

// JHtml::_('behavior.tooltip');
JHtml::_('bootstrap.tooltip');

global $Rsg2DebugActive;


//Initialize install
$rsgInstall = new rsgInstall();

// $rsgInstall->freshInstall();

// Now wish the user good luck and link to the control panel
$installCompleteMsg = $rsgInstall->installCompleteMsg(Text::_('COM_RSGALLERY2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED'));
$updateCompleteMsg = $rsgInstall->installCompleteMsg(Text::_('COM_RSGALLERY2_RSGALLERY_UPGRADE_IS_INSTALLED'));


/** Todo: check/display write message *
$rsgInstall->writeInstallMsg(Text::sprintf('COM_RSGALLERY2_MIGRATING_FROM_RSGALLERY2', $rsgConfig->get( 'version')), 'ok');

$msg = 'Deleted old RSGallery2 J!1.5 language files: <br>' . $msg;
$rsgInstall->writeInstallMsg($msg, 'ok');


if( $result === true ){
    $rsgInstall->writeInstallMsg( Text::sprintf('COM_RSGALLERY2_SUCCESS_NOW_USING_RSGALLERY2', $rsgConfig->get( 'version' )), 'ok');
}
else{
    $result = print_r( $result, true );
    $rsgInstall->writeInstallMsg( Text::_('COM_RSGALLERY2_FAILURE')."\n<br><pre>$result\n</pre>", 'error');
}
/**/


JHtml::_('formbehavior.chosen', 'select');

?>

<div id="installer-install" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=InitUpgradeMessage'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'InitUpgradeMessage')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'DevelopInstallMessage', Text::_('COM_RSGALLERY2_DEVELOP_INSTALL_MSG', true)); ?>


                <?php
                ?>


                <?php

                echo '====================================================================<br />';
                echo $this->installMessage;
                echo '====================================================================<br />';
//                echo $updateCompleteMsg;
                echo '====================================================================<br />';

                ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php echo JHtml::_('bootstrap.endTabSet'); ?>


				<input type="hidden" value="" name="task">

				<?php echo JHtml::_('form.token'); ?>

			</form>
		</div>
		<div id="loading"></div>
	</div>
</div>
