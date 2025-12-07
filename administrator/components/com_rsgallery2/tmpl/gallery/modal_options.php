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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

echo HTMLHelper::_('bootstrap.startAccordion', 'galleryOptions', ['active' => 'collapse0']);
$fieldSets = $this->form->getFieldsets('params');
$i         = 0;
?>
<?php foreach ($fieldSets as $name => $fieldSet) : ?>
    <?php
    $label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_RSGALLERY2_' . $name . '_FIELDSET_LABEL';
    echo HTMLHelper::_('bootstrap.addSlide', 'galleryOptions', Text::_($label), 'collapse' . ($i++));
    if (isset($fieldSet->description) && trim($fieldSet->description)) {
        echo '<p class="tip">' . $this->escape(Text::_($fieldSet->description)) . '</p>';
    }
    ?>
    <?php foreach ($this->form->getFieldset($name) as $field) : ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo $field->label; ?>
            </div>
            <div class="controls">
                <?php echo $field->input; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($name == 'basic') : ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('note'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('note'); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php echo HTMLHelper::_('bootstrap.endSlide'); ?>
<?php endforeach; ?>
<?php echo HTMLHelper::_('bootstrap.endAccordion'); ?>
