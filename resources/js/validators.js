export function checkCpf(value) {
    const raw = value.replace(/\D/g, '');
    if (!raw) return '';
    if (raw.length !== 11 || /^(\d)\1{10}$/.test(raw)) return 'CPF inválido.';
    let s = 0;
    for (let i = 0; i < 9; i++) s += parseInt(raw[i]) * (10 - i);
    const d1 = (s * 10) % 11 >= 10 ? 0 : (s * 10) % 11;
    s = 0;
    for (let i = 0; i < 10; i++) s += parseInt(raw[i]) * (11 - i);
    const d2 = (s * 10) % 11 >= 10 ? 0 : (s * 10) % 11;
    return (d1 === parseInt(raw[9]) && d2 === parseInt(raw[10])) ? '' : 'CPF inválido.';
}
