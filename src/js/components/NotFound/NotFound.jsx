import React from "react";
import {Link} from "react-router";

export default ({children}) => {
    return (
        <div style={{
            background: '#fff',
            textAlign: 'center',
            paddingTop: 50,
            paddingBottom: 50
        }}>
            <div className="row">
                <div className="small-12">
                    <h1>Page not found!</h1>
                    <p>
                        Sorry, your requested page was not found :-(
                    </p>
                    <Link to="/">Back to overview</Link>
                </div>
            </div>
        </div>
    );
}