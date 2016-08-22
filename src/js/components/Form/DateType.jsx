import React from "react";
import FormType from "./FormType";
import flatpickr from "flatpickr";
import moment from "moment";

export default class DatePicker extends FormType {
    componentDidMount() {
        let options = {
            time_24hr: true,
            enableTime: this.props.enableTime || false
        };

        this.flatpickr = flatpickr(this.el, options);
    }

    setData(data) {
        if (!data) {
            return;
        }

        let date = moment(data);

        let firstParts = data.split("T");
        let lastParts = firstParts[1].split("+");
        let timeParts = lastParts[0].split(":");
        let hours = timeParts[0];
        let minutes = timeParts[1];

        this.flatpickr.setDate(date.format('YYYY-MM-DD'));
        this.flatpickr.setTime(hours, minutes);
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
