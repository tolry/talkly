import React from "react";
import Speakers from "../components/Speakers";
import Votes from "../components/Votes";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            speakers: this.props.data.speakers,
            votes: this.props.data.votes
        };
    }

    componentWillMount() {
    }

    componentDidMount() {
    }

    componentWillUnmount() {
    }

    foo() {
        var speakers = this.state.speakers;
        speakers.push({
            'name': 'test'
        });

        this.setState({
            speakers: speakers
        });
    }

    render() {

        console.log(this.props.data);

        var id = "topic-" + this.props.data.id;

        return (
            <div id={id} className="clearfix highlight-target">

                <div className="row">
                    <div className="large-8 columns">
                        <h4 className="one-line" data-tooltip title={this.props.data.title }>
                            <a>
                                {this.props.data.title}
                            </a>
                        </h4>
                    </div>
                    <div className="large-4 columns text-right">
                        <a className="button tiny radius secondary">
                            edit
                        </a>

                        <a data-tooltip title="retract your vote" className="button tiny radius" onClick={this.foo.bind(this)}>
                            <i className="fa fa-thumbs-up" />
                        </a>

                        <a data-tooltip title="vote" className="button tiny radius secondary">
                            <i className="fa fa-thumbs-up" />
                        </a>

                        <a data-tooltip title="unregister as speaker" className="button tiny radius">
                            <i className="fa fa-microphone" />
                        </a>

                        <a data-tooltip title="register as speaker" className="button tiny radius secondary">
                            <i className="fa fa-microphone" />
                        </a>
                    </div>
                </div>

                <div className="row">
                    <div className="large-6 columns quiet">
                        <ul className="inline-list">
                            <li><Votes>{this.state.votes}</Votes></li>
                            <li><span><i className="fa fa-comments-o" /> {this.props.data.comment_count}</span></li>
                            <li><Speakers>{this.state.speakers}</Speakers></li>
                            <li><span><i className="fa fa-calendar"/> {this.props.data.lecture_date}</span></li>
                        </ul>
                    </div>
                    <div className="large-6 columns text-right">
                        <span className="quiet">{this.props.data.created_at}</span>
                    </div>
                </div>
            </div>
        );
    }
}
