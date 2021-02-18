/**/
export class Queue {
    constructor() {
        this._store = [];
    }
    push(val) { this._store.push(val); }
    shift() { return this._store.shift(); }
    get length() { return this._store.length; }
    isEmpty() { return this._store.length == 0; }
    isPopulated() { return this._store.length > 0; }
}
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
