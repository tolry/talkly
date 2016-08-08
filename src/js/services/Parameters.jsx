const el = document.getElementById('app');

export const base = el.getAttribute('data-base');
export const auth = el.getAttribute('data-auth');

export default {
    base: base,
    auth: auth
};