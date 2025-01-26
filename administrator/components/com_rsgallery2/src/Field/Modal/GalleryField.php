<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2005-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Field\Modal;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Database\DatabaseInterface;
use RuntimeException;

/**
 * Supports a modal gallery picker.
 *
 * @since __BUMP_VERSION__
 */
class GalleryField extends FormField
{
    /**
     * The form field type.
     *
     * @var     string
     * @since __BUMP_VERSION__
     */
    protected $type = 'Modal_Gallery';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since __BUMP_VERSION__
     */
    protected function getInput()
    {
        if ($this->element['extension']) {
            $extension = (string)$this->element['extension'];
        } else {
            $extension = (string)Factory::getApplication()->input->get('extension', 'com_content');
        }

        $allowNew    = ((string)$this->element['new'] == 'true');
        $allowEdit   = ((string)$this->element['edit'] == 'true');
        $allowClear  = ((string)$this->element['clear'] != 'false');
        $allowSelect = ((string)$this->element['select'] != 'false');

        // Load language.
        Factory::getApplication()->getLanguage()->load('com_rsgallery2', JPATH_ADMINISTRATOR);

        // The active gallery id field.
        $value = (int)$this->value > 0 ? (int)$this->value : '';

        // Create the modal id.
        $modalId = 'Gallery_' . $this->id;

        // Add the modal field script to the document head.
        HTMLHelper::_('script', 'system/fields/modal-fields.min.js', ['version' => 'auto', 'relative' => true]);
        //$this->document->getWebAssetManager()->usePreset('com_rsgallery2.backend.imagesProperties');

        // Script to proxy the select modal function to the modal-fields.js file.
        if ($allowSelect) {
            static $scriptSelect = null;

            if (is_null($scriptSelect)) {
                $scriptSelect = [];
            }

            if (!isset($scriptSelect[$this->id])) {
                //? title -> ? name
                Factory::getApplication()->getDocument()->addScriptDeclaration(
                    "
				function jSelectGallery_" . $this->id . "(id, title, object) {
					window.processModalSelect('Gallery', '" . $this->id . "', id, title, '', object);
				}
				",
                );

                $scriptSelect[$this->id] = true;
            }
        }

        // Setup variables for display.
        $linkGalleries = 'index.php?option=com_rsgallery2&amp;view=galleries&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken(
            ) . '=1'
            . '&amp;extension=' . $extension;
        $linkGallery   = 'index.php?option=com_rsgallery2&amp;view=gallery&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken(
            ) . '=1'
            . '&amp;extension=' . $extension;
        $modalTitle    = Text::_('COM_RSGALLERY2_CHANGE_GALLERY');

        if (isset($this->element['language'])) {
            $linkGalleries .= '&amp;forcedLanguage=' . $this->element['language'];
            $linkGallery   .= '&amp;forcedLanguage=' . $this->element['language'];
            $modalTitle    .= ' &#8212; ' . $this->element['label'];
        }

        $urlSelect = $linkGalleries . '&amp;function=jSelectGallery_' . $this->id;
        $urlEdit   = $linkGallery . '&amp;task=gallery.edit&amp;id=\' + document.getElementById("' . $this->id . '_id").value + \'';
        $urlNew    = $linkGallery . '&amp;task=gallery.add';

        if ($value) {
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db
                ->getQuery(true)
                ->select($db->quoteName('title'))
                ->from($db->quoteName('#__galleries'))
                ->where($db->quoteName('id') . ' = ' . (int)$value);
            $db->setQuery($query);

            try {
                $title = $db->loadResult();
            } catch (RuntimeException $e) {
                Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            }
        }

        $title = empty($title) ? Text::_('COM_RSGALLERY2_SELECT_A_GALLERY') : htmlspecialchars(
            $title,
            ENT_QUOTES,
            'UTF-8',
        );

        // The current gallery display field.
        $html = '';
        if ($allowSelect || $allowNew || $allowEdit || $allowClear) {
            $html .= '<span class="input-group">';
        }

        $html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35">';

        if ($allowSelect || $allowNew || $allowEdit || $allowClear) {
            $html .= '<span class="input-group-append">';
        }

        // Select gallery button.
        if ($allowSelect) {
            $html .= '<a'
                . ' class="btn btn-primary hasTooltip' . ($value ? ' sr-only' : '') . '"'
                . ' id="' . $this->id . '_select"'
                . ' data-toggle="modal"'
                . ' role="button"'
                . ' href="#ModalSelect' . $modalId . '"'
                . ' title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_CHANGE_GALLERY') . '">'
                . '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
                . '</a>';
        }

        // New gallery button.
        if ($allowNew) {
            $html .= '<a'
                . ' class="btn btn-secondary hasTooltip' . ($value ? ' sr-only' : '') . '"'
                . ' id="' . $this->id . '_new"'
                . ' data-toggle="modal"'
                . ' role="button"'
                . ' href="#ModalNew' . $modalId . '"'
                . ' title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_NEW_GALLERY') . '">'
                . '<span class="icon-new" aria-hidden="true"></span> ' . Text::_('JACTION_CREATE')
                . '</a>';
        }

        // Edit gallery button.
        if ($allowEdit) {
            $html .= '<a'
                . ' class="btn btn-secondary hasTooltip' . ($value ? '' : ' sr-only') . '"'
                . ' id="' . $this->id . '_edit"'
                . ' data-toggle="modal"'
                . ' role="button"'
                . ' href="#ModalEdit' . $modalId . '"'
                . ' title="' . HTMLHelper::tooltipText('COM_RSGALLERY2_EDIT_GALLERY') . '">'
                . '<span class="icon-edit" aria-hidden="true"></span> ' . Text::_('JACTION_EDIT')
                . '</a>';
        }

        // Clear gallery button.
        if ($allowClear) {
            $html .= '<a'
                . ' class="btn btn-secondary' . ($value ? '' : ' sr-only') . '"'
                . ' id="' . $this->id . '_clear"'
                . ' href="#"'
                . ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
                . '<span class="icon-remove" aria-hidden="true"></span>' . Text::_('JCLEAR')
                . '</a>';
        }

        if ($allowSelect || $allowNew || $allowEdit || $allowClear) {
            $html .= '</span></span>';
        }

        // Select gallery modal.
        if ($allowSelect) {
            $html .= HTMLHelper::_(
                'bootstrap.renderModal',
                'ModalSelect' . $modalId,
                [
                    'title'      => $modalTitle,
                    'url'        => $urlSelect,
                    'height'     => '400px',
                    'width'      => '800px',
                    'bodyHeight' => 70,
                    'modalWidth' => 80,
                    'footer'     => '<a role="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">'
                        . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
                ],
            );
        }

        // New gallery modal.
        if ($allowNew) {
            $html .= HTMLHelper::_(
                'bootstrap.renderModal',
                'ModalNew' . $modalId,
                [
                    'title'       => Text::_('COM_RSGALLERY2_NEW_GALLERY'),
                    'backdrop'    => 'static',
                    'keyboard'    => false,
                    'closeButton' => false,
                    'url'         => $urlNew,
                    'height'      => '400px',
                    'width'       => '800px',
                    'bodyHeight'  => 70,
                    'modalWidth'  => 80,
                    'footer'      => '<a role="button" class="btn btn-secondary" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'gallery\', \'cancel\', \'item-form\'); return false;">'
                        . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>'
                        . '<a role="button" class="btn btn-primary" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'gallery\', \'save\', \'item-form\'); return false;">'
                        . Text::_('JSAVE') . '</a>'
                        . '<a role="button" class="btn btn-success" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'gallery\', \'apply\', \'item-form\'); return false;">'
                        . Text::_('JAPPLY') . '</a>',
                ],
            );
        }

        // Edit gallery modal.
        if ($allowEdit) {
            $html .= HTMLHelper::_(
                'bootstrap.renderModal',
                'ModalEdit' . $modalId,
                [
                    'title'       => Text::_('COM_RSGALLERY2_EDIT_GALLERY'),
                    'backdrop'    => 'static',
                    'keyboard'    => false,
                    'closeButton' => false,
                    'url'         => $urlEdit,
                    'height'      => '400px',
                    'width'       => '800px',
                    'bodyHeight'  => 70,
                    'modalWidth'  => 80,
                    'footer'      => '<a role="button" class="btn btn-secondary" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'gallery\', \'cancel\', \'item-form\'); return false;">'
                        . Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>'
                        . '<a role="button" class="btn btn-primary" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'gallery\', \'save\', \'item-form\'); return false;">'
                        . Text::_('JSAVE') . '</a>'
                        . '<a role="button" class="btn btn-success" aria-hidden="true"'
                        . ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'gallery\', \'apply\', \'item-form\'); return false;">'
                        . Text::_('JAPPLY') . '</a>',
                ],
            );
        }

        // Note: class='required' for client side validation
        $class = $this->required ? ' class="required modal-value"' : '';

        $html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' data-required="' . (int)$this->required . '" name="' . $this->name
            . '" data-text="' . htmlspecialchars(
                Text::_('COM_RSGALLERY2_SELECT_A_GALLERY', true),
                ENT_COMPAT,
                'UTF-8',
            ) . '" value="' . $value . '">';

        return $html;
    }

    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     *
     * @since __BUMP_VERSION__
     */
    protected function getLabel()
    {
        return str_replace($this->id, $this->id . '_id', parent::getLabel());
    }
}
