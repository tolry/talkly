import React from "react";
import FormType from "./FormType";
import flatpickr from "flatpickr";

export default class DatePicker extends FormType {
    componentDidMount() {
        let options = {
            enabletime: this.props.enableTime || false
        };

        this.flatpickr = flatpickr(this.el, options);
    }

    setData(data) {
        this.flatpickr.setDate(data);
    }

    getData()
    {
        if (this.el.value) {
            return this.el.value
        }

        return null;
    }

    renderWidget() {
        return (
            <input
                className={this.shouldDisplayErrors() ? 'error' : null}
                ref={(node) => this.el = node}
                type="text"
                name={this.props.name}
                onKeyUp={this.handleKeyUp.bind(this)}
                onChange={this.handleChange.bind(this)}
                onBlur={this.handleBlur.bind(this)}
            />
        );
    }
}
