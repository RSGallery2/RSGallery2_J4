<?php
/**
 * @package     Rsgallery2\Component\Rsgallery2\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

use Joomla\CMS\Uri\Uri;
use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePathsModel;


class ImagePathsData extends ImagePathsModel
{
    /**
     * @param $image
     *
     *
     * @since version
     */
    public function assignPathData ($image) {

        $image->OriginalFile = $this->getOriginalPath ($image->name);
        $image->DisplayFile = $this->getDisplayPath ($image->name);
        $image->ThumbFile = $this->getThumbPath ($image->name);

        $image->SizePaths = $this->getSizePaths ();

        $image->UrlThumbFile = $this->getThumbUrl ($image->name);
        // $image->UrlDisplayFile = $this->getSizeUrl ('400', $image->name); // toDo: image size to path
        $image->UrlDisplayFile = $this->getDisplayUrl ($image->name);
        $image->UrlOriginalFile = $this->getOriginalUrl ($image->name);

        $image->SizeUrls = $this->getSizeUrls ($image->name);

        // does exist markers


        $image->isOriginalFileExist = file_exists($image->OriginalFile);
        $image->isDisplayFileExist = file_exists($image->DisplayFile);
        $image->isThumbFileExist = file_exists($image->DisplayFile);

        //$image->isSizePaths = $this->getSizePaths ();

    }

    /**
     * Handle URLs of missing image files
     *
     * Replace by image telling missing
     *
     * @param $image
     *
     * @since version
     */
    public function urlReplaceMissing_BySign ($image) {

        // $noImageUrl = URI::root() . '/media/com_rsgallery2/images/GalleryZeroImages.png';
        $missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.png';

        if (!$image->isThumbFileExist) {
            $image->UrlThumbFile = $missingUrl;
        }

        if (!$image->isDisplayFileExist) {
            $image->UrlDisplayFile = $missingUrl;;
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
     * @since version
     */
    public function urlReplaceMissingImages_ByChild ($image) {


        if (!$image->isThumbFileExist) {
            $missingUrl = URI::root() . '/media/com_rsgallery2/images/ImageQuestionmark.png';
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