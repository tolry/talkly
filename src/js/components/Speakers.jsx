import React from "react";
import User from "./User";

export default ({children}) => {

    var html;

    if (children.length > 0) {

        var users = children.slice(0, 4);

        html = users.map(function (el) {
            return (
                <li>
                    <User key={el.id}>{el}</User>
                </li>
            );
        });

        if (children.length > 4) {
            html.push(<li key="more">...</li>);
        }

    } else {
        html = <li key="empty">no speaker yet</li>
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