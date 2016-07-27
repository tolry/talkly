import React from "react";
import BaseForm from "../../lib/Form";

export default class Form extends BaseForm {
    renderErrors() {
        if (this.state.errors.length == 0) {
            return null;
        }

        return (
            <div className="alert-box alert" style={{marginTop: 20}}>
                {this.state.errors.map(function (error) {
                    return (
                        <div key={error}>{error}</div>
                    );
                })}
            </div>
        );
    }
}