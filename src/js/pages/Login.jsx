import React from 'react';
import Client from '../services/Client';

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: []
        };
    }

    componentWillMount() {

    }

    componentDidMount() {
    }

    componentWillUnmount() {

    }

    submit(event) {
        console.log('submit');

        Client.post('/login_check').then(function (response) {



            console.log(response.data);
        }.bind(this)).catch(function (response) {
            console.log(response);
        });

        event.preventDefault();
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