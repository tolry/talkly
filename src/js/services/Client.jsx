import axios from 'axios';
import {hashHistory} from "react-router";
import UserProvider from "./UserProvider";

var instance = axios.create({
    baseURL: window.location.pathname,
    timeout: 5000
});

instance.interceptors.request.use(function (config) {
    if (UserProvider.getToken()) {
        config.headers.Authorization = 'Bearer ' + UserProvider.getToken();
    }

    return config;

}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

instance.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if(error.status == 401) {
        console.log('401 error response');

        hashHistory.push('/login');
    } else if(error.status == 403) {
        console.log('403 error response');
        // 403 forbidden. The user is logged in but doesn't have permission for the request.
        // logout and redirect to login page.
    }

    return Promise.reject(error);
});

export default instance;