import React from "react";
import Client from "../../services/Client";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import {hashHistory} from "react-router";


export default class Index extends React.Component {
    submit(event) {
        event.preventDefault();

        Client.post('/login_check', {
            username: this.form.username.value,
            password: this.form.password.value
        }).then(function (response) {
            AuthorizationStorage.setToken(response.data.token);

            Client.get('/user/current').then(function (response) {
                AuthorizationStorage.setUser(response.data);
                hashHistory.push('/');
            }).catch(function (response) {
                AuthorizationStorage.clear();
            });

        }).catch(function (response) {
            AuthorizationStorage.clear();
        });
    }

    render() {
        return (
            <main>
                <div className="row">
                    <div className="medium-6 medium-centered large-4 large-centered columns">

                        <form onSubmit={this.submit.bind(this)} ref={(node) => this.form = node}>
                            <div className="row column log-in-form">
                                <h4 className="text-center">Log in with you email account</h4>
                                <label htmlFor="username">Username / E-Mail:</label>
                                <input type="text" id="username" name="_username"/>

                                <label htmlFor="password">Password:</label>
                                <input type="password" id="password" name="_password"/>

                                <button type="submit">Log In</button>
                            </div>
                        </form>

                    </div>
                </div>
            </main>
        );
    }
}
