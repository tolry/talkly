import React from "react";
import crypto from "crypto";

const URL = 'http://www.gravatar.com/avatar/';

export default class Gravatar extends React.Component {
    render() {
        let email = this.props.children.trim().toLowerCase();
        let size = this.props.size || 80;

        let url = URL + crypto.createHash('md5').update(email).digest("hex") + "?s=" + size;

        return <img src={url} alt={email}/>;
    }
}