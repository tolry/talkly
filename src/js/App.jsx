import "babel-polyfill";
import React from "react";
import ReactDOM from "react-dom";
import {Router, Route, IndexRoute, hashHistory} from "react-router";
import Layout from "./Layout";
import Index from "./pages/Index";
import Login from "./pages/Login";
import Profile from "./pages/Profile";
import ShowTopic from "./pages/ShowTopic";

// ID of the DOM element to mount app on
const DOM_APP_EL_ID = 'app';

// Render the router
ReactDOM.render((
    <Router history={hashHistory}>
        <Route name="/login" path="login" component={Login}/>
        <Route path="/" component={Layout}>
            <IndexRoute component={Index}/>
            <Route name="show" path=":id/show" component={ShowTopic}/>
            <Route name="profile" path="profile" component={Profile}/>
        </Route>
    </Router>
), document.getElementById(DOM_APP_EL_ID));
