import React from "react";
import Client from "../../services/Client";
import Gravatar from "../User/Gravatar";
import Markdown from "../Markdown";
import Loading from "../Loading/Loading";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.id = this.props.params.id;

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        Client.get('/api/topic/' + this.id).then(function (response) {
            this.setState({
                loading: false,
                data: response.data
            });

            console.log(response.data);
        }.bind(this));
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return (
            <div className="row">

                <Gravatar size="400">d.a.badura@gmail.com</Gravatar>
                <Markdown>{this.state.data.description}</Markdown>

            </div>
        );
    }
}




