const el = document.getElementById('app');

export const base = el.getAttribute('data-base');
export const token = el.getAttribute('data-token');

export default {
    base: base,
    token: token
};