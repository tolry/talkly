import React from "react";
import { Link } from 'react-router'
import UserProvider from "../services/UserProvider";

export default ({children}) => {
    var user = UserProvider.getUser();

    return (
        <nav className="fixed" headroom>
            <div className="row">
                <ul className="text-center">
                    <li className="small-3 columns">
                        <Link to="/" activeClassName="active" onlyActiveOnIndex={true}>
                            <i className="fa fa-home" />
                            <span><span className="hide-for-small-only"> Home</span></span>
                        </Link>
                    </li>
                    <li className="small-3 columns">
                        <Link to="/calendar" activeClassName="active">
                            <i className="fa fa-calendar" />
                            <span><span className="hide-for-small-only"> Calendar</span></span>
                        </Link>
                    </li>
                    <li className="small-3 columns">
                        <Link to="/archive" activeClassName="active">
                            <i className="fa fa-file-archive-o" />
                            <span><span className="hide-for-small-only"> Archive</span></span>
                        </Link>
                    </li>
                    <li className="small-3 columns one-line">
                        <Link to="/profile" activeClassName="active">
                            <i className="fa fa-user" />
                            <span><span className="hide-for-small-only"> {user ? user.name || user.username : ''}</span></span>

                            <span className="label info round" data-tooltip
                                  title="xx app.user.unreadNotifications|length xx unread notification(s)">
                                1
                            </span>
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>
    );
}
