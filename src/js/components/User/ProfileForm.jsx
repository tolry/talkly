import React from "react";
import Form from "../Form/Form";
import FormType from "../Form/FormType";
import SwitchType from "../Form/SwitchType";

export default class ProfileForm extends Form {
    renderForm() {
        return (
            <div>
                <FormType type="text" name="name" label="Name:" constraints={['notBlank']} />
                <FormType type="text" name="email" label="Email:" constraints={['notBlank', 'email']}/>
                <SwitchType name="notifyByEmail" label="Notify by email"/>
                <SwitchType name="notifyInApplication" label="Notify in application"/>
                <br/>
            </div>
        );
    }
}