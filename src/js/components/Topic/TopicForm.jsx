import React from "react";
import validator from "validator";

export default class TopicForm extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            errors: []
        };
    }

    submit(event) {
        event.preventDefault();

        var form = this.refs.form;
        var errors = [];

        if (validator.isNull(form.name.value)) {
            errors.push('Name darf nicht leer sein!');
        }

        this.setState({
            errors: errors
        });

        if (errors.length == 0) {
            console.log('sende Daten!');
        }
    }

    render() {
        return (
            <form ref="form" onSubmit={this.submit.bind(this)}>
                {this.state.errors.length ? this.renderErrors() : ''}

                <input type="text" name="name"/>

                <button type="submit">Submit</button>
            </form>
        );
    }

    renderErrors() {
        console.log('Render Fehler');

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




