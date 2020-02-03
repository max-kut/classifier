import cloneDeep from 'lodash/cloneDeep.js'
import {deepEqual} from "~/utils";

export default class Phrase {
    constructor(model = {}) {
        Object.defineProperty(this, '_original', {
            value: cloneDeep(model),
            enumerable: false
        });
        Object.defineProperty(this, '_attributes', {
            value: cloneDeep(model),
            enumerable: false
        });

        for (let k in model) {
            Object.defineProperty(this, k, {
                enumerable: true,
                configurable: true,
                get(){
                    return this._attributes[k] || null;
                },
                set(v) {
                    this._attributes[k] = v;
                }
            })
        }

        Object.defineProperty(this, '_isDirty', {
            enumerable: true,
            configurable: true,
            get(){
                return !deepEqual(this._original, this._attributes);
            },
        });
    }

    clone(){
        return new Phrase(this._attributes)
    }

    toRequestObject(){
        return this._attributes;
    }
}
