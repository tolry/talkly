import React from "react";
import User from "../User/User";
import Gravatar from "../User/Gravatar";

export default ({children}) => {

    let speakers = null;

    if (children.length == 0) {
        speakers = <div className="panel">no speakers volunteered yet</div>;
    } else {
        speakers = children.map((speaker) => {
            return (
                <div className="text-center" key={speaker.id}>
                    <Gravatar size={96} className="th">{speaker.email}</Gravatar>
                    <br/>
                    <p className="one-line"><User>{speaker}</User></p>
                </div>
            );
        });
    }

    return (
        <div>
            <h4>Speaker(s)</h4>
            <hr/>
            {speakers}
        </div>
    );
}
