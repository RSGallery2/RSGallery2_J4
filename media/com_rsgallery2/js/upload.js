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
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var Joomla = window.Joomla || {};
// ToDo: put to and import RSG2 lib ?
var Queue = /** @class */ (function () {
    function Queue() {
        this.list = new Array();
        this.lock = false;
    }
    Object.defineProperty(Queue.prototype, "length", {
        get: function () {
            while (this.lock) { }
            return this.list.length;
        },
        enumerable: true,
        configurable: true
    });
    // enqueue()
    Queue.prototype.push = function (item) {
        while (this.lock) { }
        this.lock = true;
        try {
            this.list.push(item);
        }
        catch (_a) {
            var outTxt = "error pushing item in queue";
            console.log(outTxt);
            alert(outTxt);
        }
        this.lock = false;
    };
    // dequeue()
    // returns T or undefined
    Queue.prototype.shift = function () {
        while (this.lock) { }
        var item = undefined;
        // elements exist
        if (this.isPopulated()) {
            this.lock = true;
            try {
                item = this.list.shift();
            }
            catch (_a) {
                var outTxt = "error shift item in queue";
                console.log(outTxt);
                alert(outTxt);
            }
            this.lock = false;
        }
        return item;
    };
    Queue.prototype.isEmpty = function () {
        while (this.lock) { }
        // return true if the queue is empty.
        return this.list.length == 0;
    };
    Queue.prototype.isPopulated = function () {
        while (this.lock) { }
        // return true if the queue has elements
        return this.list.length > 0;
    };
    return Queue;
}());
var DroppedFiles = /** @class */ (function (_super) {
    __extends(DroppedFiles, _super);
    function DroppedFiles() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    DroppedFiles.prototype.addFiles = function (files, galleryId) {
        /**
        //        for(let file of files){
        //        [...files].map(file => {
        //        Array.from(files).forEach ((file) => {
                Array.prototype.forEach.call(files, function(file) {
                    const next : IDroppedFile = {
                        name: file.,
                        path: file,
                    }
        
                    this.push (next);
                });
        /**/
        for (var idx = 0; idx < files.length; idx++) {
            var file = files[idx];
            console.log('addFile: ' + files[idx].name);
            var next = {
                file: file,
                galleryId: galleryId,
            };
            this.push(next);
        }
    };
    return DroppedFiles;
}(Queue));
var UploadHTMLElements = /** @class */ (function () {
    //    progressArea
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    function UploadHTMLElements() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.dragZone = document.getElementById('dragarea');
    }
    return UploadHTMLElements;
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
        var selection = target;
        this.checkSelection(selection.value);
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
/*----------------------------------------------------------------
   red / green border of drag area
----------------------------------------------------------------*/
var eSendState;
(function (eSendState) {
    eSendState[eSendState["idle"] = 0] = "idle";
    eSendState[eSendState["busy"] = 1] = "busy";
})(eSendState || (eSendState = {}));
var DropInDragArea = /** @class */ (function () {
    function DropInDragArea(dragZone, selectGallery, droppedFiles) {
        var _this = this;
        this.dragZone = dragZone;
        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
        //        this.progressArea = progressArea;
        var buttonManualFile = document.querySelector('#select-file-button');
        var fileInput = document.querySelector('#install_package');
        /**
        buttonManualFile.addEventListener('click', function () {
            fileInput.click();
        });
        /**/
        buttonManualFile.onclick = function () { return fileInput.click(); };
        fileInput.onchange = function (ev) { return _this.onNewFile(ev); };
        //        selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);
        //        this.checkSelection (selectGallery.value);
    }
    DropInDragArea.prototype.onNewFile = function (ev) {
        var element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        var selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        var gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(5)');
        }
        else {
            var files = element.files;
            // files exist ?
            if (!files.length) {
                return;
            }
            this.droppedFiles.addFiles(files, gallery_id);
            //        prepareReserveDbImageId(files, this.progressArea);
            this.requestDbImageId();
        }
    };
    DropInDragArea.prototype.requestDbImageId = function () {
        // Not busy
        if (this.sendState == 0) {
            if (this.droppedFiles.length > 0) {
            }
        }
        // for function reserveDbImageId
        var data = new FormData();
        // data.append('upload_file', files[idx]);
        data.append('upload_file', files[idx].name);
        data.append('imagesDroppedListIdx', imagesDroppedListIdx);
        data.append(Token, '1');
        data.append('gallery_id', gallery_id);
        //data.append('idx', idx);
        // Set progress bar
        var statusBar = new createStatusBar(progressArea);
        statusBar.setFileNameSize(files[idx].name, files[idx].size);
    };
    return DropInDragArea;
}());
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    // collect html elements
    var elements = new UploadHTMLElements();
    // on old browser just show file upload
    if (typeof FormData === 'undefined') {
        var legacy_uploader = document.getElementById('legacy-uploader');
        var uploader_wrapper = document.getElementById('uploader-wrapper');
        legacy_uploader.style.display = 'block';
        uploader_wrapper.style.display = 'none';
        return;
    }
    // Reserve list for dropped files
    var droppedFiles = new DroppedFiles();
    // init red / green border of drag area
    var gallerySelected = new GallerySelected(elements.selectGallery, elements.dragZone);
    // init drag, drop and file upload  
    var droppInDragArea = new DropInDragArea(elements.dragZone, elements.selectGallery, droppedFiles);
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
});
/**/
