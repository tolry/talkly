import axios from 'axios';
import history from "./History";
import {base} from "./Parameters";
import AuthorizationStorage from "./AuthorizationStorage";
import MessageBag from "./MessageBag";

var instance = axios.create({
    baseURL: base,
    timeout: 5000
});

instance.interceptors.request.use(function (config) {
    let token = AuthorizationStorage.getToken();

    if (token) {
        config.headers.Authorization = 'Bearer ' + token;
    }

    return config;

}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

instance.interceptors.response.use(function (response) {
    if (response.headers["content-type"].indexOf('json') === -1) {
        MessageBag.error('server error');
        throw "wrong content, json expected!";
    }

    return response;
}, function (error) {
    if(error.status == 401) {
        console.log('401 error response');

        AuthorizationStorage.clear();
        history.push('/login');
    } else if(error.status == 403) {
        console.log('403 error response');
        // 403 forbidden. The user is logged in but doesn't have permission for the request.
        // logout and redirect to login page.
    }

    return Promise.reject(error);
});

export default instance;