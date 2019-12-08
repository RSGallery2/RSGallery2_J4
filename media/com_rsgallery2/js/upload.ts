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

const Joomla = window.Joomla || {};

// ToDo: put to and import RSG2 lib ?
class Queue <T> {

    lock: boolean;
    list: T [];

    constructor() {
        this.list = new Array<T>();
        this.lock = false;
    }

    get length () {
        while (this.lock){}

        return this.list.length;
    }

    // enqueue()
    push (item: T) {
        while (this.lock) {}

        this.lock = true;
        try
        {
            this.list.push (item);
        }
        catch
        {
            const outTxt = "error pushing item in queue";
            console.log(outTxt);
            alert (outTxt);
        }

        this.lock = false;
    }

    // dequeue()
    // returns T or undefined
    shift () {
        while (this.lock){}

        let item = undefined;

        // elements exist
        if (this.isPopulated()) {

            this.lock = true;
            try {
                item = this.list.shift();
            } catch {
                const outTxt = "error shift item in queue";
                console.log(outTxt);
                alert(outTxt);
            }

            this.lock = false;
        }

        return item;
    }

    isEmpty() {
        while (this.lock){}

        // return true if the queue is empty.
        return this.list.length == 0;
    }

    isPopulated () {
        while (this.lock) {}

        // return true if the queue has elements
        return this.list.length > 0;
    }


}

// toDO: use "File" type as interface
interface IDroppedFile {
    file: File;
    galleryId: string;
//    size:number;
}

class DroppedFiles extends Queue<IDroppedFile> {

    addFiles(files: FileList, galleryId: string) {
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
            const file: File = files[idx];

            console.log('addFile: ' + files[idx].name);

            const next : IDroppedFile = {
                file: file,
                galleryId: galleryId,
            }

            this.push (next);
        }
    }




}

class UploadHTMLElements {
    selectGallery: HTMLInputElement;
    dragZone: HTMLElement;

//    progressArea

    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;

    constructor() {
        this.selectGallery = <HTMLInputElement> document.getElementById('SelectGallery');
        this.dragZone = <HTMLElement> document.getElementById('dragarea');


    }

}

/*----------------------------------------------------------------
   red / green border of drag area
----------------------------------------------------------------*/

class GallerySelected {
//    selectGallery: HTMLElement;
    dragZone: HTMLElement;

    constructor(selectGallery: HTMLInputElement, dragZone: HTMLElement) {
//        this.selectGallery = selectGallery;
        this.dragZone = dragZone;

        selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);

        this.checkSelection (selectGallery.value);
    }

    onSelectionChange(target: EventTarget) {
        let selection = <HTMLInputElement>target;
        this.checkSelection (selection.value);
    }

    checkSelection (value: string) {
        //green
        if (value != "0") {
            this.dragZone.classList.remove('dragareaDisabled');
        } else {
            // red
            this.dragZone.classList.add('dragareaDisabled');
        }
    }
}

/*----------------------------------------------------------------
   red / green border of drag area
----------------------------------------------------------------*/

enum eSendState {
    idle ,
    busy,
}

class DropInDragArea {
    selectGallery: HTMLInputElement;
    dragZone: HTMLElement;
//    progressArea: HTMLElement;
    droppedFiles : DroppedFiles;
    private sendState: number;

    constructor(dragZone: HTMLElement,
                selectGallery: HTMLInputElement,
                droppedFiles: DroppedFiles,
                //progressArea: HTMLElement
                ) {
        this.dragZone = dragZone;
        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
//        this.progressArea = progressArea;

        let buttonManualFile = <HTMLButtonElement> document.querySelector('#select-file-button');
        let fileInput = <HTMLInputElement> document.querySelector('#install_package');

        /**
        buttonManualFile.addEventListener('click', function () {
            fileInput.click();
        });
        /**/
        buttonManualFile.onclick = () =>  fileInput.click();

        fileInput.onchange = (ev) => this.onNewFile(ev);


//        selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);

//        this.checkSelection (selectGallery.value);
    }

    onNewFile(ev: Event) {
        let element = <HTMLInputElement>ev.target;
        ev.preventDefault();
        ev.stopPropagation();

        // gallery id
        const selectionHTML = <HTMLInputElement>this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        const gallery_id =  selectionHTML.value;

        // prevent empty gallery
        if (parseInt (gallery_id) < 1) {
            alert(Joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(5)');
        }
        else {

            const files: FileList = element.files;
            // files exist ?
            if (!files.length) {
                return;
            }

            this.droppedFiles.addFiles (files, gallery_id);
    //        prepareReserveDbImageId(files, this.progressArea);

            this.requestDbImageId ();

        }
    }

    requestDbImageId () {

        // Not busy
        if (this.sendState == 0) {

            if(this.droppedFiles.length > 0)
            {

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




    }


    /**
     onNewFile(target: EventTarget) {
        let element = <HTMLInputElement>target;
        this.checkSelection (element.value);
    }

    checkSelection (value: string) {
        //green
        if (value != "0") {
            this.dragZone.classList.remove('dragareaDisabled');
        } else {
            // red
            this.dragZone.classList.add('dragareaDisabled');
        }
    }
 /**/
}


//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function(event) {

    // collect html elements
    let elements = new UploadHTMLElements();

    // on old browser just show file upload
    if (typeof FormData === 'undefined') {
        let legacy_uploader = <HTMLInputElement> document.getElementById('legacy-uploader');
        let uploader_wrapper = <HTMLElement> document.getElementById('uploader-wrapper');
        legacy_uploader.style.display = 'block';
        uploader_wrapper.style.display = 'none';

        return;
    }

    // Reserve list for dropped files
    const droppedFiles = new DroppedFiles();


    // init red / green border of drag area
    let gallerySelected = new GallerySelected (elements.selectGallery, elements.dragZone);

    // init drag, drop and file upload  
    let droppInDragArea = new DropInDragArea (elements.dragZone, elements.selectGallery, droppedFiles);



    //--------------------------------------------------------------------------------------
    // new functions and new submit buttons
    //--------------------------------------------------------------------------------------

    /**
     * call imagesProperties view. There assign properties to dropped files
     */
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

