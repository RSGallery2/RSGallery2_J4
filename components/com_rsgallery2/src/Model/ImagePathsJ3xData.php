<?php

/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2014-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsJ3xModel;


class ImagePathsJ3xData extends ImagePathsJ3xModel
{
    /**
     * @param $image
     *
     *
     * @since 5.1.0     */
    public function assignPathData($image)
    {
        $image->OriginalFile = $this->getOriginalPath($image->name);
        $image->DisplayFile  = $this->getDisplayPath($image->name);
        $image->ThumbFile    = $this->getThumbPath($image->name);

        // $image->SizePaths = $this->getSizePaths ();

        $image->UrlThumbFile = $this->getThumbUrl($image->name);
        // $image->UrlDisplayFile = $this->getSizeUrl ('400', $image->name); // toDo: image size to path
        $image->UrlDisplayFile  = $this->getDisplayUrl($image->name);
        $image->UrlOriginalFile = $this->getOriginalUrl($image->name);

        //$image->SizeUrls = $this->getSizeUrls ($image->name);

        // does exist markers


        $image->isOriginalFileExist = file_exists($image->OriginalFile);
        $image->isDisplayFileExist  = file_exists($image->DisplayFile);
        $image->isThumbFileExist    = file_exists($image->DisplayFile);
        //$image->isSizePaths = $this->getSizePaths ();

    }

    /**
     * Handle URLs of missing image files
     *
     * Replace by image telling missing
     *
     * @param $image
     *
     * @since 5.1.0     */
    public function urlReplaceMissing_BySign($image)
    {
        // $noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.png';
        $missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.png';

        if (!$image->isThumbFileExist) {
            $image->UrlThumbFile = $missingUrl;
        }

        if (!$image->isDisplayFileExist) {
            $image->UrlDisplayFile = $missingUrl;
        }

        if (!$image->isOriginalFileExist) {
            $image->UrlOriginalFile = $missingUrl;
        }
    }


    /**
     * Handle URLs of missing image files
     * Missing display image may be replaced by thumb, missing original should be
     * replaced by display (ToDo: watermarked ?)
     *
     * @param $image
     *
     * @since 5.1.0     */
    public function urlReplaceMissingImages_ByChild ($image) {

        if (!$image->isThumbFileExist) {
            $missingUrl          = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.png';
            $image->UrlThumbFile = $missingUrl;
        }

        if (!$image->isDisplayFileExist) {
            $image->UrlDisplayFile = $image->UrlThumbFile;
        }

        if (!$image->isOriginalFileExist) {
            $image->UrlOriginalFile = $image->UrlDisplayFile;
        }
    }


}