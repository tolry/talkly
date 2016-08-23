import React from "react";
import Form from "../Form/Form";
import MarkdownType from "../Form/MarkdownType";
import DateType from "../Form/DateType";

export default class ArchiveForm extends Form {
    renderForm() {
        return (
            <div>
                <DateType label="Lecture Date:" name="lectureDate"/>
                <MarkdownType label="Lecture Notes:" name="lectureNote" rows={8} placeholder="markdown allowed"/>
            </div>
        );
    }
}