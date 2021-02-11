<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
//use Joomla\CMS\Language\Text;

$params = ComponentHelper::getParams('com_rsgallery2');

$published = $this->state->get('filter.published');

$user = Factory::getUser();
?>

<div class="container">
	<div class="row">
		<div class="form-group col-md-6">
			<div class="controls">
				<?php
				// echo LayoutHelper::render('Select4MoveCopy', []);
				echo LayoutHelper::render('field.GallerySelect', ['extension' => 'com_rsgallery2']);
				?>
			</div>
		</div>
		<div class="form-group col-md-6">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.access', []); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<?php if ($published >= 0) : ?>
			<div class="form-group col-md-6">
				<div class="controls">
					<?php echo LayoutHelper::render('joomla.html.batch.item', ['extension' => 'com_content']); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group col-md-6">
			<div class="controls">
				<?php echo LayoutHelper::render('joomla.html.batch.tag', []); ?>
			</div>
		</div>
		<?php if ($user->authorise('core.admin', 'com_content') && $params->get('workflow_enabled')) : ?>
			<div class="form-group col-md-6">
				<div class="controls">
					<?php echo LayoutHelper::render('joomla.html.batch.workflowstage', ['extension' => 'com_content']); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

