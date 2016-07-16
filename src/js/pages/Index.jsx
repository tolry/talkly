import React from "react";
import Client from "../services/Client";
import Topic from "../partials/Topic";
import Loading from "../components/Loading/Loading";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        Client.get('/topic/').then((response) => {
            this.setState({
                loading: false,
                data: response.data
            });
        });
    }

    render() {
        let topics = this.state.data.map((topic) => {
            return (
                <Topic key={topic.id} data={topic}/>
            );
        });

        return (
            <div>
                {this.state.loading ? <Loading size="0.5"/> : topics}
            </div>
        );
    }
}