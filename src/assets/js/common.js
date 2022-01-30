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