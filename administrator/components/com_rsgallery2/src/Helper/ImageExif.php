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
    public function __construct(string $imagPathFileName)
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

        // https://werner-zenk.de/php/exif-infos_eines_jpg-bildes_auslesen.php
//        if (exif_read_data($bild, 'IFD0')) {
//            $exif = exif_read_data($bild, 0, true);
//            foreach ($exif as $key => $section) {
//                foreach ($section as $name => $val) {
//                    echo "$key.$name: $val<br>\n";
//                }
//            }
//        }

        // required_sections: (second parameter) FILE, COMPUTED,	ANY_TAG, IFD0, EXIF, IFD0, THUMBNAIL, COMMENT,

        // check if readable
        if (exif_read_data($imagPathFileName, 'IFD0')) {
            // do read
            $exifData = exif_read_data($imagPathFileName, 0, true);

            // Debug todo: remove
//            echo '<br>--- exif data all ----------"' . $imagPathFileName . '" --------------------';
            foreach ($exifData as $key => $section) {
                foreach ($section as $name => $val) {
                    $type = gettype ($val);
//                    $strVal = strval($val);

//                    if ( ! json_encode ($strVal)){
                    $test = json_encode ($val);
                    if (json_encode ($val) === false){
                        $val = '%binary%';
                    }

//                    // Debug todo: remove
//                    echo $key . '.' . $name . ':' . $val . "<br>\n";
                    $item = $key . '.' . $name . ':' . $val;
                    $selected [$key . '.' . $name] = $val;

                }
            }

//            // Debug todo: remove
//            echo '<br>--- exif data all ----------"' . $imagPathFileName . '" --------------------';
//            foreach ($exifData as $key => $section) {
//                foreach ($section as $name => $val) {
//                    echo "$key.$name: $val<br>\n";
//                }
//            }
//
//             // IPTC auslesen
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


        }

        return $selected;
    }

    // ($this->original->filePath()
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

        // ToDo: use userExifTags
        $supportedTags = $this->userExifTags();
        // Debug: use all (J3x tags)
        $supportedTags = $this->supportedExifTags();

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

        // Debug todo: remove
        echo '<br>--- exif data selected ----------"' . $imagPathFileName . '" --------------------';

        foreach ($exifData as $key => $section) {
            foreach ($section as $name => $val) {
                if (inarray($name, $supportedTags)) {
                    // Debug todo: remove
                    echo $key . '.' . $name . ':' . $val . "<br>\n";
                    $selected [$name] = $val;
                }
            }
        }

        return $selected;
    }

//     public function exifDataSelected_Names(string $selectedNames)
//     {
//
//
//     }

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

        $supportedTags [] = 'resolutionUnit';
        $supportedTags [] = 'FileName';
        $supportedTags [] = 'FileSize';
        $supportedTags [] = 'FileDateTime';
        $supportedTags [] = 'FlashUsed';
        $supportedTags [] = 'imageDesc';
        $supportedTags [] = 'make';
        $supportedTags [] = 'model';
        $supportedTags [] = 'xResolution';
        $supportedTags [] = 'yResolution';
        $supportedTags [] = 'software';
        $supportedTags [] = 'fileModifiedDate';
        $supportedTags [] = 'YCbCrPositioning';
        $supportedTags [] = 'exposureTime';
        $supportedTags [] = 'fnumber';
        $supportedTags [] = 'exposure';
        $supportedTags [] = 'isoEquiv';
        $supportedTags [] = 'exifVersion';
        $supportedTags [] = 'DateTime';
        $supportedTags [] = 'dateTimeDigitized';
        $supportedTags [] = 'componentConfig';
        $supportedTags [] = 'jpegQuality';
        $supportedTags [] = 'exposureBias';
        $supportedTags [] = 'aperture';
        $supportedTags [] = 'meteringMode';
        $supportedTags [] = 'whiteBalance';
        $supportedTags [] = 'flashUsed';
        $supportedTags [] = 'focalLength';
        $supportedTags [] = 'makerNote';
        $supportedTags [] = 'subSectionTime';
        $supportedTags [] = 'flashpixVersion';
        $supportedTags [] = 'colorSpace';
        $supportedTags [] = 'Width';
        $supportedTags [] = 'Height';
        $supportedTags [] = 'GPSLatitudeRef';
        $supportedTags [] = 'Thumbnail';
        $supportedTags [] = 'ThumbnailSize';
        $supportedTags [] = 'sourceType';
        $supportedTags [] = 'sceneType';
        $supportedTags [] = 'compressScheme';
        $supportedTags [] = 'IsColor';
        $supportedTags [] = 'Process';
        $supportedTags [] = 'resolution';
        $supportedTags [] = 'color';
        $supportedTags [] = 'jpegProcess';

        // On further additions reserve matching language names see below

        return $supportedTags;
    }

    public static function exifTranslationId ($ExifTag) {

        // $translationId = '';
        // fall back with added Name like 'EXIF'
        $translationId = 'COM_RSGALLERY2_EXIF_TAG_' . strtoupper($ExifTag);

        if ( ! empty($ExifTag)) {

            // use second part of name as identifier
            $parts = explode(".", $ExifTag) [1];

            if (!empty ($parts[1])) {
                $name = $parts[1];

                $translationId = 'COM_RSGALLERY2_EXIF_TAG_' . strtoupper($name);
            }

        }

        return $translationId;
    }

    private function userExifTags()
    {
        $userExifTags = [];

        // ToDo: read config


        return $userExifTags;
    }

}