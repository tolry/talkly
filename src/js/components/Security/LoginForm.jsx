import React from "react";
import validator from "validator";
import Form from "../Form";

export default class LoginForm extends Form {
    validate(data) {
        let errors = [];

        if (validator.isNull(data.username)) {
            errors.push('username can not be empty!');
        }

        if (validator.isNull(data.password)) {
            errors.push('password can not be empty!');
        }

        return errors;
    }

    renderForm() {
        return (
            <div className="row column log-in-form">
                <h4 className="text-center">Log in with you email account</h4>
                <label htmlFor="username">Username / E-Mail:</label>
                <input type="text" id="username" name="username"/>

                <label htmlFor="password">Password:</label>
                <input type="password" id="password" name="password"/>

                <button type="submit">Log In</button>
            </div>
        );
    }
}