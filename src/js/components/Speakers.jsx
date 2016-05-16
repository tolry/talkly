import React from "react";
import User from "./User";

export default ({children}) => {

    var html;

    if (children.length > 0) {

        var users = children.slice(0, 4);

        html = users.map(function (el) {
            return (
                <li>
                    <User>{el}</User>
                </li>
            );
        });

        if (children.length > 4) {
            html.push(<li>...</li>);
        }

    } else {
        html = <li>no speaker yet</li>
    }

    return (
        <span>
            <i className="fa fa-microphone"/>
            <ul>
                {html}
            </ul>
        </span>
    );
}