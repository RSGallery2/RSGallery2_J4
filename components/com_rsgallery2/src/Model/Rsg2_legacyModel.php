<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_rsgallery2
 *
 * @copyright   (C) 2005 - 2021 RSGallery2 Team 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Registry\Registry;

use Rsgallery2\Component\Rsgallery2\Site\Model;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


/**
 * Rsgallery2 model for the Joomla Rsgallery2 component.
 *
 * @since  __BUMP_VERSION__
 */
class Rsg2_legacyModel extends GalleriesModel
{
    /**
    protected $layoutParams = null; // col/row count


    public function getlayoutParams ()
    {
        if ($this->layoutParams == null) {
            $this->layoutParams = $this->CascadedLayoutParameter ();
        }
        return $this->layoutParams;
    }

    private function CascadedLayoutParameter() // For gallery images view
    {
        $layoutParameter = new \stdClass();
        $layoutParameter->images_column_arrangement  = 0; // 0: auto
        $layoutParameter->max_columns_in_images_view = 0;
        $layoutParameter->images_row_arrangement     = 0; // 0: auto
        $layoutParameter->max_rows_in_images_view    = 0;
        $layoutParameter->max_images_in_images_view  = 0;

        try {


            $app = Factory::getApplication();
            $menuitem   = $app->getMenu()->getActive(); // get the active item
            // $menuitem   = $app->getMenu()->getItem($theid); // or get item by ID
            $params = $menuitem->getParams(); // get the params
            print_r($params); // print all params as overview

            //--- RSG2 config  parameter -------------------------------------------------

            $rsgConfig = ComponentHelper::getParams('com_rsgallery2');

            $images_column_arrangement = $rsgConfig->get('images_column_arrangement');
            $max_columns_in_images_view = $rsgConfig->get('max_columns_in_images_view');
            $images_row_arrangement = $rsgConfig->get('images_row_arrangement');
            $max_rows_in_images_view = $rsgConfig->get('max_rows_in_images_view');
            $max_images_in_images_view = $rsgConfig->get('max_images_in_images_view');

            //--- menu parameter -------------------------------------------------

            $app = Factory::getApplication();
            $input = $app->input;

            // overwrite config if chosen
            $images_column_arrangement_menu = $input->get('images_column_arrangement', $images_column_arrangement, 'STRING');

            if ($images_column_arrangement_menu != 'global') {
                $images_column_arrangement = (int)$images_column_arrangement_menu;

                // toDo: switch when more selections .. (0 auto)
                if ($images_column_arrangement_menu == '1') {
                    $max_columns_in_images_view = $input->get('max_columns_in_images_view', $max_columns_in_images_view, 'INT');

                    $images_row_arrangement_menu = $input->get('images_row_arrangement', $images_row_arrangement, 'INT');
                    if ($images_row_arrangement_menu != 'global') {
                        $images_row_arrangement = (int)$images_row_arrangement_menu;

                        // toDo: switch when more selections .. (0 auto)

                        if ($images_row_arrangement_menu == '1') {
                            $max_rows_in_images_view = $input->get('max_rows_in_images_view', $max_rows_in_images_view, 'INT');
                        } else {
                            $max_images_in_images_view = $input->get('max_images_in_images_view', $max_images_in_images_view, 'INT');
                        }
                    }
                }
            }

            //--- gallery parameter -------------------------------------------------

            // ToDo: gid: one get access function keep result ...
            // gallery parameter
            $gid = $input->get('gid', '', 'INT');
            $gallery_param = $this->gallery_parameter($gid);

            // overwrite config and new if chosen
            $images_column_arrangement_gallery = $gallery_param->get('images_column_arrangement');

            if ($images_column_arrangement_gallery != 'global') {
                $images_column_arrangement = (int)$images_column_arrangement_gallery;

                // toDo: switch when more selections .. (0 auto)
                if ($images_column_arrangement_gallery == '1') {
                    $max_columns_in_images_view = $gallery_param->get('max_columns_in_images_view');

                    $images_row_arrangement_gallery = $gallery_param->get('images_row_arrangement', $images_row_arrangement, 'INT');
                    if ($images_row_arrangement_gallery != 'global') {
                        $images_row_arrangement = (int)$images_row_arrangement_gallery;

                        // toDo: switch when more selections .. (0 auto)

                        if ($images_row_arrangement_gallery == '1') {
                            $max_rows_in_images_view = $gallery_param->get('max_rows_in_images_view', $max_rows_in_images_view, 'INT');
                        } else {
                            $max_images_in_images_view = $gallery_param->get('max_images_in_images_view', $max_images_in_images_view, 'INT');
                        }
                    }
                }
            }

            $layoutParameter->images_column_arrangement  = $images_column_arrangement;
            $layoutParameter->max_columns_in_images_view = $max_columns_in_images_view;
            $layoutParameter->images_row_arrangement     = $images_row_arrangement;
            $layoutParameter->max_rows_in_images_view    = $max_rows_in_images_view;
            $layoutParameter->max_images_in_images_view  = $max_images_in_images_view;


            //--- determine limit --------------------------------------------------

            $limit = 0;

            // determine image limit of one page view
            if ((int) $images_column_arrangement == 0) { // auto
                $limit = 0;
            }
            else
            {
                if((int) $images_row_arrangement == 0) { // auto
                    $limit = 0;
                }
                else
                {
                    if((int) $images_row_arrangement == 1) { // row count
                        $limit = (int) $max_columns_in_images_view * (int) $max_rows_in_images_view;
                    } else { // max images
                        $limit = (int) $max_images_in_images_view;
                    }

                }

            }

            $layoutParameter->limit = $limit;

        }
        catch (\RuntimeException $e)
        {
            $OutTxt = '';
            $OutTxt .= 'GalleriesModel: CascadedLayoutParameter: Error executing query: "' . "" . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            $app = Factory::getApplication();
            $app->enqueueMessage($OutTxt, 'error');
        }

        return $layoutParameter;
    }
    /**/
}
