/**
 * @package     RSGallery2
 *
 * supports maintenance user confirm messages
 *
 * @subpackage  com_rsgallery2
 * @copyright (c) 2016-2023 RSGallery2 Team
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author      finnern
 * @since       5.0.0.4
 */
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
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
        while (g && (g = 0, op[0] && (_ = 0)), _) try {
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
window.Joomla = window.Joomla || {};
// Joomla form token
var Token;
/*----------------------------------------------------------------
   queue
----------------------------------------------------------------*/
var Queue = /** @class */ (function () {
    function Queue() {
        this._store = [];
    }
    Queue.prototype.push = function (val) {
        this._store.push(val);
    };
    Queue.prototype.shift = function () {
        return this._store.shift();
    };
    Object.defineProperty(Queue.prototype, "length", {
        get: function () {
            return this._store.length;
        },
        enumerable: false,
        configurable: true
    });
    Queue.prototype.isEmpty = function () {
        return this._store.length == 0;
    };
    Queue.prototype.isPopulated = function () {
        return this._store.length > 0;
    };
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
var dbTransferJ3xGalleries = /** @class */ (function (_super) {
    __extends(dbTransferJ3xGalleries, _super);
    function dbTransferJ3xGalleries() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    dbTransferJ3xGalleries.prototype.addImages = function (image_ids, gallery) {
        for (var idx = 0; idx < image_ids.length; idx++) {
            console.log('   +J3x Image: ' + image_ids[idx].name);
            //--- ToDo: Check 4 allowed image type ---------------------------------
            // file.type ...
            //--- Add file with data ---------------------------------
            var next = {
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
    };
    return dbTransferJ3xGalleries;
}(Queue));
var J3xGalleries = /** @class */ (function (_super) {
    __extends(J3xGalleries, _super);
    function J3xGalleries() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    J3xGalleries.prototype.addGalleries = function (galleries) {
        for (var idx = 0; idx < galleries.length; idx++) {
            var gallery = galleries[idx];
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
    };
    return J3xGalleries;
}(Queue));
/*----------------------------------------------------------------

----------------------------------------------------------------*/
// Required gallery ID
function markImages_nGalleryTimes(maxGalleries) {
    // needs ref gallery refstate=active/deactivate)
    // j3x_rows: HTMLTableRowElement []; // = [];
    var j3x_rows;
    var checkbox;
    var galleryId;
    var doCheck = true;
    var galleries = [];
    // let j3x_rows: HTMLElement [] = <HTMLElement []> Array.from(document.getElementsByName("j3x_img_row")));
    j3x_rows = Array.from(document.getElementsByName("j3x_img_row[]"));
    //j3x_images
    j3x_rows.forEach(function (j3x_row) {
        var isMerged = j3x_row.getAttribute("isMerged");
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
var FormElements = /** @class */ (function () {
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
    function FormElements() {
        this.selectGallery = document.getElementById('SelectGallery');
        this.moveImageArea = document.getElementById('moveImageArea');
        //--- Move buttons ----------------------------------------------------
        this.btnTransferByGallery = document.getElementById('moveByGallery');
        this.btnTransferByCheckedGalleries = document.getElementById('moveByCheckedGalleries');
        this.btnTransferAllJ3xImjages = document.getElementById('moveAllJ3xImjages');
        //--- Select next buttons ----------------------------------------------------
        this.btnSelectNextGallery = document.getElementById('selectNextGallery');
        this.btnSelectNextGalleries10 = document.getElementById('selectNextGalleries10');
        this.btnSelectNextGalleries100 = document.getElementById('selectNextGalleries100');
        this.btnSelectNextGallery.onclick = function () { return markImages_nGalleryTimes(1); };
        this.btnSelectNextGalleries10.onclick = function () { return markImages_nGalleryTimes(2); };
        this.btnSelectNextGalleries100.onclick = function () { return markImages_nGalleryTimes(100); };
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
    return FormElements;
}()); // Form Elements
/*----------------------------------------------------------------
handle gallery list
----------------------------------------------------------------*/
var GalleriesListTask = /** @class */ (function () {
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
    function GalleriesListTask(
    //*        selectGallery: HTMLSelectElement,
    formElements, j3xGalleries, requestImageIdsTask) {
        var _this = this;
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
        //        formElements.btnTransferByGallery.onclick = onMoveByGallery();
        formElements.btnTransferByGallery.onclick = function (ev) { return _this.onMoveByGallery(ev, formElements.selectGallery); };
        formElements.btnTransferByCheckedGalleries.onclick = function (ev) { return _this.onMoveByCheckedGalleries(ev); };
        formElements.btnTransferAllJ3xImjages.onclick = function (ev) { return _this.onMoveAllGalleries(ev); };
        //            this.buttonFolderImport.onclick = (ev: DragEvent) => this.onImportFolder(ev);
        //            fileInput.onchange = (ev: DragEvent) => this.onNewFile(ev);
        //            fileZip.onchange = (ev: DragEvent) => this.onZipFile(ev);
    }
    //    onMoveByGallery(ev: MouseEvent) {
    GalleriesListTask.prototype.onMoveByGallery = function (ev, selectGallery) {
        // let element = <HTMLInputElement>ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        // gallery id
        //const gallery_id =  parseInt (selectGallery.value);
        var gallery_id = (Number(selectGallery.value) + 1).toString();
        var gallery_name = selectGallery.selectedOptions[0].text;
        // prevent empty gallery
        if (parseInt(gallery_id) < 1) {
            alert(joomla.JText._('COM_RSGALLERY2_PLEASE_CHOOSE_A_GALLERY_FIRST') + '(5)');
            console.log(">onMoveByGallery: Rejected");
        }
        else {
            console.log(">onMoveByGallery: (" + gallery_id + ") " + "\"" + gallery_name + "\"");
            var imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
            var actGallery = {
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
    };
    GalleriesListTask.prototype.onMoveByCheckedGalleries = function (ev) {
        //let element = <HTMLInputElement>ev.target;
        var _this = this;
        ev.preventDefault();
        ev.stopPropagation();
        console.log(">onMoveByCheckedGalleries: ");
        // all checked elements
        var checkGalleries = document.getElementsByName("cid[]");
        checkGalleries.forEach(function (checkGallery) {
            var element = checkGallery;
            // use checked galleries
            if (element.checked) {
                var gallery_id = element.value; // cb2
                // alert ("galleryId" +  gallery_id)
                var galleryLink = document.getElementById('galleryId_' + gallery_id);
                var gallery_name = galleryLink.innerText;
                var imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
                var actGallery = {
                    galleryId: gallery_id,
                    name: gallery_name,
                    // statusBar: null,
                    imgFlagArea: imgFlagArea,
                    errorZone: null
                };
                _this.j3xGalleries.addGalleries([actGallery]);
            }
        });
        // Start ajax request of DB image reservation
        this.requestImageIdsTask.ajaxRequest();
    };
    GalleriesListTask.prototype.onMoveAllGalleries = function (ev) {
        var _this = this;
        var element = ev.target;
        ev.preventDefault();
        ev.stopPropagation();
        console.log(">onMoveAllGalleries: ");
        // lazy programmers all galleries:
        //     instead of ajax call use all galleries loaded
        // all checked elements
        var checkGalleries = document.getElementsByName("cid[]");
        checkGalleries.forEach(function (checkGallery) {
            var element = checkGallery;
            var gallery_id = element.value; // cb2
            //alert ("galleryId" +  gallery_id)
            var galleryLink = document.getElementById('galleryId_' + gallery_id);
            var gallery_name = galleryLink.innerText;
            var imgFlagArea = document.getElementById('ImgFlagsArea_' + gallery_id);
            var actGallery = {
                galleryId: gallery_id,
                name: gallery_name,
                // statusBar: null,
                imgFlagArea: imgFlagArea,
                errorZone: null
            };
            _this.j3xGalleries.addGalleries([actGallery]);
        });
        // Start ajax request of DB image reservation
        this.requestImageIdsTask.ajaxRequest();
    };
    return GalleriesListTask;
}()); // class GalleriesListTask
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
var RequestImageIdsTask = /** @class */ (function () {
    function RequestImageIdsTask(formElements, 
    // progressArea: HTMLElement,
    // errorZone: HTMLElement,
    j3xGalleries, dbTransferJ3xGalleries, moveImagesTask) {
        this.isBusy = false;
        this.progressArea = formElements.moveImageArea; // progressArea;
        this.errorZone = formElements.moveImageArea; // errorZone;
        this.j3xGalleries = j3xGalleries;
        this.dbTransferJ3xGalleries = dbTransferJ3xGalleries;
        this.moveImagesTask = moveImagesTask;
    }
    // https://taylor.callsen.me/ajax-multi-file-uploader-with-native-js-and-promises/
    // https://makandracards.com/makandra/39225-manually-uploading-files-via-ajax
    // https://www.w3schools.com/js/js_ajax_http.asp
    // http://html5doctor.com/drag-and-drop-to-server/
    // -> resize, exif
    // http://christopher5106.github.io/web/2015/12/13/HTML5-file-image-upload-and-resizing-javascript-with-progress-bar.html
    /**/
    RequestImageIdsTask.prototype.callAjaxRequest = function (j3xGalleries) {
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
                                var msg = 'Error \'on request\' for ' + j3xGalleries.name + ' in DbRequest:\n*'
                                    + 'State: ' + this.status + ' ' + this.statusText + '\n'
                                    + 'responseType: ' + this.responseType + '\n';
                                //alert (msg);
                                console.log(msg);
                                reject(new Error(this.responseText)); // ToDo: check if there is more in this
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
                        data.append('gallery_name', j3xGalleries.name);
                        data.append('gallery_id', j3xGalleries.galleryId);
                        data.append(Token, '1');
                        var urlRequestDbImageId = 'index.php?option=com_rsgallery2&task=MaintenanceJ3x.ajaxRequestImageIds';
                        request.open('POST', urlRequestDbImageId, true);
                        request.onloadstart = function (e) {
                            console.log("      > callAjaxRequest: " + " (" + j3xGalleries.galleryId + ") " + j3xGalleries.name);
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
    RequestImageIdsTask.prototype.ajaxRequest = function () {
        return __awaiter(this, void 0, void 0, function () {
            var AjaxResponse, _loop_1, this_1;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    > ajaxRequest j3xGalleries: " + this.j3xGalleries.length);
                // Already busy
                if (this.isBusy) {
                    return [2 /*return*/];
                }
                this.isBusy = true;
                _loop_1 = function () {
                    var j3xGallery = this_1.j3xGalleries.shift();
                    console.log("   @Request File: " + j3xGallery.name);
                    // // badge gallery start
                    // const startBadge = createIconsBadge (
                    //     ["images"],
                    //     "primary",
                    //     j3xGallery.galleryId);
                    // j3xGallery.imgFlagArea.appendChild(startBadge);
                    /* let request = */
                    //await this.callAjaxRequest(j3xGallery)
                    this_1.callAjaxRequest(j3xGallery)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Request OK: " + j3xGallery.name);
                        console.log("      response: " + JSON.stringify(response).substring(0, 256));
                        var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                        console.log("      response data: " + JSON.stringify(data).substring(0, 256));
                        console.log("      response error/noise: " + JSON.stringify(noise).substring(0, 256));
                        // Json object exist
                        // {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
                        if (data.length) {
                            AjaxResponse = JSON.parse(data);
                        }
                        else {
                            var errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                            j3xGallery.imgFlagArea.appendChild(errorBadge);
                            var serverError = new Error(noise);
                            var errorHtml = ajaxCaughtMessages2Html(serverError, j3xGallery.name);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                            else {
                                var msg = "Error result in ajaxRequestImageIds: Undefined type: \"" + noise + "\"";
                                var msgHtml = ajaxMessages2CardHtml(msg, j3xGallery.name);
                                if (msgHtml) {
                                    _this.errorZone.appendChild(msgHtml);
                                }
                            }
                            return;
                        }
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var dbData = AjaxResponse.data;
                            // let gallery_name = dbData.gallery_name;
                            // let gallery_id = dbData.gallery_id;
                            var image_ids = dbData.image_ids;
                            _this.dbTransferJ3xGalleries.addImages(image_ids, j3xGallery);
                            // badge gallery success
                            // const successBadge = createIconsBadge (
                            //     ["images"],
                            //     "success",
                            //     j3xGallery.galleryId + ': ' + image_ids.length);
                            // j3xGallery.imgFlagArea.appendChild(successBadge);
                            var successBadge = createIconsBadge(["images", "move"], "success", 
                            // j3xGallery.galleryId + ': ' + image_ids.length);
                            image_ids.length.toString());
                            j3xGallery.imgFlagArea.appendChild(successBadge);
                            // ==> Start ajax move of files
                            _this.moveImagesTask.ajaxMove();
                        }
                        else {
                            var errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                            j3xGallery.imgFlagArea.appendChild(errorBadge);
                            if (AjaxResponse.message || AjaxResponse.messages) {
                                var errorHtml = ajaxMessages2Html(AjaxResponse, j3xGallery.name);
                                if (errorHtml) {
                                    _this.errorZone.appendChild(errorHtml);
                                }
                                else {
                                    console.log("      failed data: " + AjaxResponse.data);
                                }
                            }
                            else {
                                // No message given use noise
                                if (noise.length > 0) {
                                    var serverError = new Error(noise);
                                    var errorHtml = ajaxCaughtMessages2Html(serverError, j3xGallery.name);
                                    if (errorHtml) {
                                        _this.errorZone.appendChild(errorHtml);
                                    }
                                }
                                else {
                                    var msg = "Unsuccessful ajax call in ajaxRequestImageIds: Resulting data: " + JSON.stringify(AjaxResponse.data);
                                    var msgHtml = ajaxMessages2CardHtml(msg, j3xGallery.name);
                                    if (msgHtml) {
                                        _this.errorZone.appendChild(msgHtml);
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
                        .catch(function (errText) {
                        var errorBadge = createGalleryErrorBadge(j3xGallery.galleryId);
                        j3xGallery.imgFlagArea.appendChild(errorBadge);
                        console.log("    !!! Error request: " + j3xGallery.name);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCaughtMessages2Html(errText, j3xGallery.name);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        console.log('!!! errText' + errText);
                    });
                    /**/
                    console.log("    <Aj:j3xGalleries: " + this_1.j3xGalleries.length);
                };
                this_1 = this;
                /**/
                while (this.j3xGalleries.length > 0) {
                    _loop_1();
                }
                this.isBusy = false;
                console.log("    <j3xGalleries: " + this.j3xGalleries.length);
                return [2 /*return*/];
            });
        });
    };
    return RequestImageIdsTask;
}());
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
    var imageBadge;
    imageBadge = document.createElement('span');
    // badge
    imageBadge.classList.add('badge');
    imageBadge.classList.add('badge-pill');
    imageBadge.classList.add('bg-' + labelClass);
    // info
    imageBadge.appendChild(document.createTextNode(info));
    imageBadge.appendChild(document.createTextNode(' '));
    // icons
    // for (let icon of this.icons) {
    //     let htmlIcon= document.createElement('i');
    //     htmlIcon.classList.add('icon-' + icon);
    //     imageBadge.appendChild (htmlIcon);
    // }
    for (var _i = 0, icons_1 = icons; _i < icons_1.length; _i++) {
        var icon = icons_1[_i];
        var htmlIcon = document.createElement('i');
        htmlIcon.classList.add('icon-' + icon);
        imageBadge.appendChild(htmlIcon);
    }
    return imageBadge;
}
//---
function badge4imageState(state, imageId, stateId, imgFlagArea) {
    // primary secondary success danger warning info light dark
    var stateBadge;
    //const labelClass:string = "info";
    var labelClass = "secondary";
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
    var errorHtml = null;
    // remove unnecessary HTML
    var _a = separateErrorAndAlerts(errText.message), errorPart = _a[0], alertPart = _a[1];
    // console.log("      response error: " + JSON.stringify(errorPart));
    // console.log("      response noise: " + JSON.stringify(alertPart));
    if (errorPart || alertPart) {
        //--- bootstrap card as title ---------------------------
        var errorCardHtml = document.createElement('div');
        errorCardHtml.classList.add('card', 'errorContent');
        var errorCardHeaderHtml = document.createElement('div');
        errorCardHeaderHtml.classList.add('card-header');
        var errorCardHeaderTitle = document.createElement('h3');
        errorCardHeaderTitle.appendChild(document.createTextNode(title));
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
// ToDo: call title and body from  seperate functions see above
function ajaxMessages2CardHtml(errText, title) {
    var errorHtml = null;
    //--- bootstrap card as title ---------------------------
    var errorCardHtml = document.createElement('div');
    errorCardHtml.classList.add('card', 'errorContent');
    var errorCardHeaderHtml = document.createElement('div');
    errorCardHeaderHtml.classList.add('card-header');
    var errorCardHeaderTitle = document.createElement('h3');
    errorCardHeaderTitle.appendChild(document.createTextNode(title));
    errorCardHeaderHtml.appendChild(errorCardHeaderTitle);
    errorCardHtml.appendChild(errorCardHeaderHtml);
    //--- bootstrap card body ---------------------------
    if (errText.length > 0) {
        var errorCardBodyHtml = document.createElement('div');
        errorCardBodyHtml.classList.add('card-body');
        errorCardHtml.appendChild(errorCardBodyHtml);
        var errorCardErrorPart = document.createElement('div');
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
var MoveImagesTask = /** @class */ (function () {
    function MoveImagesTask(formElements, 
    // imagesAreaList: HTMLElement,
    // progressArea: HTMLElement,
    // errorZone: HTMLElement,
    dbTransferJ3xGalleries) {
        this.isBusyCount = 0;
        this.BusyCountLimit = 5;
        // this.imagesAreaList = imagesAreaList;
        // this.progressArea = progressArea;
        this.errorZone = formElements.moveImageArea; // errorZone;
        this.dbTransferJ3xGalleries = dbTransferJ3xGalleries;
    }
    MoveImagesTask.prototype.callAjaxMove = function (j3xImage2Move) {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                console.log("      in callAjaxMove: " + j3xImage2Move.name);
                console.log("      > callAjaxMove: " + j3xImage2Move.name);
                return [2 /*return*/, new Promise(function (resolve, reject) {
                        var request = new XMLHttpRequest();
                        request.onload = function () {
                            if (this.status === 200) {
                                // attention joomla may send error data on this channel
                                resolve(this.response);
                            }
                            else {
                                var msg = 'Error over \'on load\' for ' + j3xImage2Move.name + ' in Move:\n*'
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
                        var urlMoveImage = 'index.php?option=com_rsgallery2&task=MaintenanceJ3x.ajaxMoveJ3xImage';
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
                    })];
            });
        });
    };
    /**/
    MoveImagesTask.prototype.ajaxMove = function () {
        return __awaiter(this, void 0, void 0, function () {
            var AjaxResponse, _loop_3, this_2;
            var _this = this;
            return __generator(this, function (_a) {
                console.log("    >this.dbTransferJ3xGalleries.length: " + this.dbTransferJ3xGalleries.length);
                _loop_3 = function () {
                    this_2.isBusyCount++;
                    var j3xImage = this_2.dbTransferJ3xGalleries.shift();
                    console.log("   @Move File: " + j3xImage.name);
                    // badge image start
                    var startBadge = createIconsBadge(["arrow-right-4"], "primary", j3xImage.id);
                    j3xImage.imgFlagArea.appendChild(startBadge);
                    //
                    this_2.callAjaxMove(j3xImage)
                        .then(function (response) {
                        // attention joomla may send error data on this channel
                        console.log("   <Move OK: " + j3xImage.name);
                        console.log("       response: " + JSON.stringify(response).substring(0, 256));
                        var _a = separateDataAndNoise(response), data = _a[0], noise = _a[1];
                        console.log("      response data: " + JSON.stringify(data).substring(0, 256));
                        console.log("      response error/noise: " + JSON.stringify(noise).substring(0, 256));
                        // Json object exist
                        // {\"success\":true,\"message\":\"Copied \",\"messages\":null,\"data\":.....
                        if (data.length) {
                            AjaxResponse = JSON.parse(data);
                        }
                        else {
                            console.log("      data length: " + 0);
                            var errorBadge = createImageErrorBadge(j3xImage.id);
                            j3xImage.imgFlagArea.appendChild(errorBadge);
                            var serverError = new Error(noise);
                            var errorHtml = ajaxCaughtMessages2Html(serverError, j3xImage.name);
                            if (errorHtml) {
                                _this.errorZone.appendChild(errorHtml);
                            }
                            else {
                                var msg = "Error result in ajaxRequestImageIds: Undefined type: \"" + noise + "\"";
                                var msgHtml = ajaxMessages2CardHtml(msg, j3xImage.name);
                                if (msgHtml) {
                                    _this.errorZone.appendChild(msgHtml);
                                }
                            }
                            return;
                        }
                        if (AjaxResponse.success) {
                            console.log("      success data: " + AjaxResponse.data);
                            var moveData = AjaxResponse.data;
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
                                var successBadge = createImageFinishedBadge(j3xImage.id);
                                j3xImage.imgFlagArea.appendChild(successBadge);
                            }
                            else {
                                // badge4imageState (parseInt (moveData.state_thumb), j3xImage.id, 'A', j3xImage.imgFlagArea);
                                // badge gallery failed
                                var errorBadge = createImageErrorBadge(j3xImage.id);
                                j3xImage.imgFlagArea.appendChild(errorBadge);
                            }
                        }
                        else {
                            var errorBadge = createImageErrorBadge(j3xImage.id);
                            j3xImage.imgFlagArea.appendChild(errorBadge);
                            if (AjaxResponse.message || AjaxResponse.messages) {
                                var errorHtml = ajaxMessages2Html(AjaxResponse, j3xImage.name);
                                if (errorHtml) {
                                    _this.errorZone.appendChild(errorHtml);
                                }
                                else {
                                    console.log("      failed data: " + AjaxResponse.data);
                                }
                            }
                            else {
                                // No message given use noise
                                if (noise.length > 0) {
                                    var serverError = new Error(noise);
                                    var errorHtml = ajaxCaughtMessages2Html(serverError, j3xImage.name);
                                    if (errorHtml) {
                                        _this.errorZone.appendChild(errorHtml);
                                    }
                                }
                                else {
                                    var msg = "Unsuccessful ajax call in ajaxRequestImageIds: Resulting data: " + JSON.stringify(AjaxResponse.data);
                                    var msgHtml = ajaxMessages2CardHtml(msg, j3xImage.name);
                                    if (msgHtml) {
                                        _this.errorZone.appendChild(msgHtml);
                                    }
                                }
                            }
                        }
                    })
                        .catch(function (errText) {
                        var errorBadge = createImageErrorBadge(j3xImage.id);
                        j3xImage.imgFlagArea.appendChild(errorBadge);
                        console.log("    !!! Error move: " + j3xImage.id);
                        //                  alert ('errText' + errText);
                        //console.log("        error: " + JSON.stringify(errText));
                        console.log("        error: " + errText);
                        console.log("        error.name: " + errText.name);
                        console.log("        error.message: " + errText.message);
                        var errorHtml = ajaxCaughtMessages2Html(errText, j3xImage.name);
                        if (errorHtml) {
                            _this.errorZone.appendChild(errorHtml);
                        }
                        console.log('!!! errText' + errText);
                    })
                        .finally(function () {
                        _this.isBusyCount--;
                        _this.ajaxMove();
                    });
                };
                this_2 = this;
                // check for busy
                while (this.isBusyCount < this.BusyCountLimit
                    && this.dbTransferJ3xGalleries.length > 0) {
                    _loop_3();
                }
                console.log("    <this.dbTransferJ3xGalleries.length: " + this.dbTransferJ3xGalleries.length);
                return [2 /*return*/];
            });
        });
    };
    return MoveImagesTask;
}());
function AssignCheckBoxEvents() {
    //let checkAllToogle: HTMLInputElement;    // checkall-toggle
    //let checkGalleries: HTMLInputElement []; // cid[]
    var checkbox;
    var moveByCheckedGalleries = document.getElementById("moveByCheckedGalleries");
    //--- handle "check all" on/off ----------------------------------------
    var checkAllToogle = (document.getElementsByName("checkall-toggle")[0]);
    checkAllToogle.addEventListener("click", function (event) {
        var element = event.target;
        moveByCheckedGalleries.disabled = !element.checked;
        //        alert ('checked: ' + element.checked)
        //        alert("==> checkbox gallery");
    });
    //--- handle checked on/off (gallery line) ----------------------------
    var checkGalleries = document.getElementsByName("cid[]");
    checkGalleries.forEach(function (check) {
        var checkbox = check;
        checkbox.addEventListener("click", function (event) {
            var element = event.target;
            // On checked enable button else ... others
            moveByCheckedGalleries.disabled = !element.checked;
            // on uncheck find other checked item then enable
            if (!element.checked) {
                checkGalleries.forEach(function (checkGallery) {
                    var other = checkGallery;
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
    var elements = new FormElements();
    // Exit if no galleries are selectable
    if (!elements.selectGallery) {
        return;
    }
    // Reserve list for galleries and images
    var dbTransferJ3xGalleries = new dbTransferJ3xGalleries();
    var j3xGalleries = new J3xGalleries();
    // assign click event for check boxes
    AssignCheckBoxEvents();
    // (3) ajax request: Move file to server
    var moveImagesTask = new MoveImagesTask(elements, dbTransferJ3xGalleries);
    // (2) ajax request: database
    var requestImageIdsTask = new RequestImageIdsTask(elements, j3xGalleries, dbTransferJ3xGalleries, moveImagesTask);
    //                                                   j3xGalleries, dbTransferJ3xGalleries, moveImagesTask);
    // (1) collect galleries, start request galleries from DB
    var galleriesListTask = new GalleriesListTask(elements, j3xGalleries, requestImageIdsTask);
});
