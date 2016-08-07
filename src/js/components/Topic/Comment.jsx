import React from "react";
import Gravatar from "../User/Gravatar";
import User from "../User/User";
import Date from "../Date";
import Markdown from "../Markdown/Markdown";

export default class Comment extends React.Component {

    render() {
        let comment = this.props.children;

        return (
            <div className="row">
                <div className="columns small-12">
                    <blockquote>
                        <div className="one-line">
                            <Gravatar size={16}>{comment.createdBy.email}</Gravatar>
                            &nbsp;
                            <strong><User>{comment.createdBy}</User></strong>
                            <span className="quiet"><Date withIcon={true}>{comment.createdAt}</Date></span>
                        </div>
                        <div className="quiet"></div>
                        <div className="markdown">
                            <Markdown>{comment.commentText}</Markdown>
                        </div>
                    </blockquote>
                </div>
            </div>
        );
    }
}




