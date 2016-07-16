import React from "react";

export default ({children}) => {
    return (
        <header>
            <div className="row">
                <div className="large-12 columns text-center">
                    <h1><i className="fa fa-bullhorn"/> talkly</h1>

                    <h2>
                        <small>request, vote &amp; deliver the talks you like to hear</small>
                    </h2>
                </div>
            </div>
        </header>
    );
}