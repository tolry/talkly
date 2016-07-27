import React from "react";
import FormType from "./FormType";

export default class Form extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            errors: [],
            valid: false
        };

        this.inputs = {};
    }

    add(component) {
        if (component instanceof FormType) {
            if (this.inputs[component.getName()]) {
                return;
            }

            this.inputs[component.getName()] = component;
            component.subscribe(() => this.update());

            if (this.props.data) {
                component.setData(this.props.data[component.getName()] || '');
            }
        }
    }

    register(elements) {
        return React.Children.map(elements, (element) => {
            if (typeof(element) != 'object') {
                return element;
            }

            if (typeof(element.type) == 'function') {
                return React.cloneElement(element, {ref: this.add.bind(this)}, this.register(element.props.children))
            }

            return React.cloneElement(element, element.props, this.register(element.props.children))
        });
    }

    submit(event = null) {
        if (event) {
            event.preventDefault();
        }

        this.update((valid) => {
            this.displayErrors();
            if (valid && this.props.submit) {
                this.props.submit(this.getData());

                if (this.props.resetAfterSubmit) {
                    this.reset();
                }
            }
        });
    }

    update(callback = function() {}) {
        let errors = this.validate(this.getData());
        let valid = errors.length == 0;

        for (let [key, c] of Object.entries(this.inputs)) {
            valid = valid && c.isValid();
        }

        this.setState({
            errors: errors,
            valid: valid
        }, () => callback(valid));
    }

    validate(data) {
        return this.props.validate ? this.props.validate(data) : [];
    }

    getData() {
        let data = {};

        for (let [key, c] of Object.entries(this.inputs)) {
            data[c.getName()] = c.getData();
        }

        return data;
    }

    setData(data) {
        for (let [key, c] of Object.entries(this.inputs)) {
            c.setData(data[c.getName()] || '');
        }
    }

    displayErrors() {
        for (let [key, c] of Object.entries(this.inputs)) {
            c.displayErrors();
        }
    }

    reset() {
        for (let [key, c] of Object.entries(this.inputs)) {
            c.setData('');
        }
    }

    renderForm() {
        return this.props.children;
    }

    render() {
        return (
            <form onSubmit={this.submit.bind(this)}>
                {this.renderErrors()}
                {this.register(this.renderForm())}
                {this.renderSubmitButton()}
            </form>
        );
    }

    renderSubmitButton() {
        return <button type="submit" disabled={!this.state.valid}>{this.props.submitLabel || 'Submit'}</button>
    }

    renderErrors() {
        if (this.state.errors.length == 0) {
            return null;
        }

        return (
            <ul>
                {this.state.errors.map(function (error) {
                    return (
                        <li key={error}>{error}</li>
                    );
                })}
            </ul>
        );
    }
}