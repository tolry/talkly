import React from "react";
import Client from "../../services/Client";
import Loading from "../Loading/Loading";
import Markdown from "../Markdown/Markdown";
import Gravatar from "../User/Gravatar";
import User from "../User/User";
import Speakers from "./Speakers";
import Votes from "./Votes";
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

            console.log(response.data);
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

                let old = speakers;

                console.log(speakers);

                speakers = speakers.filter(function (el) {
                    return el.id != this.user.id;
                }.bind(this));

                console.log(speakers == old);

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
                    <h4>Speaker(s)</h4>
                    <hr/>
                    {this.renderSpeakers()}

                    <h4>Vote(s)</h4>
                    <br/>
                    {this.renderVotes()}
                </div>

                <div className="small-12 large-9 columns">
                    <div id="topic-21" className="clearfix highlight-target">

                        <div className="right">
                            {this.renderVoteButton()}
                            &nbsp;
                            {this.renderSpeakerButton()}
                        </div>

                        <h4 className="one-line">{this.state.data.title}</h4>
                        <hr/>
                        <div className="quiet">
                            <ul className="inline-list">
                                <li><Votes>{this.state.votes}</Votes></li>
                                <li><span><i
                                    className="fa fa-comments-o"/> {this.state.data.comment_count ? this.state.data.comment_count : 0}</span>
                                </li>
                                <li><Speakers>{this.state.speakers}</Speakers></li>
                                <li><Date showIcon={true}>{this.state.data.lectureDate}</Date></li>
                            </ul>
                            <br/>
                        </div>
                        <br/>

                        <div className="one-line quiet">
                            <Gravatar className="th" size={16}>{this.state.data.createdBy.email}</Gravatar>
                            &nbsp;
                            <strong><User>{this.state.data.createdBy}</User></strong>
                            &nbsp;
                            <span className="quiet"><Date showIcon={true}>{this.state.data.createdAt}</Date></span>
                        </div>

                        <div className="markdown"><Markdown>{this.state.data.description}</Markdown></div>

                        <p>
                            <a href="#">
                                <i className="fa fa-graduation-cap"/> Lecture
                                <i className="fa fa-angle-double-down data-toggle-open"/>
                                <i className="fa fa-angle-double-right data-toggle-closed"/>
                            </a>
                        </p>


                        <h4><i className="fa fa-comments-o"/> 0 comment(s)</h4>


                        <div className="row">
                            <form role="form" action="/topic/21/comment" method="post"
                                  className="ng-pristine ng-valid">
                                        <textarea name="comment" className="form-controll"
                                                  placeholder="add comment (markdown allowed)"/>

                                <div>
                                    <label>markdown preview</label>
                                    <div className="panel"></div>
                                </div>

                                <div className="right">
                                    <button className="button tiny radius">comment</button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <hr/>
                </div>

            </div>
        );
    }

    renderVotes() {

        if (this.state.votes.length == 0) {
            return (
                <div className="panel">there are no votes for this talk yet</div>
            );
        }

        return this.state.votes.map((vote) => {
            return (
                <p className="one-line" key={vote.id}>
                    <Gravatar size={16} className="th">{vote.email}</Gravatar>
                    &nbsp;
                    <User>{vote}</User>
                </p>
            );
        });
    }

    renderSpeakers() {

        if (this.state.speakers.length == 0) {
            return (
                <div className="panel">no speakers volunteered yet</div>
            );
        }

        return this.state.speakers.map((speaker) => {
            return (
                <div className="text-center" key={speaker.id}>
                    <Gravatar size={96} className="th">{speaker.email}</Gravatar>
                    <br/>
                    <p className="one-line"><User>{speaker}</User></p>
                </div>
            );
        });
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




