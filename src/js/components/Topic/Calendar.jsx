import React from 'react';
import Client from '../../services/Client';
import Loading from "../Loading/Loading";
import GroupedList from "./GroupedList";

export default class Calendar extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        Client.get('/calendar/', {

        }).then((response) => {

            console.log(response.data);

            this.setState({
                loading: false,
                data: response.data
            });
        });
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return <GroupedList data={this.state.data}/>;
    }
}