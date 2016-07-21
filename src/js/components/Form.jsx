import React from "react";

export default class Form extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            errors: []
        };
    }

    submit(event) {
        event.preventDefault();

        let data = this.getData();
        let errors = this.validate(data);

        this.setState({
            errors: errors
        });

        if (errors.length == 0 && this.props.submit) {
            this.props.submit(data);
        }
    }

    validate(data) {
        return [];
    }

    getData() {
        let data = {};

        for (let el of this.form.elements) {
            if (!el.name) {
                continue;
            }

            data[el.name] = el.value.trim();
        }

        return data;
    }

    renderForm() {
        return null;
    }

    render() {
        return (
            <form ref={(node) => this.form = node} onSubmit={this.submit.bind(this)}>
                {this.renderErrors()}
                {this.renderForm()}
            </form>
        );
    }

    renderErrors() {
        if (this.state.errors.length == 0) {
            return null;
        }

        return (
            <ul>
                {this.state.errors.map(function(error) {
                    return (
                      <li key={error}>{error}</li>
                    );
                })}
            </ul>
        );
    }
}