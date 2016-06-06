import React from 'react';
import Client from '../services/Client';
import Topic from '../partials/Topic';

export default class Calendar extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: []
        };
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        Client.get('/topic/').then(function (response) {
            this.setState({
                data: response.data
            });
        }.bind(this));
    }

    render() {
        return (
            <div>
                Calendar
            </div>
        );
    }
}