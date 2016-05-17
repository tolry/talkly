import React from "react";
import crypto from "crypto";

const URL = 'http://www.gravatar.com/avatar/';

export default class Gravatar extends React.Component {
    constructor(props) {
        super(props);

        this.email = props.children.trim().toLowerCase();
        this.size = props.size || 80;

        this.url = URL + crypto.createHash('md5').update(this.email).digest("hex") + "?s=" + this.size;
    }

    render() {
        return (
            <img src={this.url} alt={this.email}/>
        );
    }
}