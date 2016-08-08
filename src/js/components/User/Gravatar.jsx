import React from "react";
import crypto from "crypto";

const URL = '//www.gravatar.com/avatar/';

export default class Gravatar extends React.Component {
    render() {

        let email = this.props.children ? this.props.children.trim().toLowerCase() : '';
        let size = this.props.size || 80;
        let fallback = this.props.default || "identicon";

        let url = URL + crypto.createHash('md5').update(email).digest("hex") + "?s=" + size + "&d=" + fallback;

        return <img src={url} alt={email} className={this.props.className}/>;
    }
}