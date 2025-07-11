<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$app = Factory::getApplication();

if ($app->isClient('site')) {
    $this->checkToken();
}

// 2024.10.06
JLoader::register('ContentHelperRoute', JPATH_ROOT . '/components/com_rsgallery2/helpers/route.php');
// JLoader::registerAlias('ContentHelperRoute', JPATH_ROOT . '/components/com_rsgallery2/helpers/route.php');

HTMLHelper::_('behavior.core');
HTMLHelper::_('bootstrap.popover', '.hasPopover', ['placement' => 'bottom']);

$extension = $this->escape($this->state->get('filter.extension'));
$function  = $app->input->getCmd('function', 'jSelectGallery');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>
<div class="container-popup">

	<form action="<?php echo Route::_('index.php?option=com_rsgallery2&view=galleries&layout=modal&tmpl=component&function=' . $function . '&' . Session::getFormToken() . '=1'); ?>" method="post" name="adminForm" id="adminForm">

        <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

		<?php if (empty($this->items)) : ?>
			<div class="alert alert-warning">
                <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
        <?php else : ?>
			<table class="table" id="galleryList">
				<caption id="captionTable" class="sr-only">
                    <?php echo Text::_('COM_RSGALLERY2_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
				</caption>
				<thead>
				<tr>
					<th scope="col" style="width:1%" class="text-center">
                        <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th scope="col">
                        <?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:10%" class="d-none d-md-table-cell">
							<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:15%" class="d-none d-md-table-cell">
							<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
					</th>
					<th scope="col" style="width:1%" class="d-none d-md-table-cell">
                        <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
				</thead>
				<tbody>
                <?php
                $iconStates = [
                    -2 => 'icon-trash',
                    0  => 'icon-unpublish',
                    1  => 'icon-publish',
                    2  => 'icon-archive',
                ];
                ?>
                <?php foreach ($this->items as $i => $item) : ?>
                    <?php
                    if ($item->language && Multilanguage::isEnabled()) {
                        $tag = strlen($item->language);
                        if ($tag == 5) {
                            $lang = substr($item->language, 0, 2);
                        } elseif ($tag == 6) {
                            $lang = substr($item->language, 0, 3);
                        } else {
                            $lang = '';
                        }
                    } elseif (!Multilanguage::isEnabled()) {
                        $lang = '';
                    }
                    ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="text-center">
                            <span class="<?php echo $iconStates[$this->escape($item->published)]; ?>" aria-hidden="true"></span>
						</td>
						<th scope="row">
                            <?php echo LayoutHelper::render('joomla.html.treeprefix', ['level' => $item->level]); ?>
							<a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', null, '<?php echo $this->escape(
//                                ContentHelperRoute::getGalleryRoute($item->id, $item->language),
// ToDo: getGalleryRoute not defined as such
                                RouteHelper::getGalleryRoute($item->id, $item->language),
                            ); ?>', '<?php echo $this->escape($lang); ?>', null);">
                                <?php echo $this->escape($item->title); ?></a>
							<span class="small" title="<?php echo $this->escape($item->path); ?>">
									<?php if (empty($item->note)) : ?>
                                        <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                    <?php else : ?>
										<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
                                    <?php endif; ?>
								</span>
						</th>
						<td class="small d-none d-md-table-cell">
                            <?php echo $this->escape($item->access_level); ?>
						</td>
						<td class="small d-none d-md-table-cell">
                            <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
						</td>
						<td class="d-none d-md-table-cell">
                            <?php echo (int)$item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php // load the pagination. ?>
            <?php echo $this->pagination->getListFooter(); ?>

        <?php endif; ?>

		<input type="hidden" name="extension" value="<?php echo $extension; ?>">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="forcedLanguage" value="<?php echo $app->input->get('forcedLanguage', '', 'CMD'); ?>">
        <?php echo HTMLHelper::_('form.token'); ?>

	</form>
</div>
