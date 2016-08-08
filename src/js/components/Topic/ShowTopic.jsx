import React from "react";
import {Link} from "react-router";
import Client from "../../services/Client";
import Loading from "../Loading/Loading";
import Markdown from "../Markdown/Markdown";
import Gravatar from "../User/Gravatar";
import User from "../User/User";
import Speakers from "./Speakers";
import SpeakersList from "./SpeakersList";
import VotesList from "./VotesList";
import Votes from "./Votes";
import Comments from "./Comments";
import Date from "../Date";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import MessageBag from "../../services/MessageBag";

export default class ShowTopic extends React.Component {
    constructor(props) {
        super(props);

        this.id = this.props.params.id;

        this.state = {
            loading: true,
            data: [],
            speakers: [],
            votes: []
        };

        this.user = AuthorizationStorage.getUser();
    }

    componentDidMount() {
        Client.get('/api/topic/' + this.id).then(function (response) {
            this.setState({
                loading: false,
                data: response.data,
                speakers: response.data.speakers,
                votes: response.data.votes,
            });
        }.bind(this));
    }


    registerSpeaker() {
        Client.post('/api/topic/' + this.id + '/add-speaker')
            .then(function () {
                var speakers = this.state.speakers;
                speakers.push(this.user);

                this.setState({
                    speakers: speakers
                });

                MessageBag.success('Success');
            }.bind(this));
    }

    unregisterSpeaker() {
        Client.post('/api/topic/' + this.id + '/remove-speaker')
            .then(function () {
                var speakers = this.state.speakers;

                speakers = speakers.filter(function (el) {
                    return el.id != this.user.id;
                }.bind(this));

                this.setState({
                    speakers: speakers
                });

                MessageBag.success('Success');
            }.bind(this));
    }

    vote() {
        Client.post('/api/topic/' + this.id + '/cast-vote')
            .then(function () {

                var votes = this.state.votes;
                votes.push(this.user);
                this.setState({
                    votes: votes
                });

                MessageBag.success('Success');
            }.bind(this));
    }

    unvote() {
        Client.post('/api/topic/' + this.id + '/retract-vote')
            .then(function () {
                var votes = this.state.votes;

                votes = votes.filter(function (el) {
                    return el.id != this.user.id;
                }.bind(this));

                this.setState({
                    votes: votes
                });

                MessageBag.success('Success');
            }.bind(this));
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return (
            <div className="row">

                <div className="small-12 large-3 columns">
                    <SpeakersList>{this.state.speakers}</SpeakersList>
                    <VotesList>{this.state.votes}</VotesList>
                </div>

                <div className="small-12 large-9 columns">
                    <div id="topic-21" className="clearfix highlight-target">

                        <div className="right">
                            { this.state.data.lectureHeld
                                ? null
                                :
                                <Link className="button tiny radius secondary" to={"/topic/" + this.id + "/archive"}>
                                    <i className="fa fa-check"/>
                                </Link>
                            }
                            &nbsp;
                            <Link className="button tiny radius secondary" to={"/topic/" + this.id + "/edit"}>
                                <i className="fa fa-pencil"/>
                            </Link>
                            &nbsp;
                            {this.renderVoteButton()}
                            &nbsp;
                            {this.renderSpeakerButton()}
                        </div>

                        <h4 className="one-line">{this.state.data.title}</h4>
                        <hr/>
                        <div className="quiet">
                            <ul className="inline-list">
                                <li><Votes>{this.state.votes}</Votes></li>
                                <li><Speakers>{this.state.speakers}</Speakers></li>
                                <li><Date showIcon={true}>{this.state.data.lectureDate}</Date></li>
                            </ul>
                            <br/>
                        </div>

                        <br/>

                        <div className="markdown"><Markdown>{this.state.data.description}</Markdown></div>


                        <div className="one-line quiet">
                            <Gravatar size={16}>{this.state.data.createdBy.email}</Gravatar>
                            &nbsp;
                            <strong><User>{this.state.data.createdBy}</User></strong>
                            &nbsp;
                            <span className="quiet"><Date showIcon={true}>{this.state.data.createdAt}</Date></span>
                        </div>

                        <br/>
                        {this.state.data.lectureHeld ? this.renderLectureInfo() : null}
                        <br/>

                        <Comments id={this.id} topic={this.state.data}/>
                    </div>

                    <hr/>
                </div>

            </div>
        );
    }

    renderVoteButton() {
        var hasVote = this.state.votes.find((el) => {
            return el.id == this.user.id;
        });

        if (hasVote) {
            return (
                <a title="retract your vote" className="button tiny radius"
                   onClick={this.unvote.bind(this)}>
                    <i className="fa fa-thumbs-up"/>
                </a>
            );
        }

        return (
            <a title="vote" className="button tiny radius secondary" onClick={this.vote.bind(this)}>
                <i className="fa fa-thumbs-up"/>
            </a>
        );
    }

    renderSpeakerButton() {
        var isSpeaker = this.state.speakers.find((el) => {
            return el.id == this.user.id;
        });

        if (isSpeaker) {
            return (
                <a title="unregister as speaker" className="button tiny radius"
                   onClick={this.unregisterSpeaker.bind(this)}>
                    <i className="fa fa-microphone"/>
                </a>
            )
        }

        return (
            <a title="register as speaker" className="button tiny radius secondary"
               onClick={this.registerSpeaker.bind(this)}>
                <i className="fa fa-microphone"/>
            </a>
        )
    }

    renderLectureInfo() {
        return (
            <div className="panel callout radius">
                <div className="markdown"><Markdown>{this.state.data.lectureNote}</Markdown></div>
            </div>
        );
    }
}




