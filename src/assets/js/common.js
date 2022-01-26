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