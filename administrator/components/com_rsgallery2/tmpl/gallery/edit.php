<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Tmpl\Gallery;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

//HTMLHelper::_('behavior.formvalidator');
//HTMLHelper::_('behavior.keepalive');
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_contenthistory');
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('com_contenthistory.admin-history-versions');
$wa->usePreset('com_rsgallery2.backend.gallery');


$app   = Factory::getApplication();
$input = $app->input;

$assoc = Associations::isEnabled();
// Are associations implemented for this extension?
$extensionassoc = array_key_exists('item_associations', $this->form->getFieldsets());

// Fieldsets to not automatically render by /layouts/joomla/edit/params.php
$this->ignore_fieldsets = ['jmetadata', 'item_associations'];
$this->useCoreUI        = true;

// In case of modal
$isModal = $input->get('layout') == 'modal' ? true : false;
$layout  = $isModal ? 'modal' : 'edit';
$tmpl    = $isModal || $input->get('tmpl', '', 'cmd') === 'component' ? '&tmpl=component' : '';
?>

<form action="<?php echo Route::_(
    'index.php?option=com_rsgallery2&extension=' . $input->getCmd(
        'extension',
        'com_rsgallery2',
    ) . '&layout=' . $layout . $tmpl . '&id=' . (int)$this->item->id,
); ?>"
      method="post" name="adminForm" id="gallery-form" class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div>
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general']); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_RSGALLERY2_GENERAL')); ?>
        <div class="row">
            <div class="col-lg-9">
                <div>
                    <div class="card-body">
                        <fieldset class="adminform">
                            <?php echo $this->form->getLabel('description'); ?>
                            <?php echo $this->form->getInput('description'); ?>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="bg-light px-3">
                    <?php echo $this->getForm()->renderField('thumb_id') ?>
                    <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                    <?php echo $this->getForm()->renderField('lft') ?>
                    <?php echo $this->getForm()->renderField('galleryOrdering') ?>
                </div>
            </div>
        </div>

        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('COM_RSGALLERY2_FIELDSET_PUBLISHING')); ?>
        <div class="row">
            <div class="col-12 col-lg-6">
                <fieldset id="fieldset-publishingdata" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
                    <div>
                        <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                    </div>
                </fieldset>
            </div>
            <div class="col-12 col-lg-6">
                <fieldset id="fieldset-metadata" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_FIELDSET_METADATA_OPTIONS'); ?></legend>
                    <div>
                        <?php echo LayoutHelper::render('joomla.edit.metadata', $this); ?>
                    </div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php if (!$isModal && $assoc && $extensionassoc) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'associations', Text::_('JGLOBAL_FIELDSET_ASSOCIATIONS')); ?>
            <?php echo $this->loadTemplate('associations'); ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php elseif ($isModal && $assoc && $extensionassoc) : ?>
            <div class="hidden"><?php echo $this->loadTemplate('associations'); ?></div>
        <?php endif; ?>

        <?php if ($this->canDo->get('core.admin')) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'rules', Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL')); ?>
            <?php echo $this->form->getInput('rules'); ?>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>

        <?php echo $this->form->getInput('extension'); ?>
        <input type="hidden" name="task" value="">
        <input type="hidden" name="forcedLanguage" value="<?php echo $input->get('forcedLanguage', '', 'cmd'); ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
