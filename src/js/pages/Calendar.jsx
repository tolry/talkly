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
        Client.get('/topic/', {

        }).then((response) => {
            console.log('begin');
            this.setState({
                data: response.data
            });
        });
    }

    render() {
        let topics = this.state.data.map((topic) => {
            return (
                <Topic key={topic.id} data={topic} />
            );
        });

        return (
            <div>
                {topics}
            </div>
        );
    }
}