import React from "react";
import User from "../User/User";

export default ({children}) => {
    let users = children.map(function (user) {
        return (
            <li key={user.id}>
                <User>{user}</User>
            </li>
        );
    });

    if (users.length == 0) {
        users.push(<li>no speaker yet</li>);
    }

    return (
        <span className={children.length ? 'text-highlight' : ''}>
            <i className="fa fa-microphone"/>
            <ul className="taglist">
                {users}
            </ul>
        </span>
    );
}
