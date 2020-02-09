// ToDo: use save file name in display
/**/
//declare var joomla: Joomla;
//const joomla = window.Joomla || {};
const joomla = window.Joomla || {};
// Joomla form token
var Token;
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
class DroppedFiles extends Queue {
    addFiles(files, galleryId) {
        for (let idx = 0; idx < files.length; idx++) {
            const file = files[idx];
            console.log('   +droppedFile: ' + files[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            const next = {
                file: file,
                galleryId: galleryId,
                statusBar: null,
                errorZone: null,
            };
            this.push(next);
        }
    }
}
class ZipFiles extends Queue {
    addFiles(files, galleryId) {
        for (let idx = 0; idx < files.length; idx++) {
            const file = files[idx];
            console.log('   +ZipFile: ' + files[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            const next = {
                file: file,
                galleryId: galleryId,
                statusBar: null,
                errorZone: null,
            };
            this.push(next);
        }
    }
}
class ServerFiles extends Queue {
    addFiles(files) {
        for (let idx = 0; idx < files.length; idx++) {
            const serverFile = files[idx];
            console.log('   +ServerFile: ' + files[idx].serverFile.fileName);
            //--- Add file with data ---------------------------------
            this.push(serverFile);
        }
    }
}
class TransferFiles extends Queue {
    add(nextFile, imageId, fileName, dstFileName) {
        console.log('    +TransferFile: ' + nextFile.file.name);
        const next = {
            file: nextFile.file,
            galleryId: nextFile.galleryId,
            imageId: imageId,
            fileName: fileName,
            dstFileName: dstFileName,
            statusBar: nextFile.statusBar,
            errorZone: nextFile.errorZone,
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
    // : HTMLElement;
    constructor() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.dragZone = document.getElementById('dragarea');
        this.imagesArea = document.getElementById('imagesAreaList');
        this.progressArea = document.getElementById('uploadProgressArea');
        this.errorZone = document.getElementById('uploadErrorArea');
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
    constructor(selectGallery, droppedFiles, requestDbImageIdTask, zipFiles, requestZipUploadTask, serverFolder, serverFiles, requestFilesInFolderTask, RequestTransferFolderFilesTask) {
        this.selectGallery = selectGallery;
        this.droppedFiles = droppedFiles;
        this.requestDbImageIdTask = requestDbImageIdTask;
        this.zipFiles = zipFiles;
        this.requestZipUploadTask = requestZipUploadTask;
        this.serverFolder = serverFolder;
        this.serverFiles = serverFiles;
        this.requestFilesInFolderTask = requestFilesInFolderTask;
        this.requestTransferFolderFilesTask = RequestTransferFolderFilesTask;
        let buttonManualFiles = document.querySelector('#select-file-button-drop');
        let buttonZipFile = document.querySelector('#select-zip-file-button-drop');
        let buttonFolderImport = document.querySelector('#ftp-upload-folder-button-drop');
        let fileInput = document.querySelector('#input_files');
        let fileZip = document.querySelector('#input_zip');
        buttonManualFiles.onclick = () => fileInput.click();
        buttonZipFile.onclick = () => fileZip.click();
        buttonFolderImport.onclick = (ev) => this.onImportFolder(ev);
        fileInput.onchange = (ev) => this.onNewFile(ev);
        fileZip.onchange = (ev) => this.onZipFile(ev);
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
    onZipFile(ev) {
        let element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // transfer zip, tell entpackte files, ajax single files conversion
        // gallery id
        const selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        const gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onZipFile: Rejected");
        }
        else {
            const files = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }
            console.log(">onZipFile: " + files.length);
            this.zipFiles.addFiles(files, gallery_id);
            this.requestZipUploadTask.ajaxRequest();
        }
    }
    onImportFolder(ev) {
        let element = ev.target;
        // tell folder files, ajax single files conversion
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
            // ToDo: add path from (not existing) input control
            this.serverFolder.path = '';
            this.serverFolder.galleryId = gallery_id;
            console.log(">onImportFolder: " + this.serverFolder.path);
            this.requestFilesInFolderTask.ajaxRequest();
        }
    }
}
//=================================================================================
// Handle status bar for one actual uploading image
class createStatusBar {
    constructor(progressArea, fileName, fileSize) {
        createStatusBar.imgCount++;
        //        let even_odd = (createStatusBar.imgCount % 2 == 0) ? "odd" : "even";
        // Add all elements. single line in *.css
        //this.htmlStatusbar = $("<div class='statusbar " + row + "'></div>");
        this.htmlStatusbar = document.createElement('div');
        this.htmlStatusbar.classList.add('statusbar');
        //        this.htmlStatusbar.classList.add(even_odd);
        this.htmlStatusbarInner = document.createElement('div');
        this.htmlStatusbar.appendChild(this.htmlStatusbarInner);
        //this.htmlFilename = $("<div class='filename'></div>").appendTo(this.statusbar);
        this.htmlFilename = document.createElement('div');
        this.htmlFilename.classList.add('filename');
        this.htmlStatusbarInner.appendChild(this.htmlFilename);
        //this.htmlSize = $("<div class='filesize'></div>").appendTo(this.statusbar);
        this.htmlSize = document.createElement('div');
        this.htmlSize.classList.add('filesize');
        this.htmlStatusbarInner.appendChild(this.htmlSize);
        //this.htmlProgressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
        this.htmlProgressBarOuter = document.createElement('div');
        this.htmlProgressBarOuter.classList.add('progressBar');
        this.htmlProgressBarInner = document.createElement('div');
        this.htmlProgressBarOuter.appendChild(this.htmlProgressBarInner);
        this.htmlStatusbarInner.appendChild(this.htmlProgressBarOuter);
        //this.htmlAbort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
        this.htmlAbort = document.createElement('span');
        this.htmlAbort.classList.add('abort');
        //this.htmlAbort.appendChild(document.createTextNode('Abort'));
        this.htmlAbort.innerHTML = 'Abort';
        this.htmlStatusbarInner.appendChild(this.htmlAbort);
        /**/
        this.htmlIconUpload = document.createElement('i');
        this.htmlIconUpload.classList.add('icon-upload');
        this.htmlIconOk = document.createElement('i');
        this.htmlIconOk.classList.add('icon-checkmark');
        this.htmlIconError = document.createElement('i');
        this.htmlIconError.classList.add('icon-smiley-sad-2');
        //        this.htmlIconError.classList.add('icon-smiley-sad');
        this.htmlBadgeUpload = document.createElement('span');
        this.htmlBadgeUpload.classList.add('upload');
        this.htmlBadgeUpload.classList.add('label');
        this.htmlBadgeUpload.classList.add('label-primary');
        this.htmlBadgeUpload.appendChild(this.htmlIconUpload);
        this.htmlBadgeUpload.appendChild(document.createTextNode('Uploaded'));
        //        this.htmlBadgeUpload.appendChild(document.createTextNode('Create images'));
        this.htmlBadgeUpload.style.display = "none";
        this.htmlStatusbarInner.appendChild(this.htmlBadgeUpload);
        this.htmlBadgeOk = document.createElement('span');
        this.htmlBadgeOk.classList.add('OK');
        this.htmlBadgeOk.classList.add('label');
        this.htmlBadgeOk.classList.add('label-success');
        this.htmlBadgeOk.style.display = "none";
        this.htmlBadgeOk.appendChild(this.htmlIconOk);
        //        this.htmlBadgeOk.appendChild(document.createTextNode('OK'));
        this.htmlBadgeOk.appendChild(document.createTextNode('finished OK'));
        this.htmlStatusbarInner.appendChild(this.htmlBadgeOk);
        this.htmlBadgeError = document.createElement('span');
        this.htmlBadgeError.classList.add('error');
        this.htmlBadgeError.classList.add('label');
        this.htmlBadgeError.classList.add('label-error');
        this.htmlBadgeError.style.display = "none";
        this.htmlBadgeError.appendChild(this.htmlIconError);
        //        this.htmlBadgeError.appendChild(document.createTextNode('Error'));
        this.htmlBadgeError.appendChild(document.createTextNode('finished with ERROR'));
        this.htmlStatusbarInner.appendChild(this.htmlBadgeError);
        this.htmlFilename.innerHTML = fileName;
        if (fileSize > 0) {
            this.htmlSize.innerHTML = this.fileSizeText(fileSize);
        }
        else {
            this.htmlSize.innerHTML = '%'; // Not defined
        }
        //// set as first element: Latest file on top to compare if already shown in image area
        //progressArea.prepend(this.statusbar);
        // set as last element: Latest file on top to compare if already shown in image area
        progressArea.appendChild(this.htmlStatusbar);
    }
    //--- file size in KB/MB .... -------------------
    // toDo: move to lib file
    fileSizeText(fileSize) {
        let sizeStr = "";
        let sizeKB = fileSize / 1024;
        if (sizeKB > 1024) {
            let sizeMB = sizeKB / 1024;
            sizeStr = sizeMB.toFixed(2) + " MB";
        }
        else {
            sizeStr = sizeKB.toFixed(2) + " KB";
        }
        return sizeStr;
    }
    ;
    //========================================
    // Change progress value
    setProgress(percentage) {
        let width = parseInt(this.htmlProgressBarOuter.style.width) || 0;
        let progressBarWidth = percentage * width / 100;
        // ToDo: animate change of width
        // transition:300ms linear; class progressBar ? put to inner ?
        //this.htmlprogressBar.find('div').animate({width: progressBarWidth}, 10).html(percentage + "%");
        this.htmlProgressBarInner.style.width = progressBarWidth.toString();
        // do not abort when nearly finished
        if (percentage >= 99.999) {
            this.htmlAbort.style.display = 'none';
            //          this.htmlProgressBarInner.style.width = "50%";
            console.log("      *** setProgress: " + 100 + '%');
        }
    }
    ;
    //========================================
    // Handle abort click
    // ToDo: Test for second ajax still working ?
    // ToDo: !!!
    setAbort(jqxhr) {
        let htmlStatusbar = this.htmlStatusbar;
        this.htmlAbort.addEventListener('click', function () {
            jqxhr.abort();
            // toDo: file name strikethrough
            //htmlStatusbar.style.display = 'none';
            htmlStatusbar.style.textDecoration = 'line-through';
        });
    }
    ;
    removeAbort() {
        this.htmlAbort.style.display = 'none';
    }
    setUpload(state) {
        const display = state ? "inline-block" : "none";
        this.htmlBadgeUpload.style.display = display;
    }
    setOK(state) {
        const display = state ? "inline-block" : "none";
        this.htmlBadgeOk.style.display = display;
    }
    setError(state) {
        const display = state ? "inline-block" : "none";
        this.htmlBadgeError.style.display = display;
    }
    //========================================
    // Remove item after successful file upload
    remove() {
        this.htmlStatusbar.style.display = 'none';
    }
    ;
}
createStatusBar.imgCount = 0;
/*----------------------------------------------------------------
  joomla ajax may return pretext to data
----------------------------------------------------------------*/
// extract json data which may be preceded with unwanted informtion
function separateDataAndNoise(response) {
    let data = response;
    let error = "";
    const StartIdx = response.indexOf('{"'); // ToDo: {"Success
    if (StartIdx > -1) {
        error = response.substring(0, StartIdx - 1);
        data = response.substring(StartIdx);
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
// jData = jQuery.parseJSON(jsonText);
/*----------------------------------------------------------------
   Ajax request DB items for each file in list
   First step in transfer of file
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
class RequestDbImageIdTask {
    constructor(progressArea, errorZone, droppedFiles, transferFiles, transferImagesTask) {
        this.isBusy = false;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.droppedFiles = droppedFiles;
        this.transferFiles = transferFiles;
        this.transferImagesTask = transferImagesTask;
    }
    // https://taylor.callsen.me/ajax-multi-file-uploader-with-native-js-and-promises/
    // https://makandracards.com/makandra/39225-manually-uploading-files-via-ajax
    // https://www.w3schools.com/js/js_ajax_http.asp
    // http://html5doctor.com/drag-and-drop-to-server/
    // -> resize, exif
    // http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html
    /**/
    async callAjaxRequest(nextFile) {
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error \'on load\' for ' + nextFile.file.name + ' in DbRequest:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n';
                    +'responseType: ' + this.responseType + '\n';
                    //alert (msg);
                    console.log(msg);
                    // reject(new Error(this.response));
                    reject(new Error(this.responseText)); // ToDo: check if there is mor in this
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
            data.append('upload_file_name', nextFile.file.name);
            data.append('upload_size', nextFile.file.size.toString());
            data.append('upload_type', nextFile.file.type);
            data.append(Token, '1');
            data.append('gallery_id', nextFile.galleryId);
            const urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxReserveDbImageId';
            request.open('POST', urlRequestDbImageId, true);
            request.onloadstart = function (e) {
                console.log("      > callAjaxRequest: " + nextFile.file.name);
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxRequest: ");
            };
            request.send(data);
        });
        /**
         console.log("      > callAjaxRequest: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/
    /**/
    async ajaxRequest() {
        console.log("    > ajaxRequest droppedFiles: " + this.droppedFiles.length);
        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/
        while (this.droppedFiles.length > 0) {
            let nextFile = this.droppedFiles.shift();
            console.log("   @Request File: " + nextFile.file.name);
            nextFile.statusBar = new createStatusBar(this.progressArea, nextFile.file.name, nextFile.file.size);
            /* let request = */
            //await this.callAjaxRequest(nextFile)
            this.callAjaxRequest(nextFile)
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Request OK: " + nextFile.file.name);
                console.log("      response: " + JSON.stringify(response));
                const [data, noise] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data));
                console.log("      response error/noise: " + JSON.stringify(noise));
                let AjaxResponse = JSON.parse(data);
                //console.log("      response data: " + JSON.stringify(data));
                if (AjaxResponse.success) {
                    console.log("      success data: " + AjaxResponse.data);
                    let dbData = AjaxResponse.data;
                    let fileName = dbData.uploadFileName;
                    let dstFileName = dbData.dstFileName;
                    let imageId = dbData.imageId;
                    this.transferFiles.add(nextFile, imageId.toString(), fileName, dstFileName);
                    // ==> Start ajax transfer of files
                    this.transferImagesTask.ajaxTransfer();
                }
                else {
                    console.log("      failed data: " + AjaxResponse.data);
                }
                if (AjaxResponse.message || AjaxResponse.messages) {
                    const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.file.name);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                }
            })
                .catch((errText) => {
                console.log("    !!! Error request: " + nextFile.file.name);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCatchedMessages2Html(errText, nextFile.file.name);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                console.log('!!! errText' + errText);
            });
            /**/
            console.log("    <Aj:droppedFiles: " + this.droppedFiles.length);
        }
        this.isBusy = false;
        console.log("    <droppedFiles: " + this.droppedFiles.length);
    }
}
/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/
function ajaxCatchedMessages2Html(errText, fileName) {
    let errorHtml = null;
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
        errorCardHeaderTitle.appendChild(document.createTextNode(fileName));
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
     Ajax transfer files to server
----------------------------------------------------------------*/
class TransferImagesTask {
    constructor(imagesArea, progressArea, errorZone, transferFiles) {
        this.isBusyCount = 0;
        this.BusyCountLimit = 5;
        this.imagesArea = imagesArea;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.transferFiles = transferFiles;
    }
    async callAjaxTransfer(nextFile) {
        console.log("      in callAjaxTransfer: " + nextFile.file);
        console.log("      > callAjaxTransfer: " + nextFile.file);
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error over \'on load\' for ' + nextFile.file.name + ' in Transfer:\n*'
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
            data.append('upload_file', nextFile.file);
            data.append(Token, '1');
            data.append('gallery_id', nextFile.galleryId);
            data.append('imageId', nextFile.imageId);
            data.append('fileName', nextFile.fileName);
            console.log('   >fileName: ' + nextFile.fileName);
            data.append('dstFileName', nextFile.dstFileName);
            console.log('   >dstFileName: ' + nextFile.dstFileName);
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
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxTransfer: ");
            };
            request.upload.onprogress = function (event) {
                if (event.lengthComputable) {
                    const progress = (event.loaded / event.total * 100 | 0);
                    // Can't interrupt uploaded image (still creating thumbs and ...)
                    nextFile.statusBar.setProgress(progress);
                    if (progress >= 99.999) {
                        nextFile.statusBar.removeAbort();
                        nextFile.statusBar.setUpload(true);
                    }
                }
            };
            request.send(data);
        });
        /**
         console.log("      > callAjaxTransfer: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxTransfer: " + nextFile.file.name)
        }, 333);
         /**/
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
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Transfer OK: " + nextFile.file);
                console.log("       response: " + JSON.stringify(response));
                const [data, error] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data));
                console.log("      response error: " + JSON.stringify(error));
                let AjaxResponse = JSON.parse(data);
                //console.log("      response data: " + JSON.stringify(data));
                if (AjaxResponse.success) {
                    console.log("      success data: " + AjaxResponse.data);
                    let transferData = AjaxResponse.data;
                    console.log("      response data.file: " + transferData.fileName);
                    console.log("      response data.imageId: " + transferData.imageId);
                    console.log("      response data.fileUrl: " + transferData.fileUrl);
                    console.log("      response data.safeFileName: " + transferData.safeFileName);
                    nextFile.statusBar.setOK(true);
                    this.showThumb(transferData);
                }
                else {
                    console.log("      failed data: " + AjaxResponse.data);
                    nextFile.statusBar.setError(true);
                }
                if (AjaxResponse.message || AjaxResponse.messages) {
                    const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.fileName);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                }
            })
                .catch((errText) => {
                console.log("    !!! Error transfer: " + nextFile.file);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCatchedMessages2Html(errText, nextFile.fileName);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                nextFile.statusBar.removeAbort();
                nextFile.statusBar.setError(true);
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
    // toDo: Html lib or similar
    showThumb(responseData) {
        // Add HTML to show thumb of uploaded image
        // ToDo: images area class:span12 && #imagesAreaList class:thumbnails around ...
        //this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
        const imageBox = document.createElement('li');
        this.imagesArea.appendChild(imageBox);
        //this.thumbArea = $("<div class='thumbnail imgProperty'></div>").appendTo(this.imageBox);
        const thumbArea = document.createElement('div');
        thumbArea.classList.add('thumbnail');
        thumbArea.classList.add('imgProperty');
        imageBox.appendChild(thumbArea);
        //this.imgContainer = $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
        const imgContainer = document.createElement('div');
        imgContainer.classList.add('imgContainer');
        thumbArea.appendChild(imgContainer);
        //this.imageDisplay = $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgContainer);
        const imageDisplay = document.createElement('img');
        imageDisplay.classList.add('img-rounded');
        imageDisplay.setAttribute('data-src', 'holder.js/600x400');
        imageDisplay.setAttribute('data-src', 'holder.js/600x400');
        imageDisplay.src = responseData.fileUrl;
        imgContainer.appendChild(imageDisplay);
        //
        //this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
        const caption = document.createElement('div');
        caption.classList.add('caption');
        imageBox.appendChild(caption);
        //this.imageName = $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
        const imageName = document.createElement('small');
        imageName.innerText = responseData.fileName;
        caption.appendChild(imageName);
        // toDo: title ?
        //this.imageId = $("<small> (" + jData.data.cid + ":" + jData.data.order + ")</small>").appendTo(this.imageDisplay);
        const imageId = document.createElement('small');
        imageId.innerText = '(' + responseData.imageId + ')'; // order
        //imageId.innerText = '(' + responseData.imageId + ':' + responseData.safeFileName + ')'; // order
        caption.appendChild(imageId);
        //this.cid = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);
        const cid = document.createElement('input');
        cid.classList.add('imageCid');
        cid.name = 'cid[]';
        cid.type = 'hidden';
        cid.innerText = responseData.imageId;
        imageBox.appendChild(cid);
    }
}
//--------------------------------------------------------------------------------------
// Zip file ...
//--------------------------------------------------------------------------------------
class RequestZipUploadTask {
    constructor(progressArea, errorZone, zipFiles, serverFiles, RequestTransferFolderFilesTask) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.zipFiles = zipFiles;
        this.serverFiles = serverFiles;
        this.requestTransferFolderFilesTask = RequestTransferFolderFilesTask;
    }
    /**/
    async callAjaxRequest(nextFile) {
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error \'on upload\' for ' + nextFile.file.name + ' in zip:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n';
                    +'responseType: ' + this.responseType + '\n';
                    //alert (msg);
                    console.log(msg);
                    // reject(new Error(this.response));
                    reject(new Error(this.responseText)); // ToDo: check if there is mor in this
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
            data.append('upload_zip_name', nextFile.file.name);
            data.append('upload_size', nextFile.file.size.toString());
            data.append('upload_type', nextFile.file.type);
            data.append(Token, '1');
            data.append('gallery_id', nextFile.galleryId);
            const urlRequestZipUpload = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxZipExtractReserveDbImageId';
            request.open('POST', urlRequestZipUpload, true);
            request.onloadstart = function (e) {
                console.log("      > callAjaxRequest: " + nextFile.file.name);
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxRequest: ");
            };
            request.send(data);
        });
        /**
         console.log("      > callAjaxRequest: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/
    async ajaxRequest() {
        console.log("    >ajaxRequest Zip: zipFiles: " + this.zipFiles.length);
        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/
        while (this.zipFiles.length > 0) {
            let nextFile = this.zipFiles.shift();
            console.log("   @Request zip File: " + nextFile.file.name);
            nextFile.statusBar = new createStatusBar(this.progressArea, nextFile.file.name, nextFile.file.size);
            /* let request = */
            //await this.callAjaxRequest(nextFile)
            this.callAjaxRequest(nextFile)
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Request OK: " + nextFile.file.name);
                console.log("      response: " + JSON.stringify(response));
                const [data, noise] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data));
                console.log("      response error/noise: " + JSON.stringify(noise));
                let AjaxResponse = JSON.parse(data);
                //console.log("      response data: " + JSON.stringify(data));
                if (AjaxResponse.success) {
                    console.log("      success data: " + AjaxResponse.data);
                    let severFiles = AjaxResponse.data;
                    for (let idx = 0; idx < severFiles.files.length; idx++) {
                        const severFile = severFiles.files[idx];
                        const nextFile = {
                            serverFile: severFile,
                            // ToDo create statusbar entry
                            statusBar: null,
                            errorZone: null,
                        };
                        this.serverFiles.addFiles([nextFile]);
                    }
                    // ==> Start ajax transfer of files
                    this.requestTransferFolderFilesTask.ajaxRequest();
                }
                else {
                    console.log("      failed data: " + AjaxResponse.data);
                }
                if (AjaxResponse.message || AjaxResponse.messages) {
                    const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.file.name);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                }
            })
                .catch((errText) => {
                console.log("    !!! Error request: " + nextFile.file.name);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCatchedMessages2Html(errText, nextFile.file.name);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                console.log('!!! errText' + errText);
            });
            /**/
            console.log("    <Aj:zipFiles: " + this.zipFiles.length);
        }
        this.isBusy = false;
        console.log("    <zipFiles: " + this.zipFiles.length);
    }
}
//--------------------------------------------------------------------------------------
// Report list of files in folder on server
//--------------------------------------------------------------------------------------
class RequestFilesInFolderTask {
    constructor(progressArea, errorZone, serverFolder, serverFiles, requestTransferFolderFilesTask) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.serverFolder = serverFolder;
        this.serverFiles = serverFiles;
        this.requestTransferFolderFilesTask = requestTransferFolderFilesTask;
    }
    // ToDo: do update this part
    async callAjaxRequest(serverFolder) {
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error \'on load\' for ' + serverFolder.path + ' in DbRequest:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n';
                    +'responseType: ' + this.responseType + '\n';
                    //alert (msg);
                    console.log(msg);
                    // reject(new Error(this.response));
                    reject(new Error(this.responseText)); // ToDo: check if there is mor in this
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
            data.append('folderPath', serverFolder.path);
            data.append('galleryId', serverFolder.galleryId);
            data.append(Token, '1');
            const urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxFilesInFolderReserveDbImageId';
            request.open('POST', urlRequestDbImageId, true);
            request.onloadstart = function (e) {
                console.log("      > callAjaxRequest: " + serverFolder.path);
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxRequest: ");
            };
            request.send(data);
        });
        /**
         console.log("      > callAjaxRequest: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/
    async ajaxRequest() {
        console.log("    >ajaxRequest FilesInFolder: " + this.serverFolder.path);
        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/
        this.serverFolder.statusBar = new createStatusBar(this.progressArea, this.serverFolder.path, 0);
        /* let request = */
        //await this.callAjaxRequest(nextFile)
        this.callAjaxRequest(this.serverFolder)
            .then((response) => {
            // attention joomla may send error data on this channel
            console.log("   <Request OK: " + this.serverFolder.path);
            console.log("      response: " + JSON.stringify(response));
            const [data, noise] = separateDataAndNoise(response);
            console.log("      response data: " + JSON.stringify(data));
            console.log("      response error/noise: " + JSON.stringify(noise));
            let AjaxResponse = JSON.parse(data);
            //console.log("      response data: " + JSON.stringify(data));
            if (AjaxResponse.success) {
                console.log("      success data: " + AjaxResponse.data);
                let severFiles = AjaxResponse.data;
                for (let idx = 0; idx < severFiles.files.length; idx++) {
                    const severFile = severFiles.files[idx];
                    const nextFile = {
                        serverFile: severFile,
                        // ToDo create statusbar entry
                        statusBar: null,
                        errorZone: null,
                    };
                    this.serverFiles.addFiles([nextFile]);
                }
                // ==> Start ajax transfer of files
                this.requestTransferFolderFilesTask.ajaxRequest();
            }
            else {
                console.log("      failed data: " + AjaxResponse.data);
            }
            if (AjaxResponse.message || AjaxResponse.messages) {
                const errorHtml = ajaxMessages2Html(AjaxResponse, this.serverFolder.path);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
            }
        })
            .catch((errText) => {
            console.log("    !!! Error request: " + this.serverFolder.path);
            //                  alert ('errText' + errText);
            //console.log("        error: " + JSON.stringify(errText));
            console.log("        error: " + errText);
            console.log("        error.name: " + errText.name);
            console.log("        error.message: " + errText.message);
            const errorHtml = ajaxCatchedMessages2Html(errText, this.serverFolder.path);
            if (errorHtml) {
                this.errorZone.appendChild(errorHtml);
            }
            console.log('!!! errText' + errText);
        });
        /**/
        console.log("    <Aj:FilesInFolder: " + this.serverFolder.path);
        this.isBusy = false;
        console.log("    <FilesInFolder: " + this.serverFolder.path);
    }
}
//--------------------------------------------------------------------------------------
// Files already on server by ftp or zip upload
//--------------------------------------------------------------------------------------
class RequestTransferFolderFilesTask {
    constructor(imagesArea, progressArea, errorZone, 
    //        zipFiles: ZipFiles,
    serverFiles) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.imagesArea = imagesArea;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.serverFiles = serverFiles;
    }
    async callAjaxRequest(nextFile) {
        return new Promise(function (resolve, reject) {
            const request = new XMLHttpRequest();
            request.onload = function () {
                if (this.status === 200) {
                    // attention joomla may send error data on this channel
                    resolve(this.response);
                }
                else {
                    let msg = 'Error \'on load\' for ' + nextFile.fileName + ' in DbRequest:\n*'
                        + 'State: ' + this.status + ' ' + this.statusText + '\n';
                    +'responseType: ' + this.responseType + '\n';
                    //alert (msg);
                    console.log(msg);
                    // reject(new Error(this.response));
                    reject(new Error(this.responseText)); // ToDo: check if there is mor in this
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
            data.append('fileName', nextFile.fileName);
            data.append('imageId', nextFile.imageId);
            data.append('baseName', nextFile.baseName);
            data.append('dstFileName', nextFile.dstFileName);
            data.append(Token, '1');
            const urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxTransferFolderFile';
            request.open('POST', urlRequestDbImageId, true);
            request.onloadstart = function (e) {
                console.log("      > callAjaxRequest: " + nextFile.fileName);
            };
            request.onloadend = function (e) {
                console.log("      < callAjaxRequest: ");
            };
            request.send(data);
        });
        /**
         console.log("      > callAjaxRequest: " + nextFile.file.name);
         let result = await setTimeout(() => {
            console.log("< callAjaxRequest: " + nextFile.file.name)
        }, 333);
         /**/
    }
    /**/
    /**/
    async ajaxRequest() {
        console.log("    > ajaxRequest serverFiles: " + this.serverFiles.length);
        // Already busy
        if (this.isBusy) {
            return;
        }
        this.isBusy = true;
        /**/
        while (this.serverFiles.length > 0) {
            const serverFile = this.serverFiles.shift().serverFile;
            let nextFile = {
                fileName: serverFile.fileName,
                imageId: serverFile.imageId,
                baseName: serverFile.baseName,
                dstFileName: serverFile.dstFileName,
                statusBar: null,
                errorZone: null,
            };
            console.log("   @Request File: " + nextFile.fileName);
            nextFile.statusBar = new createStatusBar(this.progressArea, nextFile.fileName, serverFile.size);
            /* let request = */
            //await this.callAjaxRequest(nextFile)
            this.callAjaxRequest(nextFile)
                .then((response) => {
                // attention joomla may send error data on this channel
                console.log("   <Request OK: " + nextFile.fileName);
                console.log("      response: " + JSON.stringify(response));
                const [data, noise] = separateDataAndNoise(response);
                console.log("      response data: " + JSON.stringify(data));
                console.log("      response error/noise: " + JSON.stringify(noise));
                let AjaxResponse = JSON.parse(data);
                //console.log("      response data: " + JSON.stringify(data));
                if (AjaxResponse.success) {
                    console.log("      success data: " + AjaxResponse.data);
                    let transferData = AjaxResponse.data;
                    console.log("      response data.file: " + transferData.fileName);
                    console.log("      response data.imageId: " + transferData.imageId);
                    console.log("      response data.fileUrl: " + transferData.fileUrl);
                    console.log("      response data.safeFileName: " + transferData.safeFileName);
                    nextFile.statusBar.setOK(true);
                    this.showThumb(transferData);
                }
                else {
                    console.log("      failed data: " + AjaxResponse.data);
                }
                if (AjaxResponse.message || AjaxResponse.messages) {
                    const errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.fileName);
                    if (errorHtml) {
                        this.errorZone.appendChild(errorHtml);
                    }
                }
            })
                .catch((errText) => {
                console.log("    !!! Error request: " + nextFile.fileName);
                //                  alert ('errText' + errText);
                //console.log("        error: " + JSON.stringify(errText));
                console.log("        error: " + errText);
                console.log("        error.name: " + errText.name);
                console.log("        error.message: " + errText.message);
                const errorHtml = ajaxCatchedMessages2Html(errText, nextFile.fileName);
                if (errorHtml) {
                    this.errorZone.appendChild(errorHtml);
                }
                console.log('!!! errText' + errText);
            });
            /**/
            console.log("    <Aj:droppedFiles: " + this.serverFiles.length);
        }
        this.isBusy = false;
        console.log("    <droppedFiles: " + this.serverFiles.length);
    }
    // toDo: Html lib or similar
    showThumb(responseData) {
        // Add HTML to show thumb of uploaded image
        // ToDo: images area class:span12 && #imagesAreaList class:thumbnails around ...
        //this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
        const imageBox = document.createElement('li');
        this.imagesArea.appendChild(imageBox);
        //this.thumbArea = $("<div class='thumbnail imgProperty'></div>").appendTo(this.imageBox);
        const thumbArea = document.createElement('div');
        thumbArea.classList.add('thumbnail');
        thumbArea.classList.add('imgProperty');
        imageBox.appendChild(thumbArea);
        //this.imgContainer = $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
        const imgContainer = document.createElement('div');
        imgContainer.classList.add('imgContainer');
        thumbArea.appendChild(imgContainer);
        //this.imageDisplay = $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgContainer);
        const imageDisplay = document.createElement('img');
        imageDisplay.classList.add('img-rounded');
        imageDisplay.setAttribute('data-src', 'holder.js/600x400');
        imageDisplay.setAttribute('data-src', 'holder.js/600x400');
        imageDisplay.src = responseData.fileUrl;
        imgContainer.appendChild(imageDisplay);
        //
        //this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
        const caption = document.createElement('div');
        caption.classList.add('caption');
        imageBox.appendChild(caption);
        //this.imageName = $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
        const imageName = document.createElement('small');
        imageName.innerText = responseData.fileName;
        caption.appendChild(imageName);
        // toDo: title ?
        //this.imageId = $("<small> (" + jData.data.cid + ":" + jData.data.order + ")</small>").appendTo(this.imageDisplay);
        const imageId = document.createElement('small');
        imageId.innerText = '(' + responseData.imageId + ')'; // order
        //imageId.innerText = '(' + responseData.imageId + ':' + responseData.safeFileName + ')'; // order
        caption.appendChild(imageId);
        //this.cid = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);
        const cid = document.createElement('input');
        cid.classList.add('imageCid');
        cid.name = 'cid[]';
        cid.type = 'hidden';
        cid.innerText = responseData.imageId;
        imageBox.appendChild(cid);
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
    const zipFiles = new ZipFiles();
    const serverFiles = new ServerFiles();
    const serverFolder = {
        path: "",
        galleryId: '-1',
        statusBar: null,
        errorZone: null,
    };
    // move file on server to rsgallery path (and multiply file)
    const requestTransferFolderFilesTask = new RequestTransferFolderFilesTask(elements.imagesArea, elements.progressArea, elements.errorZone, serverFiles);
    // Get list of files on server (and create image DB IDs)
    const requestFilesInFolderTask = new RequestFilesInFolderTask(elements.progressArea, elements.errorZone, serverFolder, serverFiles, requestTransferFolderFilesTask);
    // Upload zip to a server folder, return list of files on server (and create image DB IDs)
    const requestZipUploadTask = new RequestZipUploadTask(elements.progressArea, elements.errorZone, zipFiles, serverFiles, requestTransferFolderFilesTask);
    // init red / green border of drag area
    const gallerySelected = new Border4SelectedGallery(elements.selectGallery, elements.dragZone);
    // (3) ajax request: Transfer file to server
    const transferImagesTask = new TransferImagesTask(elements.imagesArea, elements.progressArea, elements.errorZone, transferFiles);
    // (2) ajax request: database image item
    //const requestDbImageIdTask = new RequestDbImageIdTask (elements.dragZone, elements.progressArea, elements.errorZone,
    //    droppedFiles, transferFiles, transferImagesTask);
    const requestDbImageIdTask = new RequestDbImageIdTask(elements.progressArea, elements.errorZone, droppedFiles, transferFiles, transferImagesTask);
    // (1) collect dropped files, start request DB image ID
    const droppedFilesTask = new DroppedFilesTask(elements.selectGallery, droppedFiles, requestDbImageIdTask, zipFiles, requestZipUploadTask, serverFolder, serverFiles, requestFilesInFolderTask, requestTransferFolderFilesTask);
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
        // ToDo: decide *.zip or other on both
        let isImage = false;
        let isZip = false;
        //        Array.from(files).foreach ((File) => {console.log("filename: " + File.name);});
        for (let i = 0; i < files.length; i++) {
            console.log("filename: " + files[i].name);
            // Zip ?
            if (files[i].name.toLowerCase().endsWith('.zip')) {
                isZip = false;
            }
            else {
                isImage = false;
            }
        }
        /**/
        if (isImage && isZip) {
            alert('Zip and image files selected. Please chooes one of both');
        }
        if (isImage) {
            droppedFilesTask.onNewFile(event);
        }
        else {
            droppedFilesTask.onZipFile(event);
        }
    });
});
/**/
