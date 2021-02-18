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
/**/
//declare var joomla: Joomla;
//const joomla = window.Joomla || {};
const joomla = window.Joomla || {};
//const joomla: Joomla = ((Joomla) window.Joomla) || {};
// Joomla form token
var Token;
/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/
class Queue {
    constructor() {
        this._store = [];
    }
    push(val) {
        this._store.push(val);
    }
    shift() {
        return this._store.shift();
    }
    get length() {
        return this._store.length;
    }
    isEmpty() {
        return this._store.length == 0;
    }
    isPopulated() {
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
class J3xImages2Move extends Queue {
    addImages(image_ids, gallery) {
        for (let idx = 0; idx < image_ids.length; idx++) {
            console.log('   +J3x Image: ' + image_ids[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            const next = {
                name: image_ids[idx].name,
                id: image_ids[idx].id,
                galleryId: gallery.galleryId,
                imgFlagArea: gallery.imgFlagArea
            };
            this.push(next);
            // // Debug: restrict to 4 image per button click
            // if (this.length > 4) {
            //     break;
            // }
        }
    }
}
class J3xGalleries extends Queue {
    addGalleries(galleries) {
        for (let idx = 0; idx < galleries.length; idx++) {
            const gallery = galleries[idx];
            console.log('   +Gallery: ' + galleries[idx].name);
            //--- Add file with data ---------------------------------
            // const next: Ij3xGallery = {
            //     galleryId: gallery.galleryId,
            //     name: gallery.name,
            //
            //     //statusBar: gallery.statusBar,
            //     imgFlagArea: gallery.imgFlagArea,
            //     errorZone: gallery.errorZone
            // };
            //
            // this.push(next);
            this.push(gallery);
        }
    }
}
/*----------------------------------------------------------------

----------------------------------------------------------------*/
// Required gallery ID
function markImages_nGalleryTimes(maxGalleries) {
    // needs ref gallery refstate=active/deactivate)
    // j3x_rows: HTMLTableRowElement []; // = [];
    let j3x_rows;
    let checkbox;
    let galleryId;
    const doCheck = true;
    let galleries = [];
    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = Array.from(document.getElementsByName("j3x_img_row[]"));
    //j3x_images
    j3x_rows.forEach((j3x_row) => {
        let isMerged = j3x_row.getAttribute("isMerged");
        // count not merged galleries
        if (!isMerged) {
            galleryId = j3x_row.getAttribute("galleryId");
            checkbox = j3x_row.querySelector('input[type="checkbox"]');
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
        this.selectGallery = document.getElementById('SelectGallery');
        this.moveImageArea = document.getElementById('moveImageArea');
        //--- Move buttons ----------------------------------------------------
        this.btnMoveByGallery = document.getElementById('moveByGallery');
        this.btnMoveByCheckedGalleries = document.getElementById('moveByCheckedGalleries');
        this.btnMoveAllJ3xImjages = document.getElementById('moveAllJ3xImjages');
        //--- Select next buttons ----------------------------------------------------
        this.btnSelectNextGallery = document.getElementById('selectNextGallery');
        this.btnSelectNextGalleries10 = document.getElementById('selectNextGalleries10');
        this.btnSelectNextGalleries100 = document.getElementById('selectNextGalleries100');
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
    // private zipFiles: ZipFiles;
    // private serverFolder:IRequestFolderImport;
    // private serverFiles: ServerFiles;
    // private requestZipUploadTask: RequestZipUploadTask;
    // private requestFilesInFolderTask: RequestFilesInFolderTask;
    // private requestMoveFolderFilesTask: RequestMoveFolderFilesTask;
    //
    // private buttonManualFiles : HTMLButtonElement;
    // private buttonZipFile : HTMLButtonElement;
    // private buttonFolderImport : HTMLButtonElement;
    // private inputFtpFolder : HTMLInputElement;
    constructor(
    //*        selectGallery: HTMLSelectElement,
    formElements, j3xGalleries, requestImageIdsTask) {
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
    onMoveByGallery(ev, selectGallery) {
        // let element = <HTMLInputElement>ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        //const gallery_id =  parseInt (selectGallery.value);
        const gallery_id = (Number(selectGallery.value) + 1).toString();
        const gallery_name = selectGallery.selectedOptions[0].text;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onMoveByGallery: Rejected");
        }
        else {
            console.log(">onMoveByGallery: (" + gallery_id + ") " + "\"" + gallery_name + "\"");
            const imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
            const actGallery = {
                galleryId: gallery_id,
                name: gallery_name,
                // statusBar: null,
                imgFlagArea: imgFlagArea,
                errorZone: null
            };
            this.j3xGalleries.addGalleries([actGallery]);
            // Start ajax request of DB image reservation
            this.requestImageIdsTask.ajaxRequest();
        }
    }
    onMoveByCheckedGalleries(ev) {
        //let element = <HTMLInputElement>ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        console.log(">onMoveByCheckedGalleries: ");
        // all checked elements
        const checkGalleries = document.getElementsByName("cid[]");
        checkGalleries.forEach(checkGallery => {
            const element = checkGallery;
            // use checked galleries
            if (element.checked) {
                const gallery_id = element.value; // cb2
                // alert ("galleryId" +  gallery_id)
                const galleryLink = document.getElementById('galleryId_' + gallery_id);
                const gallery_name = galleryLink.innerText;
                const imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
                const actGallery = {
                    galleryId: gallery_id,
                    name: gallery_name,
                    // statusBar: null,
                    imgFlagArea: imgFlagArea,
                    errorZone: null
                };
                this.j3xGalleries.addGalleries([actGallery]);
            }
        });
        // Start ajax request of DB image reservation
        this.requestImageIdsTask.ajaxRequest();
    }
    onMoveAllGalleries(ev) {
        let element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        console.log(">onMoveAllGalleries: ");
        // lazy programmers all galleries:
        //     instead of ajax call use all galleries loaded
        // all checked elements
        const checkGalleries = document.getElementsByName("cid[]");
        checkGalleries.forEach(checkGallery => {
            const element = checkGallery;
            const gallery_id = element.value; // cb2
            //alert ("galleryId" +  gallery_id)
            const galleryLink = document.getElementById('galleryId_' + gallery_id);
            const gallery_name = galleryLink.innerText;
            const imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
            const actGallery = {
                galleryId: gallery_id,
                name: gallery_name,
                // statusBar: null,
                imgFlagArea: imgFlagArea,
                errorZone: null
            };
            this.j3xGalleries.addGalleries([actGallery]);
        });
        // Start ajax request of DB image reservation
        this.requestImageIdsTask.ajaxRequest();
    }
} // class GalleriesListTask
/*----------------------------------------------------------------
  joomla ajax may return pretext to data
----------------------------------------------------------------*/
// extract json data which may be preceded with unwanted informtion
function separateDataAndNoise(response) {
    let data = "";
    let error = "";
    const query = '{"success';
    // const StartIdx = response.indexOf('{"'); // ToDo: {"Success
    const StartIdx = response.indexOf(query);
    if (StartIdx > -1) {
        error = response.substring(0, StartIdx - 1);
        data = response.substring(StartIdx);
    }
    else {
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
function separateErrorAndAlerts(errMessage) {
    let errorHtml = errMessage; // Plan b: show all if nothing detected
    let alertHtml = "";
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
   First step in move of file
----------------------------------------------------------------*/
// {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
// {\"success\":false,\"message\":\"COM_COMPONENT_MY_TASK_ERROR\",\"messages\":{\"info\":[\"This part has error\"],\"notice\":[\"Enqueued notice 1\",\"Enqueued notice 2\"],\"warning\":[\"Here was a small warning 1\",\"Here was a small warning 2\"],\"error\":[\"Here was a small error 1\",\"Here was a small error 2\"]},\"data\":\"result text\"}"
var JoomlaMessages;
(function (JoomlaMessages) {
    JoomlaMessages["message"] = "info";
    JoomlaMessages["notice"] = "notice";
    JoomlaMessages["warning"] = "warning";
    JoomlaMessages["error"] = "error";
})(JoomlaMessages || (JoomlaMessages = {}));
// interface IResponseServerFile {
//     fileName: string;
//     imageId: string; //number
//     baseName: string;
//     dstFileName: string;
//     size: number;
// }
//
// interface IResponseServerFiles {
//     // Path ?
//     files: IResponseServerFile [];
// }
//---  -----------------------------------------------------------------------------------
class RequestImageIdsTask {
    constructor(formElements, 
    // progressArea: HTMLElement,
    // errorZone: HTMLElement,
    j3xGalleries, j3xImages2Move, moveImagesTask) {
        this.isBusy = false;
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
    async callAjaxRequest(j3xGalleries) {
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error \'on request\' for ' + j3xGalleries.name + ' in DbRequest:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n'
                        + 'responseType: ' + this.responseType + '\n';
                    //alert (msg);
                    console.log(msg);
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
        });
        /**
         console.log("      > callAjaxRequest: " + j3xGalleries.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + j3xGalleries.file.name)
        }, 333);
         /**/
    }
    /**/
    /**/
    async ajaxRequest() {
        let AjaxResponse;
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
            // // badge gallery start
            // const startBadge = createIconsBadge (
            //     ["images"],
            //     "primary",
            //     j3xGallery.galleryId);
            // j3xGallery.imgFlagArea.appendChild(startBadge);
            /* let request = */
            //await this.callAjaxRequest(j3xGallery)
            this.callAjaxRequest(j3xGallery)
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Request OK: " + j3xGallery.name);
                console.log("      response: " + JSON.stringify(response).substring(0, 256));
                const [data, noise] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data).substring(0, 256));
                console.log("      response error/noise: " + JSON.stringify(noise).substring(0, 256));
                // Json object exist
                // {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
                if (data.length) {
                    AjaxResponse = JSON.parse(data);
                }
                else {
                    const errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                    j3xGallery.imgFlagArea.appendChild(errorBadge);
                    let serverError = new Error(noise);
                    const errorHtml = ajaxCaughtMessages2Html(serverError, j3xGallery.name);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                    else {
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
                    let dbData = AjaxResponse.data;
                    // let gallery_name = dbData.gallery_name;
                    // let gallery_id = dbData.gallery_id;
                    let image_ids = dbData.image_ids;
                    this.j3xImages2Move.addImages(image_ids, j3xGallery);
                    // badge gallery success
                    // const successBadge = createIconsBadge (
                    //     ["images"],
                    //     "success",
                    //     j3xGallery.galleryId + ': ' + image_ids.length);
                    // j3xGallery.imgFlagArea.appendChild(successBadge);
                    const successBadge = createIconsBadge(["images", "move"], "success", 
                    // j3xGallery.galleryId + ': ' + image_ids.length);
                    image_ids.length.toString());
                    j3xGallery.imgFlagArea.appendChild(successBadge);
                    // ==> Start ajax move of files
                    this.moveImagesTask.ajaxMove();
                }
                else {
                    const errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                    j3xGallery.imgFlagArea.appendChild(errorBadge);
                    if (AjaxResponse.message || AjaxResponse.messages) {
                        const errorHtml = ajaxMessages2Html(AjaxResponse, j3xGallery.name);
                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                        }
                    }
                    else {
                        // No message given use noise
                        if (noise.length > 0) {
                            let serverError = new Error(noise);
                            const errorHtml = ajaxCaughtMessages2Html(serverError, j3xGallery.name);
                            if (errorHtml) {
                                this.errorZone.appendChild(errorHtml);
                            }
                        }
                        else {
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

                    const errorHtml = ajaxCaughtMessages2Html(error.value, j3xGallery.name);

                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }

                    console.log('!!! errText' + errText);
            }
            /* end (error) */
            )
                .catch((errText) => {
                const errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                j3xGallery.imgFlagArea.appendChild(errorBadge);
                console.log("    !!! Error request: " + j3xGallery.name);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCaughtMessages2Html(errText, j3xGallery.name);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                console.log('!!! errText' + errText);
            });
            /**/
            console.log("    <Aj:j3xGalleries: " + this.j3xGalleries.length);
        }
        this.isBusy = false;
        console.log("    <j3xGalleries: " + this.j3xGalleries.length);
    }
}
//=================================================================================
// Handle status bar for one actual uploading image
// function createStatusBar {
//
//     let errorHtml: HTMLElement;
//
//
//
//     return errorHtml;
//
// }
function createImageFinishedBadge(imageId) {
    return createIconsBadge(["checkmark"], "success", imageId);
}
//---
function createGalleryErrorBadge(imageId) {
    return createIconsBadge(["images", "warning-2"], "danger", imageId);
}
//---
function createImageErrorBadge(imageId) {
    return createIconsBadge(["warning-2"], "danger", imageId);
}
// label: default, primary, success, info, warning, danger
// badge: primary, secondary, success, danger, warning, info, light, dark
function createIconsBadge(icons, labelClass, info) {
    let imageBadge;
    imageBadge = document.createElement('span');
    // badge
    imageBadge.classList.add('badge');
    imageBadge.classList.add('badge-pill');
    imageBadge.classList.add('badge-' + labelClass);
    // info
    imageBadge.appendChild(document.createTextNode(info));
    imageBadge.appendChild(document.createTextNode(' '));
    // icons
    // for (let icon of this.icons) {
    //     let htmlIcon= document.createElement('i');
    //     htmlIcon.classList.add('icon-' + icon);
    //     imageBadge.appendChild (htmlIcon);
    // }
    for (let icon of icons) {
        let htmlIcon = document.createElement('i');
        htmlIcon.classList.add('icon-' + icon);
        imageBadge.appendChild(htmlIcon);
    }
    return imageBadge;
}
//---
function badge4imageState(state, imageId, stateId, imgFlagArea) {
    // primary secondary success danger warning info light dark
    let stateBadge;
    //const labelClass:string = "info";
    const labelClass = "secondary";
    // standard will not be shown
    if (state != eImgMoveState.J3X_IMG_MOVED) {
        switch (state) {
            case eImgMoveState.J3X_IMG_NOT_FOUND:
                stateBadge = createIconsBadge(["question-2"], labelClass, imageId + ':' + stateId);
                break;
            case eImgMoveState.J3X_IMG_ALREADY_MOVED:
                stateBadge = createIconsBadge(["move"], labelClass, imageId + ':' + stateId);
                break;
            case eImgMoveState.J3X_IMG_J3X_DELETED:
                stateBadge = createIconsBadge(["file-remove"], labelClass, imageId + ':' + stateId);
                break;
            case eImgMoveState.J3X_IMG_MOVING_FAILED:
                stateBadge = createIconsBadge(["warning-circle"], labelClass, imageId + ':' + stateId);
                break;
        }
        imgFlagArea.appendChild(stateBadge);
    }
}
/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/
// ToDo: call title and body from  seperate functions
function ajaxCaughtMessages2Html(errText, title) {
    let errorHtml = null;
    // remove unnecessary HTML
    const [errorPart, alertPart] = separateErrorAndAlerts(errText.message);
    // console.log("      response error: " + JSON.stringify(errorPart));
    // console.log("      response noise: " + JSON.stringify(alertPart));
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
function ajaxMessages2CardHtml(errText, title) {
    let errorHtml = null;
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
function ajaxMessages2Html(AjaxResponse, fileName) {
    let errorHtml = null;
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
                const jMessages = AjaxResponse.messages[jMsgType];
                let alertType = 'alert-';
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
                });
            }
        }
        errorHtml = errorCardHtml;
    }
    return errorHtml;
}
/*----------------------------------------------------------------
     Ajax move files to server
----------------------------------------------------------------*/
// state after move attempt
var eImgMoveState;
(function (eImgMoveState) {
    eImgMoveState[eImgMoveState["J3X_IMG_NOT_FOUND"] = 0] = "J3X_IMG_NOT_FOUND";
    eImgMoveState[eImgMoveState["J3X_IMG_MOVED"] = 1] = "J3X_IMG_MOVED";
    eImgMoveState[eImgMoveState["J3X_IMG_ALREADY_MOVED"] = 2] = "J3X_IMG_ALREADY_MOVED";
    eImgMoveState[eImgMoveState["J3X_IMG_J3X_DELETED"] = 3] = "J3X_IMG_J3X_DELETED";
    eImgMoveState[eImgMoveState["J3X_IMG_MOVING_FAILED"] = 4] = "J3X_IMG_MOVING_FAILED";
    eImgMoveState[eImgMoveState["J3X_IMG_MOVED_AND_DB"] = 5] = "J3X_IMG_MOVED_AND_DB";
})(eImgMoveState || (eImgMoveState = {}));
class MoveImagesTask {
    constructor(formElements, 
    // imagesAreaList: HTMLElement,
    // progressArea: HTMLElement,
    // errorZone: HTMLElement,
    j3xImages2Move) {
        this.isBusyCount = 0;
        this.BusyCountLimit = 5;
        // this.imagesAreaList = imagesAreaList;
        // this.progressArea = progressArea;
        this.errorZone = formElements.moveImageArea; // errorZone;
        this.j3xImages2Move = j3xImages2Move;
    }
    async callAjaxMove(j3xImage2Move) {
        console.log("      in callAjaxMove: " + j3xImage2Move.name);
        console.log("      > callAjaxMove: " + j3xImage2Move.name);
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error over \'on load\' for ' + j3xImage2Move.name + ' in Move:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n';
                    //alert (msg);
                    console.log(msg);
                    reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                }
            };
            request.onerror = function () {
                let msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                msg += 'responseType: ' + this.responseType + '\n';
                msg += 'responseText: ' + this.responseText + '\n';
                //alert (msg);
                console.log(msg);
                //                    reject(new Error('XMLHttpRequest Error: ' + this.statusText));
                reject(new Error(this.responseText));
            };
            let data = new FormData();
            data.append(Token, '1');
            data.append('gallery_id', j3xImage2Move.galleryId);
            data.append('image_id', j3xImage2Move.id);
            data.append('image_name', j3xImage2Move.name);
            console.log('   >image name: ' + j3xImage2Move.name);
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
            //const urlMoveImages = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
            const urlMoveImage = 'index.php?option=com_rsgallery2&task=MaintenanceJ3x.ajaxMoveJ3xImage';
            request.open('POST', urlMoveImage, true);
            request.onloadstart = function (e) {
                console.log("      > callAjaxMove: " + j3xImage2Move.name);
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxMove: ");
            };
            // request.upload.onprogress = function (event) {
            //     if (event.lengthComputable) {
            //         const progress = (event.loaded / event.total * 100 | 0);
            //
            //         // Can't interrupt uploaded image (still creating thumbs and ...)
            //         nextFile.statusBar.setProgress(progress);
            //         if (progress >= 99.999) {
            //             nextFile.statusBar.removeAbort();
            //             nextFile.statusBar.setUpload(true);
            //         }
            //     }
            // };
            request.send(data);
        });
        /**
         console.log("      > callAjaxMove: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxMove: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/
    async ajaxMove() {
        let AjaxResponse;
        console.log("    >this.j3xImages2Move.length: " + this.j3xImages2Move.length);
        // check for busy
        while (this.isBusyCount < this.BusyCountLimit
            && this.j3xImages2Move.length > 0) {
            this.isBusyCount++;
            let j3xImage = this.j3xImages2Move.shift();
            console.log("   @Move File: " + j3xImage.name);
            // badge image start
            const startBadge = createIconsBadge(["arrow-right-4"], "primary", j3xImage.id);
            j3xImage.imgFlagArea.appendChild(startBadge);
            //
            this.callAjaxMove(j3xImage)
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Move OK: " + j3xImage.name);
                console.log("       response: " + JSON.stringify(response).substring(0, 256));
                const [data, noise] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data).substring(0, 256));
                console.log("      response error/noise: " + JSON.stringify(noise).substring(0, 256));
                // Json object exist
                // {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
                if (data.length) {
                    AjaxResponse = JSON.parse(data);
                }
                else {
                    console.log("      data length: " + 0);
                    const errorBadge = createImageErrorBadge(j3xImage.id);
                    j3xImage.imgFlagArea.appendChild(errorBadge);
                    let serverError = new Error(noise);
                    const errorHtml = ajaxCaughtMessages2Html(serverError, j3xImage.name);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                    else {
                        const msg = "Error result in ajaxRequestImageIds: Undefined type: \"" + noise + "\"";
                        const msgHtml = ajaxMessages2CardHtml(msg, j3xImage.name);
                        if (msgHtml) {
                            this.errorZone.appendChild(msgHtml);
                        }
                    }
                    return;
                }
                if (AjaxResponse.success) {
                    console.log("      success data: " + AjaxResponse.data);
                    let moveData = AjaxResponse.data;
                    console.log("      response data.imageId: " + moveData.imageId);
                    console.log("      response data.imageName: " + moveData.imageName);
                    console.log("      response data.gallery_id: " + moveData.gallery_id);
                    console.log("      response data.state_original: " + moveData.state_original);
                    console.log("      response data.state_display: " + moveData.state_display);
                    console.log("      response data.state_thumb: " + moveData.state_thumb);
                    console.log("      response data.state_watermarked: " + moveData.state_watermarked);
                    console.log("      response data.state_image_db: " + moveData.state_image_db);
                    //--- reaction to states ------------------------------------------------
                    badge4imageState(parseInt(moveData.state_original), j3xImage.id, 'O', j3xImage.imgFlagArea);
                    badge4imageState(parseInt(moveData.state_display), j3xImage.id, 'D', j3xImage.imgFlagArea);
                    badge4imageState(parseInt(moveData.state_thumb), j3xImage.id, 'T', j3xImage.imgFlagArea);
                    // badge4imageState (parseInt (moveData.state_watermarked), j3xImage.id, 'W', j3xImage.imgFlagArea);
                    //--- badge for all over state -----------------------------------
                    // successful moved and DB
                    if (parseInt(moveData.state_image_db) == eImgMoveState.J3X_IMG_MOVED_AND_DB) {
                        // badge gallery success
                        const successBadge = createImageFinishedBadge(j3xImage.id);
                        j3xImage.imgFlagArea.appendChild(successBadge);
                    }
                    else {
                        // badge4imageState (parseInt (moveData.state_thumb), j3xImage.id, 'A', j3xImage.imgFlagArea);
                        // badge gallery failed
                        const errorBadge = createImageErrorBadge(j3xImage.id);
                        j3xImage.imgFlagArea.appendChild(errorBadge);
                    }
                }
                else {
                    const errorBadge = createImageErrorBadge(j3xImage.id);
                    j3xImage.imgFlagArea.appendChild(errorBadge);
                    if (AjaxResponse.message || AjaxResponse.messages) {
                        const errorHtml = ajaxMessages2Html(AjaxResponse, j3xImage.name);
                        if (errorHtml) {
                            this.errorZone.appendChild(errorHtml);
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                        }
                    }
                    else {
                        // No message given use noise
                        if (noise.length > 0) {
                            let serverError = new Error(noise);
                            const errorHtml = ajaxCaughtMessages2Html(serverError, j3xImage.name);
                            if (errorHtml) {
                                this.errorZone.appendChild(errorHtml);
                            }
                        }
                        else {
                            const msg = "Unsuccessful ajax call in ajaxRequestImageIds: Resulting data: " + JSON.stringify(AjaxResponse.data);
                            const msgHtml = ajaxMessages2CardHtml(msg, j3xImage.name);
                            if (msgHtml) {
                                this.errorZone.appendChild(msgHtml);
                            }
                        }
                    }
                }
            })
                .catch((errText) => {
                const errorBadge = createImageErrorBadge(j3xImage.id);
                j3xImage.imgFlagArea.appendChild(errorBadge);
                console.log("    !!! Error move: " + j3xImage.id);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCaughtMessages2Html(errText, j3xImage.name);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                console.log('!!! errText' + errText);
            })
                .finally(() => {
                this.isBusyCount--;
                this.ajaxMove();
            });
            /**/
        }
        console.log("    <this.j3xImages2Move.length: " + this.j3xImages2Move.length);
    }
}
function AssignCheckBoxEvents() {
    //let checkAllToogle: HTMLInputElement;    // checkall-toggle
    //let checkGalleries: HTMLInputElement []; // cid[]
    let checkbox;
    const moveByCheckedGalleries = document.getElementById("moveByCheckedGalleries");
    //--- handle "check all" on/off ----------------------------------------
    const checkAllToogle = (document.getElementsByName("checkall-toggle")[0]);
    checkAllToogle.addEventListener("click", (event) => {
        let element = event.target;
        moveByCheckedGalleries.disabled = !element.checked;
        //        alert ('checked: ' + element.checked)
        //        alert("==> checkbox gallery");
    });
    //--- handle checked on/off (gallery line) ----------------------------
    const checkGalleries = document.getElementsByName("cid[]");
    checkGalleries.forEach(check => {
        const checkbox = check;
        checkbox.addEventListener("click", (event) => {
            const element = event.target;
            // On checked enable button else ... others
            moveByCheckedGalleries.disabled = !element.checked;
            // on uncheck find other checked item then enable
            if (!element.checked) {
                checkGalleries.forEach(checkGallery => {
                    const other = checkGallery;
                    // any check enables  button
                    if (other.checked) {
                        moveByCheckedGalleries.disabled = false;
                        return;
                    }
                });
            }
        });
    });
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
    AssignCheckBoxEvents();
    // (3) ajax request: Move file to server
    const moveImagesTask = new MoveImagesTask(elements, j3xImages2Move);
    // (2) ajax request: database
    const requestImageIdsTask = new RequestImageIdsTask(elements, j3xGalleries, j3xImages2Move, moveImagesTask);
    //                                                   j3xGalleries, j3xImages2Move, moveImagesTask);
    // (1) collect galleries, start request galleries from DB
    const galleriesListTask = new GalleriesListTask(elements, j3xGalleries, requestImageIdsTask);
});
