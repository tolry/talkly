import "babel-polyfill";
import React from "react";
import {Router, Route, IndexRoute, hashHistory} from "react-router";
import Layout from "./Layout";
import Login from "../Security/Login";
import Index from "../Topic/Index";
import Calendar from "../Topic/Calendar";
import Archive from "../Topic/Archive";
import ShowTopic from "../Topic/ShowTopic";
import Profile from "../User/Profile";

export default () => {
    return (
        <Router history={hashHistory}>
            <Route name="/login" path="login" component={Login}/>
            <Route path="/" component={Layout}>
                <IndexRoute component={Index}/>
                <Route name="profile" path="profile" component={Profile}/>
                <Route name="calendar" path="calendar" component={Calendar}/>
                <Route name="archive" path="archive" component={Archive}/>
                <Route name="show" path=":id/show" component={ShowTopic}/>
            </Route>
        </Router>
    );
}