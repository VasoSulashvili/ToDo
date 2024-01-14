const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
const baseUrl = document.querySelector('meta[name="base-url"]').content + '/api/';

export { csrf_token, baseUrl };
