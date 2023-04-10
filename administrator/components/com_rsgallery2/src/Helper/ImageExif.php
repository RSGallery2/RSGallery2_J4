<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_rsgallery2
 *
 * @copyright (c) 2023-2023 RSGallery2 Team
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;


// Attention exif features may contain a array as subset
// ToDo: should exif features be kept in DB as json ? instead of reading it when needed ?
// ? then use selected and support recreate exif information as possibility in maintenance for changed selection with more or other elements
//
class ImageExif
{
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
        $selected = [];

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

                    $selected [$key . '.' . $name] = $val;
                }
            }

        }

        return $selected;
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

        /*
        		<field
				id="exifTags"
				name="exifTags"
				type="list"
				label="COM_RSGALLERY2_SELECT_EXIF_TAGS_TO_DISPLAY"
				description=""
				class="inputbox"
				size="5"
				multiple="true"
		>
			<option value="resolutionUnit">Resolution unit</option>
			<option value="FileName">Filename</option>
			<option value="FileSize">Filesize</option>
			<option value="FileDateTime">File Date</option>
			<option value="FlashUsed">Flash used</option>
			<option value="imageDesc">Image description</option>
			<option value="make">Camera make</option>
			<option value="model">Camera model</option>
			<option value="xResolution">X Resolution</option>
			<option value="yResolution">Y Resolution</option>
			<option value="software">Software used</option>
			<option value="fileModifiedDate">File modified date</option>
			<option value="YCbCrPositioning">YCbCrPositioning</option>
			<option value="exposureTime">Exposure time</option>
			<option value="fnumber">f-Number</option>
			<option value="exposure">Exposure</option>
			<option value="isoEquiv">ISO equivalent</option>
			<option value="exifVersion">EXIF version</option>
			<option value="DateTime">Date &amp; time</option>
			<option value="dateTimeDigitized">Original date</option>
			<option value="componentConfig">Component config</option>
			<option value="jpegQuality">Jpeg quality</option>
			<option value="exposureBias">Exposure bias</option>
			<option value="aperture">Aperture</option>
			<option value="meteringMode">Metering Mode</option>
			<option value="whiteBalance">White balance</option>
			<option value="flashUsed">Flash used</option>
			<option value="focalLength">Focal lenght</option>
			<option value="makerNote">Maker note</option>
			<option value="subSectionTime">Subsection time</option>
			<option value="flashpixVersion">Flashpix version</option>
			<option value="colorSpace">Color Space</option>
			<option value="Width">Width</option>
			<option value="Height">Height</option>
			<option value="GPSLatitudeRef">GPS Latitude reference</option>
			<option value="Thumbnail">Thumbnail</option>
			<option value="ThumbnailSize">Thumbnail size</option>
			<option value="sourceType">Source type</option>
			<option value="sceneType">Scene type</option>
			<option value="compressScheme">Compress scheme</option>
			<option value="IsColor">Color or B&amp;W</option>
			<option value="Process">Process</option>
			<option value="resolution">Resolution</option>
			<option value="color">Color</option>
			<option value="jpegProcess">Jpeg process</option>
		</field>

        /**/

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

        // On further additions reserve matching language names see below

        return $supportedTags;
    }

    public static function exifTranslationId ($ExifTag) {

         $translationId = '';

        if ( ! empty($ExifTag)) {

            if (!empty ($ExifTag[1])) {
                // use second part of name as identifier
                $name = explode(".", $ExifTag) [1];

                $translationId = 'COM_RSGALLERY2_EXIF_TAG_' . strtoupper($name);
            }
        }

        return $translationId;
    }

    public static function userExifTags()
    {
        $userExifTags = [];

        // ToDo: read config


        return $userExifTags;
    }

    public static function checkTagsNotSupported ($existingExifTags)    {

        $notSupportedTags = [];

        $supportedTags = self::supportedExifTags ();

//        foreach ($existingExifTags as $existingExifTag) {
        foreach ($existingExifTags as $existingExifTagSections) {

            $existingExifTag = explode ('.', $existingExifTagSections);
            if ( ! in_array($existingExifTag, $supportedTags)) {

                // $supportedTags [] = $existingExifTag;
                $notSupportedTags [] = $existingExifTagSections;

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
