import React from "react";
import Client from "../../services/Client";
import Loading from "../Loading/Loading";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import MessageBag from "../../services/MessageBag";
import CommentForm from "./CommentForm";
import Comment from "./Comment";

export default class Comments extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: !props.topic,
            comments: props.topic.comments || []
        };

        this.user = AuthorizationStorage.getUser();
    }

    componentDidMount() {
        if (this.state.comments.length > 0) {
            return;
        }

        Client.get('/api/topic/' + this.props.id).then(function (response) {
            this.setState({
                loading: false,
                comments: response.data.comments,
            });
        }.bind(this));
    }

    submit(data) {
        Client.post('/api/topic/' + this.props.id + '/comment', {
            comment: data.comment
        }).then(function () {
            let comments = this.state.comments;

            comments.push({
                createdBy: this.user,
                createdAt: Date.now(),
                commentText: data.comment
            });

            this.setState({
                comments: comments
            });

            MessageBag.success('Success');
        }.bind(this));
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        return (
            <div>
                <h4><i className="fa fa-comments-o"/> {this.state.comments.length} comment(s)</h4>
                {this.renderComments()}
                <CommentForm resetAfterSubmit={true} submit={this.submit.bind(this)}/>
            </div>
        );
    }

    renderComments() {
        return this.state.comments.map((comment) => {
            return <Comment key={comment.id}>{comment}</Comment>;
        });
    }
}




