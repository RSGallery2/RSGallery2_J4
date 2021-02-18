
/**/
export class Queue<T> {
    private _store: T[] = [];
    push(val: T) { this._store.push(val); }
    shift(): T | undefined { return this._store.shift(); }
    get length():number{ return this._store.length; }
    isEmpty():boolean {return this._store.length == 0;}
    isPopulated():boolean {return this._store.length > 0; }
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


console.log ("why not");

