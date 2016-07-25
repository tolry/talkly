import React from "react";
import validator from "validator";
import Form from "../Form";
import MarkdownTextarea from "../Markdown/MarkdownTextarea";

export default class CommentForm extends Form {
    validate(data) {
        let errors = [];

        if (validator.isNull(data.comment)) {
            errors.push('comment can not be empty!');
        }

        return errors;
    }

    keyHandler(event) {
        if (event.ctrlKey && event.key == 'Enter') {
            this.submit(event);
        }
    }

    renderForm() {
        return (
            <div>
                <MarkdownTextarea
                    keyHandler={this.keyHandler.bind(this)}
                    name="comment"
                    rows={8}
                    placeholder="add comment (markdown allowed)"
                />
                <button type="submit" className="button tiny radius right">comment</button>
            </div>
        );
    }
}