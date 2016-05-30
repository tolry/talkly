import React from "react";
import User from "./User";

export default ({children}) => {

    var html, css;

    if (children.length > 0) {
        var users = children.map(function(el) {
            return el.username;
        });

        html = users.join();
        css = "text-highlight";
    } else {
        html = "no speaker yet";
        css = "";
    }

    return (
        <span className={css}>
            <i className="fa fa-microphone"/>&nbsp;
                {html}
        </span>
    );
}
