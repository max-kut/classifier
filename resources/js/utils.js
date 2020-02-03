export const deepEqual = (a, b) => {
    if (a instanceof Number || a instanceof String || a instanceof Boolean || a instanceof Date) {
        a = a.valueOf();
    }
    if (b instanceof Number || b instanceof String || b instanceof Boolean || b instanceof Date) {
        b = b.valueOf();
    }

    if (a === b) {
        return true;
    }

    if (a == null || typeof a != "object" || b == null || typeof b != "object") {
        return false;
    }

    if (Object.keys(a).length !== Object.keys(b).length) {
        return false;
    }

    for (let prop in b) {
        if (!(prop in a) || !deepEqual(a[prop], b[prop])) {
            return false;
        }
    }

    return true;
};

export const strStudly = str => {
    return str.replace(/[-_]/g, ' ')
        .split(' ')
        .map(s=>(s.charAt(0).toUpperCase() + s.substr(1).toLowerCase()))
        .join('')
};
