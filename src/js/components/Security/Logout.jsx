import React from "react";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import History from "../../services/History";

export default class Logout extends React.Component {
    logout() {
        AuthorizationStorage.clear();
        History.push('/login');
    }

    render() {
        return (
          <a onClick={this.logout}>{this.props.children}</a>
        );
    }
}