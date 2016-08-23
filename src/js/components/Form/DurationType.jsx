import React from "react";
import BaseFormType from "./FormType";

const step = 15;

let minutes = [];
for (let i = 0; i < 60; i = i + step) {
  minutes.push(i);
}

let hours = [];
for (let i = 0; i <= 12; i++) {
  hours.push(i);
}

export default class DurationType extends BaseFormType {
   constructor(props) {
     super(props);
   }

  renderWidget() {
    return (
      <select
        className={this.shouldDisplayErrors() ? 'error' : null}
        ref={(node) => this.el = node}
        type={this.props.type}
        name={this.props.name}
        onKeyUp={this.handleKeyUp.bind(this)}
        onChange={this.handleChange.bind(this)}
        onBlur={this.handleBlur.bind(this)}
      >
        <option value={null} />
        {hours.map(this.renderOptionGroup.bind(this))}
      </select>
    );
  }

  getData() {
    if (this.el.value) {
      return parseInt(this.el.value, 10);
    }

    return null;
  }

  renderOptionGroup(hour) {
    return (
      <optgroup label={hour + ' hour(s)'}>
        {minutes.map((minute) => this.renderOption(hour, minute))}
      </optgroup>
    );
  }

  renderOption(hour, minute) {
    return (
      <option value={hour * 60 + minute}>{hour + ':' + ("0" + minute).slice(-2)}</option>
    );
  }
}