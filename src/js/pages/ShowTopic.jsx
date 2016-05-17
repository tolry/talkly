import React from 'react';
import Client from '../services/Client';
import Gravatar from '../components/Gravatar';

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.id = this.props.params.id;

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
        Client.get('/topic/' + this.id).then(function (response) {
            this.setState({
                data: response.data
            });

            console.log(response.data);
        }.bind(this));
    }

    render() {
        return (
            <div class="row">

              <Gravatar size="400">d.a.badura@gmail.com</Gravatar>

            </div>
        );
    }
}




