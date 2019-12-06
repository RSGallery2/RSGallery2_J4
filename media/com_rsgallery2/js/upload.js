/**
 * @package     RSGallery2
 *
 * supports zip/ftp upload buttons
 * supports ajax drag and drop file upload wit two calls
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       4.3.0
 */
var uploadHTMLElements = /** @class */ (function () {
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    function uploadHTMLElements() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.dragZone = document.getElementById('dragarea');
    }
    return uploadHTMLElements;
}());
/*----------------------------------------------------------------
   red / green border of drag area
----------------------------------------------------------------*/
var GallerySelected = /** @class */ (function () {
    function GallerySelected(selectGallery, dragZone) {
        var _this = this;
        //        this.selectGallery = selectGallery;
        this.dragZone = dragZone;
        selectGallery.onchange = function (ev) { return _this.onSelectionChange(ev.target); };
        this.checkSelection(selectGallery.value);
    }
    GallerySelected.prototype.onSelectionChange = function (target) {
        var element = target;
        this.checkSelection(element.value);
    };
    GallerySelected.prototype.checkSelection = function (value) {
        //green
        if (value != "0") {
            this.dragZone.classList.remove('dragareaDisabled');
        }
        else {
            // red
            this.dragZone.classList.add('dragareaDisabled');
        }
    };
    return GallerySelected;
}());
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    // collect html elements
    var elements = new uploadHTMLElements();
    // red / green border of drag area
    var gallerySelected = new GallerySelected(elements.selectGallery, elements.dragZone);
});
//--------------------------------------------------------------------------------------
// new functions and new submit buttons
//--------------------------------------------------------------------------------------
/**
 * call imagesProperties view. There assign properties to dropped files
 */
Joomla.submitAssign2DroppedFiles = function () {
    // submitAssign2DroppedFiles = function () {
    //        alert('submitAssignDroppedFiles:  ...');
    var form = document.getElementById('adminForm');
    // ToDo: check if one image exists
    form.task.value = 'imagesProperties.PropertiesView';
    form.submit();
};
/**
 * Upload zip file, checks and calls
 */
Joomla.submitButtonManualFileZipPc = function () {
    // alert('Upload Manual File Zip from Pc: controller upload.uploadFromZip ...');
    var form = document.getElementById('adminForm');
    var zip_path = form.zip_file.value;
    var gallery_id = jQuery('#SelectGalleries_01').val();
    var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_01"]').val();
    // No file path given
    if (zip_path == "") {
        alert(Joomla.JText._('COM_RSGALLERY2_ZIP_MINUS_UPLOAD_SELECTED_BUT_NO_FILE_CHOSEN'));
    }
    else {
        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(2)');
        }
        else {
            // yes transfer files ...
            form.task.value = 'upload.uploadFromZip'; // upload.uploadZipFile
            form.batchmethod.value = 'zip';
            form.ftppath.value = "";
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";
            jQuery('#loading').css('display', 'block');
            form.submit();
        }
    }
};
/**/
/*
 * Upload server file checks and calls
 */
Joomla.submitButtonManualFileFolderServer = function () {
    // alert('Upload Folder server: upload.uploadFromFtpFolder ...');
    var form = document.getElementById('adminForm');
    //var GalleryId = jQuery('#SelectGalleries_03').chosen().val();
    var gallery_id = jQuery('#SelectGalleries_02').val();
    var ftp_path = form.ftp_path.value;
    var bOneGalleryName4All = jQuery('input[name="all_img_in_step1_02"]').val();
    // ftp path is not given
    if (ftp_path == "") {
        alert(Joomla.JText._('COM_RSGALLERY2_FTP_UPLOAD_CHOSEN_BUT_NO_FTP_PATH_PROVIDED'));
    }
    else {
        // Is invalid galleryId selected ?
        if (bOneGalleryName4All && (gallery_id < 1)) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(4)');
        }
        else {
            // yes transfer files ...
            form.task.value = 'upload.uploadFromFtpFolder'; // upload.uploadZipFile
            form.batchmethod.value = 'FTP';
            form.ftppath.value = ftp_path;
            form.xcat.value = gallery_id;
            form.selcat.value = bOneGalleryName4All;
            form.rsgOption.value = "";
            //jQuery('#loading').css('display', 'block');
            form.submit();
        }
    }
};
/**/
