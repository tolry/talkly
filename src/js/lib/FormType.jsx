import React from "react";
import Validator from "./validator";

export default class FormType extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            errors: [],
            used: false
        };

        this.listeners = [];
    }

    subscribe(listener) {
        this.listeners.push(listener);
    }

    componentDidMount() {
        if (this.props.value) {
            this.el.value = this.props.value;
        }
    }

    validate(data) {
        let violations = Validator.validate(data, this.props.constraints || []);

        return violations.map((violation) => violation.getMessage());
    }

    isValid() {
        return this.state.errors.length == 0;
    }

    shouldDisplayErrors() {
        return this.state.errors.length > 0 && this.state.used;
    }

    displayErrors() {
        this.setState({
            used: true
        });
    }

    getData() {
        return this.el.value.trim();
    }

    setData(data) {
        this.el.value = data;
        this.update();
    }

    getName() {
        return this.props.name;
    }

    reset() {
        this.el.value = '';
    }

    handleChange(event) {
        this.update();
    }

    handleBlur(event) {
        this.setState({
            used: true
        }, () => {
            this.update();
        });
    }

    handleKeyUp(event) {
        this.update();
    }

    update() {
        this.setState({
            errors: this.validate(this.getData())
        }, () => {
            for (let listener of this.listeners) {
                listener();
            }
        });
    }

    render() {
        return (
            <div>
                {this.renderLabel()}
                {this.renderWidget()}
                {this.renderErrors()}
            </div>
        );
    }

    renderLabel() {
        return <label>{this.props.label}</label>
    }

    renderWidget() {
        return (
            <input
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