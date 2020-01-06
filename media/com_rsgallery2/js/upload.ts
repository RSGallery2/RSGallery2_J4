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


var Token:string;


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
    simulate wait
----------------------------------------------------------------*/

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
/**
function resolveAfter2Seconds(x) {
    return new Promise(resolve => {
        setTimeout(() => {
            resolve(x);
        }, 2000);
    });
}
/**/

/*----------------------------------------------------------------
   List of dropped files (waiting for db request)
----------------------------------------------------------------*/

interface IDroppedFile {
    file: File;
    galleryId: string;
    statusBar: createStatusBar | null;
    errorZone: HTMLElement | null;

//    size:number;
}

class DroppedFiles extends Queue<IDroppedFile> {

    addFiles(files: FileList, galleryId: string) {

        for (let idx = 0; idx < files.length; idx++) {
            const file: File = files[idx];

            console.log('   +droppedFile: ' + files[idx].name);

            //--- ToDo: Check 4 allowed image type ---------------------------------

            // file.type ...

            //--- Add file with data ---------------------------------

            const next : IDroppedFile = {
                file: file,
                galleryId: galleryId,
                statusBar: null,
                errorZone: null,
            };

            this.push (next);
        }

    }
}

/*----------------------------------------------------------------
   List of files (waiting to transfer)
----------------------------------------------------------------*/

interface ITransferFile extends IDroppedFile {
    imageId: string;
    fileName: string;
    dstFileName: string;
}

class TransferFiles extends Queue<ITransferFile> {

    add(nextFile: IDroppedFile, imageId: string, fileName: string, dstFileName: string) {

        console.log('    +TransferFile: ' + nextFile.file.name);
        const next : ITransferFile = {
            file: nextFile.file,
            galleryId: nextFile.galleryId,
            statusBar: nextFile.statusBar,
            errorZone: nextFile.errorZone,
            imageId: imageId,
            fileName: fileName,
            dstFileName: dstFileName,
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
    errorZone: HTMLElement;

    // : HTMLElement;
    // : HTMLElement;
    // : HTMLElement;

    constructor() {
        this.selectGallery = <HTMLInputElement> document.getElementById('SelectGallery');
        this.dragZone = <HTMLElement> document.getElementById('dragarea');
        this.progressArea = <HTMLElement> document.getElementById('uploadProgressArea');
        this.errorZone = <HTMLElement> document.getElementById('uploadErrorArea');
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
handle dropped files
----------------------------------------------------------------*/

class DroppedFilesTask {
    private readonly selectGallery: HTMLInputElement;
    private droppedFiles: DroppedFiles;
    private requestDbImageIdTask: RequestDbImageIdTask;

    constructor(
        selectGallery: HTMLInputElement,
        droppedFiles: DroppedFiles,
        requestDbImageIdTask: RequestDbImageIdTask,
    ) {

        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
        this.requestDbImageIdTask = requestDbImageIdTask;

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
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onNewFile: Rejected");
        }
        else {

            const files: FileList = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }

            console.log(">onNewFile: " + files.length);
            this.droppedFiles.addFiles (files, gallery_id);

            // Start ajax request of DB image reservation
            this.requestDbImageIdTask.ajaxRequest ();
        }
    }

}


//=================================================================================
// Handle status bar for one actual uploading image

class createStatusBar {

    private static imgCount:number = 0;

    private htmlStatusbar: HTMLElement;
    private htmlFilename: HTMLElement;
    private htmlSize: HTMLElement;
    private htmlProgressBarOuter: HTMLElement;
    private htmlProgressBarInner: HTMLElement;
    private htmlAbort: HTMLElement;


    constructor(
        progressArea: HTMLElement,
        file:File,
    ){
        createStatusBar.imgCount++;
        let even_odd = (createStatusBar.imgCount % 2 == 0) ? "odd" : "even";

        // Add all elements. single line in *.css
        //this.htmlStatusbar = $("<div class='statusbar " + row + "'></div>");
        this.htmlStatusbar = document.createElement('div');
        this.htmlStatusbar.classList.add('statusbar');

        //this.htmlFilename = $("<div class='filename'></div>").appendTo(this.statusbar);
        this.htmlFilename = document.createElement('div');
        this.htmlFilename.classList.add('filename');
        this.htmlStatusbar.appendChild (this.htmlFilename);

        //this.htmlSize = $("<div class='filesize'></div>").appendTo(this.statusbar);
        this.htmlSize = document.createElement('div');
        this.htmlSize.classList.add('filesize');
        this.htmlStatusbar.appendChild (this.htmlSize);

        //this.htmlProgressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
        this.htmlProgressBarOuter = document.createElement('div');
        this.htmlProgressBarOuter.classList.add('progressBar');
        this.htmlProgressBarInner = document.createElement('div');
        this.htmlProgressBarOuter.appendChild(this.htmlProgressBarInner);
        this.htmlStatusbar.appendChild (this.htmlProgressBarOuter);

        //this.htmlAbort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
        this.htmlAbort = document.createElement('div');
        this.htmlAbort.classList.add('abort');
        //this.htmlAbort.appendChild(document.createTextNode('Abort'));
        this.htmlAbort.innerHTML = 'Abort';
        this.htmlStatusbar.appendChild (this.htmlAbort);

        this.setFileNameAndSize (file);

        //// set as first element: Latest file on top to compare if already shown in image area
        //progressArea.prepend(this.statusbar);
        // set as last element: Latest file on top to compare if already shown in image area
        progressArea.appendChild(this.htmlStatusbar);

    }

    //--- file size in KB/MB .... -------------------

    public setFileNameAndSize (file:File) {
        let sizeStr = "";
        let sizeKB = file.size / 1024;

        if (sizeKB > 1024) {
            var sizeMB = sizeKB / 1024;
            sizeStr = sizeMB.toFixed(2) + " MB";
        }
        else {
            sizeStr = sizeKB.toFixed(2) + " KB";
        }

        this.htmlFilename.innerHTML = file.name;
        this.htmlSize.innerHTML = sizeStr;
    };

    //========================================
    // Change progress value
    public setProgress (percentage: number) {
        let width = parseInt(this.htmlProgressBarOuter.style.width);

        let progressBarWidth = percentage * width  / 100;

        // ToDo: animate change of width
        // transition:300ms linear; class progressBar ? put to inner ?
        //this.htmlprogressBar.find('div').animate({width: progressBarWidth}, 10).html(percentage + "%");
        this.htmlProgressBarInner.style.width = progressBarWidth.toString();

        // do not abort when nearly finished
        if (percentage >= 99.999) {
            this.htmlAbort.style.display = 'none';
        }
    };

    //========================================
    // Handle abort click
    // ToDo: Test for second ajax still working ?

    // ToDo: !!!
    public setAbort (jqxhr) {
        let htmlStatusbar = this.htmlStatusbar;
        this.htmlAbort.addEventListener('click', function () {
            jqxhr.abort();

            // toDo: file name strikethrough
            //htmlStatusbar.style.display = 'none';
            htmlStatusbar.style.textDecoration = 'line-through';
        });
    };

    //========================================
    // Remove item after successful file upload
    public remove () {

        this.htmlStatusbar.style.display = 'none';
    };
}


/*----------------------------------------------------------------
  joomla ajax may return pretext to data
----------------------------------------------------------------*/

// extract json data which may be preceded with unwanted informtion
function separateDataAndNoise(response:string):[string,string] {

    let data:string = response;
    let error:string = "";

    const StartIdx = response.indexOf('{"'); // ToDo: {"Success
    if (StartIdx > -1) {

        error = response.substring(0, StartIdx - 1);
        data = response.substring(StartIdx);

    }

    return [ data, error ];
}

/*----------------------------------------------------------------
  joomla ajax error returns complete page
----------------------------------------------------------------*/

// extract html part of error page
function separateErrorAndNoise(errMessage:string):[string,string] {

    let errorHtml:string = errMessage;
    let noise:string = "";

    const StartIdx = errMessage.indexOf('<h1>An error has occurred.</h1>');
    if (StartIdx > -1) {

        //
        noise = errMessage.substring(0, StartIdx - 1);

        // End followed by <a href="/Joomla4x/administrator" class="btn btn-secondary">
        const EndIdx = errMessage.indexOf('<p>');
        errorHtml = errMessage.substring(StartIdx, EndIdx);
    }

    return [ errorHtml, noise ];
}

// jData = jQuery.parseJSON(jsonText);


/*----------------------------------------------------------------
   Ajax request DB items for each file in list
   First step in transfer of file
----------------------------------------------------------------*/

// {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....

interface IAjaxResponse {
    success: boolean;
    message: string;
    messages: string[] | null;
    data: string | null;
}
// {\"file\":\"dstFile_DSC_5501.JPG\",\"imageId\":2,\"dstFile\":\"http:\\/\\/127.0.0.1\\/Joomla4x\\/images\\/rsgallery2\\/dstFile_DSC_5501.JPG\",\"originalFileName\":\"DSC_5501.JPG\"}}"

interface IResponseRequest {
    uploadFileName: string;
    imageId: number;
    baseName: string;
    dstFileName: string;
}

interface IResponseTransfer {
    file: string;
    imageId: string; //number
    fileUrl: string;
    safeFileName: string;
}

class RequestDbImageIdTask {

    private dragZone: HTMLElement;
    private progressArea: HTMLElement;
    private errorZone: HTMLElement;

    private droppedFiles: DroppedFiles;
    private transferFiles: TransferFiles;
    private transferImagesTask: TransferImagesTask;

    private request: Promise<IDroppedFile>;
    private isBusy: boolean = false;

    constructor(
        dragZone: HTMLElement,
        progressArea: HTMLElement,
        errorZone: HTMLElement,

        droppedFiles: DroppedFiles,
        transferFiles: TransferFiles,
        transferImagesTask: TransferImagesTask,
        ) {
            this.dragZone = dragZone;
            this.droppedFiles = droppedFiles;
            this.errorZone = errorZone;

            this.transferFiles = transferFiles;
            this.progressArea = progressArea;
            this.transferImagesTask = transferImagesTask;
    }


    // https://taylor.callsen.me/ajax-multi-file-uploader-with-native-js-and-promises/
    // https://makandracards.com/makandra/39225-manually-uploading-files-via-ajax
    // https://www.w3schools.com/js/js_ajax_http.asp

    // http://html5doctor.com/drag-and-drop-to-server/
    // -> resize, exif
    // http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html

    /**/
    private async callAjaxRequest(nextFile: IDroppedFile): Promise<any> {
        return new Promise<any>(
            function (resolve, reject) {

                const request = new XMLHttpRequest();
                request.onload = function () {
                    if (this.status === 200) {
                        // attention joomla may send error data on this channel
                        resolve(this.response);
                    } else {
                         let msg = 'Error \'on load\' for ' + nextFile.file.name + ' in DbRequest:\n*'
                             + 'State: ' + this.status + ' ' + this.statusText + '\n';
                             + 'responseType: ' + this.responseType + '\n';
                         alert (msg);

                        // reject(new Error(this.response));
                        reject(new Error(this.responseText));
                    }
                };
                request.onerror = function () {
                    let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                    msg += 'responseType: ' + this.responseType + '\n';
                    //msg += 'responseText: ' + this.responseText + '\n';
                    alert (msg);

                    // reject(new Error(this.response));
                    reject(new Error(this.responseText));
                };

                let data = new FormData();
                data.append('upload_file_name', nextFile.file.name);
                data.append('upload_size', nextFile.file.size.toString());
                data.append('upload_type', nextFile.file.type);
                data.append(Token, '1');
                data.append('gallery_id', nextFile.galleryId);

                const urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxReserveDbImageId';

                request.open('POST', urlRequestDbImageId, true);
                request.onloadstart = function (e) {
                    console.log("      > callAjaxRequest: " + nextFile.file.name);
                }
                request.onloadend = function (e) {
                    console.log("      < callAjaxRequest: ");
                }

                request.send(data);
            }
            );

        /**
         console.log("      > callAjaxRequest: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/

    /**/
    public async ajaxRequest() {

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

            nextFile.statusBar = new createStatusBar(this.progressArea, nextFile.file);
//            statusBar.setFileNameSize(files[idx].name, files[idx].size);


            /* let request = */
            //await this.callAjaxRequest(nextFile)
            this.callAjaxRequest(nextFile)
                .then((response) => {
                    // attention joomla may send error data on this channel
                    console.log("   <Request OK: " + nextFile.file.name);
                    console.log("      response: " + JSON.stringify(response));

                    const [data, noise] = separateDataAndNoise (response);

                    console.log("      response data: " + JSON.stringify(data));
                    console.log("      response error: " + JSON.stringify(noise));

                    let AjaxResponse:IAjaxResponse = JSON.parse(data);
                    //console.log("      response data: " + JSON.stringify(data));

                    if (AjaxResponse.success) {
                        console.log("      success data: " + AjaxResponse.data);

                        let dbData = <IResponseRequest><unknown>AjaxResponse.data;

                        let fileName = dbData.uploadFileName;
                        let dstFileName = dbData.dstFileName;
                        let imageId = dbData.imageId;
                        this.transferFiles.add(nextFile, imageId.toString(), fileName, dstFileName);

                        // Start ajax transfer of files
                        this.transferImagesTask.ajaxTransfer();
                    } else {
                    // error found ToDo: fill out

                    }

                })
                .catch((errText:Error) => {
                     console.log("    !!! Error request: " + nextFile.file.name);
  //                  alert ('errText' + errText);

                     // remove unnecessary HTML
                    const [ errorPart, noise ] = separateErrorAndNoise (errText.message);

                    console.log("      response noise: " + JSON.stringify(noise));
                    console.log("      response error: " + JSON.stringify(errorPart));

                    let errorHtml = document.createElement('div');
                    errorHtml.classList.add('errorContent');

                    if (errorPart.length > 0)
                    {
                        errorHtml.innerHTML = errorPart;

                        let errorTitle = document.createElement('h1');
                        errorTitle.innerHTML = '<strong>' + nextFile.file.name + '</strong>';
                        errorHtml.prepend (errorTitle);

                    } else {
                        errorHtml.appendChild(document.createTextNode(errText.message));
                    }

                    this.errorZone.appendChild (errorHtml);

                     console.log('!!! errText' + errText);
                })
            ;
            /**/

            console.log("    *this.droppedFiles.length: " + this.droppedFiles.length);
        }

        this.isBusy = false;
        console.log("    <this.droppedFiles.length: " + this.droppedFiles.length);
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

class TransferImagesTask {

    private dragZone: HTMLElement;
    private progressArea: HTMLElement;
    private errorZone: HTMLElement;

    private transferFiles: TransferFiles;

    private request: Promise<ITransferFile>;
    private isBusyCount: number = 0;
    private readonly BusyCountLimit: number = 5;

    constructor(
        dragZone: HTMLElement,
        progressArea: HTMLElement,
        errorZone: HTMLElement,

        transferFiles: TransferFiles,
    ) {
        this.dragZone = dragZone;
        this.errorZone = errorZone;

        this.transferFiles = transferFiles;
        this.progressArea = progressArea;
    }

    private async callAjaxTransfer(nextFile: ITransferFile) {

        console.log("      in callAjaxTransfer: " + nextFile.file);
        console.log("      > callAjaxTransfer: " + nextFile.file);

        return new Promise<any>(
            function (resolve, reject) {

                const request = new XMLHttpRequest();
                request.onload = function () {
                    if (this.status === 200) {
                        // attention joomla may send error data on this channel
                        resolve(this.response);
                    } else {

                        let msg = 'Error over \'on load\' for ' + nextFile.file.name + ' in Transfer:\n*'
                            + 'State: ' + this.status + ' ' + this.statusText + '\n';
                        alert (msg);

                        reject(new Error(this.responseText));
                    }
                };
                request.onerror = function () {
                    let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                    msg += 'responseType: ' + this.responseType + '\n';
                    msg += 'responseText: ' + this.responseText + '\n';
//                    alert (msg);

//                    reject(new Error('XMLHttpRequest Error: ' + this.statusText));
                    reject(new Error(this.responseText));
                };

                let data = new FormData();
                data.append('upload_file', nextFile.file);
                data.append(Token, '1');
                data.append('gallery_id', nextFile.galleryId);
                data.append('imageId', nextFile.imageId);
                data.append('fileName', nextFile.fileName);
                console.log ('   >fileName: ' + nextFile.fileName);
                data.append('dstFileName', nextFile.dstFileName);
                console.log ('   >dstFileName: ' + nextFile.dstFileName);


                /**

                 get:
                 request.onprogress = function (e) {
                    if (e.lengthComputable) {
                        console.log(e.loaded+  " / " + e.total)
                    }
                }

                 post: upload
                 xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        console.log("add upload event-listener" + evt.loaded + "/" + evt.total);
                    }
                }, false);

                 xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        var complete = (event.loaded / event.total * 100 | 0);
                        progress.value = progress.innerHTML = complete;
                    }
                };

                 post: download
                 xhr.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with download progress
                        console.log(percentComplete);
                    }
                }, false);


                 /**
                 xhr.onloadstart = function (e) {
                    console.log("start")
                }
                 xhr.onloadend = function (e) {
                    console.log("end")
                }
                 I would advise the use of a
                 HTML <progress> element to
                 display current progress.

                 upload with resizing
                 http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html
                 /**/

                const urlTransferImages = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';

                request.open('POST', urlTransferImages, true);

                request.onloadstart = function (e) {
                    console.log("      > callAjaxTransfer: " + nextFile.file.name);
                }
                request.onloadend = function (e) {
                    console.log("      < callAjaxTransfer: ");
                }

                request.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        var progress = (event.loaded / event.total * 100 | 0);
                        console.log("         progress: " + progress);
                    }
                };

                request.send(data);
            }
        );

        /**
         console.log("      > callAjaxTransfer: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxTransfer: " + nextFile.file.name)
        }, 333);
         /**/
    }

    /**/
    public async ajaxTransfer() {

        console.log("    >this.transferFiles.length: " + this.transferFiles.length);
        // check for busy
        while (this.isBusyCount < this.BusyCountLimit
            && this.transferFiles.length > 0)
        {
            this.isBusyCount++;

            let nextFile = this.transferFiles.shift();
            console.log("   @Transfer File: " + nextFile.file);

            //
            this.callAjaxTransfer(nextFile)
                .then((response) => {
                    // attention joomla may send error data on this channel
                    console.log("   <Transfer OK: " + nextFile.file);
                    console.log("       response: " + JSON.stringify(response));

                    const [data, error]  = separateDataAndNoise (response);

                    console.log("      response data: " + JSON.stringify(data));
                    console.log("      response error: " + JSON.stringify(error));

                    let AjaxResponse:IAjaxResponse = JSON.parse(data);
                    //console.log("      response data: " + JSON.stringify(data));

                    if (AjaxResponse.success) {
                        console.log("      success data: " + AjaxResponse.data);

                        let transferData = <IResponseTransfer><unknown>AjaxResponse.data;

                        console.log("      response data.file: " + transferData.file);
                        console.log("      response data.imageId: " + transferData.imageId);
                        console.log("      response data.fileUrl: " + transferData.fileUrl);
                        console.log("      response data.safeFileName: " + transferData.safeFileName);

                    } else {
                        // error found ToDo: fill out

                    }
                })
                .catch((errText:Error) => {
                    console.log("    !!! Error transfer: " + nextFile.file);
                    //                  alert ('errText' + errText);

                    // remove unnecessary HTML
                    const [ errorPart, noise ] = separateErrorAndNoise (errText.message);

                    console.log("      transfer noise: " + JSON.stringify(noise));
                    console.log("      transfer error: " + JSON.stringify(errorPart));

                    let errorHtml = document.createElement('div');
                    errorHtml.classList.add('errorContent');

                    if (errorPart.length > 0)
                    {
                        errorHtml.innerHTML = errorPart;
                    } else {
                        errorHtml.appendChild(document.createTextNode(errText));
                    }

                    this.errorZone.appendChild (errorHtml);

                    console.log('!!! errText' + errText);
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

    // (3) ajax request: Transfer file to server
    const transferImagesTask = new TransferImagesTask (elements.dragZone, elements.progressArea, elements.errorZone,
        transferFiles);

    // (2) ajax request: database image item
    const requestDbImageIdTask = new RequestDbImageIdTask (elements.dragZone, elements.progressArea, elements.errorZone,
        droppedFiles, transferFiles, transferImagesTask);

    // (1) collect dropped files, start request DB image ID
    let droppedFilesTask = new DroppedFilesTask (elements.selectGallery, droppedFiles, requestDbImageIdTask);





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

        droppedFilesTask.onNewFile (event);
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

