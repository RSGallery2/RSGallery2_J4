<?php
/**
 * @package       RSGallery2
 * @copyright (C) 2003-2018 RSGallery2 Team
 * @license       http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * RSGallery is Free Software
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

?>

<div id="installer-install" class="clearfix">
	<?php if (!empty($this->sidebar)) : ?>
	<div id="j-sidebar-container" class="">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<form action="<?php echo JRoute::_('index.php?option=com_rsgallery2&view=InstallMessage'); ?>"
					method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">

				<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'InstallMessage')); ?>

				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'InstallMessage', Text::_('COM_RSGALLERY2_DEVELOP_INSTALL_MSG_TEST', true)); ?>


                <?php

                echo '====================================================================<br />';
                //echo '<br />';
                echo $this->installMessage;
                //echo '<br />';
                echo '====================================================================<br />';
                echo $this->installMessage2;
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
