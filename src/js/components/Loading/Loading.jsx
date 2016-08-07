import React from "react";
import css from "./style.css";

export default class Loading extends React.Component {
    render() {
        let style = {
            transform: "scale(" + (this.props.size || 1) + ")"
        };

        return <div className={css.uil_ring_css} style={style}><div></div></div>;
    }
}