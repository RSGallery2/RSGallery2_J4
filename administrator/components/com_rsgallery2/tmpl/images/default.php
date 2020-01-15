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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\String\Inflector;

HTMLHelper::_('behavior.multiselect');

$user      = Factory::getUser();
$userId    = $user->get('id');
$extension = $this->escape($this->state->get('filter.extension'));
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = ($listOrder == 'a.lft' && strtolower($listDirn) == 'asc');
$parts     = explode('.', $extension, 2);
$component = $parts[0];
$section   = null;

/**/
if (count($parts) > 1)
{
	$section = $parts[1];

	$inflector = Inflector::getInstance();

	if (!$inflector->isPlural($section))
	{
		$section = $inflector->toPlural($section);
	}
}
/**/

if ($saveOrder && !empty($this->items))
{
	$saveOrderingUrl = 'index.php?option=com_rsgallery2&task=galleries.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}
?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=images'); ?>"
       method="post" name="adminForm" id="adminForm">
	<div class="row">
		<?php if (!empty($this->sidebar)) : ?>
		<div id="j-sidebar-container" class="col-md-2">
			<?php echo $this->sidebar; ?>
		</div>
		<?php endif; ?>
		<div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>">
			<div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
//				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>
				<?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
						<?php echo JText::_('COM_RSGALLERY2_NO_GALLERY_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
                    </div>
				<?php else : ?>



				<?php endif; ?>

				<input type="hidden" name="extension" value="<?php echo $extension; ?>">
				<input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>


