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
        this.loadData();
    }

    componentWillUnmount() {

    }

    loadData() {
        Client.get('/api/topics/').then(function (response) {
            this.setState({
                data: response.data
            });
        }.bind(this));
    }

    render() {
        return (
            <ul>
                {this.state.data.map(this.renderElement.bind(this))}
            </ul>
        );
    }

    renderElement(data) {
        console.log(data);

        return (
            <li>
                {data.title}
            </li>
        );
    }
}