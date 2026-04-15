<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2005-2026 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Api\Controller;

use Joomla\CMS\MVC\Controller\ApiController;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * The latest galleries controller
 *
 * @since  4.0.0
 */
class LatestgalleryController extends ApiController
{
    /**
     * The content type of the item.
     *
     * @var    string
     * @since  4.0.0
     */
    protected $contentType = 'latestgallery';

    /**
     * The default view for the display method.
     *
     * @var    string
     * @since  3.0
     */
    protected $default_view = 'latestgallery';

    /**
     * @param   null  $id
     */
    /* <br /> error: use following to create an error with prepend error message
    public function displayItem(int|null $id = null)
    /**/
    public function displayItem($id = null)
    {
        // Set the id as the parent sets it as int
        $this->modelState->set('id', $this->input->get('id', '', 'string'));

        return parent::displayItem();
    }

}
