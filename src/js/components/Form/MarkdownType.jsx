import React from "react";
import Markdown from "../Markdown/Markdown";
import FormType from "./FormType";
import style from "./MarkdownType.css";

export default class MarkdownType extends FormType {
    constructor(props) {
        super(props);

        this.state['preview'] = false;
    }

    edit() {
        this.setState({
            preview: false
        });
    }

    preview() {
        this.setState({
            preview: true
        });
    }

    shouldDisplayErrors() {
        return super.shouldDisplayErrors() && this.state.preview == false;
    }

    renderWidget() {
        return (
            <div>

                <ul className={style.tab}>
                    <li><a className="tablinks" onClick={() => this.edit()}>Edit</a></li>
                    <li><a className="tablinks" onClick={() => this.preview()}>Preview</a></li>
                </ul>

                <div style={{display: this.state.preview ? 'none' : 'block'}} className={style.edit}>
                    <textarea
                        className={this.shouldDisplayErrors() ? 'error' : null}
                        rows={6}
                        placeholder={this.props.placeholder}
                        ref={(node) => this.el = node}
                        name={this.props.name}
                        onKeyDown={this.props.onKeyDown}
                        onKeyUp={this.handleKeyUp.bind(this)}
                        onChange={this.handleChange.bind(this)}
                        onBlur={this.handleBlur.bind(this)}
                    />
                </div>

                <div style={{display: this.state.preview ? 'block' : 'none'}} className={style.markdown}>
                    <Markdown fallback="nothing to see here">{this.state.preview ? this.el.value : ''}</Markdown>
                </div>
            </div>
        );
    }
}
