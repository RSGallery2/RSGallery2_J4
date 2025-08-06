<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2003-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

function displayRsgMenuLinks($Rsg2MenuLinks)
{
    if (empty ($Rsg2MenuLinks)) {
        echo "<br> % No items found<br>";
    } else {
        ?>

		<table class="table">
			<thead>
			<tr>
				<th scope="col">Idx</th>
				<th scope="col">Menu Id</th>
				<th scope="col">Link</th>
				<!--th scope="col">Params</th-->
			</tr>
			</thead>
			<tbody>

            <?php
            $row_id = 0;
            foreach ($Rsg2MenuLinks as $idx => $Rsg2MenuLink) {
                $row_id++;
                [$link, $params] = $Rsg2MenuLink;

                ?>

				<tr>
					<th scope="row"><?php echo $row_id; ?></th>
					<td><?php echo $idx; ?></td>
					<td><?php echo $link; ?></td>
					<!--td><?php echo "%"; // $params;
                    ?></td-->
				</tr>

                <?php
            }
            ?>
			</tbody>
		</table>

        <?php
    }
}

/*--------------------------------------------------------------------------------
	change menu links (example: j3x: '...&gallery&Gid...' => j4x: '...&galleryj3x&Gid...'
--------------------------------------------------------------------------------*/

?>

<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=MaintenanceJ3x&layout=changeGidMenuLinks'); ?>"
      method="post" name="adminForm" id="adminForm">
	<div class="d-flex flex-row">
        <?php if (!empty($this->sidebar)) : ?>
			<div id="j-sidebar-container" class="">
                <?php echo $this->sidebar; ?>
			</div>
        <?php endif; ?>
		<!--div class="<?php echo (!empty($this->sidebar)) ? 'col-md-10' : 'col-md-12'; ?>"-->
		<div class="flex-fill">
			<div id="j-main-container" class="j-main-container">

				<div class="card text-center">
					<div class="card-body">
						<h3 class="card-title"><?php echo Text::_('COM_RSGALLERY2_EXCHANGE_MENU_GID2ID'); ?></h3>

						<p class="card-text"><?php echo Text::_('COM_RSGALLERY2_USE_BELOW_BUTTON'); ?></p>

						<button class="btn btn-success" type="submit"
						        onclick="Joomla.submitbutton('MaintenanceJ3x.j3xReplaceGid2IdMenuLinks');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_EXCHANGE_MENU_GID2ID'); ?>
						</button>

                        <?php // 				// ToDo: remove  ?>
						<button class="btn btn-info" type="submit"
						        onclick="Joomla.submitbutton('MaintenanceJ3x.j3xReplaceId2GidMenuLinks');return false;">
                            <?php echo Text::_('COM_RSGALLERY2_EXCHANGE_MENU_ID2GID'); ?>
						</button>

					</div>
				</div>

				<div class="card text-center">
					<div class="card-body">
						<p class="card-text">
                            <?php echo Text::_('COM_RSGALLERY2_EXCHANGE_MENU_GID2ID_DESC'); ?>
						</p>

					</div>
				</div>

				<div class="card text-center">
					<div class="card-body">
						<h3 class="card-title"><?php echo Text::_('Links which will be upgraded', true); ?></h3>

						<p class="card-text">
                            <?php displayRsgMenuLinks($this->j3xRsg2MenuLinks); ?>
						</p>

					</div>
				</div>

				<div class="card text-center">
					<div class="card-body">
						<h3 class="card-title"><?php echo Text::_('Links which have been upgraded', true); ?></h3>

						<p class="card-text">
                            <?php displayRsgMenuLinks($this->j4xRsg2MenuLinks); ?>
						</p>

					</div>
				</div>


				<input type="hidden" name="boxchecked" value="0"/>
				<input type="hidden" name="task" value=""/>
                <?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>

    <?php echo HTMLHelper::_('form.token'); ?>
</form>

