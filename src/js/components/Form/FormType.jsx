import React from "react";
import BaseFormType from "../../lib/FormType";

export default class FormType extends BaseFormType {
    render() {
        return (
            <div>
                {this.renderLabel()}
                {this.renderErrors()}
            </div>
        );
    }

    renderLabel() {
        return (
            <label>
                {this.props.label}
                {this.renderWidget()}
            </label>
        );
    }

    renderWidget() {
        return (
            <input
                className={this.shouldDisplayErrors() ? 'error' : null}
                ref={(node) => this.el = node}
                type={this.props.type}
                name={this.props.name}
                onKeyUp={this.handleKeyUp.bind(this)}
                onChange={this.handleChange.bind(this)}
                onBlur={this.handleBlur.bind(this)}
            />
        );
    }

    renderErrors() {
        if (!this.shouldDisplayErrors()) {
            return null;
        }

        return <small className="error">{this.state.errors[0]}</small>;
    }
}