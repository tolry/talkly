import React from "react";
import Loading from "../Loading/Loading";
import ArchiveForm from "./ArchiveForm";
import SpeakersList from "./SpeakersList";
import VotesList from "./VotesList";
import Client from "../../services/Client";
import History from "../../services/History";
import MessageBag from "../../services/MessageBag";

export default class ArchiveTopic extends React.Component {
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
        }.bind(this));
    }


    submit(data) {
        Client.post('/api/topic/' + this.id + '/archive', data).then((response) => {
            MessageBag.success('Success');
            History.push('/topic/' + this.id);
        });
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return (
            <div className="row">
                <div className="small-12 large-3 columns">
                    <SpeakersList>{this.state.data.speakers}</SpeakersList>
                    <VotesList>{this.state.data.votes}</VotesList>
                </div>

                <div className="small-12 large-9 columns">
                    <h1>Close Lecture</h1>

                    <ArchiveForm data={this.state.data} submit={this.submit.bind(this)}/>
                </div>
            </div>
        );
    }
}