import { checkCpf } from './validators';

export function applyMaskCpf(value) {
    let v = value.replace(/\D/g, '').slice(0, 11);
    if (v.length > 9)      v = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6,9) + '-' + v.slice(9);
    else if (v.length > 6) v = v.slice(0,3) + '.' + v.slice(3,6) + '.' + v.slice(6);
    else if (v.length > 3) v = v.slice(0,3) + '.' + v.slice(3);
    return v;
}

export function applyMaskPhone(value) {
    let v = value.replace(/\D/g, '').slice(0, 11);
    if (v.length >= 11)    return '(' + v.slice(0,2) + ') ' + v.slice(2,7) + '-' + v.slice(7,11);
    if (v.length > 6)      return '(' + v.slice(0,2) + ') ' + v.slice(2,6) + '-' + v.slice(6);
    if (v.length > 2)      return '(' + v.slice(0,2) + ') ' + v.slice(2);
    if (v.length > 0)      return '(' + v;
    return v;
}

export function inputMasks() {
    return {
        cpfError: '',
        maskCpf(e)     { e.target.value = applyMaskCpf(e.target.value); },
        maskPhone(e)   { e.target.value = applyMaskPhone(e.target.value); },
        validateCpf(e) { this.cpfError = checkCpf(e.target.value); },
    };
}
