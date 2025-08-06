/**
 * @package        RSGallery2
 * @subpackage     com_rsgallery2
 * @author         RSGallery2 Team <team2@rsgallery2.org>
 * @copyright  (c) 2016-2025 RSGallery2 Team
 * @license        GNU General Public License version 2 or later
 */

/*----------------------------------------------------------------

   supports
   @since       5.0.0.4
----------------------------------------------------------------*/

/**/
export class Queue<T> {
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

