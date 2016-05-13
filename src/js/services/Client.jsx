import axios from 'axios';

var instance = axios.create({
    baseURL: window.location.pathname,
    timeout: 5000,
    headers: {'X-Custom-Header': 'foobar'}
});

export default instance;