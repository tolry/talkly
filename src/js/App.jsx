import 'babel-polyfill';
import React from 'react';
import ReactDOM from 'react-dom';
import {Router, Route, IndexRoute, hashHistory} from "react-router";
import Layout from "./Layout";
import Index from "./pages/Index";

// ID of the DOM element to mount app on
const DOM_APP_EL_ID = 'app';

// Render the router
ReactDOM.render((
    <Router history={hashHistory}>
        <Route path="/" component={Layout}>
            <IndexRoute component={Index} />
        </Route>
    </Router>
), document.getElementById(DOM_APP_EL_ID));
