import React from "react";
import Markdown from "./Markdown";
import style from "./MarkdownTextarea.css";

export default class MarkdownTextarea extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            preview: false
        };
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

    render() {
        let text = this.textarea ? this.textarea.value : '';

        return (
            <div>

                <ul className={style.tab}>
                    <li><a className="tablinks" onClick={() => this.edit()}>Edit</a></li>
                    <li><a className="tablinks" onClick={() => this.preview()}>Preview</a></li>
                </ul>

                <div style={{display: this.state.preview ? 'none' : 'block'}} className={style.edit}>
                    <textarea rows={6} placeholder={this.props.placeholder} ref={(node) => this.textarea = node} name={this.props.name}/>
                </div>

                <div style={{display: this.state.preview ? 'block' : 'none'}} className={style.markdown}>
                    <Markdown>{text || 'no content'}</Markdown>
                </div>
            </div>
        );
    }
}
