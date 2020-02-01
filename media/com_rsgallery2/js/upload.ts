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

// Joomla form token
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
    private htmlIconUpload: HTMLElement;
    private htmlIconOk: HTMLElement;

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
        this.htmlAbort = document.createElement('span');
        this.htmlAbort.classList.add('abort');
        //this.htmlAbort.appendChild(document.createTextNode('Abort'));
        this.htmlAbort.innerHTML = 'Abort';
        this.htmlStatusbar.appendChild (this.htmlAbort);

        this.htmlIconUpload = document.createElement('span');
        this.htmlIconUpload.classList.add('success');
        //this.htmlIconUpload.appendChild(document.createTextNode('Abort'));
        this.htmlIconUpload.innerHTML = '<i class="icon-upload"></i>\n';
        this.htmlIconUpload.style.display = "none";
        this.htmlStatusbar.appendChild (this.htmlIconUpload);

        this.htmlIconOk = document.createElement('span');
        this.htmlIconOk.classList.add('class');
        //this.htmlIconOk.appendChild(document.createTextNode('Abort'));
        this.htmlIconUpload.style.display = "none";
        this.htmlIconUpload.innerHTML = '<i class="icon-ok"></i>\n';
        this.htmlStatusbar.appendChild (this.htmlIconOk);

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
            let sizeMB = sizeKB / 1024;
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
        let width = parseInt(this.htmlProgressBarOuter.style.width) || 0;

        let progressBarWidth = percentage * width  / 100;

        // ToDo: animate change of width
        // transition:300ms linear; class progressBar ? put to inner ?
        //this.htmlprogressBar.find('div').animate({width: progressBarWidth}, 10).html(percentage + "%");
        this.htmlProgressBarInner.style.width = progressBarWidth.toString();

        // do not abort when nearly finished
        if (percentage >= 99.999) {
            this.htmlAbort.style.display = 'none';
  //          this.htmlIconUpload.style.display = 'block';

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
function separateErrorAndAlerts(errMessage:string):[string,string] {

    let errorHtml:string = errMessage; // Plan b: show all if nothing detected
    let alertHtml:string = "";

    const StartErrorIdx = errMessage.indexOf('<section id="content" class="content">');
    const EndErrorIdx = errMessage.indexOf('</section>');

    const StartAlertIdx = errMessage.indexOf('<div class="notify-alerts">', EndErrorIdx);
    // behind alerts are scripts
    const StartScriptIdx = errMessage.indexOf('<script src=');
    // three divs back
    let EndAlertIdx_01 = errMessage.lastIndexOf('</div>', StartScriptIdx);
//    let EndAlertIdx_02 = errMessage.lastIndexOf('</div>', EndAlertIdx_01 - 6);
    let EndAlertIdx_02 = EndAlertIdx_01;
    let EndAlertId = errMessage.lastIndexOf('</div>', EndAlertIdx_02 - 6);

    if (StartErrorIdx > -1 && EndErrorIdx > -1) {

        if (StartErrorIdx < EndErrorIdx) {
            errorHtml = errMessage.substring(StartErrorIdx, EndErrorIdx + 10);
        }
    }

    if (StartAlertIdx > -1 && EndAlertId > -1) {

        if (StartAlertIdx < EndAlertId) {
            alertHtml = errMessage.substring(StartAlertIdx, EndAlertId);
        }
    }

    return [ errorHtml, alertHtml ];
}

// jData = jQuery.parseJSON(jsonText);


/*----------------------------------------------------------------
   Ajax request DB items for each file in list
   First step in transfer of file
----------------------------------------------------------------*/

// {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
// {\"success\":false,\"message\":\"COM_COMPONENT_MY_TASK_ERROR\",\"messages\":{\"info\":[\"This part has error\"],\"notice\":[\"Enqueued notice 1\",\"Enqueued notice 2\"],\"warning\":[\"Here was a small warning 1\",\"Here was a small warning 2\"],\"error\":[\"Here was a small error 1\",\"Here was a small error 2\"]},\"data\":\"result text\"}"

enum JoomlaMessages {
    message = "info",
    notice = "notice",
    warning = "warning",
    error = "error",
}

type JoomlaMessage = {
    msgType:JoomlaMessages,
    messages: string [],
}

interface IAjaxResponse {
    success: boolean;
    message: string;
//    messages: Record <string, string[]> [] | null;
//    messages: Record <JoomlaMessages, string[]> [] | null;
    messages: JoomlaMessage [] | null;
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
                         //alert (msg);
                         console.log (msg);

                        // reject(new Error(this.response));
                        reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                    }
                };
                request.onerror = function () {
                    let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                    msg += 'responseType: ' + this.responseType + '\n';
                    //msg += 'responseText: ' + this.responseText + '\n';
                    //alert (msg);
                    console.log (msg);

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
                    console.log("      response error/noise: " + JSON.stringify(noise));

                    let AjaxResponse:IAjaxResponse = JSON.parse(data);
                    //console.log("      response data: " + JSON.stringify(data));

                    if (AjaxResponse.success) {
                        console.log("      success data: " + AjaxResponse.data);

                        let dbData = <IResponseRequest><unknown>AjaxResponse.data;

                        let fileName = dbData.uploadFileName;
                        let dstFileName = dbData.dstFileName;
                        let imageId = dbData.imageId;
                        this.transferFiles.add(nextFile, imageId.toString(), fileName, dstFileName);

                        // ==> Start ajax transfer of files
                        this.transferImagesTask.ajaxTransfer();
                    } else {
                        console.log("      failed data: " + AjaxResponse.data);
                    }

                    if (AjaxResponse.message || AjaxResponse.messages) {
                        const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile);
                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        }
                    }

                })
                .catch((errText: Error) => {
                    console.log("    !!! Error request: " + nextFile.file.name);
                    //                  alert ('errText' + errText);
                    //console.log("        error: " + JSON.stringify(errText));
                    console.log("        error: " + errText);
                    console.log("        error.name: " + errText.name);
                    console.log("        error.message: " + errText.message);

                    const errorHtml = ajaxCatchedMessages2Html(errText, nextFile);

                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }

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
     let data = new FormData();
     // data.append('upload_file', files[idx]);
     data.append('upload_file', files[idx].name);
     data.append('imagesDroppedListIdx', imagesDroppedListIdx);

     data.append(Token, '1');
     data.append('gallery_id', gallery_id);
     //data.append('idx', idx);

     // Set progress bar
     let statusBar = new createStatusBar(progressArea);
     statusBar.setFileNameSize(files[idx].name, files[idx].size);

     /**/

}

/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/

function ajaxCatchedMessages2Html (errText: Error, nextFile: IDroppedFile):  HTMLElement | null {

    let errorHtml:HTMLElement = null;

    // remove unnecessary HTML
    const [errorPart, alertPart] = separateErrorAndAlerts(errText.message);

    console.log("      response error: " + JSON.stringify(errorPart));
    console.log("      response noise: " + JSON.stringify(alertPart));

    if (errorPart || alertPart) {
        //--- bootstrap card as title ---------------------------

        const errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');

        const errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        const errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(nextFile.file.name));
        errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
        errorCardHtml.appendChild(errorCardHeaderHtml);

        //--- bootstrap card body ---------------------------

        if (errorPart.length > 0) {
            const errorCardBodyHtml = document.createElement('div');
            errorCardBodyHtml.classList.add('card-body');
            errorCardHtml.appendChild(errorCardBodyHtml);
            const errorCardErrorPart = document.createElement('div');
            errorCardErrorPart.innerHTML = errorPart;
            errorCardBodyHtml.appendChild(errorCardErrorPart);
            errorCardHtml.appendChild(errorCardBodyHtml);
        }


        if (alertPart.length > 0) {
            const errorCardBodyHtml = document.createElement('div');
            errorCardBodyHtml.classList.add('card-body');
            errorCardHtml.appendChild(errorCardBodyHtml);
            const errorCardErrorPart = document.createElement('div');
            errorCardErrorPart.innerHTML = alertPart;
            errorCardBodyHtml.appendChild(errorCardErrorPart);
            errorCardHtml.appendChild(errorCardBodyHtml);
        }

        errorHtml = errorCardHtml;
    }

    return errorHtml;
}

/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/

function ajaxMessages2Html (AjaxResponse: IAjaxResponse, nextFile: IDroppedFile): HTMLElement | null
{
    let errorHtml:HTMLElement = null;

    if (AjaxResponse.message || AjaxResponse.messages) {

        //--- bootstrap card as title ---------------------------

        const errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');

        const errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        const errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(nextFile.file.name));
        errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
        errorCardHtml.appendChild(errorCardHeaderHtml);

        //--- bootstrap card body ---------------------------

        const errorCardBodyHtml = document.createElement('div');
        errorCardBodyHtml.classList.add('card-body');
        errorCardHtml.appendChild(errorCardBodyHtml);

        if (AjaxResponse.message) {

            const errorCardBodyTitle = document.createElement('h4');
            errorCardBodyTitle.classList.add('card-title');
            errorCardBodyTitle.appendChild(document.createTextNode(AjaxResponse.message));
            errorCardBodyHtml.appendChild(errorCardBodyTitle);
            console.log('!!! message:' + AjaxResponse.message);
        }

        if (AjaxResponse.messages) {
            // JoomlaMessage {string, string []}
            for (const jMsgType of Object.keys(AjaxResponse.messages)) {
                // enum JoomlaMessages
                const jMessages: string[] = AjaxResponse.messages [jMsgType];

                let alertType: string = 'alert-';
                switch (jMsgType) {
                    case "info": {
                        alertType += 'info';
                        break;
                    }
                    case "notice": {
                        alertType += 'primary';
                        break;
                    }
                    case "warning": {
                        alertType += 'warning';
                        break;
                    }
                    case "error": {
                        alertType += 'danger';
                        break;
                    }
                    default: {
                        alertType += 'secondary';
                        break;
                    }
                }

                //const jMsg = AjaxResponse.messages [jMsgType];
                jMessages.map(msg => {
                    const htmlText = '[' + jMsgType + '] "' + msg + '"';
                    const msgHtml = document.createElement('div');
                    //errorHtml.classList.add('errorContent');
                    msgHtml.classList.add('alert', alertType, 'errorContent');
                    msgHtml.appendChild(document.createTextNode(htmlText));

                    //errorHtml.appendChild(msgHtml);
                    errorCardBodyHtml.appendChild(msgHtml);
                })
            }
        }

        errorHtml = errorCardHtml;
    }

    return errorHtml;
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
                        //alert (msg);
                        console.log (msg);

                        reject(new Error(this.responseText));  // ToDo: check if there is mor in this
                    }
                };
                request.onerror = function () {
                    let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                    msg += 'responseType: ' + this.responseType + '\n';
                    msg += 'responseText: ' + this.responseText + '\n';
                    //alert (msg);
                    console.log (msg);

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
                        let complete = (event.loaded / event.total * 100 | 0);
                        progress.value = progress.innerHTML = complete;
                    }
                };

                 post: download
                 xhr.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        let percentComplete = evt.loaded / evt.total;
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
                        const progress = (event.loaded / event.total * 100 | 0);

//                        nextFile.statusBar.setProgress(progress);
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
                        console.log("      failed data: " + AjaxResponse.data);
                    }

                    if (AjaxResponse.message || AjaxResponse.messages) {
                        const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile);
                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        }
                    }

                })
                .catch((errText:Error) => {
                    console.log("    !!! Error transfer: " + nextFile.file);
                    //                  alert ('errText' + errText);
                    //console.log("        error: " + JSON.stringify(errText));
                    console.log("        error: " + errText);
                    console.log("        error.name: " + errText.name);
                    console.log("        error.message: " + errText.message);

                    const errorHtml = ajaxCatchedMessages2Html(errText, nextFile);

                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }

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
        // const eventFiles: FileList | undefined = event.target.files;
        //const files = eventFiles || event.dataTransfer.files;
        const files = event.target.files || event.dataTransfer.files;
        /**/

        if (!files.length) {
            return;
        }
//        Array.from(files).foreach ((File) => {console.log("filename: " + File.name);});
        for (let i = 0; i < files.length; i++) {
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

        let form = document.getElementById('adminForm');

        let zip_path = form.zip_file.value;
        let gallery_id = jQuery('#SelectGalleries_01').val();
        let bOneGalleryName4All = jQuery('input[name="all_img_in_step1_01"]').val();

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

        let form = document.getElementById('adminForm');

        //let GalleryId = jQuery('#SelectGalleries_03').chosen().val();
        let gallery_id = jQuery('#SelectGalleries_02').val();
        let ftp_path = form.ftp_path.value;
        let bOneGalleryName4All = jQuery('input[name="all_img_in_step1_02"]').val();

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

