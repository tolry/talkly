import React from "react";
import Client from "../../services/Client";
import Topic from "./Topic";
import AddTopic from "./AddTopic";
import Loading from "../Loading/Loading";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        this.load();
    }

    load() {
        Client.get('/api/topic/').then((response) => {
            this.setState({
                loading: false,
                data: response.data
            });
        });
    }

    add() {
        this.setState({
            loading: true,
            data: []
        });

        this.load();
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        let topics = this.state.data.map((topic) => {
            return (
                <Topic key={topic.id} data={topic}/>
            );
        });

        return (
            <div>
                <AddTopic callback={this.add.bind(this)}/>
                {topics}
            </div>
        );
    }
}