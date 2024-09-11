<?php
/**
 * @package    RSGallery2
 * @subpackage com_rsgallery2
 *
 * @copyright  (c) 2005-2024 RSGallery2 Team
 * @license    GNU General Public License version 2 or later
 */
\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\User\UserFactoryInterface;

$params = ComponentHelper::getParams('com_rsgallery2');

$published = $this->state->get('filter.published');

//$user = Factory::getContainer()->get(UserFactoryInterface::class);
$user      = Factory::getApplication()->getIdentity();
?>

<div class="container">
	<div class="row">
		<div class="form-group col-md-12">
			<div class="controls">
				<?php
                echo $this->form->renderField('gallery_id');
				?>
			</div>
		</div>
    </div>
</div>


