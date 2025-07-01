<?php
/**
 * @package         RSGallery2
 * @subpackage      com_rsgallery2
 *
 * @author          RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c)  2020-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */


// assign local layout

protected function getLayoutData(): array
{
    $data = parent::getLayoutData();

    $params = $data['params'];

    if ($params->get('param_name')) {
    $params->set('layout', 'first_layout');
    } else {
    $params->set('layout', 'second_layout');
}

$data['params'] = $params;

return $data;
}



