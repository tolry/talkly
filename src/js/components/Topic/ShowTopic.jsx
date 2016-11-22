import React from "react";
import {Link} from "react-router";
import Markdown from "../Markdown/Markdown";
import Gravatar from "../User/Gravatar";
import User from "../User/User";
import Speakers from "./Speakers";
import SpeakersList from "./SpeakersList";
import VotesList from "./VotesList";
import Votes from "./Votes";
import Comments from "./Comments";
import Date from "../Date";
import Duration from "../Duration";
import AuthorizationStorage from "../../services/AuthorizationStorage";

export default class ShowTopic extends React.Component {
  render() {
    return (
      <div className="row">

        <div className="small-12 large-3 columns">
          <SpeakersList>{this.props.topic.speakers}</SpeakersList>
          <VotesList>{this.props.topic.votes}</VotesList>
        </div>

        <div className="small-12 large-9 columns">
          <div id="topic-21" className="clearfix highlight-target">

            <div className="right">
              { this.props.topic.lectureHeld
                ? null
                :
                <Link className="button tiny radius secondary" to={"/topic/" + this.props.topic.id + "/archive"}>
                  <i className="fa fa-check"/>
                </Link>
              }
              &nbsp;
              <Link className="button tiny radius secondary" to={"/topic/" + this.props.topic.id + "/edit"}>
                <i className="fa fa-pencil"/>
              </Link>
              &nbsp;
              {this.renderVoteButton()}
              &nbsp;
              {this.renderSpeakerButton()}
            </div>

            <h4 className="one-line">{this.props.topic.title}</h4>
            <hr/>
            <div className="quiet">
              <ul className="inline-list">
                <li><Votes>{this.props.topic.votes}</Votes></li>
                <li><Speakers>{this.props.topic.speakers}</Speakers></li>
                <li><Date showIcon={true}>{this.props.topic.lectureDate}</Date></li>
                <li><Duration showIcon={true}>{this.props.topic.lectureDuration}</Duration></li>
              </ul>
              <br/>
            </div>

            <br/>

            <div className="markdown"><Markdown>{this.props.topic.description}</Markdown></div>

            <div className="one-line quiet">
              <Gravatar size={16}>{this.props.topic.createdBy.email}</Gravatar>
              &nbsp;
              <strong><User>{this.props.topic.createdBy}</User></strong>
              &nbsp;
              <span className="quiet"><Date showIcon={true}>{this.props.topic.createdAt}</Date></span>
            </div>

            <br/>
            {this.props.topic.lectureHeld ? this.renderLectureInfo() : null}
            <br/>

            <Comments id={this.props.topic.id} topic={this.props.topic}/>
          </div>

          <hr/>
        </div>

      </div>
    );
  }

  renderVoteButton() {
    var hasVote = this.props.topic.votes.find((el) => {
      return el.id == AuthorizationStorage.getUser().id;
    });

    if (hasVote) {
      return (
        <a title="retract your vote" className="button tiny radius"
           onClick={this.props.unvote}>
          <i className="fa fa-thumbs-up"/>
        </a>
      );
    }

    return (
      <a title="vote" className="button tiny radius secondary" onClick={this.props.vote}>
        <i className="fa fa-thumbs-up"/>
      </a>
    );
  }

  renderSpeakerButton() {
    var isSpeaker = this.props.topic.speakers.find((el) => {
      return el.id == AuthorizationStorage.getUser().id;
    });

    if (isSpeaker) {
      return (
        <a title="unregister as speaker" className="button tiny radius"
           onClick={this.props.unregisterSpeaker}>
          <i className="fa fa-microphone"/>
        </a>
      )
    }

    return (
      <a title="register as speaker" className="button tiny radius secondary"
         onClick={this.props.registerSpeaker}>
        <i className="fa fa-microphone"/>
      </a>
    )
  }

  renderLectureInfo() {
    return (
      <div className="panel callout radius">
        <div className="markdown"><Markdown>{this.props.topic.lectureNote}</Markdown></div>
      </div>
    );
  }
}