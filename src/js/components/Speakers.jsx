import React from "react";
import User from "./User";

export default ({children}) => {
    let users = children.map(function (el) {
        return (
            <li>
                <User>{el}</User>
            </li>
        );
    });

    return (
        <span className={children.length ? 'text-highlight' : ''}>
            <i className="fa fa-microphone"/>
            <ul className="taglist">
                {users}
            </ul>
        </span>
    );
}
