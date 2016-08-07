import React from "react";
import Form from "../Form/Form";
import MarkdownType from "../Form/MarkdownType";

export default class CommentForm extends Form {
    onKeyDown(event) {
        if (event.ctrlKey && event.key == 'Enter') {
            this.submit(event);
        }
    }

    renderForm() {
        return (
            <div>
                <MarkdownType
                    onKeyDown={this.onKeyDown.bind(this)}
                    name="comment"
                    rows={8}
                    placeholder="add comment (markdown allowed)"
                    constraints="notBlank"
                />
            </div>
        );
    }
}