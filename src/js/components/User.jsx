import React from "react";

export default class User extends React.Component {
    render() {
        return (<span>{this.props.children.name}</span>);
    }
}