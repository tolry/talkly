import React from "react";
import {Link} from "react-router";
import Speakers from "../components/Speakers";
import Votes from "../components/Votes";
import Date from "../components/Date";
import Client from '../services/Client';

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            speakers: this.props.data.speakers,
            votes: this.props.data.votes
        };

        this.user = {
            id: 999,
            username: 'test'
        };
    }

    componentWillMount() {
    }

    componentDidMount() {
    }

    componentWillUnmount() {
    }

    registerSpeaker() {
        var speakers = this.state.speakers;

        speakers.push(this.user);

        this.setState({
            speakers: speakers
        });
    }

    unregisterSpeaker() {
        var speakers = this.state.speakers;

        speakers = speakers.filter(function (el) {
            return el.id != this.user.id;
        }.bind(this));

        this.setState({
            speakers: speakers
        });
    }

    vote() {
        console.log(this);
        console.log(this.state);
        Client.post('/topic/' + this.props.data.id + '/cast-vote');
        var votes = this.state.votes;

        votes.push(this.user);

        this.setState({
            votes: votes
        });
    }

    unvote() {
        var votes = this.state.votes;

        votes = votes.filter(function (el) {
            return el.id != this.user.id;
        }.bind(this));

        this.setState({
            votes: votes
        });
    }

    render() {
        var id = "topic-" + this.props.data.id;
        var link = this.props.data.id + "/show";

        return (
            <div id={id} className="clearfix highlight-target">
                <div className="row">
                    <div className="large-8 columns">
                        <h4 className="one-line" data-tooltip title={this.props.data.title }>
                            <Link to={link}>
                                {this.props.data.title}
                            </Link>
                        </h4>
                    </div>
                    <div className="large-4 columns text-right">
                        <a className="button tiny radius secondary">
                            edit
                        </a>
                        {this.renderVoteButton()}
                        {this.renderSpeakerButton()}
                    </div>
                </div>

                <div className="row">
                    <div className="large-6 columns quiet">
                        <ul className="inline-list">
                            <li><Votes>{this.state.votes}</Votes></li>
                            <li><span><i className="fa fa-comments-o"/> {this.props.data.comment_count}</span></li>
                            <li><Speakers>{this.state.speakers}</Speakers></li>
                            <li><Date showIcon={true}>{this.props.data.lecture_date}</Date></li>
                        </ul>
                    </div>
                    <div className="large-6 columns text-right">
                        <span className="quiet"><Date>{this.props.data.created_at}</Date></span>
                    </div>
                </div>
                <hr/>
            </div>
        );
    }

    renderVoteButton() {
        var hasVote = this.state.votes.find(function (el) {
            return el.id == this.user.id;
        }.bind(this));

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
        var isSpeaker = this.state.speakers.find(function (el) {
            return el.id == this.user.id;
        }.bind(this));

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
