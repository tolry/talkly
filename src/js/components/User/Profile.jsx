import React from "react";
import { Link } from 'react-router'
import Loading from "../Loading/Loading";
import Client from "../../services/Client";
import Notifications from "./Notifications";
import Logout from "../Security/Logout";
import Gravatar from "./Gravatar";
import User from "./User";
import AuthStorage from "../../services/AuthorizationStorage";

export default class Profile extends React.Component {
    constructor(props) {
        super(props);

        this.id = this.props.params.id;

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        Client.get('/api/user/' + this.id).then(function (response) {
            this.setState({
                loading: false,
                data: response.data
            });
        }.bind(this));
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return (
            <div className="row">
                <div className="small-12 large-3 columns">
                    <div>
                        <div className="text-center">
                            <Gravatar size={96} className="th">{this.state.data.email}</Gravatar>
                            <br/>
                            <p className="one-line"><User>{this.state.data}</User></p>
                        </div>
                    </div>
                </div>

                <div className="small-12 large-9 columns">
                    {this.renderContent()}
                </div>
            </div>
        );
    }

    renderContent() {
        if (AuthStorage.getUser().id != this.state.data.id) {
            return (
                <div>nothing to see...</div>
            );
        }

        return (
            <div>
                <div className="text-right">
                    <Link to={"/user/" + this.state.data.id + "/edit"}>
                        edit profile
                    </Link>
                    &nbsp;|&nbsp;
                    <Logout>logout</Logout>
                </div>

                <br/>

                <Notifications/>
            </div>
        );
    }
}