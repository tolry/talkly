import React from "react";
import {Router, Route, IndexRoute, hashHistory} from "react-router";
import Index from "./pages/Index";

export default (
    <Router history={hashHistory}>
        <Route path="/" component={Index}/>
    </Router>
);