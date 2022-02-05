export function clone(obj) {
    return Object.assign({},obj);
}

export function toFormData(data){
    let formData = new FormData();
    for (const [key, value] of Object.entries(data)) {   
        formData.append(key, value)
    }
    return formData;
}

export function copyToClipboard(text) {
    var input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
 }

 export function isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}

export function randomHash(length){
    return [...Array(length)].map(() => Math.random().toString(36)[2]).join('');
}