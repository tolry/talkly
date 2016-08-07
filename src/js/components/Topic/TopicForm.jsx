import React from "react";
import Form from "../Form/Form";
import FormType from "../Form/FormType";
import MarkdownType from "../Form/MarkdownType";
import DateType from "../Form/DateType";

export default class TopicForm extends Form {
    renderForm() {
        return (
            <div>
                <FormType type="text" name="title" label="Title:" constraints="notBlank"/>
                <MarkdownType label="Description:" name="description" rows={8} placeholder="markdown allowed" constraints="notBlank"/>
                <DateType label="Lecture Date:" name="lectureDate"/>
            </div>
        );
    }
}