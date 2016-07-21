import React from "react";
import validator from "validator";
import Form from "../Form";

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
                <input type="text" name="title"/>
                <textarea name="description"/>
                <button type="submit">Submit</button>
            </div>
        );
    }
}