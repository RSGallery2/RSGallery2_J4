<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2023-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Component\ComponentHelper;

class ImageExif
{
    // Tag names are not case senitive, so use it with lower cas as often it is possible

    protected string $imagPathFileName = '';

    protected $exifData = [];

    protected $exifFeatureTags = [];

    /**
     * Second ImageReference constructor. Tells if watermarked images shall be checked too
     *
     * @param   bool  $watermarked
     *
     * @since version 4.3
     */
    public function __construct(string $imagPathFileName='')
    {
        if ( ! empty ($imagPathFileName)) {

            // $this->readExifData ($imagPathFileName);
            $this->imagPathFileName = $imagPathFileName;
        }
    }

    // ($this->original->filePath()
    public function readExifDataAll(string $imagPathFileName = '')
    {
        $items = [];

        if ($imagPathFileName != '') {
            $this->imagPathFileName = $imagPathFileName;
        } else {
            $imagPathFileName = $this->imagPathFileName;
        }

        if (!function_exists('exif_read_data'))
        {
            return false;
        }

        // filename not defined
        if (empty($imagPathFileName))
        {
            return false;
        }

        // https://werner-zenk.de/php/exif-infos_eines_jpg-bildes_auslesen.php
//        if (exif_read_data($bild, 'IFD0')) {
//            $exif = exif_read_data($bild, 0, true);
//            foreach ($exif as $key => $section) {
//                foreach ($section as $name => $val) {
//                    echo "$key.$name: $val<br>\n";
//                }
//            }
//        }

        // https://stackoverflow.com/questions/30177313/exif-or-other-meta-data-data-that-windows-displays-but-php-does-not

        // required_sections: (second parameter) FILE, COMPUTED, ANY_TAG, IFD0, EXIF, IFD0, THUMBNAIL, COMMENT,


        // ??? ini_set('exif.encode_unicode', 'UTF-8');

        // check if readable
        if (exif_read_data($imagPathFileName, 'IFD0')) {
            // do read
            $exifData = exif_read_data($imagPathFileName, 0, true);

            foreach ($exifData as $key => $section) {

// if (is_array($section)) {
                foreach ($section as $name => $val) {
                    $type = gettype ($val);
                    if (json_encode ($val) === false){
                        $val = '%binary%';
                    }

                    // comma separated string
                    if (is_array($val)) {
                        $val = implode(',', $val);
                    }

                    $items [$key . '.' . $name] = $val;
                }
            }

        }

        return $items;
    }

    public function readExifDataSelected(string $imagPathFileName = '')
    {
        $selected = [];

        // align both file names
        if ($imagPathFileName != '') {
            $this->imagPathFileName = $imagPathFileName;
        } else {
            $imagPathFileName = $this->imagPathFileName;
        }

        if (!function_exists('exif_read_data')) {
            return $selected;
        }

        $supportedTags = $this->userExifTags();
//        // Debug: use all (J3x tags)
//        $supportedTags = $this->supportedExifTags();

        // required_sections: (second parameter) FILE, COMPUTED,	ANY_TAG, IFD0, EXIF, IFD0, THUMBNAIL, COMMENT,

        // check if readable
        if (exif_read_data($imagPathFileName, 'IFD0')) {
            // do read
            $exifData = exif_read_data($imagPathFileName, 0, true);
        }

        // IPTC auslesen
//             // Beim Auslesen der IPTC-Daten wird es schon etwas trickreicher. Das funktioniert über die Funktion getimagesize, genauer gesagt über den Zusatzparameter $info.
//
//             $size = getimagesize($imagPathFileName, $imgInfo);
//
//             if (isset($imgInfonfos["APP13"])) {
//
//                 $iptc_orig = iptcparse($imgInfo["APP13"]);
//
//                 var_dump($iptc_orig);
//
//             } else {
//                 echo "Keine IPTC-Daten ";
//             }

        foreach ($exifData as $key => $section) {
            foreach ($section as $name => $val) {
                if (in_array($name, $supportedTags)) {
                    $selected [$name] = $val;
                }
            }
        }

        return $selected;
    }

    // do use for debug purposes only
    public function exifData_FeatureTags()
    {
        if (empty($this->exifFeatureTags)) {
            $exifFeatureTags = [];

            foreach ($this->exifData as $key => $value) {

                if ( ! is_array ($value)) {

                    $this->exifFeatureTags [] = $key;

                }
                else
                {
                    // debug it ;-)
                    $test_outer = json_encode ($value);
                    foreach ($value as $item) {

                        $test = json_encode ($item);

                    }

                }

            }

        }

        return $this->exifFeatureTags;
    }

    // do use for debug purposes only
    public function exifData_Features()
    {

    }

    public function exifData_SelectedFeatures($selectedNames = [])
    {


    }

    public static function supportedExifTags () {

        $supportedTags = [];

        $supportedTags [] = 'EXIF.resolutionUnit';
        $supportedTags [] = 'EXIF.FileName';
        $supportedTags [] = 'EXIF.FileSize';
        $supportedTags [] = 'EXIF.FileDateTime';
        $supportedTags [] = 'EXIF.FlashUsed';
        $supportedTags [] = 'EXIF.imageDesc';
        $supportedTags [] = 'EXIF.make';
        $supportedTags [] = 'EXIF.model';
        $supportedTags [] = 'EXIF.xResolution';
        $supportedTags [] = 'EXIF.yResolution';
        $supportedTags [] = 'EXIF.software';
        $supportedTags [] = 'EXIF.fileModifiedDate';
        $supportedTags [] = 'EXIF.YCbCrPositioning';
        $supportedTags [] = 'EXIF.exposureTime';
        $supportedTags [] = 'EXIF.fnumber';
        $supportedTags [] = 'EXIF.exposure';
        $supportedTags [] = 'EXIF.isoEquiv';
        $supportedTags [] = 'EXIF.exifVersion';
        $supportedTags [] = 'EXIF.DateTime';
        $supportedTags [] = 'EXIF.dateTimeDigitized';
        $supportedTags [] = 'EXIF.componentConfig';
        $supportedTags [] = 'EXIF.jpegQuality';
        $supportedTags [] = 'EXIF.exposureBias';
        $supportedTags [] = 'EXIF.aperture';
        $supportedTags [] = 'EXIF.meteringMode';
        $supportedTags [] = 'EXIF.whiteBalance';
        $supportedTags [] = 'EXIF.flashUsed';
        $supportedTags [] = 'EXIF.focalLength';
        $supportedTags [] = 'EXIF.makerNote';
        $supportedTags [] = 'EXIF.subSectionTime';
        $supportedTags [] = 'EXIF.flashpixVersion';
        $supportedTags [] = 'EXIF.colorSpace';
        $supportedTags [] = 'EXIF.Width';
        $supportedTags [] = 'EXIF.Height';
        $supportedTags [] = 'EXIF.GPSLatitudeRef';
        $supportedTags [] = 'EXIF.Thumbnail';
        $supportedTags [] = 'EXIF.ThumbnailSize';
        $supportedTags [] = 'EXIF.sourceType';
        $supportedTags [] = 'EXIF.sceneType';
        $supportedTags [] = 'EXIF.compressScheme';
        $supportedTags [] = 'EXIF.IsColor';
        $supportedTags [] = 'EXIF.Process';
        $supportedTags [] = 'EXIF.resolution';
        $supportedTags [] = 'EXIF.color';
        $supportedTags [] = 'EXIF.jpegProcess';

        $supportedTags [] = 'FILE.FileName';
        $supportedTags [] = 'FILE.FileDateTime';
        $supportedTags [] = 'FILE.FileSize';
        $supportedTags [] = 'FILE.FileType';
        $supportedTags [] = 'FILE.MimeType';
        $supportedTags [] = 'FILE.SectionsFound';

        $supportedTags [] = 'COMPUTED.html';
        $supportedTags [] = 'COMPUTED.Height';
        $supportedTags [] = 'COMPUTED.Width';
        $supportedTags [] = 'COMPUTED.IsColor';
        $supportedTags [] = 'COMPUTED.ByteOrderMotorola';
        $supportedTags [] = 'COMPUTED.ApertureFNumber';
        $supportedTags [] = 'COMPUTED.Thumbnail.FileType';
        $supportedTags [] = 'COMPUTED.Thumbnail.MimeType';

        $supportedTags [] = 'IFD0.Make';
        $supportedTags [] = 'IFD0.Model';
        $supportedTags [] = 'IFD0.Orientation';
        $supportedTags [] = 'IFD0.XResolution';
        $supportedTags [] = 'IFD0.YResolution';
        $supportedTags [] = 'IFD0.ResolutionUnit';
        $supportedTags [] = 'IFD0.Software';
        $supportedTags [] = 'IFD0.DateTime';
        $supportedTags [] = 'IFD0.YCbCrPositioning';
        $supportedTags [] = 'IFD0.Exif_IFD_Pointer';
        $supportedTags [] = 'IFD0.GPS_IFD_Pointer';

        $supportedTags [] = 'THUMBNAIL.Compression';
        $supportedTags [] = 'THUMBNAIL.XResolution';
        $supportedTags [] = 'THUMBNAIL.YResolution';
        $supportedTags [] = 'THUMBNAIL.ResolutionUnit';
        $supportedTags [] = 'THUMBNAIL.JPEGInterchangeFormat';
        $supportedTags [] = 'THUMBNAIL.JPEGInterchangeFormatLength';
        $supportedTags [] = 'THUMBNAIL.YCbCrPositioning';

        natcasesort($supportedTags);

        return $supportedTags;
    }

    public static function tag2TypeAndName ($ExifTag) {

        $type = '';
        $name = '';

        if ( ! empty($ExifTag)) {

            $exifParts = explode(".", $ExifTag);

            if (!empty ($exifParts[0])) {
                // use second part of name as identifier
                $type = $exifParts [0];
            }

            // use second part as name
            if (!empty ($exifParts[1])) {

                // Tag names are not case senitive, so use it with lower cas as often it is possible
                $name = strtolower ($exifParts [1]);
            }
        }

        return [$type, $name];
    }

    public static function exifTranslationId ($ExifName) {

        $translationId = 'COM_RSGALLERY2_EXIF_TAG_' . strtoupper($ExifName);
        return $translationId;
    }

    public static function userExifTags()
    {
        $userExifTags = [];

        // ToDo: read config

        $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        //$compo_params = ComponentHelper::getComponent('com_rsgallery2')->getParams();
        $userExifTags = $rsgConfig->get('exifTags');


        return $userExifTags;
    }

    public static function checkTagsNotSupported ($existingExifTags)    {

        $notSupportedTags = [];

        // lower case array
        $supportedTags = array_map('strtolower', self::supportedExifTags ());


        foreach ($existingExifTags as $existingExifTag) {

            if ( ! in_array(strtolower($existingExifTag), $supportedTags)) {

                $notSupportedTags [] = $existingExifTag;
            }
        }

        return $notSupportedTags;
    }

    public static function checkNotUserSelected($existingExifTags)
    {
        $notUserExifTags = [];

        $userTags = self::userExifTags ();

//        foreach ($existingExifTags as $existingExifTag) {
        foreach ($existingExifTags as $existingExifTagSections) {

            $existingExifTag = explode ('.', $existingExifTagSections);
            if ( ! in_array($existingExifTag, $userTags)) {

                // $userTags [] = $existingExifTag;
                $notUserExifTags [] = $existingExifTagSections;

            }
        }

        return $notUserExifTags;
    }

}
