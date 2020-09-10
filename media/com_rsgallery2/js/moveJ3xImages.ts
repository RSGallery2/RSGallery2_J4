/**
 * @package     RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage  com_rsgallery2
 * @copyright   (C) 2016-2019 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       5.0.0.4
 */

/*----------------------------------------------------------------
   Joomla interface created by ericfernance on 27/11/2015
   (with rsg2 additions found in internet
----------------------------------------------------------------*/

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
const joomla: Joomla = window.Joomla || {};

// Joomla form token
var Token: string;

/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/

class Queue<T> {
    private _store: T[] = [];

    push(val: T) {
        this._store.push(val);
    }

    shift(): T | undefined {
        return this._store.shift();
    }

    get length(): number {
        return this._store.length;
    }

    isEmpty(): boolean {
        return this._store.length == 0;
    }

    isPopulated(): boolean {
        return this._store.length > 0;
    }
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

/*----------------------------------------------------------------
   List of image file queue
----------------------------------------------------------------*/

interface Ij3xFiles {
    name: string;
    id: string;
    galleryId: string;
}

interface Ij3xFileDb {
    name: string;
    id: string;
    // isHasMoved:boolean;
    // isWasMoved:boolean;
    // isNotFound:boolean;
}

class J3xImages2Move extends Queue<Ij3xFiles> {

    addImages(files: Ij3xFileDb[], galleryId: string) {

        for (let idx = 0; idx < files.length; idx++) {

            console.log('   +J3x Image: ' + files[idx].name);

            //--- ToDo: Check 4 allowed image type ---------------------------------

            // file.type ...

            //--- Add file with data ---------------------------------

            const next: Ij3xFiles = {
                name: files[idx].name,
                id: files[idx].id,
                galleryId: galleryId
            };

            this.push(next);
        }

    }
}

/*----------------------------------------------------------------
   List of activated galleries
----------------------------------------------------------------*/

interface Ij3xGalleries {
    galleryId: string;
    name: string;

    statusBar: createStatusBar | null;
    errorZone: HTMLElement | null;
}

class J3xGalleries extends Queue<Ij3xGalleries> {

    addGalleries(galleries: Ij3xGalleries[]) {

        for (let idx = 0; idx < galleries.length; idx++) {
            const gallery: Ij3xGalleries = galleries[idx];

            console.log('   +Gallery: ' + galleries[idx].name);

            //--- ToDo: Check 4 allowed image type ---------------------------------

            // file.type ...

            //--- Add file with data ---------------------------------

            const next: Ij3xGalleries = {
                galleryId: gallery.galleryId,
                name: gallery.name,

                statusBar: gallery.statusBar,
                errorZone: gallery.errorZone
            };

            this.push(next);
        }

    }
}

/*----------------------------------------------------------------

----------------------------------------------------------------*/

// Required gallery ID
function markImages_nGalleryTimes(maxGalleries: number) {

    // needs ref gallery refstate=active/deactive)
    // j3x_rows: HTMLTableRowElement []; // = [];

    let j3x_rows: HTMLElement [];
    let checkbox: HTMLInputElement;
    let galleryId: string;
    let doCheck: boolean = true;

    let galleries: string[] = [];

    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = <HTMLElement []>Array.from(document.getElementsByName("j3x_img_row[]"));

    //j3x_images
    j3x_rows.forEach((j3x_row) => {
        let isMerged = j3x_row.getAttribute("isMerged");

        // count not merged galleries
        if (!isMerged) {

            galleryId = j3x_row.getAttribute("galleryId");
            checkbox = <HTMLInputElement>j3x_row.querySelector('input[type="checkbox"]');

            // Assign if necessary
            if (checkbox.checked != doCheck) {

                // within range ? add to enabled list
                if (galleries.length < maxGalleries) {
                    if (!galleries.includes(galleryId)) {
                        galleries.push(galleryId);
                    }
                }

                // Mark if gallery is in range
                if (galleries.includes(galleryId)) {
                    checkbox.checked = doCheck;
                }
            }
        }
    });

}

/*----------------------------------------------------------------
   Pointer to used html elements on form
----------------------------------------------------------------*/


class FormElements {
    selectGallery: HTMLSelectElement;

    btnMoveByGallery: HTMLButtonElement;
    btnMoveByCheckedGalleries: HTMLButtonElement;
    btnMoveAllJ3xImjages: HTMLButtonElement;

    btnSelectNextGallery: HTMLButtonElement;
    btnSelectNextGalleries10: HTMLButtonElement;
    btnSelectNextGalleries100: HTMLButtonElement;

    //: HTMLButtonElement;
    moveImageArea: HTMLElement;
    //: HTMLElement;


    // dragZone: HTMLElement;
    // imagesAreaList: HTMLElement;
    // progressArea: HTMLElement;
    // errorZone: HTMLElement;
    //
    // buttonManualFiles : HTMLButtonElement;
    // buttonZipFile : HTMLButtonElement;
    // buttonFolderImport : HTMLButtonElement;
    //
    // inputFtpFolder : HTMLInputElement;

    // : HTMLElement;
    // select eElements on form
    constructor() {
        this.selectGallery = <HTMLSelectElement>document.getElementById('SelectGallery');
        this.moveImageArea = <HTMLElement>document.getElementById('moveImageArea');

        //--- Move buttons ----------------------------------------------------

        this.btnMoveByGallery = <HTMLButtonElement>document.getElementById('moveByGallery');
        this.btnMoveByCheckedGalleries = <HTMLButtonElement>document.getElementById('moveByCheckedGalleries');
        this.btnMoveAllJ3xImjages = <HTMLButtonElement>document.getElementById('moveAllJ3xImjages');

        //--- Select next buttons ----------------------------------------------------

        this.btnSelectNextGallery = <HTMLButtonElement>document.getElementById('selectNextGallery');
        this.btnSelectNextGalleries10 = <HTMLButtonElement>document.getElementById('selectNextGalleries10');
        this.btnSelectNextGalleries100 = <HTMLButtonElement>document.getElementById('selectNextGalleries100');

        this.btnSelectNextGallery.onclick = () => markImages_nGalleryTimes(1);
        this.btnSelectNextGalleries10.onclick = () => markImages_nGalleryTimes(2);
        this.btnSelectNextGalleries100.onclick = () => markImages_nGalleryTimes(100);

        // this.dragZone = <HTMLElement> document.getElementById('dragarea');
        // this.imagesAreaList = <HTMLElement> document.getElementById('imagesAreaList');
        // this.progressArea = <HTMLElement> document.getElementById('uploadProgressArea');
        // this.errorZone = <HTMLElement> document.getElementById('uploadErrorArea');
        //
        // this.buttonManualFiles = <HTMLButtonElement> document.querySelector('#select-file-button-drop');
        // this.buttonZipFile = <HTMLButtonElement> document.querySelector('#select-zip-file-button-drop');
        // this.buttonFolderImport = <HTMLButtonElement> document.querySelector('#ftp-upload-folder-button-drop');
        //
        // this.inputFtpFolder = <HTMLInputElement> document.querySelector('#ftp_upload_directory');
    }

} // Form Elements

/*----------------------------------------------------------------
handle gallery list
----------------------------------------------------------------*/

class GalleriesListTask {
    private readonly selectGallery: HTMLSelectElement;
    private j3xGalleries: J3xGalleries;
    private requestImageIdsTask: RequestImageIdsTask;
    // private zipFiles: ZipFiles;
    // private serverFolder:IRequestFolderImport;
    // private serverFiles: ServerFiles;
    // private requestZipUploadTask: RequestZipUploadTask;
    // private requestFilesInFolderTask: RequestFilesInFolderTask;
    // private requestTransferFolderFilesTask: RequestTransferFolderFilesTask;
    //
    // private buttonManualFiles : HTMLButtonElement;
    // private buttonZipFile : HTMLButtonElement;
    // private buttonFolderImport : HTMLButtonElement;
    // private inputFtpFolder : HTMLInputElement;


    constructor(
        //*        selectGallery: HTMLSelectElement,
        formElements: FormElements,
        j3xGalleries: J3xGalleries,
        requestImageIdsTask: RequestImageIdsTask,
        // zipFiles: ZipFiles,
        // requestZipUploadTask: RequestZipUploadTask,
        // serverFolder:IRequestFolderImport,
        // serverFiles: ServerFiles,
        // requestFilesInFolderTask: RequestFilesInFolderTask,
        // RequestTransferFolderFilesTask: RequestTransferFolderFilesTask,
    ) {
        this.selectGallery = formElements.selectGallery;
        // this.buttonManualFiles = formElements.buttonManualFiles;
        // this.buttonZipFile = formElements.buttonZipFile;
        // this.buttonFolderImport = formElements.buttonFolderImport;
        // this.inputFtpFolder = formElements.inputFtpFolder;
        //
        this.j3xGalleries = j3xGalleries;
        this.requestImageIdsTask = requestImageIdsTask;
        // this.zipFiles = zipFiles;
        // this.requestZipUploadTask = requestZipUploadTask;

//        formElements.btnMoveByGallery.onclick = onMoveByGallery();

        formElements.btnMoveByGallery.onclick = (ev) => this.onMoveByGallery(ev, formElements.selectGallery);
        formElements.btnMoveByCheckedGalleries.onclick = (ev) => this.onMoveByCheckedGalleries(ev);
        formElements.btnMoveAllJ3xImjages.onclick = (ev) => this.onMoveAllGalleries(ev);


//            this.buttonFolderImport.onclick = (ev: DragEvent) => this.onImportFolder(ev);

//            fileInput.onchange = (ev: DragEvent) => this.onNewFile(ev);
//            fileZip.onchange = (ev: DragEvent) => this.onZipFile(ev);
    }

//    onMoveByGallery(ev: MouseEvent) {
    onMoveByGallery(ev: MouseEvent, selectGallery: HTMLSelectElement) {
        let element = <HTMLInputElement>ev.target;

        ev.preventDefault();
        ev.stopPropagation();

        // gallery id
        //const gallery_id =  parseInt (selectGallery.value);
        const gallery_id = selectGallery.value;
//        const gallery_name = selectGallery.options[gallery_id].text;
        const gallery_name = selectGallery.selectedOptions[0].text;

        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onMoveByGallery: Rejected");
        } else {

            console.log(">onMoveByGallery: (" + gallery_id + ") " + "\"" + gallery_name + "\"");

            const actGallery: Ij3xGalleries = {
                galleryId: gallery_id,
                name: gallery_name,

                statusBar: null,
                errorZone: null
            };

            this.j3xGalleries.addGalleries([actGallery]);

            // Start ajax request of DB image reservation
            this.requestImageIdsTask.ajaxRequest();
        }
    }


    onMoveByCheckedGalleries(ev: MouseEvent) {
        let element = <HTMLInputElement>ev.target;

        ev.preventDefault();
        ev.stopPropagation();

        console.log(">onMoveByCheckedGalleries: ");

        // // gallery id
        // const selectionHTML = <HTMLInputElement>this.selectGallery;
        // //const gallery_id =  parseInt (selectionHTML.value);
        // const gallery_id = selectionHTML.value;
        //
        // // prevent empty gallery
        // if (parseInt(gallery_id) < 1) {
        //     alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
        //     console.log(">onNewFile: Rejected");
        // } else {
        //
        //     console.log(">onNewFile: " + files.length);
        //     this.j3xGalleries.addFiles(files, gallery_id);
        //
        //     // Start ajax request of DB image reservation
        //     this.requestImageIdsTask.ajaxRequest();
        // }
    }


    onMoveAllGalleries(ev: MouseEvent) {
        let element = <HTMLInputElement>ev.target;

        ev.preventDefault();
        ev.stopPropagation();

        console.log(">onMoveAllGalleries: ");

        // // gallery id
        // const selectionHTML = <HTMLInputElement>this.selectGallery;
        // //const gallery_id =  parseInt (selectionHTML.value);
        // const gallery_id = selectionHTML.value;
        //
        // // prevent empty gallery
        // if (parseInt(gallery_id) < 1) {
        //     alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
        //     console.log(">onNewFile: Rejected");
        // } else {
        //
        //     console.log(">onNewFile: " + files.length);
        //     this.j3xGalleries.addFiles(files, gallery_id);
        //
        //     // Start ajax request of DB image reservation
        //     this.requestImageIdsTask.ajaxRequest();
        // }
    }



} // class GalleriesListTask

/*----------------------------------------------------------------
  joomla ajax may return pretext to data
----------------------------------------------------------------*/

// extract json data which may be preceded with unwanted informtion
function separateDataAndNoise(response: string): [string, string] {

    let data: string = "";
    let error: string = "";
    const query = '{"success';

    // const StartIdx = response.indexOf('{"'); // ToDo: {"Success
    const StartIdx = response.indexOf(query);
    if (StartIdx > -1) {

        error = response.substring(0, StartIdx - 1);
        data = response.substring(StartIdx);

    } else {

        error = response;
        // simulate ajax error for internal use;
        // data = "{\"success\":false}";
        // data = "{}";
        data = "";
    }

    return [data, error];
}

/*----------------------------------------------------------------
  joomla ajax error returns complete page
----------------------------------------------------------------*/

// extract html part of error page
function separateErrorAndAlerts(errMessage: string): [string, string] {

    let errorHtml: string = errMessage; // Plan b: show all if nothing detected
    let alertHtml: string = "";

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

    return [errorHtml, alertHtml];
}

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
    msgType: JoomlaMessages,
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

interface IImageIds {
    id: string;
    name: string;
}

interface IResponseRequest {
    gallery_name: string;
    gallery_id: number;
    image_ids: IImageIds [];
}

interface IResponseTransfer {
    fileName: string;
    imageId: string; //number
    fileUrl: string;
    safeFileName: string;
    thumbSize: string;
}

interface IResponseServerFile {
    fileName: string;
    imageId: string; //number
    baseName: string;
    dstFileName: string;
    size: number;
}

interface IResponseServerFiles {
    // Path ?
    files: IResponseServerFile [];
}


//---  -----------------------------------------------------------------------------------

class RequestImageIdsTask {

//    private dragZone: HTMLElement;
    private progressArea: HTMLElement;
    private errorZone: HTMLElement;

    private j3xGalleries: J3xGalleries;
    private j3xImages2Move: J3xImages2Move;
    private moveImagesTask: MoveImagesTask;

    private request: Promise<Ij3xGalleries>;
    private isBusy: boolean = false;

    constructor(
        formElements: FormElements,
        // progressArea: HTMLElement,
        // errorZone: HTMLElement,

        j3xGalleries: J3xGalleries,
        j3xImages2Move: J3xImages2Move,
        moveImagesTask: MoveImagesTask,
    ) {
        this.progressArea = formElements.moveImageArea; // progressArea;
        this.errorZone = formElements.moveImageArea; // errorZone;

        this.j3xGalleries = j3xGalleries;
        this.j3xImages2Move = j3xImages2Move;
        this.moveImagesTask = moveImagesTask;
    }


    // https://taylor.callsen.me/ajax-multi-file-uploader-with-native-js-and-promises/
    // https://makandracards.com/makandra/39225-manually-uploading-files-via-ajax
    // https://www.w3schools.com/js/js_ajax_http.asp

    // http://html5doctor.com/drag-and-drop-to-server/
    // -> resize, exif
    // http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html

    /**/
    private async callAjaxRequest(j3xGalleries: Ij3xGalleries): Promise<any> {
        return new Promise<any>(
            function (resolve, reject) {

                const request = new XMLHttpRequest();
                request.onload = function () {
                    if (this.status === 200) {
                        // attention joomla may send error data on this channel
                        resolve(this.response);
                    } else {
                        let msg = 'Error \'on request\' for ' + j3xGalleries.name + ' in DbRequest:\n*'
                            + 'State: ' + this.status + ' ' + this.statusText + '\n'
                            +'responseType: ' + this.responseType + '\n';
                        //alert (msg);
                        console.log(msg);

                        // reject(new Error(this.response));
                        let testError = new Error(this.responseText);
                        reject(new Error(this.responseText)); // ToDo: check if there is more in this
                    }
                };
                request.onerror = function () {
                    let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                    msg += 'responseType: ' + this.responseType + '\n';
                    //msg += 'responseText: ' + this.responseText + '\n';
                    //alert (msg);
                    console.log(msg);

                    // reject(new Error(this.response));
                    reject(new Error(this.responseText));
                };

                let data = new FormData();
                data.append('gallery_name', j3xGalleries.name);
                data.append('gallery_id', j3xGalleries.galleryId);
                data.append(Token, '1');

                const urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=MaintenanceJ3x.ajaxRequestImageIds';

                request.open('POST', urlRequestDbImageId, true);
                request.onloadstart = function (e) {
                    console.log("      > callAjaxRequest: " + " (" + j3xGalleries.galleryId + ") " + j3xGalleries.name);
                };
                request.onloadend = function (e) {
                    console.log("      < callAjaxRequest: ");
                };

                request.send(data);
            }
        );

        /**
         console.log("      > callAjaxRequest: " + j3xGalleries.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + j3xGalleries.file.name)
        }, 333);
         /**/
    }

    /**/

    /**/
    public async ajaxRequest() {
        let AjaxResponse: IAjaxResponse;

        console.log("    > ajaxRequest j3xGalleries: " + this.j3xGalleries.length);

        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/

        while (this.j3xGalleries.length > 0) {
            let j3xGallery = this.j3xGalleries.shift();
            console.log("   @Request File: " + j3xGallery.name);

            // j3xGallery.statusBar = new createStatusBar(this.progressArea,
            //     j3xGallery.file.name, j3xGallery.file.size, 'image');

            /* let request = */
            //await this.callAjaxRequest(j3xGallery)
            this.callAjaxRequest(j3xGallery)
                .then((response) => {
                    // attention joomla may send error data on this channel
                    console.log("   <Request OK: " + j3xGallery.name);
                    console.log("      response: " + JSON.stringify(response));

                    const [data, noise] = separateDataAndNoise(response);

                    console.log("      response data: " + JSON.stringify(data));
                    console.log("      response error/noise: " + JSON.stringify(noise));

                    // Json object exist
                    // {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
                    if (data.length) {
                        AjaxResponse = JSON.parse(data);
                    } else {
                        let serverError = new Error(noise);

                        const errorHtml = ajaxCatchedMessages2Html(serverError, j3xGallery.name);
                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        } else {
                            const msg = "Error result in ajaxRequestImageIds: Undefined type: \"" + noise + "\"";
                            const msgHtml = ajaxMessages2CardHtml(msg, j3xGallery.name);
                            if (msgHtml) {
                                this.errorZone.appendChild(msgHtml);
                            }
                        }

                        return;
                    }

                    if (AjaxResponse.success) {
                        console.log("      success data: " + AjaxResponse.data);

                            let dbData = <IResponseRequest><unknown>AjaxResponse.data;

                            let gallery_name = dbData.gallery_name;
                            let gallery_id = dbData.gallery_id;
                            let image_ids = dbData.image_ids;
                            this.j3xImages2Move.add(j3xGallery, imageId.toString(), fileName, dstFileName);

                            // ==> Start ajax transfer of files
                            this.moveImagesTask.ajaxTransfer();
                    } else {

                        if (AjaxResponse.message || AjaxResponse.messages) {

                            const errorHtml = ajaxMessages2Html(AjaxResponse, j3xGallery.name);
                            if (errorHtml) {
                                this.errorZone.appendChild(errorHtml);
                            } else {
                                console.log("      failed data: " + AjaxResponse.data);
                            }
                        } else {
                            // No message given use noise
                            if (noise.length > 0) {
                                let serverError = new Error(noise);

// ToDo: rename all catched ==> caught
                                const errorHtml = ajaxCatchedMessages2Html(serverError, j3xGallery.name);
                                if (errorHtml) {
                                    this.errorZone.appendChild(errorHtml);
                                }
                            }
                            else
                            {
                                const msg = "Unsuccessful ajax call in ajaxRequestImageIds: Resulting data: " + JSON.stringify(AjaxResponse.data);
                                const msgHtml = ajaxMessages2CardHtml(msg, j3xGallery.name);
                                if (msgHtml) {
                                    this.errorZone.appendChild(msgHtml);
                                }
                            }
                        }

                    }
                }
                /* --------------------------------------------------------
                error may be "caught" here, then part in .catch will n ot be reached

                // error: function (result) { self.result(JSON.stringify(result.responseJSON)); }
                , (error) => {

                        console.log("    !!! Error reject: " + j3xGallery.name);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + error.value);
                        console.log("        error.name: " + error.name);
                        console.log("        error.message: " + error.message);

                        const errorHtml = ajaxCatchedMessages2Html(error.value, j3xGallery.name);

                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        }

                        console.log('!!! errText' + errText);
                }
                /* end (error) */
                )
                .catch((errText: Error) => {
                    console.log("    !!! Error request: " + j3xGallery.name);
                    //                  alert ('errText' + errText);
                    //console.log("        error: " + JSON.stringify(errText));
                    console.log("        error: " + errText);
                    console.log("        error.name: " + errText.name);
                    console.log("        error.message: " + errText.message);

                    const errorHtml = ajaxCatchedMessages2Html(errText, j3xGallery.name);

                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }

                    console.log('!!! errText' + errText);
                })
            ;
            /**/

            console.log("    <Aj:j3xGalleries: " + this.j3xGalleries.length);
        }

        this.isBusy = false;
        console.log("    <j3xGalleries: " + this.j3xGalleries.length);
    }


}

//=================================================================================
// Handle status bar for one actual uploading image

class createStatusBar {

}

/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/

// ToDo: call title and body from  seperate functions

function ajaxCatchedMessages2Html(errText: Error, title: string): HTMLElement | null {

    let errorHtml: HTMLElement = null;

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
        errorCardHeaderTitle.appendChild(document.createTextNode(title));
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

// ToDo: call title and body from  seperate functions see above

function ajaxMessages2CardHtml(errText: string, title: string): HTMLElement | null {

    let errorHtml: HTMLElement = null;

    //--- bootstrap card as title ---------------------------

    const errorCardHtml = document.createElement('div');
    errorCardHtml.classList.add('card', 'errorContent');

    const errorCardHeaderHtml = document.createElement('div');
    errorCardHeaderHtml.classList.add('card-header');
    const errorCardHeaderTitle = document.createElement('h3');
    errorCardHeaderTitle.appendChild(document.createTextNode(title));
    errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
    errorCardHtml.appendChild(errorCardHeaderHtml);

    //--- bootstrap card body ---------------------------

    if (errText.length > 0) {
        const errorCardBodyHtml = document.createElement('div');
        errorCardBodyHtml.classList.add('card-body');
        errorCardHtml.appendChild(errorCardBodyHtml);
        const errorCardErrorPart = document.createElement('div');
        errorCardErrorPart.innerHTML = errText;
        errorCardBodyHtml.appendChild(errorCardErrorPart);
        errorCardHtml.appendChild(errorCardBodyHtml);
        errorCardHtml.appendChild(document.createElement('br'));
    }

    errorHtml = errorCardHtml;


return errorHtml;
}

/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/

function ajaxMessages2Html(AjaxResponse: IAjaxResponse, fileName: string): HTMLElement | null {
    let errorHtml: HTMLElement = null;

    if (AjaxResponse.message || AjaxResponse.messages) {

        //--- bootstrap card as title ---------------------------

        const errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');

        const errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        const errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(fileName));
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

class MoveImagesTask {

    // private imagesAreaList: HTMLElement;
    // private progressArea: HTMLElement;
    private errorZone: HTMLElement;

    private j3xImages2Move: J3xImages2Move;

    // private request: Promise<ITransferFile>;
    // private isBusyCount: number = 0;
    // private readonly BusyCountLimit: number = 5;

    constructor(
        formElements: FormElements,
        // imagesAreaList: HTMLElement,
        // progressArea: HTMLElement,
        // errorZone: HTMLElement,
        j3xImages2Move: J3xImages2Move,
    ) {
        // this.imagesAreaList = imagesAreaList;
        // this.progressArea = progressArea;
        this.errorZone = formElements.moveImageArea; // errorZone;

        this.j3xImages2Move = j3xImages2Move;
    }

//     private async callAjaxTransfer(nextFile: ITransferFile) {
//
//         console.log("      in callAjaxTransfer: " + nextFile.file);
//         console.log("      > callAjaxTransfer: " + nextFile.file);
//
//         return new Promise<any>(
//             function (resolve, reject) {
//
//                 const request = new XMLHttpRequest();
//                 request.onload = function () {
//                     if (this.status === 200) {
//                         // attention joomla may send error data on this channel
//                         resolve(this.response);
//                     } else {
//
//                         let msg = 'Error over \'on load\' for ' + nextFile.file.name + ' in Transfer:\n*'
//                             + 'State: ' + this.status + ' ' + this.statusText + '\n';
//                         //alert (msg);
//                         console.log(msg);
//
//                         reject(new Error(this.responseText));  // ToDo: check if there is mor in this
//                     }
//                 };
//                 request.onerror = function () {
//                     let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
//                     msg += 'responseType: ' + this.responseType + '\n';
//                     msg += 'responseText: ' + this.responseText + '\n';
//                     //alert (msg);
//                     console.log(msg);
//
// //                    reject(new Error('XMLHttpRequest Error: ' + this.statusText));
//                     reject(new Error(this.responseText));
//                 };
//
//                 let data = new FormData();
//                 data.append('upload_file', nextFile.file);
//                 data.append(Token, '1');
//                 data.append('gallery_id', nextFile.galleryId);
//                 data.append('imageId', nextFile.imageId);
//                 data.append('fileName', nextFile.fileName);
//                 console.log('   >fileName: ' + nextFile.fileName);
//                 data.append('dstFileName', nextFile.dstFileName);
//                 console.log('   >dstFileName: ' + nextFile.dstFileName);
//
//
//                 /**
//
//                  get:
//                  request.onprogress = function (e) {
//                     if (e.lengthComputable) {
//                         console.log(e.loaded+  " / " + e.total)
//                     }
//                 }
//
//                  post: upload
//                  xhr.upload.addEventListener("progress", function(evt){
//                     if (evt.lengthComputable) {
//                         console.log("add upload event-listener" + evt.loaded + "/" + evt.total);
//                     }
//                 }, false);
//
//                  xhr.upload.onprogress = function (event) {
//                     if (event.lengthComputable) {
//                         let complete = (event.loaded / event.total * 100 | 0);
//                         progress.value = progress.innerHTML = complete;
//                     }
//                 };
//
//                  post: download
//                  xhr.addEventListener("progress", function(evt){
//                     if (evt.lengthComputable) {
//                         let percentComplete = evt.loaded / evt.total;
//                         //Do something with download progress
//                         console.log(percentComplete);
//                     }
//                 }, false);
//
//
//                  /**
//                  xhr.onloadstart = function (e) {
//                     console.log("start")
//                 }
//                  xhr.onloadend = function (e) {
//                     console.log("end")
//                 }
//                  I would advise the use of a
//                  HTML <progress> element to
//                  display current progress.
//
//                  upload with resizing
//                  http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html
//                  /**/
//
//                 const urlTransferImages = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
//
//                 request.open('POST', urlTransferImages, true);
//
//                 request.onloadstart = function (e) {
//                     console.log("      > callAjaxTransfer: " + nextFile.file.name);
//                 };
//                 request.onloadend = function (e) {
//                     console.log("      < callAjaxTransfer: ");
//                 };
//
//                 request.upload.onprogress = function (event) {
//                     if (event.lengthComputable) {
//                         const progress = (event.loaded / event.total * 100 | 0);
//
//                         // Can't interrupt uploaded image (still creating thumbs and ...)
//                         nextFile.statusBar.setProgress(progress);
//                         if (progress >= 99.999) {
//                             nextFile.statusBar.removeAbort();
//                             nextFile.statusBar.setUpload(true);
//                         }
//                     }
//                 };
//
//                 request.send(data);
//             }
//         );
//
//         /**
//          console.log("      > callAjaxTransfer: " + nextFile.file.name);
//          let result = await setTimeout(() => {
//             console.log("< callAjaxTransfer: " + nextFile.file.name)
//         }, 333);
//          /**/
//     }
//
//     /**/
//     public async ajaxTransfer() {
//
//         console.log("    >this.j3xImages2Move.length: " + this.j3xImages2Move.length);
//         // check for busy
//         while (this.isBusyCount < this.BusyCountLimit
//         && this.j3xImages2Move.length > 0) {
//             this.isBusyCount++;
//
//             let nextFile = this.j3xImages2Move.shift();
//             console.log("   @Transfer File: " + nextFile.file);
//
//             //
//             this.callAjaxTransfer(nextFile)
//                 .then((response) => {
//                     // attention joomla may send error data on this channel
//                     console.log("   <Transfer OK: " + nextFile.file);
//                     console.log("       response: " + JSON.stringify(response));
//
//                     const [data, error] = separateDataAndNoise(response);
//
//                     console.log("      response data: " + JSON.stringify(data));
//                     console.log("      response error: " + JSON.stringify(error));
//
//                     let AjaxResponse: IAjaxResponse = JSON.parse(data);
//                     //console.log("      response data: " + JSON.stringify(data));
//
//                     if (AjaxResponse.success) {
//                         console.log("      success data: " + AjaxResponse.data);
//
//                         let transferData = <IResponseTransfer><unknown>AjaxResponse.data;
//
//                         console.log("      response data.file: " + transferData.fileName);
//                         console.log("      response data.imageId: " + transferData.imageId);
//                         console.log("      response data.fileUrl: " + transferData.fileUrl);
//                         console.log("      response data.safeFileName: " + transferData.safeFileName);
//                         console.log("      response data.thumbSize: " + transferData.thumbSize);
//
//                         nextFile.statusBar.setOK(true);
//
//                         this.showThumb(transferData)
//
//                     } else {
//                         console.log("      failed data: " + AjaxResponse.data);
//                         nextFile.statusBar.setError(true);
//                     }
//
//                     if (AjaxResponse.message || AjaxResponse.messages) {
//                         const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.fileName);
//                         if (errorHtml) {
//                             this.errorZone.appendChild(errorHtml);
//                         }
//                     }
//
//                 })
//
//                 .catch((errText: Error) => {
//                     console.log("    !!! Error transfer: " + nextFile.file);
//                     //                  alert ('errText' + errText);
//                     //console.log("        error: " + JSON.stringify(errText));
//                     console.log("        error: " + errText);
//                     console.log("        error.name: " + errText.name);
//                     console.log("        error.message: " + errText.message);
//
//                     const errorHtml = ajaxCatchedMessages2Html(errText, nextFile.fileName);
//
//                     if (errorHtml) {
//                         this.errorZone.appendChild(errorHtml);
//                     }
//
//                     nextFile.statusBar.removeAbort();
//                     nextFile.statusBar.setError(true);
//
//                     console.log('!!! errText' + errText);
//                 })
//
//                 .finally(() => {
//
//                     this.isBusyCount--;
//                     this.ajaxTransfer();
//
//                 });
//             /**/
//         }
//
//         console.log("    <this.j3xImages2Move.length: " + this.j3xImages2Move.length);
//     }


}

//======================================================================================
// On start:  DOM is loaded and ready
//======================================================================================

document.addEventListener("DOMContentLoaded", function (event) {

    // collect html elements
    let elements = new FormElements();

    // Exit if no galleries are selectable
    if (!elements.selectGallery) {
        return;
    }

    // Reserve list for galleries and images
    const j3xImages2Move = new J3xImages2Move();
    const j3xGalleries = new J3xGalleries();

    // assign click event for check boxes


    // (3) ajax request: Transfer file to server
    const moveImagesTask = new MoveImagesTask(elements, j3xImages2Move);


    // (2) ajax request: database
    const requestImageIdsTask = new RequestImageIdsTask(elements, j3xGalleries, j3xImages2Move, moveImagesTask);
    //                                                   j3xGalleries, j3xImages2Move, moveImagesTask);


    // (1) collect galleries, start request galleries from DB
    const galleriesListTask = new GalleriesListTask(elements,
        j3xGalleries, requestImageIdsTask) //,
    // zipFiles, requestZipUploadTask,
    // serverFolder,
    // serverFiles, requestFilesInFolderTask,
    // requestTransferFolderFilesTask);


//    selectGallery : HTMLInputElement;

    // buttonPresetNextGallery : HTMLAnchorElement;
    // fileInput : HTMLButtonElement;

    // let selectGallery = <HTMLSelectElement> document.getElementById('SelectGallery');
    // selectGallery.onclick = (ev) => onSelectionChange (ev.target);

    //


//    buttonSetNextGalleryFiles.onclick = (ev) => onSelectionChange (ev.target);
    //let fileInput = <HTMLInputElement> document.querySelector('#config_file');

    // buttonManualFiles.onclick = () => {
    //     alert ("buttonManualFiles.onclick href:" + buttonManualFiles.getAttribute('href'));
    //     //fileInput.click();
    //     //joomla.submitbutton(buttonManualFiles.getAttribute('href'));
    //
    // }

//    selectGallery.onchange = (ev) => this.onSelectionChange(ev.target);


});
