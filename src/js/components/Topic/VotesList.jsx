import React from "react";
import User from "../User/User";
import Gravatar from "../User/Gravatar";

export default ({children}) => {

    let votes = null;

    if (children.length == 0) {
        votes = <div className="panel">there are no votes for this talk yet</div>;
    } else {
        votes = children.map((vote) => {
            return (
                <p className="one-line" key={vote.id}>
                    <Gravatar size={16} className="th">{vote.email}</Gravatar>
                    &nbsp;
                    <User>{vote}</User>
                </p>
            );
        });
    }

    return (
        <div>
            <h4>Vote(s)</h4>
            <hr/>
            {votes}
        </div>
    );
}
