"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Queue = void 0;
/**/
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
exports.Queue = Queue;
/**/
/**
export class Stack<T> {
    _store: T[] = [];
    push(val: T) { this ._store.push(val); }
    pop(): T | undefined { return this ._store.pop(); }
    length():number{ return this._store.length; }
    isEmpty():boolean {return this._store.length == 0;}
    isPopulated():boolean {return this._store.length > 0; }
}
 /**/
console.log("why not");
