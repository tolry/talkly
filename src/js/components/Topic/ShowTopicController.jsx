import React from "react";
import Client from "../../services/Client";
import Loading from "../Loading/Loading";
import MessageBag from "../../services/MessageBag";
import ShowTopic from "./ShowTopic";

export default class ShowTopicController extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      topic: null
    };
  }

  componentDidMount() {
    this.load(this.props.params.id);
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.params.id != this.props.params.id) {
      this.setState({
        topic: null
      });

      this.load(nextProps.params.id);
    }
  }

  load(id) {
    Client.get('/api/topic/' + id).then(function (response) {
      this.setState({
        topic: response.data,
      });
    }.bind(this));
  }

  registerSpeaker() {
    Client.post('/api/topic/' + this.props.params.id + '/add-speaker')
      .then(() => {
        this.load(this.props.params.id);

        MessageBag.success('Success');
      });
  }

  unregisterSpeaker() {
    Client.post('/api/topic/' + this.props.params.id + '/remove-speaker')
      .then(() => {
        this.load(this.props.params.id);

        MessageBag.success('Success');
      });
  }

  vote() {
    Client.post('/api/topic/' + this.props.params.id + '/cast-vote')
      .then(() => {
        this.load(this.props.params.id);

        MessageBag.success('Success');
      });
  }

  unvote() {
    Client.post('/api/topic/' + this.props.params.id + '/retract-vote')
      .then(() => {
        this.load(this.props.params.id);

        MessageBag.success('Success');
      });
  }

  render() {
    if (!this.state.topic) {
      return <Loading size="0.5"/>;
    }

    return (
      <ShowTopic
        topic={this.state.topic}
        vote={this.vote.bind(this)}
        unvote={this.unvote.bind(this)}
        registerSpeaker={this.registerSpeaker.bind(this)}
        unregisterSpeaker={this.unregisterSpeaker.bind(this)}
      />
    );
  }
}