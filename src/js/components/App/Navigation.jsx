import React from "react";
import { Link } from 'react-router'
import AuthorizationStorage from "../../services/AuthorizationStorage";

export default class Navigation extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            user: AuthorizationStorage.getUser()
        };

        this.listener = () => this.update();
    }

    componentDidMount() {
        AuthorizationStorage.subscribe(this.listener);
    }

    componentWillUnmount() {
        AuthorizationStorage.unsubscribe(this.listener);
    }

    update() {
        this.setState({
            user: AuthorizationStorage.getUser()
        });
    }

    render() {
        return (
            <nav className="fixed">
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
                            <Link to={this.state.user ? "/user/" + this.state.user.id : ''} activeClassName="active">
                                <i className="fa fa-user" />&nbsp;
                                <span><span className="hide-for-small-only">{this.state.user ? this.state.user.name || this.state.user.username : ''}</span></span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
        );
    }
}
