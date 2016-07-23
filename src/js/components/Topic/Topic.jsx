import React from "react";
import {Link} from "react-router";
import Speakers from "./Speakers";
import Votes from "./Votes";
import Date from "../Date";
import Client from "../../services/Client";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import MessageBag from "../../services/MessageBag";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            speakers: this.props.data.speakers,
            votes: this.props.data.votes
        };

        this.user = AuthorizationStorage.getUser();
    }

    registerSpeaker() {
        Client.post('/api/topic/' + this.props.data.id + '/add-speaker')
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
        Client.post('/api/topic/' + this.props.data.id + '/remove-speaker')
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
        Client.post('/api/topic/' + this.props.data.id + '/cast-vote')
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
        Client.post('/api/topic/' + this.props.data.id + '/retract-vote')
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
        var id = "topic-" + this.props.data.id;
        var link = "/topic/" + this.props.data.id;

        return (
            <div id={id} className="clearfix highlight-target">
                <div className="row">
                    <div className="large-9 columns">
                        <h4 className="one-line" data-tooltip title={this.props.data.title }>
                            <Link to={link}>
                                {this.props.data.title}
                            </Link>
                        </h4>
                    </div>
                    <div className="large-3 columns text-right">
                        {this.renderVoteButton()}
                        &nbsp;
                        {this.renderSpeakerButton()}
                    </div>
                </div>

                <div className="row">
                    <div className="large-9 columns quiet">
                        <ul className="inline-list">
                            <li><Votes>{this.state.votes}</Votes></li>
                            <li><span><i
                                className="fa fa-comments-o"/> {this.props.data.comment_count ? this.props.data.comment_count : 0}</span>
                            </li>
                            <li><Speakers>{this.state.speakers}</Speakers></li>
                            <li><Date showIcon={true}>{this.props.data.lectureDate}</Date></li>
                        </ul>
                    </div>
                    <div className="large-3 columns text-right">
                        <span className="quiet"><Date>{this.props.data.createdAt}</Date></span>
                    </div>
                </div>
                <hr/>
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
}
