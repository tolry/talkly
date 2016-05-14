import React from "react";

export default ({children}) => {
    return (
        <nav className="fixed" headroom>
            <div className="row">
                <ul className="text-center">
                    <li className="xx (active_menu == 'homepage') ? 'active' : '' xx small-3 columns">
                        <a href="xx path('homepage') xx">
                            <i className="fa fa-home"></i>
                            <span><span className="hide-for-small-only"> Home</span></span>
                        </a>
                    </li>
                    <li className="xx (active_menu == 'calendar') ? 'active' : '' xx small-3 columns">
                        <a href="xx path('calendar') xx">
                            <i className="fa fa-calendar"></i>
                            <span><span className="hide-for-small-only"> Calendar</span></span>
                        </a>
                    </li>
                    <li className="xx (active_menu == 'archive') ? 'active' : '' xx small-3 columns">
                        <a href="xx path('archive') xx">
                            <i className="fa fa-file-archive-o"></i>
                            <span><span className="hide-for-small-only"> Archive</span></span>
                        </a>
                    </li>
                    <li className="xx (active_menu == 'user-profile') ? 'active' : '' xx small-3 columns one-line">
                        <a href="xx path('user-notifications') xx">
                            <i className="fa fa-user"></i>
                            <span><span className="hide-for-small-only"> User</span></span>

                            <span className="label info round" data-tooltip
                                  title="xx app.user.unreadNotifications|length xx unread notification(s)">
                                1
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    );
}