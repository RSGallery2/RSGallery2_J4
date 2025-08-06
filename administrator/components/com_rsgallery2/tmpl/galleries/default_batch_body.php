<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

//use Joomla\CMS\Language\Text;
use Joomla\CMS\User\UserFactoryInterface;

$params = ComponentHelper::getParams('com_rsgallery2');

$published = $this->state->get('filter.published');

// $user = Factory::getContainer()->get(UserFactoryInterface::class);
$user = Factory::getApplication()->getIdentity();
?>

<div class="container">
	<div class="row">
		<div class="form-group col-md-6">
			<div class="controls">
                <?php echo LayoutHelper::render('joomla.html.batch.language', []); ?>
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

