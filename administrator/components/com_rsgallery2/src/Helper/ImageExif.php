<?php
/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 *
 * @copyright  (c)  2023-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;

use Joomla\CMS\Component\ComponentHelper;

use const SORT_FLAG_CASE;
use const SORT_NATURAL;

/**
 * @package     Rsgallery2\Component\Rsgallery2\Administrator\Helper
 *
 * @since       version
 */
class ImageExif
{
    // Tag names are not case-sensitive, so use it with lower case as often it is possible

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
    public function __construct(string $imagPathFileName = '')
    {
        if (!empty ($imagPathFileName)) {
            // $this->readExifData ($imagPathFileName);
            $this->imagPathFileName = $imagPathFileName;
        }
    }

    public function readExifDataAll(string $imagPathFileName = '')
    {
        $items = [];

        if ($imagPathFileName != '') {
            $this->imagPathFileName = $imagPathFileName;
        } else {
            $imagPathFileName = $this->imagPathFileName;
        }

        if (!function_exists('exif_read_data')) {
            return false;
        }

        // filename not defined
        if (empty($imagPathFileName)) {
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

        ini_set('exif.encode_unicode', 'UTF-8');
        // ini_set('exif.decode_unicode_motorola', 'UCS-2LE');

        // check if readable
        if (exif_read_data($imagPathFileName, 'IFD0')) {
            // do read
            $exifData = exif_read_data($imagPathFileName, 0, true);

            foreach ($exifData as $key => $section) {
                foreach ($section as $name => $val) {
                    $type = gettype($val);
                    if (json_encode($val) === false) {
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

        // $test = natcasesort($items);
        $test = ksort($items, \SORT_NATURAL | \SORT_FLAG_CASE);

        return $items;
    }

    public function readExifDataUserSelected($supportedTags = [])
    {
        $selected = [];

        $exifItems = $this->readExifDataAll($this->imagPathFileName);

        // $supportedTags = $this->userExifTags();

//        foreach ($exifItems as $key => $section) {
//
//            foreach ($section as $name => $val) {
//                if (in_array(strtolower ($name), $supportedTags)) {
//                    $selected [$name] = $val;
//                }
//            }
//        }

        // user has selected tags
        if (!empty ($supportedTags)) {
            foreach ($exifItems as $name => $value) {
                if (in_array(strtolower($name), $supportedTags)) {
                    $selected [$name] = $value;
                }
            }
        }

        return $selected;
    }

    public static function supportedExifTags () {

        $supportedTags = [];

        $supportedTags [] = 'EXIF.aperture';
        $supportedTags [] = 'EXIF.color';
        $supportedTags [] = 'EXIF.colorSpace';
        $supportedTags [] = 'EXIF.componentConfig';
        $supportedTags [] = 'EXIF.compressScheme';
        $supportedTags [] = 'EXIF.DateTime';
        $supportedTags [] = 'EXIF.dateTimeDigitized';
        $supportedTags [] = 'EXIF.exifVersion';
        $supportedTags [] = 'EXIF.ExifImageLength';
        $supportedTags [] = 'EXIF.ExifImageWidth';
        $supportedTags [] = 'EXIF.exposure';
        $supportedTags [] = 'EXIF.exposureBias';
        $supportedTags [] = 'EXIF.exposureTime';
        $supportedTags [] = 'EXIF.FileDateTime';
        $supportedTags [] = 'EXIF.fileModifiedDate';
        $supportedTags [] = 'EXIF.FileName';
        $supportedTags [] = 'EXIF.FileSize';
        $supportedTags [] = 'EXIF.flashpixVersion';
        $supportedTags [] = 'EXIF.flashUsed';
        $supportedTags [] = 'EXIF.FlashUsed';
        $supportedTags [] = 'EXIF.Fnumber';
        $supportedTags [] = 'EXIF.focalLength';
        $supportedTags [] = 'EXIF.GPSLatitudeRef';
        $supportedTags [] = 'EXIF.ImageDescription';
        $supportedTags [] = 'EXIF.IsColor';
        $supportedTags [] = 'EXIF.isoEquiv';
        $supportedTags [] = 'EXIF.jpegProcess';
        $supportedTags [] = 'EXIF.jpegQuality';
        $supportedTags [] = 'EXIF.make';
        $supportedTags [] = 'EXIF.makerNote';
        $supportedTags [] = 'EXIF.meteringMode';
        $supportedTags [] = 'EXIF.model';
        $supportedTags [] = 'EXIF.Process';
        $supportedTags [] = 'EXIF.resolution';
        $supportedTags [] = 'EXIF.resolutionUnit';
        $supportedTags [] = 'EXIF.sceneType';
        $supportedTags [] = 'EXIF.software';
        $supportedTags [] = 'EXIF.sourceType';
        $supportedTags [] = 'EXIF.subSectionTime';
        $supportedTags [] = 'EXIF.Thumbnail';
        $supportedTags [] = 'EXIF.ThumbnailSize';
        $supportedTags [] = 'EXIF.version';
        $supportedTags [] = 'EXIF.whiteBalance';
        $supportedTags [] = 'EXIF.xResolution';
        $supportedTags [] = 'EXIF.YCbCrPositioning';
        $supportedTags [] = 'EXIF.yResolution';

        $supportedTags [] = 'FILE.FileDateTime';
        $supportedTags [] = 'FILE.FileName';
        $supportedTags [] = 'FILE.FileSize';
        $supportedTags [] = 'FILE.FileType';
        $supportedTags [] = 'FILE.MimeType';
        $supportedTags [] = 'FILE.SectionsFound';

        $supportedTags [] = 'COMPUTED.ApertureFNumber';
        $supportedTags [] = 'COMPUTED.ByteOrderMotorola';
        $supportedTags [] = 'COMPUTED.Height';
        $supportedTags [] = 'COMPUTED.html';
        $supportedTags [] = 'COMPUTED.IsColor';
        $supportedTags [] = 'COMPUTED.Thumbnail.FileType';
        $supportedTags [] = 'COMPUTED.Thumbnail.MimeType';
        $supportedTags [] = 'COMPUTED.Width';

        $supportedTags [] = 'IFD0.DateTime';
        $supportedTags [] = 'IFD0.Exif_IFD_Pointer';
        $supportedTags [] = 'IFD0.GPS_IFD_Pointer';
        $supportedTags [] = 'IFD0.Make';
        $supportedTags [] = 'IFD0.Model';
        $supportedTags [] = 'IFD0.Orientation';
        $supportedTags [] = 'IFD0.ResolutionUnit';
        $supportedTags [] = 'IFD0.Software';
        $supportedTags [] = 'IFD0.XResolution';
        $supportedTags [] = 'IFD0.YCbCrPositioning';
        $supportedTags [] = 'IFD0.YResolution';

        $supportedTags [] = 'THUMBNAIL.Compression';
        $supportedTags [] = 'THUMBNAIL.JPEGInterchangeFormat';
        $supportedTags [] = 'THUMBNAIL.JPEGInterchangeFormatLength';
        $supportedTags [] = 'THUMBNAIL.ResolutionUnit';
        $supportedTags [] = 'THUMBNAIL.XResolution';
        $supportedTags [] = 'THUMBNAIL.YCbCrPositioning';
        $supportedTags [] = 'THUMBNAIL.YResolution';

        natcasesort($supportedTags);

        return $supportedTags;
    }

    public static function tag2TypeAndName ($ExifTag) {

        $type = '';
        $name = '';

        if (!empty($ExifTag)) {
            $exifParts = explode(".", $ExifTag);

            if (!empty ($exifParts[0])) {
                // use second part of name as identifier
                $type = $exifParts [0];
            }

            // use second part as name
            if (!empty ($exifParts[1])) {
                // Tag names are not case senitive, so use it with lower cas as often it is possible
                $name = strtolower($exifParts [1]);
            }
        }

        return [$type, $name];
    }

    public static function exifTranslationId($ExifName)
    {
        $translationId = 'COM_RSGALLERY2_EXIF_TAG_' . strtoupper($ExifName);

        return $translationId;
    }

    public static function userExifTagsJ3x()
    {
        $userExifTags = [];

        $rsgConfig = ComponentHelper::getComponent('com_rsgallery2')->getParams();

        // lower case array
        $userExifTags = array_map('strtolower', $rsgConfig->get('exifTagsJ3x'));

        return $userExifTags;
    }

    public static function checkTagsNotSupported ($existingExifTags)    {

        $notSupportedTags = [];

        // lower case array
        $supportedTags = array_map('strtolower', self::supportedExifTags());

        foreach ($existingExifTags as $existingExifTag) {
            if (!in_array(strtolower($existingExifTag), $supportedTags)) {
                $notSupportedTags [] = $existingExifTag;
            }
        }

        natcasesort($notSupportedTags);

        return $notSupportedTags;
    }

    public static function checkNotUserSelected($existingExifTags)
    {
        $notUserExifTags = [];

        $userTags = self::userExifTagsJ3x();

//        foreach ($existingExifTags as $existingExifTag) {
        foreach ($existingExifTags as $existingExifTagSections) {
            $existingExifTag = explode('.', $existingExifTagSections);
            if (!in_array($existingExifTag, $userTags)) {
                // $userTags [] = $existingExifTag;
                $notUserExifTags [] = $existingExifTagSections;
            }
        }

        natcasesort($notUserExifTags);

        return $notUserExifTags;
    }

    public static function neededTranslationIds()
    {
        $neededIds = [];

        $supportedExifTags = self::supportedExifTags();

        foreach ($supportedExifTags as $exifTag) {
            [$type, $name] = ImageExif::tag2TypeAndName($exifTag);
            $neededIds [] = ImageExif::exifTranslationId($name);
        }

        return $neededIds;
    }

}
