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
/**/
//declare var joomla: Joomla;
//const joomla = window.Joomla || {};
const joomla = window.Joomla || {};
/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/
class Queue {
    constructor() {
        this._store = [];
    }
    push(val) { this._store.push(val); }
    shift() { return this._store.shift(); }
    get length() { return this._store.length; }
    isEmpty() { return this._store.length == 0; }
    isPopulated() { return this._store.length > 0; }
}
async function stall(stallTime = 333) {
    await new Promise(resolve => setTimeout(resolve, stallTime));
}
/**/
function resolveAfter2Seconds(x, time = 2000) {
    return new Promise(resolve => {
        setTimeout(() => {
            resolve(x);
        }, time);
    });
}
class DroppedFiles extends Queue {
    addFiles(files, galleryId) {
        for (let idx = 0; idx < files.length; idx++) {
            const file = files[idx];
            console.log('   +droppedFile: ' + files[idx].name);
            const next = {
                file: file,
                galleryId: galleryId,
            };
            this.push(next);
        }
    }
}
class TransferFiles extends Queue {
    add(file, imageId) {
        console.log('    +TransferFile: ' + file);
        const next = {
            file: file,
            imageId: imageId,
        };
        this.push(next);
    }
}
/*----------------------------------------------------------------
  Pointer to used html elements on form
----------------------------------------------------------------*/
class FormElements {
    // : HTMLElement;
    // : HTMLElement;
    constructor() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.dragZone = document.getElementById('dragarea');
        this.progressArea = document.getElementById('#uploadProgressArea');
    }
}
/*----------------------------------------------------------------
   gallery selection defines red / green border of drag area
----------------------------------------------------------------*/
class Border4SelectedGallery {
    constructor(selectGallery, dragZone) {
        //        this.selectGallery = selectGallery;
        this.dragZone = dragZone;
        selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);
        this.checkSelection(selectGallery.value);
    }
    onSelectionChange(target) {
        let selection = target;
        this.checkSelection(selection.value);
    }
    checkSelection(value) {
        //green
        if (value != "0") {
            this.dragZone.classList.remove('dragareaDisabled');
        }
        else {
            // red
            this.dragZone.classList.add('dragareaDisabled');
        }
    }
}
/*----------------------------------------------------------------
handle dropped files
----------------------------------------------------------------*/
class DroppedFilesTask {
    constructor(selectGallery, droppedFiles, requestDbImageIdTask) {
        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
        this.requestDbImageIdTask = requestDbImageIdTask;
        let buttonManualFile = document.querySelector('#select-file-button-drop');
        let fileInput = document.querySelector('#install_package');
        buttonManualFile.onclick = () => fileInput.click();
        fileInput.onchange = (ev) => this.onNewFile(ev);
    }
    onNewFile(ev) {
        let element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        const selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        const gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onNewFile: Rejected");
        }
        else {
            const files = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }
            console.log(">onNewFile: " + files.length);
            this.droppedFiles.addFiles(files, gallery_id);
            // Start ajax request of DB image reservation
            this.requestDbImageIdTask.ajaxRequest();
        }
    }
}
/*----------------------------------------------------------------
   Ajax request DB items for each file in list
   First step in transfer of file
----------------------------------------------------------------*/
class RequestDbImageIdTask {
    constructor(dragZone, droppedFiles, transferFiles, progressArea, transferImagesTask) {
        this.isBusy = false;
        this.dragZone = dragZone;
        this.droppedFiles = droppedFiles;
        this.transferFiles = transferFiles;
        this.progressArea = progressArea;
        this.transferImagesTask = transferImagesTask;
    }
    /**/
    async callAjaxRequest(nextFile) {
        console.log("      > callAjaxRequest: " + nextFile.file.name);
        /**
        let result = await setTimeout(() => {
            console.log("> callAjaxRequest: " + nextFile.file.name)
        }, 333);
        /**/
        //        await stall (50);
        console.log("      < callAjaxRequest: ");
        return nextFile;
    }
    /**/
    /**/
    async ajaxRequest() {
        console.log("    >this.droppedFiles.length: " + this.droppedFiles.length);
        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/
        while (this.droppedFiles.length > 0) {
            let nextFile = this.droppedFiles.shift();
            console.log("   @Request File: " + nextFile.file.name);
            /* let request = */
            await this.callAjaxRequest(nextFile)
                .then(() => {
                console.log("   <Request OK: " + nextFile.file.name);
                this.transferFiles.add(nextFile.file.name, nextFile.galleryId);
                // Start ajax transfer of files
                this.transferImagesTask.ajaxTransfer();
            })
                .catch(() => {
                console.log("    !!! Error request: " + nextFile.file.name);
            });
            /**/
            console.log("    *this.droppedFiles.length: " + this.droppedFiles.length);
        }
        this.isBusy = false;
        console.log("    <this.droppedFiles.length: " + this.droppedFiles.length);
    }
}
/*----------------------------------------------------------------
     Ajax transfer files to server
----------------------------------------------------------------*/
class TransferImagesTask {
    constructor(dragZone, transferFiles, progressArea) {
        this.isBusyCount = 0;
        this.BusyCountLimit = 5;
        this.dragZone = dragZone;
        this.transferFiles = transferFiles;
        this.progressArea = progressArea;
    }
    async callAjaxTransfer(nextFile) {
        console.log("      > callAjaxTransfer: " + nextFile.file);
        /**
        let result = await setTimeout(() => {
            console.log("> callAjaxTransfer: " + nextFile.file)
        }, 333);
        /**/
        await stall(3000);
        //await resolveAfter2Seconds(20, 3000);
        console.log("      < callAjaxTransfer: ");
        return nextFile;
    }
    /**/
    async ajaxTransfer() {
        console.log("    >this.transferFiles.length: " + this.transferFiles.length);
        // check for busy
        while (this.isBusyCount < this.BusyCountLimit
            && this.transferFiles.length > 0) {
            this.isBusyCount++;
            let nextFile = this.transferFiles.shift();
            console.log("   @Transfer File: " + nextFile.file);
            //
            this.callAjaxTransfer(nextFile)
                .then(() => {
                console.log("   <Transfer OK: " + nextFile.file);
            })
                .catch(() => {
                console.log("    !!! Error transfer: " + nextFile.file);
            })
                .finally(() => {
                this.isBusyCount--;
                this.ajaxTransfer();
            });
            /**/
        }
        console.log("    <this.transferFiles.length: " + this.transferFiles.length);
    }
}
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    // collect html elements
    let elements = new FormElements();
    // on old browser just show file upload
    if (typeof FormData === 'undefined') {
        let legacy_uploader = document.getElementById('legacy-uploader');
        let uploader_wrapper = document.getElementById('uploader-wrapper');
        legacy_uploader.style.display = 'block';
        uploader_wrapper.style.display = 'none';
        return;
    }
    // Exit if no galleries are selectable
    if (!elements.selectGallery) {
        return;
    }
    // Reserve list for dropped files
    const droppedFiles = new DroppedFiles();
    const transferFiles = new TransferFiles();
    // init red / green border of drag area
    const gallerySelected = new Border4SelectedGallery(elements.selectGallery, elements.dragZone);
    // (3) ajax request: Transfer file to server
    const transferImagesTask = new TransferImagesTask(elements.dragZone, transferFiles, elements.progressArea);
    // (2) ajax request: database image item
    const requestDbImageIdTask = new RequestDbImageIdTask(elements.dragZone, droppedFiles, transferFiles, elements.progressArea, transferImagesTask);
    // (1) collect dropped files, start request DB image ID
    let droppedFilesTask = new DroppedFilesTask(elements.selectGallery, droppedFiles, requestDbImageIdTask);
    //--------------------------------------------------------------------------------------
    // Drop init and start
    //--------------------------------------------------------------------------------------
    //--- no other drop on the form ---------------------
    /**/
    window.addEventListener('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = "copy";
    });
    window.addEventListener('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    window.addEventListener('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    /**/
    //--- drop zone events ---------------------
    elements.dragZone.addEventListener('dragenter', event => {
        event.preventDefault();
        event.stopPropagation();
        //elements.dragZone.classList.add('hover');
        elements.dragZone.classList.add('hover');
        event.dataTransfer.dropEffect = "copy";
        return false;
    }); // Notify user when file is over the drop area
    elements.dragZone.addEventListener('dragover', event => {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.add('hover');
        return false;
    });
    elements.dragZone.addEventListener('dragleave', event => {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.remove('hover');
        return false;
    });
    elements.dragZone.addEventListener('drop', event => {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.remove('hover');
        /**/
        const files = event.target.files || event.dataTransfer.files;
        /**/
        if (!files.length) {
            return;
        }
        //        Array.from(files).foreach ((File) => {console.log("filename: " + File.name);});
        for (var i = 0; i < files.length; i++) {
            console.log("filename: " + files[i].name);
            console.log(files[i]);
        }
        /**/
        droppedFilesTask.onNewFile(event);
    });
    //--------------------------------------------------------------------------------------
    //
    //--------------------------------------------------------------------------------------
    //--------------------------------------------------------------------------------------
    // new functions and new submit buttons
    //--------------------------------------------------------------------------------------
    /**
     * call imagesProperties view. There assign properties to dropped files
     */
    /**
    Joomla.submitAssign2DroppedFiles = function () {
    // submitAssign2DroppedFiles = function () {
    //        alert('submitAssignDroppedFiles:  ...');
        const form: HTMLFormElement = <HTMLFormElement> document.getElementById('adminForm');

        // ToDo: check if one image exists
        form.task.value = 'imagesProperties.PropertiesView';

        form.submit();
    };

    /**
     * Upload zip file, checks and calls
     */
    /**
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
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(2)');
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
    /**
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
                alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(4)');
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
});
/**/
