import React from "react";
import validator from "validator";
import Form from "../Form";
import MarkdownTextarea from "../Markdown/MarkdownTextarea";
import DatePicker from "../DatePicker";

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
                <label>
                    Title
                    <input type="text" name="title" placeholder="title"/>
                </label>
                <label>
                    Description:
                    <MarkdownTextarea name="description" rows={8} placeholder="description (markdown allowed)"/>
                </label>
                <label>
                    Lecture Date:
                    <DatePicker name="lectureDate"/>
                </label>
                <button type="submit" className="button radius small right radius"><i className="fa fa-plus-square"/>
                    submit
                </button>
            </div>
        );
    }
}