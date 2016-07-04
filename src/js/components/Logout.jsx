import React from "react";

import AuthorizationStorage from "../services/AuthorizationStorage";
import {hashHistory} from "react-router";

export default class Logout extends React.Component {
    logout() {
        AuthorizationStorage.clear();
        hashHistory.push('/login');
    }

    render() {
        return (
          <a onClick={this.logout}>{this.props.children}</a>
        );
    }
}