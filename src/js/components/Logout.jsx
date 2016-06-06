import React from "react";

import UserProvider from "../services/UserProvider";
import {hashHistory} from "react-router";

export default class Logout extends React.Component {
    logout() {
        UserProvider.clear();
        hashHistory.push('/login');
    }

    render() {
        return (
          <a onClick={this.logout}>{this.props.children}</a>
        );
    }
}