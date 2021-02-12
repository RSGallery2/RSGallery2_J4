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

$params = ComponentHelper::getParams('com_rsgallery2');

$published = $this->state->get('filter.published');

$user = Factory::getUser();
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


