import React from "react";
import BaseFormType from "../../lib/FormType";

export default class SwitchType extends BaseFormType {
    render() {
        let labelStyle = {
            position: 'relative',
            fontSize: '0.875rem',
            color: '#4d4d4d',
            fontWeight: 'normal',
            lineHeight: 2,
            top: -9,
            marginBottom: 0
        };

        return (
            <div className="switch small radius">
                {this.renderWidget()}
                {this.renderLabel()}
                <span style={labelStyle}>
                {this.props.label}
                </span>
                {this.renderErrors()}
            </div>
        );
    }

    renderLabel() {
        return (
            <label style={{marginLeft: 0}} htmlFor={this.props.name}/>
        );
    }

    setData(bool) {
        this.el.checked = !!bool;
    }

    getData() {
        return this.el.checked;
    }

    renderWidget() {
        return (
            <input
                id={this.props.name}
                className={this.shouldDisplayErrors() ? 'error' : null}
                ref={(node) => this.el = node}
                type="checkbox"
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