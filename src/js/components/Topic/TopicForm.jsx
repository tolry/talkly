import React from "react";
import validator from "validator";
import Form from "../Form";
import MarkdownTextarea from "../Markdown/MarkdownTextarea";

export default class TopicForm extends Form {
    validate(data) {
        let errors = [];

        if (validator.isNull(data.title)) {
            errors.push('title can not be empty!');
        }

        if (validator.isNull(data.description)) {
            errors.push('description can not be empty!');
        }

        return errors;
    }

    renderForm() {
        return (
            <div>
                <input type="text" name="title" placeholder="title"/>
                <MarkdownTextarea name="description" rows={8} placeholder="description (markdown allowed)"/>
                <button type="submit" className="button radius small right radius"><i className="fa fa-plus-square"/> submit</button>
            </div>
        );
    }
}