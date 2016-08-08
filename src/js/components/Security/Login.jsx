import React from "react";
import Client from "../../services/Client";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import History from "../../services/History";
import LoginForm from "./LoginForm";
import {auth} from "../../services/Parameters";

export default class Index extends React.Component {
    submit(data) {
        Client.post('/api/login', {
            username: data.username,
            password: data.password
        }).then(function (response) {
            AuthorizationStorage.setToken(response.data.token);

            Client.get('/api/user/current').then(function (response) {
                AuthorizationStorage.setUser(response.data);

                if (response.data.name && response.data.email) {
                    History.push('/');
                } else {
                    History.push('/user/' + response.data.id + "/edit");
                }
            }).catch(function () {
                AuthorizationStorage.clear();
            });

        }).catch(function () {
            AuthorizationStorage.clear();
        });
    }

    componentDidMount() {
        if (auth === 'ntlm') {
            this.submit({
                username: null,
                password: null
            });
        }
    }

    render() {
        if (auth === 'ntlm') {
            return null;
        }

        return (
            <main>
                <div className="row">
                    <div className="medium-6 medium-centered large-4 large-centered columns">
                        <LoginForm submit={this.submit.bind(this)}/>
                    </div>
                </div>
            </main>
        );
    }
}
