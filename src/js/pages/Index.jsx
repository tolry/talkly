import React from 'react';
import Client from '../services/Client';
import Topic from '../partials/Topic';

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
            <div>
                {this.state.data.map(this.renderElement.bind(this))}
            </div>
        );
    }

    renderElement(data) {
        return (
            <Topic key={data.id} data={data} />
        );
    }
}