<?php
/**
 * @package     Rsgallery2\Component\Rsgallery2\Site\Model
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Rsgallery2\Component\Rsgallery2\Site\Model;

use Rsgallery2\Component\Rsgallery2\Administrator\Model\ImagePaths;


class ImagePathsData extends ImagePaths
{
    public function assignPathData ($image) {

        $image->OriginalFile = $this->getOriginalPath ($image->name);
        $image->DisplayFile = $this->getDisplayPath ($image->name);
        $image->ThumbFile = $this->getThumbPath ($image->name);

        $image->SizePaths = $this->getSizePaths ();

        $image->UrlThumbFile = $this->getThumbUrl ($image->name);
        // $image->UrlDisplayFile = $this->getSizeUrl ('400', $image->name); // toDo: image size to path
        $image->UrlDisplayFiles = $this->getDisplayUrl ($image->name);
        $image->UrlOriginalFile = $this->getOriginalUrl ($image->name);

        $image->SizeUrls = $this->getSizeUrls ($image->name);

        // does exist markers


        $image->isOriginalFileExist = file_exists($image->OriginalFile);
        $image->isDisplayFileExist = file_exists($image->DisplayFile);
        $image->isThumbFileExist = file_exists($image->DisplayFile);

        //$image->isSizePaths = $this->getSizePaths ();


    }





}