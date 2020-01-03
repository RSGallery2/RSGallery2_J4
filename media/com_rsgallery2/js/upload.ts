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

/**
// import { Queue } from './libQueueStack';
/// <reference path="./libQueueStack.ts" /> #
/**/

/**
 * Created by ericfernance on 27/11/2015.
 */
/**/
interface Joomla {
    JText: {
        _(String)
    }
    submitbutton: any;
    submitform: any;
}
/**/

//declare var joomla: Joomla;

//const joomla = window.Joomla || {};
const joomla:Joomla = window.Joomla || {};

/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/

class Queue<T> {
    private _store: T[] = [];
    push(val: T) { this._store.push(val); }
    shift(): T | undefined { return this._store.shift(); }
    get length():number{ return this._store.length; }
    isEmpty():boolean {return this._store.length == 0;}
    isPopulated():boolean {return this._store.length > 0; }
}

/*----------------------------------------------------------------
   List of dropped files (waiting for db request)
----------------------------------------------------------------*/

interface IDroppedFile {
    file: File;
    galleryId: string;
//    size:number;
}

class DroppedFiles extends Queue<IDroppedFile> {

    addFiles(files: FileList, galleryId: string) {

        for (let idx = 0; idx < files.length; idx++) {
            const file: File = files[idx];

            console.log('addFile: ' + files[idx].name);

            const next : IDroppedFile = {
                file: file,
                galleryId: galleryId,
            };

            this.push (next);
        }

    }
}

/*----------------------------------------------------------------
   List of files (waiting to transfer)
----------------------------------------------------------------*/

interface ITransferFile {
    file: string;
    imageId: string;
//    size:number;
}

class TransferFiles extends Queue<ITransferFile> {

    addFiles(file: string, imageId: string) {

        console.log('addFile: ' + file);
        const next : ITransferFile = {
            file: file,
            imageId: imageId,
        };

        this.push (next);
    }

}

/*----------------------------------------------------------------
  Pointer to used html elements on form
----------------------------------------------------------------*/

class FormElements {
    selectGallery: HTMLInputElement;
    dragZone: HTMLElement;
    progressArea: HTMLElement;

    // : HTMLElement;
    // : HTMLElement;

    constructor() {
        this.selectGallery = <HTMLInputElement> document.getElementById('SelectGallery');
        this.dragZone = <HTMLElement> document.getElementById('dragarea');
        this.progressArea = <HTMLElement> document.getElementById('#uploadProgressArea');
    }

}

/*----------------------------------------------------------------
   gallery selection defines red / green border of drag area
----------------------------------------------------------------*/

class Border4SelectedGallery {
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
   Ajax request DB items for each file in list
   First step in transfer of file
----------------------------------------------------------------*/

class RequestDbImageId {

    private dragZone: HTMLElement;
    private progressArea: HTMLElement;
    private droppedFiles: DroppedFiles;
//    private sendState: eSendState;

    private request: Promise<IDroppedFile>;
    private isBusy: boolean = false;

    constructor(
        dragZone: HTMLElement,
        droppedFiles: DroppedFiles,
        progressArea: HTMLElement
        ) {
            this.dragZone = dragZone;
            this.droppedFiles = droppedFiles;
            this.progressArea = progressArea;

    }

    /**/
    private async callAjaxRequest(nextFile: IDroppedFile) {

        let result = await setTimeout(() => {
            console.log("callAjaxRequest: " + nextFile.file)
        }, 333);

        return nextFile;
    }
    /**/

    /**/
    public async doRequestDbImageId() {

        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/


        while (this.droppedFiles.length > 0) {
            let nextFile = this.droppedFiles.shift();
            console.log("nextFile: " + nextFile.file);

            /* let request = */
            await this.callAjaxRequest(nextFile)
                .then(() => {
                    console.log("success: " + nextFile.file);



                })
                .catch(() => {
                    console.log("error: " + nextFile.file);
                });
            /**/

        }

        this.isBusy = false;
    }

    /**
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

     /**/

}

/*----------------------------------------------------------------
     Ajax transfer files to server
----------------------------------------------------------------*/

class TransferImagesAjax {

    private dragZone: HTMLElement;
    private progressArea: HTMLElement;
    private transferFiles: DroppedFiles;

    private request: Promise<IDroppedFile>;
    private isBusy: boolean = false;

    constructor(
        dragZone: HTMLElement,
        transferFiles: TransferFiles,
        progressArea: HTMLElement
    ) {
        this.dragZone = dragZone;
        this.transferFiles = transferFiles;
        this.progressArea = progressArea;
    }

    private async callAjaxRequest(nextFile: IDroppedFile) {

        let result = await setTimeout(() => {
            console.log("callAjaxRequest: " + nextFile.file)
        }, 333);

        return nextFile;
    }

    /**/
    public async doRequestDbImageId() {

        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/


        while (this.transferFiles.length > 0) {
            let nextFile = this.transferFiles.shift();
            console.log("nextFile: " + nextFile.file);

            /* let request = */
            await this.callAjaxRequest(nextFile)
                .then(() => {
                    console.log("success: " + nextFile.file);



                })
                .catch(() => {
                    console.log("error: " + nextFile.file);
                });
            /**/

        }

        this.isBusy = false;
    }


}

/*----------------------------------------------------------------
handle dropped files
----------------------------------------------------------------*/

class DroppingFiles {
    private selectGallery: HTMLInputElement;
    private droppedFiles: DroppedFiles;
    private requestDbImageId: RequestDbImageId;

    constructor(
                selectGallery: HTMLInputElement,
                droppedFiles: DroppedFiles,
                requestDbImageId: RequestDbImageId
                ) {

        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
        this.requestDbImageId = requestDbImageId;

        let buttonManualFile = <HTMLButtonElement> document.querySelector('#select-file-button-drop');
        let fileInput = <HTMLInputElement> document.querySelector('#install_package');

        buttonManualFile.onclick = () =>  fileInput.click();
        fileInput.onchange = (ev: DragEvent) => this.onNewFile(ev);
    }

    onNewFile(ev: DragEvent ) {
        let element = <HTMLInputElement>ev.target;

        ev.preventDefault();
        ev.stopPropagation();

        // gallery id
        const selectionHTML = <HTMLInputElement>this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        const gallery_id =  selectionHTML.value;

        // prevent empty gallery
        if (parseInt (gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_CATEGORY_FIRST') + '(5)');
        }
        else {

            const files: FileList = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }

            this.droppedFiles.addFiles (files, gallery_id);

            // Start first Part of transfer files
            this.requestDbImageId.doRequestDbImageId ();

        }
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
    let elements = new FormElements();

    // on old browser just show file upload
    if (typeof FormData === 'undefined') {
        let legacy_uploader = <HTMLInputElement> document.getElementById('legacy-uploader');
        let uploader_wrapper = <HTMLElement> document.getElementById('uploader-wrapper');
        legacy_uploader.style.display = 'block';
        uploader_wrapper.style.display = 'none';

        return;
    }

    // Exit if no galleries are selectable
    if ( ! elements.selectGallery)
    {
        return;
    }

    // Reserve list for dropped files
    const droppedFiles = new DroppedFiles();
    const transferFiles = new TransferFiles();

    // init red / green border of drag area
    const gallerySelected = new Border4SelectedGallery (elements.selectGallery, elements.dragZone);

    // ajax request: database image item
    const requestDbImageId = new RequestDbImageId (elements.dragZone, droppedFiles, elements.progressArea);

    const transferImages = new TransferImagesAjax (elements.dragZone, transferFiles, elements.progressArea);

    // init drag, drop and file upload  
    let dropInDragArea = new DroppingFiles (elements.selectGallery, droppedFiles, requestDbImageId);





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
        dropInDragArea.onNewFile (event);
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


});



/**/

