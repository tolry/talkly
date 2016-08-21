import React from "react";
import Form from "../Form/Form";
import FormType from "../Form/FormType";
import MarkdownType from "../Form/MarkdownType";
import DateType from "../Form/DateType";

export default class TopicForm extends Form {
  renderForm() {
    return (
      <div className="row">
        <div className="medium-12 columns">
          <FormType type="text" name="title" label="Title:" constraints="notBlank"/>
          <MarkdownType label="Description:" name="description" rows={8} placeholder="markdown allowed"
                        constraints="notBlank"/>
        </div>
        <div className="medium-6 columns">
          <DateType enableTime={true} label="Lecture From:" name="lectureFrom"/>
        </div>
        <div className="medium-6 columns">
          <DateType enableTime={true} label="Lecture Until:" name="lectureUntil"/>
        </div>

      </div>
    );
  }
}