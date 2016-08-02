import React from "react";
import Loading from "../Loading/Loading";
import Form from "./ProfileForm";
import Client from "../../services/Client";
import History from "../../services/History";
import MessageBag from "../../services/MessageBag";
import Gravatar from "./Gravatar";
import User from "./User";
import AuthStorage from "../../services/AuthorizationStorage";

export default class EditProfile extends React.Component {
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


    submit(data) {
        Client.post('/api/user/' + this.id + '/edit', data).then((response) => {
            MessageBag.success('Success');
            AuthStorage.setUser(response.data);
            History.push('/user/' + this.id);
        });
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
                    <Form data={this.state.data} submit={this.submit.bind(this)}/>
                </div>
            </div>
        );
    }
}