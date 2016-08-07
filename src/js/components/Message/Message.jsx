import React from "react";
import style from "./style.css";

export default class Message extends React.Component {
    render() {
        return (
            <div className={style.message}>
                {this.props.children}
            </div>
        );
    }
}