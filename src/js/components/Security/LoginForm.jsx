import React from "react";
import Form from "../Form/Form";
import FormType from "../Form/FormType";

export default class LoginForm extends Form {
    renderForm() {
        return (
            <div className="row column log-in-form">
                <h4 className="text-center">Log in with you email account</h4>

                <FormType type="text" name="username" label="Username / E-Mail:" constraints={['notBlank']} />
                <FormType type="password" name="password" label="Password:" constraints={['notBlank']}/>
            </div>
        );
    }
}