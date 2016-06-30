import React from "react";
import Client from "../services/Client";
import TokenStorage from "../services/TokenStorage";
import {hashHistory} from "react-router";


export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: []
        };
    }

    submit(event) {
        event.preventDefault();

        Client.post('/login_check', {
            username: this.refs.username.value,
            password: this.refs.password.value
        }).then(function (response) {
            TokenStorage.setToken(response.data.token);
            hashHistory.push('/');
        }.bind(this)).catch(function (response) {
            TokenStorage.clear();
        });
    }

    render() {
        return (
            <main>
                <div className="row">
                    <div className="medium-6 medium-centered large-4 large-centered columns">

                        <form onSubmit={this.submit.bind(this)}>
                            <div className="row column log-in-form">
                                <h4 className="text-center">Log in with you email account</h4>
                                <label htmlFor="username">Username / E-Mail:</label>
                                <input ref="username" type="text" id="username" name="_username"/>

                                <label htmlFor="password">Password:</label>
                                <input ref="password" type="password" id="password" name="_password"/>

                                <button type="submit">Log In</button>
                            </div>
                        </form>

                    </div>
                </div>
            </main>
        );
    }
}
