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
 * @since       5.0.0.2
 */
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
/**/
//declare var joomla: Joomla;
//const joomla = window.Joomla || {};
var joomla = window.Joomla || {};
// Joomla form token
var Token;
/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/
var Queue = /** @class */ (function () {
    function Queue() {
        this._store = [];
    }
    Queue.prototype.push = function (val) { this._store.push(val); };
    Queue.prototype.shift = function () { return this._store.shift(); };
    Object.defineProperty(Queue.prototype, "length", {
        get: function () { return this._store.length; },
        enumerable: false,
        configurable: true
    });
    Queue.prototype.isEmpty = function () { return this._store.length == 0; };
    Queue.prototype.isPopulated = function () { return this._store.length > 0; };
    return Queue;
}());
/*----------------------------------------------------------------
    simulate wait
----------------------------------------------------------------*/
function stall(stallTime) {
    if (stallTime === void 0) { stallTime = 333; }
    return __awaiter(this, void 0, void 0, function () {
        return __generator(this, function (_a) {
            switch (_a.label) {
                case 0: return [4 /*yield*/, new Promise(function (resolve) { return setTimeout(resolve, stallTime); })];
                case 1:
                    _a.sent();
                    return [2 /*return*/];
            }
        });
    });
}
/**/
function resolveAfter2Seconds(x, time) {
    if (time === void 0) { time = 2000; }
    return new Promise(function (resolve) {
        setTimeout(function () {
            resolve(x);
        }, time);
    });
}
var DroppedFiles = /** @class */ (function (_super) {
    __extends(DroppedFiles, _super);
    function DroppedFiles() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    DroppedFiles.prototype.addFiles = function (files, galleryId) {
        for (var idx = 0; idx < files.length; idx++) {
            var file = files[idx];
            console.log('   +droppedFile: ' + files[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            var next = {
                file: file,
                galleryId: galleryId,
                statusBar: null,
                errorZone: null,
            };
            this.push(next);
        }
    };
    return DroppedFiles;
}(Queue));
var ZipFiles = /** @class */ (function (_super) {
    __extends(ZipFiles, _super);
    function ZipFiles() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    ZipFiles.prototype.addFiles = function (files, galleryId) {
        for (var idx = 0; idx < files.length; idx++) {
            var file = files[idx];
            console.log('   +ZipFile: ' + files[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            var next = {
                file: file,
                galleryId: galleryId,
                statusBar: null,
                errorZone: null,
            };
            this.push(next);
        }
    };
    return ZipFiles;
}(Queue));
/**
interface IResponseServerFile {
    fileName: string;
    imageId: string; //number
    baseName: string;
    dstFileName: string;
    size: number;
}
/**/
/**
interface IServerFile {
    serverFile: IResponseServerFile;
    galleryId: string;

    statusBar: createStatusBar | null;
    errorZone: HTMLElement | null;
}
/**/
var ServerFiles = /** @class */ (function (_super) {
    __extends(ServerFiles, _super);
    function ServerFiles() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    ServerFiles.prototype.addFiles = function (files) {
        for (var idx = 0; idx < files.length; idx++) {
            var serverFile = files[idx];
            console.log('   +ServerFile: ' + files[idx].fileName);
            //--- Add file with data ---------------------------------
            this.push(serverFile);
        }
    };
    return ServerFiles;
}(Queue));
var TransferFiles = /** @class */ (function (_super) {
    __extends(TransferFiles, _super);
    function TransferFiles() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TransferFiles.prototype.add = function (nextFile, imageId, fileName, dstFileName) {
        console.log('    +TransferFile: ' + nextFile.file.name);
        var next = {
            file: nextFile.file,
            galleryId: nextFile.galleryId,
            imageId: imageId,
            fileName: fileName,
            dstFileName: dstFileName,
            statusBar: nextFile.statusBar,
            errorZone: nextFile.errorZone,
        };
        this.push(next);
    };
    return TransferFiles;
}(Queue));
/*----------------------------------------------------------------
  Pointer to used html elements on form
----------------------------------------------------------------*/
var FormElements = /** @class */ (function () {
    // : HTMLElement;
    // select eElements on form
    function FormElements() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.dragZone = document.getElementById('dragarea');
        this.imagesAreaList = document.getElementById('imagesAreaList');
        this.progressArea = document.getElementById('uploadProgressArea');
        this.errorZone = document.getElementById('uploadErrorArea');
        this.buttonManualFiles = document.querySelector('#select-file-button-drop');
        this.buttonZipFile = document.querySelector('#select-zip-file-button-drop');
        this.buttonFolderImport = document.querySelector('#ftp-upload-folder-button-drop');
        this.inputFtpFolder = document.querySelector('#ftp_upload_directory');
    }
    return FormElements;
}());
/*----------------------------------------------------------------
   gallery selection defines red / green border of drag area
----------------------------------------------------------------*/
var enableDragZone = /** @class */ (function () {
    function enableDragZone(formElements) {
        var _this = this;
        this.dragZone = formElements.dragZone;
        this.buttonManualFiles = formElements.buttonManualFiles;
        this.buttonZipFile = formElements.buttonZipFile;
        this.buttonFolderImport = formElements.buttonFolderImport;
        this.inputFtpFolder = formElements.inputFtpFolder;
        formElements.selectGallery.onchange = function (ev) { return _this.onSelectionChange(ev.target); };
        this.checkSelection(formElements.selectGallery.value);
    }
    enableDragZone.prototype.onSelectionChange = function (target) {
        var selection = target;
        this.checkSelection(selection.value);
    };
    enableDragZone.prototype.checkSelection = function (value) {
        // is selected (green)
        if (value != "0") {
            this.dragZone.classList.remove('dragareaDisabled');
            this.buttonManualFiles.disabled = false;
            this.buttonZipFile.disabled = false;
            this.buttonFolderImport.disabled = false;
            this.inputFtpFolder.disabled = false;
        }
        else {
            // not selected (red)
            this.dragZone.classList.add('dragareaDisabled');
            this.buttonManualFiles.disabled = true;
            this.buttonZipFile.disabled = true;
            this.buttonFolderImport.disabled = true;
            this.inputFtpFolder.disabled = true;
        }
    };
    return enableDragZone;
}());
/*----------------------------------------------------------------
handle dropped files
----------------------------------------------------------------*/
var DroppedFilesTask = /** @class */ (function () {
    function DroppedFilesTask(
    //*        selectGallery: HTMLInputElement,
    formElements, droppedFiles, requestDbImageIdTask, zipFiles, requestZipUploadTask, serverFolder, serverFiles, requestFilesInFolderTask, RequestTransferFolderFilesTask) {
        var _this = this;
        this.selectGallery = formElements.selectGallery;
        this.buttonManualFiles = formElements.buttonManualFiles;
        this.buttonZipFile = formElements.buttonZipFile;
        this.buttonFolderImport = formElements.buttonFolderImport;
        this.inputFtpFolder = formElements.inputFtpFolder;
        this.droppedFiles = droppedFiles;
        this.requestDbImageIdTask = requestDbImageIdTask;
        this.zipFiles = zipFiles;
        this.requestZipUploadTask = requestZipUploadTask;
        this.serverFolder = serverFolder;
        this.serverFiles = serverFiles;
        this.requestFilesInFolderTask = requestFilesInFolderTask;
        this.requestTransferFolderFilesTask = RequestTransferFolderFilesTask;
        var fileInput = document.querySelector('#input_files');
        var fileZip = document.querySelector('#input_zip');
        this.buttonManualFiles.onclick = function () { return fileInput.click(); };
        this.buttonZipFile.onclick = function () { return fileZip.click(); };
        this.buttonFolderImport.onclick = function (ev) { return _this.onImportFolder(ev); };
        fileInput.onchange = function (ev) { return _this.onNewFile(ev); };
        fileZip.onchange = function (ev) { return _this.onZipFile(ev); };
    }
    DroppedFilesTask.prototype.onNewFile = function (ev) {
        var element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        var selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        var gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onNewFile: Rejected");
        }
        else {
            var files = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }
            console.log(">onNewFile: " + files.length);
            this.droppedFiles.addFiles(files, gallery_id);
            // Start ajax request of DB image reservation
            this.requestDbImageIdTask.ajaxRequest();
        }
    };
    DroppedFilesTask.prototype.onZipFile = function (ev) {
        var element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // transfer zip, tell entpackte files, ajax single files conversion
        // gallery id
        var selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        var gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onZipFile: Rejected");
        }
        else {
            var files = element.files || ev.dataTransfer.files;
            // files exist ?
            if (!files.length) {
                return;
            }
            console.log(">onZipFile: " + files.length);
            this.zipFiles.addFiles(files, gallery_id);
            this.requestZipUploadTask.ajaxRequest();
        }
    };
    DroppedFilesTask.prototype.onImportFolder = function (ev) {
        var element = ev.target;
        // tell folder files, ajax single files conversion
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        var selectionHTML = this.selectGallery;
        //const gallery_id =  parseInt (selectionHTML.value);
        var gallery_id = selectionHTML.value;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onNewFile: Rejected");
        }
        else {
            // ToDo: add path from (not existing) input control
            this.serverFolder.path = this.inputFtpFolder.value;
            this.serverFolder.galleryId = gallery_id;
            console.log(">onImportFolder: " + this.serverFolder.path);
            this.requestFilesInFolderTask.ajaxRequest(gallery_id);
        }
    };
    return DroppedFilesTask;
}());
//=================================================================================
// Handle status bar for one actual uploading image
var createStatusBar = /** @class */ (function () {
    function createStatusBar(progressArea, fileName, fileSize, origin) {
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
        if (origin == 'image') {
            this.htmlFilename.classList.add('filename');
            this.htmlFilename.classList.add('shorten-long-text');
        }
        else {
            // ToDo: May be others too
            this.htmlFilename.classList.add('zip');
        }
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
        this.htmlAbort.classList.add('label');
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
    createStatusBar.prototype.fileSizeText = function (fileSize) {
        var sizeStr = "";
        var sizeKB = fileSize / 1024;
        if (sizeKB > 1024) {
            var sizeMB = sizeKB / 1024;
            sizeStr = sizeMB.toFixed(2) + " MB";
        }
        else {
            sizeStr = sizeKB.toFixed(2) + " KB";
        }
        return sizeStr;
    };
    ;
    //========================================
    // Change progress value
    createStatusBar.prototype.setProgress = function (percentage) {
        this.htmlProgressBarInner.style.width = '' + percentage.toString() + '%';
        // remove abort button when nearly finished
        if (percentage >= 99.999) {
            //this.htmlAbort.style.display = 'none';
            this.removeAbort();
        }
        //        console.log("      *** setProgress: " + percentage + '%');
    };
    ;
    //========================================
    // Handle abort click
    // ToDo: Test for second ajax still working ?
    // ToDo: !!!
    createStatusBar.prototype.setAbort = function (jqxhr) {
        var htmlStatusbar = this.htmlStatusbar;
        this.htmlAbort.addEventListener('click', function () {
            jqxhr.abort();
            // toDo: file name strikethrough
            //htmlStatusbar.style.display = 'none';
            htmlStatusbar.style.textDecoration = 'line-through';
        });
    };
    ;
    createStatusBar.prototype.removeAbort = function () {
        this.htmlAbort.style.display = 'none';
    };
    createStatusBar.prototype.setUpload = function (state) {
        var display = state ? "inline-block" : "none";
        this.htmlBadgeUpload.style.display = display;
    };
    createStatusBar.prototype.setOK = function (state) {
        var display = state ? "inline-block" : "none";
        this.htmlBadgeOk.style.display = display;
    };
    createStatusBar.prototype.setError = function (state) {
        var display = state ? "inline-block" : "none";
        this.htmlBadgeError.style.display = display;
    };
    //========================================
    // Remove item after successful file upload
    createStatusBar.prototype.remove = function () {
        this.htmlStatusbar.style.display = 'none';
    };
    ;
    createStatusBar.imgCount = 0;
    return createStatusBar;
}());
/*----------------------------------------------------------------
  joomla ajax may return pretext to data
----------------------------------------------------------------*/
// extract json data which may be preceded with unwanted informtion
function separateDataAndNoise(response) {
    var data = "";
    var error = "";
    var query = '{"success';
    // const StartIdx = response.indexOf('{"'); // ToDo: {"Success
    var StartIdx = response.indexOf(query);
    if (StartIdx > -1) {
        error = response.substring(0, StartIdx - 1);
        data = response.substring(StartIdx);
    }
    else {
        error = response;
        data = "{}";
    }
    return [data, error];
}
/*----------------------------------------------------------------
  joomla ajax error returns complete page
----------------------------------------------------------------*/
// extract html part of error page
function separateErrorAndAlerts(errMessage) {
    var errorHtml = errMessage; // Plan b: show all if nothing detected
    var alertHtml = "";
    var StartErrorIdx = errMessage.indexOf('<section id="content" class="content">');
    var EndErrorIdx = errMessage.indexOf('</section>');
    var StartAlertIdx = errMessage.indexOf('<div class="notify-alerts">', EndErrorIdx);
    // behind alerts are scripts
    var StartScriptIdx = errMessage.indexOf('<script src=');
    // three divs back
    var EndAlertIdx_01 = errMessage.lastIndexOf('</div>', StartScriptIdx);
    //    let EndAlertIdx_02 = errMessage.lastIndexOf('</div>', EndAlertIdx_01 - 6);
    var EndAlertIdx_02 = EndAlertIdx_01;
    var EndAlertId = errMessage.lastIndexOf('</div>', EndAlertIdx_02 - 6);
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
var RequestDbImageIdTask = /** @class */ (function () {
    function RequestDbImageIdTask(progressArea, errorZone, droppedFiles, transferFiles, transferImagesTask) {
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
    RequestDbImageIdTask.prototype.callAjaxRequest = function (nextFile) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error \'on load\' for ' + nextFile.file.name + ' in DbRequest:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n';
                                +'responseType: ' + this.responseType + '\n';
                                //alert (msg);
                                console.log(msg);
                                // reject(new Error(this.response));
                                reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                            }
                        };
                        request.onerror = function () {
                            var msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                            msg += 'responseType: ' + this.responseType + '\n';
                            //msg += 'responseText: ' + this.responseText + '\n';
                            //alert (msg);
                            console.log(msg);
                            // reject(new Error(this.response));
                            reject(new Error(this.responseText));
                        };
                        var data = new FormData();
                        data.append('upload_file_name', nextFile.file.name);
                        data.append('upload_size', nextFile.file.size.toString());
                        data.append('upload_type', nextFile.file.type);
                        data.append(Token, '1');
                        data.append('gallery_id', nextFile.galleryId);
                        var urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxReserveDbImageId';
                        request.open('POST', urlRequestDbImageId, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxRequest: " + nextFile.file.name);
                        };
                        request.onloadend = function (e) {
                            console.log("      < callAjaxRequest: ");
                        };
                        request.send(data);
                    })];
            });
        });
    };
    /**/
    /**/
    RequestDbImageIdTask.prototype.ajaxRequest = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _loop_1, this_1;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    > ajaxRequest droppedFiles: " + this.droppedFiles.length);
                // Already busy
                if (this.isBusy) {
                    return [2 /*return*/];
                }
                this.isBusy = true;
                _loop_1 = function () {
                    var nextFile = this_1.droppedFiles.shift();
                    console.log("   @Request File: " + nextFile.file.name);
                    nextFile.statusBar = new createStatusBar(this_1.progressArea, nextFile.file.name, nextFile.file.size, 'image');
                    /* let request = */
                    //await this.callAjaxRequest(nextFile)
                    this_1.callAjaxRequest(nextFile)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Request OK: " + nextFile.file.name);
                        console.log("      response: " + JSON.stringify(response));
                        var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                        console.log("      response data: " + JSON.stringify(data));
                        console.log("      response error/noise: " + JSON.stringify(noise));
                        var AjaxResponse = JSON.parse(data);
                        //console.log("      response data: " + JSON.stringify(data));
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var dbData = AjaxResponse.data;
                            var fileName = dbData.uploadFileName;
                            var dstFileName = dbData.dstFileName;
                            var imageId = dbData.imageId;
                            _this.transferFiles.add(nextFile, imageId.toString(), fileName, dstFileName);
                            // ==> Start ajax transfer of files
                            _this.transferImagesTask.ajaxTransfer();
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                        }
                        if (AjaxResponse.message || AjaxResponse.messages) {
                            var errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.file.name);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                        }
                    })
                        .catch(function (errText) {
                        console.log("    !!! Error request: " + nextFile.file.name);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCatchedMessages2Html(errText, nextFile.file.name);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        console.log('!!! errText' + errText);
                    });
                    /**/
                    console.log("    <Aj:droppedFiles: " + this_1.droppedFiles.length);
                };
                this_1 = this;
                /**/
                while (this.droppedFiles.length > 0) {
                    _loop_1();
                }
                this.isBusy = false;
                console.log("    <droppedFiles: " + this.droppedFiles.length);
                return [2 /*return*/];
            });
        });
    };
    return RequestDbImageIdTask;
}());
/*----------------------------------------------------------------
     ajax messages as html elements
----------------------------------------------------------------*/
function ajaxCatchedMessages2Html(errText, fileName) {
    var errorHtml = null;
    // remove unnecessary HTML
    var _a = separateErrorAndAlerts(errText.message), errorPart = _a[0], alertPart = _a[1];
    console.log("      response error: " + JSON.stringify(errorPart));
    console.log("      response noise: " + JSON.stringify(alertPart));
    if (errorPart || alertPart) {
        //--- bootstrap card as title ---------------------------
        var errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');
        var errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        var errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(fileName));
        errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
        errorCardHtml.appendChild(errorCardHeaderHtml);
        //--- bootstrap card body ---------------------------
        if (errorPart.length > 0) {
            var errorCardBodyHtml = document.createElement('div');
            errorCardBodyHtml.classList.add('card-body');
            errorCardHtml.appendChild(errorCardBodyHtml);
            var errorCardErrorPart = document.createElement('div');
            errorCardErrorPart.innerHTML = errorPart;
            errorCardBodyHtml.appendChild(errorCardErrorPart);
            errorCardHtml.appendChild(errorCardBodyHtml);
        }
        if (alertPart.length > 0) {
            var errorCardBodyHtml = document.createElement('div');
            errorCardBodyHtml.classList.add('card-body');
            errorCardHtml.appendChild(errorCardBodyHtml);
            var errorCardErrorPart = document.createElement('div');
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
    var errorHtml = null;
    if (AjaxResponse.message || AjaxResponse.messages) {
        //--- bootstrap card as title ---------------------------
        var errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');
        var errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        var errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(fileName));
        errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
        errorCardHtml.appendChild(errorCardHeaderHtml);
        //--- bootstrap card body ---------------------------
        var errorCardBodyHtml_1 = document.createElement('div');
        errorCardBodyHtml_1.classList.add('card-body');
        errorCardHtml.appendChild(errorCardBodyHtml_1);
        if (AjaxResponse.message) {
            var errorCardBodyTitle = document.createElement('h4');
            errorCardBodyTitle.classList.add('card-title');
            errorCardBodyTitle.appendChild(document.createTextNode(AjaxResponse.message));
            errorCardBodyHtml_1.appendChild(errorCardBodyTitle);
            console.log('!!! message:' + AjaxResponse.message);
        }
        if (AjaxResponse.messages) {
            var _loop_2 = function (jMsgType) {
                // enum JoomlaMessages
                var jMessages = AjaxResponse.messages[jMsgType];
                var alertType = 'alert-';
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
                jMessages.map(function (msg) {
                    var htmlText = '[' + jMsgType + '] "' + msg + '"';
                    var msgHtml = document.createElement('div');
                    //errorHtml.classList.add('errorContent');
                    msgHtml.classList.add('alert', alertType, 'errorContent');
                    msgHtml.appendChild(document.createTextNode(htmlText));
                    //errorHtml.appendChild(msgHtml);
                    errorCardBodyHtml_1.appendChild(msgHtml);
                });
            };
            // JoomlaMessage {string, string []}
            for (var _i = 0, _a = Object.keys(AjaxResponse.messages); _i < _a.length; _i++) {
                var jMsgType = _a[_i];
                _loop_2(jMsgType);
            }
        }
        errorHtml = errorCardHtml;
    }
    return errorHtml;
}
/*----------------------------------------------------------------
     Ajax transfer files to server
----------------------------------------------------------------*/
var TransferImagesTask = /** @class */ (function () {
    function TransferImagesTask(imagesAreaList, progressArea, errorZone, transferFiles) {
        this.isBusyCount = 0;
        this.BusyCountLimit = 5;
        this.imagesAreaList = imagesAreaList;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.transferFiles = transferFiles;
    }
    TransferImagesTask.prototype.callAjaxTransfer = function (nextFile) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                console.log("      in callAjaxTransfer: " + nextFile.file);
                console.log("      > callAjaxTransfer: " + nextFile.file);
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error over \'on load\' for ' + nextFile.file.name + ' in Transfer:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n';
                                //alert (msg);
                                console.log(msg);
                                reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                            }
                        };
                        request.onerror = function () {
                            var msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                            msg += 'responseType: ' + this.responseType + '\n';
                            msg += 'responseText: ' + this.responseText + '\n';
                            //alert (msg);
                            console.log(msg);
                            //                    reject(new Error('XMLHttpRequest Error: ' + this.statusText));
                            reject(new Error(this.responseText));
                        };
                        var data = new FormData();
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
                        var urlTransferImages = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxSingleFile';
                        request.open('POST', urlTransferImages, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxTransfer: " + nextFile.file.name);
                        };
                        request.onloadend = function (e) {
                            console.log("      < callAjaxTransfer: ");
                        };
                        request.upload.onprogress = function (event) {
                            if (event.lengthComputable) {
                                var progress = (event.loaded / event.total * 100 | 0);
                                // Can't interrupt uploaded image (still creating thumbs and ...)
                                nextFile.statusBar.setProgress(progress);
                                if (progress >= 99.999) {
                                    nextFile.statusBar.removeAbort();
                                    nextFile.statusBar.setUpload(true);
                                }
                            }
                        };
                        request.send(data);
                    })];
            });
        });
    };
    /**/
    TransferImagesTask.prototype.ajaxTransfer = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _loop_3, this_2;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    >this.transferFiles.length: " + this.transferFiles.length);
                _loop_3 = function () {
                    this_2.isBusyCount++;
                    var nextFile = this_2.transferFiles.shift();
                    console.log("   @Transfer File: " + nextFile.file);
                    //
                    this_2.callAjaxTransfer(nextFile)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Transfer OK: " + nextFile.file);
                        console.log("       response: " + JSON.stringify(response));
                        var _a = separateDataAndNoise(response), data = _a[0], error = _a[1];
                        console.log("      response data: " + JSON.stringify(data));
                        console.log("      response error: " + JSON.stringify(error));
                        var AjaxResponse = JSON.parse(data);
                        //console.log("      response data: " + JSON.stringify(data));
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var transferData = AjaxResponse.data;
                            console.log("      response data.file: " + transferData.fileName);
                            console.log("      response data.imageId: " + transferData.imageId);
                            console.log("      response data.fileUrl: " + transferData.fileUrl);
                            console.log("      response data.safeFileName: " + transferData.safeFileName);
                            console.log("      response data.thumbSize: " + transferData.thumbSize);
                            nextFile.statusBar.setOK(true);
                            _this.showThumb(transferData);
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                            nextFile.statusBar.setError(true);
                        }
                        if (AjaxResponse.message || AjaxResponse.messages) {
                            var errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.fileName);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                        }
                    })
                        .catch(function (errText) {
                        console.log("    !!! Error transfer: " + nextFile.file);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCatchedMessages2Html(errText, nextFile.fileName);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        nextFile.statusBar.removeAbort();
                        nextFile.statusBar.setError(true);
                        console.log('!!! errText' + errText);
                    })
                        .finally(function () {
                        _this.isBusyCount--;
                        _this.ajaxTransfer();
                    });
                };
                this_2 = this;
                // check for busy
                while (this.isBusyCount < this.BusyCountLimit
                    && this.transferFiles.length > 0) {
                    _loop_3();
                }
                console.log("    <this.transferFiles.length: " + this.transferFiles.length);
                return [2 /*return*/];
            });
        });
    };
    // toDo: Html lib or similar
    // ToDO: Extract function and use also otherwise
    TransferImagesTask.prototype.showThumb = function (responseData) {
        // Add HTML to show thumb of uploaded image
        // ToDo: images area class:span12 && #imagesAreaList class:thumbnails around ...
        //this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
        var imageBox = document.createElement('li');
        this.imagesAreaList.appendChild(imageBox);
        var thumbArea = document.createElement('div');
        thumbArea.classList.add('rsg2_thumbnail');
        imageBox.appendChild(thumbArea);
        //this.imgContainer = $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
        var imgContainer = document.createElement('div');
        imgContainer.classList.add('imgContainer');
        imgContainer.style.width = responseData.thumbSize + 'px';
        imgContainer.style.height = responseData.thumbSize + 'px';
        thumbArea.appendChild(imgContainer);
        //this.imageDisplay = $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgContainer);
        var imageDisplay = document.createElement('img');
        imageDisplay.classList.add('img-rounded');
        // 2021.02.15 imageDisplay.style.width = responseData.thumbSize + 'px';
        // 2021.02.15 imageDisplay.style.height = responseData.thumbSize + 'px';
        imageDisplay.src = responseData.fileUrl;
        imgContainer.appendChild(imageDisplay);
        //
        //this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
        var caption = document.createElement('div');
        caption.classList.add('caption');
        caption.classList.add('shorten-long-text');
        imageBox.appendChild(caption);
        //this.imageName = $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
        var imageName = document.createElement('small');
        imageName.innerText = responseData.fileName;
        caption.appendChild(imageName);
        //        caption.appendChild(document.createTextNode(' '));
        // toDo: title ?
        //this.imageId = $("<small> (" + jData.data.cid + ":" + jData.data.order + ")</small>").appendTo(this.imageDisplay);
        var imageId = document.createElement('small');
        imageId.innerText = ' (' + responseData.imageId + ')'; // order
        //imageId.innerText = '(' + responseData.imageId + ':' + responseData.safeFileName + ')'; // order
        caption.appendChild(imageId);
        //this.cid = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);
        var cid = document.createElement('input');
        cid.classList.add('imageCid');
        cid.name = 'cid[]';
        cid.type = 'hidden';
        cid.innerText = responseData.imageId;
        imageBox.appendChild(cid);
    };
    return TransferImagesTask;
}());
//--------------------------------------------------------------------------------------
// Zip file ...
//--------------------------------------------------------------------------------------
var RequestZipUploadTask = /** @class */ (function () {
    function RequestZipUploadTask(progressArea, errorZone, zipFiles, serverFiles, RequestTransferFolderFilesTask) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.zipFiles = zipFiles;
        this.serverFiles = serverFiles;
        this.requestTransferFolderFilesTask = RequestTransferFolderFilesTask;
    }
    /**/
    RequestZipUploadTask.prototype.callAjaxRequest = function (nextFile) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error \'on upload\' for ' + nextFile.file.name + ' in zip:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n';
                                +'responseType: ' + this.responseType + '\n';
                                //alert (msg);
                                console.log(msg);
                                // reject(new Error(this.response));
                                reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                            }
                        };
                        request.onerror = function () {
                            var msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                            msg += 'responseType: ' + this.responseType + '\n';
                            //msg += 'responseText: ' + this.responseText + '\n';
                            //alert (msg);
                            console.log(msg);
                            // reject(new Error(this.response));
                            reject(new Error(this.responseText));
                        };
                        var data = new FormData();
                        data.append('upload_zip_name', nextFile.file.name);
                        data.append('upload_size', nextFile.file.size.toString());
                        data.append('upload_type', nextFile.file.type);
                        data.append(Token, '1');
                        data.append('gallery_id', nextFile.galleryId);
                        var urlRequestZipUpload = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxZipExtractReserveDbImageId';
                        request.open('POST', urlRequestZipUpload, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxRequest: " + nextFile.file.name);
                        };
                        request.onloadend = function (e) {
                            console.log("      < callAjaxRequest: ");
                        };
                        request.send(data);
                    })];
            });
        });
    };
    /**/
    RequestZipUploadTask.prototype.ajaxRequest = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _loop_4, this_3;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    >ajaxRequest Zip: zipFiles: " + this.zipFiles.length);
                // Already busy
                if (this.isBusy) {
                    return [2 /*return*/];
                }
                this.isBusy = true;
                _loop_4 = function () {
                    var nextFile = this_3.zipFiles.shift();
                    console.log("   @Request zip File: " + nextFile.file.name);
                    nextFile.statusBar = new createStatusBar(this_3.progressArea, "*Zip: " + nextFile.file.name, nextFile.file.size, 'zip');
                    /* let request = */
                    //await this.callAjaxRequest(nextFile)
                    this_3.callAjaxRequest(nextFile)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Request OK: " + nextFile.file.name);
                        console.log("      response: " + JSON.stringify(response));
                        var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                        console.log("      response data: " + JSON.stringify(data));
                        console.log("      response error/noise: " + JSON.stringify(noise));
                        var AjaxResponse = JSON.parse(data);
                        //console.log("      response data: " + JSON.stringify(data));
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var foundFiles = AjaxResponse.data;
                            for (var idx = 0; idx < foundFiles.files.length; idx++) {
                                var foundFile = foundFiles.files[idx];
                                var serverFile = {
                                    fileName: foundFile.fileName,
                                    imageId: foundFile.imageId,
                                    baseName: foundFile.baseName,
                                    dstFileName: foundFile.dstFileName,
                                    size: foundFile.size,
                                    galleryId: nextFile.galleryId,
                                    origin: 'zip',
                                    // ToDo create statusbar entry
                                    statusBar: null,
                                    errorZone: null,
                                };
                                _this.serverFiles.addFiles([serverFile]);
                            }
                            // ==> Start ajax transfer of files
                            _this.requestTransferFolderFilesTask.ajaxRequest();
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                        }
                        if (AjaxResponse.message || AjaxResponse.messages) {
                            var errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.file.name);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                        }
                    })
                        .catch(function (errText) {
                        console.log("    !!! Error request: " + nextFile.file.name);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCatchedMessages2Html(errText, nextFile.file.name);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        console.log('!!! errText' + errText);
                    });
                    /**/
                    console.log("    <Aj:zipFiles: " + this_3.zipFiles.length);
                };
                this_3 = this;
                /**/
                while (this.zipFiles.length > 0) {
                    _loop_4();
                }
                this.isBusy = false;
                console.log("    <zipFiles: " + this.zipFiles.length);
                return [2 /*return*/];
            });
        });
    };
    return RequestZipUploadTask;
}());
//--------------------------------------------------------------------------------------
// Report list of files in folder on server
//--------------------------------------------------------------------------------------
var RequestFilesInFolderTask = /** @class */ (function () {
    function RequestFilesInFolderTask(progressArea, errorZone, serverFolder, serverFiles, requestTransferFolderFilesTask) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.serverFolder = serverFolder;
        this.serverFiles = serverFiles;
        this.requestTransferFolderFilesTask = requestTransferFolderFilesTask;
    }
    // ToDo: do update this part
    RequestFilesInFolderTask.prototype.callAjaxRequest = function (serverFolder) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error \'on load\' for ' + serverFolder.path + ' in DbRequest:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n';
                                +'responseType: ' + this.responseType + '\n';
                                //alert (msg);
                                console.log(msg);
                                // reject(new Error(this.response));
                                reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                            }
                        };
                        request.onerror = function () {
                            var msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                            msg += 'responseType: ' + this.responseType + '\n';
                            //msg += 'responseText: ' + this.responseText + '\n';
                            //alert (msg);
                            console.log(msg);
                            // reject(new Error(this.response));
                            reject(new Error(this.responseText));
                        };
                        console.log("(15) serverFolder.galleryId: " + serverFolder.galleryId);
                        var data = new FormData();
                        data.append('folderPath', serverFolder.path);
                        data.append('gallery_id', serverFolder.galleryId);
                        data.append(Token, '1');
                        var urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxFilesInFolderReserveDbImageId';
                        request.open('POST', urlRequestDbImageId, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxRequest: " + serverFolder.path);
                        };
                        request.onloadend = function (e) {
                            console.log("      < callAjaxRequest: ");
                        };
                        request.send(data);
                    })];
            });
        });
    };
    /**/
    RequestFilesInFolderTask.prototype.ajaxRequest = function (galleryId) {
        return __awaiter(this, void 0, void 0, function () {
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    >ajaxRequest FilesInFolder: " + this.serverFolder.path);
                console.log("(10) galleryId: " + galleryId);
                // Already busy
                if (this.isBusy) {
                    return [2 /*return*/];
                }
                this.isBusy = true;
                /**/
                this.serverFolder.statusBar = new createStatusBar(this.progressArea, '*Server: ' + this.serverFolder.path, 0, 'server');
                /* let request = */
                //await this.callAjaxRequest(nextFile)
                this.callAjaxRequest(this.serverFolder)
                    .then(function (response) {
                    // attention joomla may send error data on this channel
                    console.log("   <Request OK: " + _this.serverFolder.path);
                    console.log("      response: " + JSON.stringify(response));
                    var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                    console.log("      response data: " + JSON.stringify(data));
                    console.log("      response error/noise: " + JSON.stringify(noise));
                    var AjaxResponse = JSON.parse(data);
                    //console.log("      response data: " + JSON.stringify(data));
                    if (AjaxResponse.success) {
                        console.log("      success data: " + AjaxResponse.data);
                        var foundFiles = AjaxResponse.data;
                        for (var idx = 0; idx < foundFiles.files.length; idx++) {
                            var foundFile = foundFiles.files[idx];
                            var serverFile = {
                                fileName: foundFile.fileName,
                                imageId: foundFile.imageId,
                                baseName: foundFile.baseName,
                                dstFileName: foundFile.dstFileName,
                                size: foundFile.size,
                                galleryId: galleryId,
                                origin: 'server',
                                // ToDo create statusbar entry
                                statusBar: null,
                                errorZone: null,
                            };
                            _this.serverFiles.addFiles([serverFile]);
                        }
                        // ==> Start ajax transfer of files
                        _this.requestTransferFolderFilesTask.ajaxRequest();
                    }
                    else {
                        console.log("      failed data: " + AjaxResponse.data);
                    }
                    if (AjaxResponse.message || AjaxResponse.messages) {
                        var errorHtml = ajaxMessages2Html(AjaxResponse, _this.serverFolder.path);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                    }
                })
                    .catch(function (errText) {
                    console.log("    !!! Error request: " + _this.serverFolder.path);
                    //                  alert ('errText' + errText);
                    //console.log("        error: " + JSON.stringify(errText));
                    console.log("        error: " + errText);
                    console.log("        error.name: " + errText.name);
                    console.log("        error.message: " + errText.message);
                    var errorHtml = ajaxCatchedMessages2Html(errText, _this.serverFolder.path);
                    if (errorHtml) {
                        _this.errorZone.appendChild(errorHtml);
                    }
                    console.log('!!! errText' + errText);
                });
                /**/
                console.log("    <Aj:FilesInFolder: " + this.serverFolder.path);
                this.isBusy = false;
                console.log("    <FilesInFolder: " + this.serverFolder.path);
                return [2 /*return*/];
            });
        });
    };
    return RequestFilesInFolderTask;
}());
//--------------------------------------------------------------------------------------
// Files already on server by ftp or zip upload
//--------------------------------------------------------------------------------------
var RequestTransferFolderFilesTask = /** @class */ (function () {
    function RequestTransferFolderFilesTask(imagesAreaList, progressArea, errorZone, 
    //        zipFiles: ZipFiles,
    serverFiles) {
        //    private request: Promise<IDroppedFile>;
        this.isBusy = false;
        this.imagesAreaList = imagesAreaList;
        this.progressArea = progressArea;
        this.errorZone = errorZone;
        this.serverFiles = serverFiles;
    }
    RequestTransferFolderFilesTask.prototype.callAjaxRequest = function (nextFile) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error \'on load\' for ' + nextFile.fileName + ' in DbRequest:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n';
                                +'responseType: ' + this.responseType + '\n';
                                //alert (msg);
                                console.log(msg);
                                // reject(new Error(this.response));
                                reject(new Error(this.responseText)); // ToDo: check if there is mor in this
                            }
                        };
                        request.onerror = function () {
                            var msg = 'onError::  state: ' + this.status + ' ' + this.statusText + '\n';
                            msg += 'responseType: ' + this.responseType + '\n';
                            //msg += 'responseText: ' + this.responseText + '\n';
                            //alert (msg);
                            console.log(msg);
                            // reject(new Error(this.response));
                            reject(new Error(this.responseText));
                        };
                        var data = new FormData();
                        data.append('fileName', nextFile.fileName);
                        data.append('imageId', nextFile.imageId);
                        data.append('baseName', nextFile.baseName);
                        data.append('dstFileName', nextFile.dstFileName);
                        data.append('gallery_id', nextFile.galleryId);
                        data.append('origin', nextFile.origin);
                        data.append(Token, '1');
                        var urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=upload.uploadAjaxTransferFolderFile';
                        request.open('POST', urlRequestDbImageId, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxRequest: " + nextFile.fileName);
                        };
                        request.onloadend = function (e) {
                            console.log("      < callAjaxRequest: ");
                        };
                        request.send(data);
                    })];
            });
        });
    };
    /**/
    /**/
    RequestTransferFolderFilesTask.prototype.ajaxRequest = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _loop_5, this_4;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    > ajaxRequest serverFiles: " + this.serverFiles.length);
                // Already busy
                if (this.isBusy) {
                    return [2 /*return*/];
                }
                this.isBusy = true;
                _loop_5 = function () {
                    var nextFile = this_4.serverFiles.shift();
                    console.log("   @Request File: " + nextFile.fileName);
                    nextFile.statusBar = new createStatusBar(this_4.progressArea, nextFile.baseName, nextFile.size, 'folder');
                    /* let request = */
                    //await this.callAjaxRequest(nextFile)
                    this_4.callAjaxRequest(nextFile)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Request OK: " + nextFile.fileName);
                        console.log("      response: " + JSON.stringify(response));
                        var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                        console.log("      response data: " + JSON.stringify(data));
                        console.log("      response error/noise: " + JSON.stringify(noise));
                        var AjaxResponse = JSON.parse(data);
                        //console.log("      response data: " + JSON.stringify(data));
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var transferData = AjaxResponse.data;
                            console.log("      response data.file: " + transferData.fileName);
                            console.log("      response data.imageId: " + transferData.imageId);
                            console.log("      response data.fileUrl: " + transferData.fileUrl);
                            console.log("      response data.safeFileName: " + transferData.safeFileName);
                            nextFile.statusBar.setOK(true);
                            _this.showThumb(transferData);
                        }
                        else {
                            console.log("      failed data: " + AjaxResponse.data);
                        }
                        if (AjaxResponse.message || AjaxResponse.messages) {
                            var errorHtml = ajaxMessages2Html(AjaxResponse, nextFile.fileName);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                        }
                    })
                        .catch(function (errText) {
                        console.log("    !!! Error request: " + nextFile.fileName);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCatchedMessages2Html(errText, nextFile.fileName);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        console.log('!!! errText' + errText);
                    });
                    /**/
                    console.log("    <Aj:droppedFiles: " + this_4.serverFiles.length);
                };
                this_4 = this;
                /**/
                while (this.serverFiles.length > 0) {
                    _loop_5();
                }
                this.isBusy = false;
                console.log("    <droppedFiles: " + this.serverFiles.length);
                return [2 /*return*/];
            });
        });
    };
    // toDo: Html lib or similar
    RequestTransferFolderFilesTask.prototype.showThumb = function (responseData) {
        // Add HTML to show thumb of uploaded image
        // ToDo: images area class:span12 && #imagesAreaList class:thumbnails around ...
        //this.imageBox = $("<li></li>").appendTo($('#imagesAreaList'));
        var imageBox = document.createElement('li');
        this.imagesAreaList.appendChild(imageBox);
        var thumbArea = document.createElement('div');
        thumbArea.classList.add('rsg2_thumbnail');
        imageBox.appendChild(thumbArea);
        //this.imgContainer = $("<div class='imgContainer' ></div>").appendTo(this.thumbArea);
        var imgContainer = document.createElement('div');
        imgContainer.classList.add('imgContainer');
        imgContainer.style.width = responseData.thumbSize + 'px';
        imgContainer.style.height = responseData.thumbSize + 'px';
        thumbArea.appendChild(imgContainer);
        //this.imageDisplay = $("<img class='img-rounded' data-src='holder.js/600x400' src='" + jData.data.dstFile + "' alt='' />").appendTo(this.imgContainer);
        var imageDisplay = document.createElement('img');
        imageDisplay.classList.add('img-rounded');
        // 2021.02.15 imageDisplay.style.width = responseData.thumbSize +'px';
        // 2021.02.15 imageDisplay.style.height = responseData.thumbSize + 'px';
        imageDisplay.src = responseData.fileUrl;
        imgContainer.appendChild(imageDisplay);
        //
        //this.caption = $("<div class='caption' ></div>").appendTo(this.imageBox);
        var caption = document.createElement('div');
        caption.classList.add('caption');
        imageBox.appendChild(caption);
        //this.imageName = $("<small>" + jData.data.file + "</small>").appendTo(this.caption);
        var imageName = document.createElement('small');
        imageName.innerText = responseData.fileName;
        caption.appendChild(imageName);
        // toDo: title ?
        //this.imageId = $("<small> (" + jData.data.cid + ":" + jData.data.order + ")</small>").appendTo(this.imageDisplay);
        var imageId = document.createElement('small');
        imageId.innerText = ' (' + responseData.imageId + ')'; // order
        //imageId.innerText = '(' + responseData.imageId + ':' + responseData.safeFileName + ')'; // order
        caption.appendChild(imageId);
        //this.cid = $("<input name='cid[]' class='imageCid' type='hidden' value='" + jData.data.cid + "' />").appendTo(this.imageBox);
        var cid = document.createElement('input');
        cid.classList.add('imageCid');
        cid.name = 'cid[]';
        cid.type = 'hidden';
        cid.innerText = responseData.imageId;
        imageBox.appendChild(cid);
    };
    return RequestTransferFolderFilesTask;
}());
//--------------------------------------------------------------------------------------
// On start:  DOM is loaded and ready
//--------------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (event) {
    // collect html elements
    var elements = new FormElements();
    // on old browser just show file upload
    if (typeof FormData === 'undefined') {
        var legacy_uploader = document.getElementById('legacy-uploader');
        var uploader_wrapper = document.getElementById('uploader-wrapper');
        legacy_uploader.style.display = 'block';
        uploader_wrapper.style.display = 'none';
        return;
    }
    // Exit if no galleries are selectable
    if (!elements.selectGallery) {
        return;
    }
    // Reserve list for dropped files
    var droppedFiles = new DroppedFiles();
    var transferFiles = new TransferFiles();
    var zipFiles = new ZipFiles();
    var serverFiles = new ServerFiles();
    var serverFolder = {
        path: "",
        galleryId: '-1',
        statusBar: null,
        errorZone: null,
    };
    // move file on server to rsgallery path (and multiply file)
    var requestTransferFolderFilesTask = new RequestTransferFolderFilesTask(elements.imagesAreaList, elements.progressArea, elements.errorZone, serverFiles);
    // Get list of files on server (and create image DB IDs)
    var requestFilesInFolderTask = new RequestFilesInFolderTask(elements.progressArea, elements.errorZone, serverFolder, serverFiles, requestTransferFolderFilesTask);
    // Upload zip to a server folder, return list of files on server (and create image DB IDs)
    var requestZipUploadTask = new RequestZipUploadTask(elements.progressArea, elements.errorZone, zipFiles, serverFiles, requestTransferFolderFilesTask);
    // init red / green border of drag area
    var onGalleryChange = new enableDragZone(elements);
    // (3) ajax request: Transfer file to server
    var transferImagesTask = new TransferImagesTask(elements.imagesAreaList, elements.progressArea, elements.errorZone, transferFiles);
    // (2) ajax request: database image item
    //const requestDbImageIdTask = new RequestDbImageIdTask (elements.dragZone, elements.progressArea, elements.errorZone,
    //    droppedFiles, transferFiles, transferImagesTask);
    var requestDbImageIdTask = new RequestDbImageIdTask(elements.progressArea, elements.errorZone, droppedFiles, transferFiles, transferImagesTask);
    // (1) collect dropped files, start request DB image ID
    var droppedFilesTask = new DroppedFilesTask(elements, droppedFiles, requestDbImageIdTask, zipFiles, requestZipUploadTask, serverFolder, serverFiles, requestFilesInFolderTask, requestTransferFolderFilesTask);
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
    elements.dragZone.addEventListener('dragenter', function (event) {
        event.preventDefault();
        event.stopPropagation();
        //elements.dragZone.classList.add('hover');
        elements.dragZone.classList.add('hover');
        event.dataTransfer.dropEffect = "copy";
        return false;
    }); // Notify user when file is over the drop area
    elements.dragZone.addEventListener('dragover', function (event) {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.add('hover');
        return false;
    });
    elements.dragZone.addEventListener('dragleave', function (event) {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.remove('hover');
        return false;
    });
    elements.dragZone.addEventListener('drop', function (event) {
        event.preventDefault();
        event.stopPropagation();
        elements.dragZone.classList.remove('hover');
        /**/
        // const files = event.target.files || event.dataTransfer.files;
        var files = event.target.files || event.dataTransfer.files;
        /**/
        if (!files.length) {
            return;
        }
        // ToDo: decide *.zip or other on both
        var isImage = false;
        var isZip = false;
        //        Array.from(files).foreach ((File) => {console.log("filename: " + File.name);});
        for (var i = 0; i < files.length; i++) {
            console.log("filename: " + files[i].name);
            // Zip ?
            if (files[i].name.toLowerCase().endsWith('.zip')) {
                isZip = true;
            }
            else {
                isImage = true;
            }
        }
        /**/
        if (isImage && isZip) {
            alert('Zip and image files selected. Please choose only one of both');
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
