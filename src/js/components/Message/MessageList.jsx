import React from "react";
import Message from "./Message";
import MessageBag from "../../services/MessageBag";
import style from "./style.css";

export default class MessageList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            messages: []
        };

        this.listener = () => this.update();
    }

    componentDidMount() {
        MessageBag.subscribe(this.listener);
        this.update();
    }

    componentWillUnmount() {
        MessageBag.unsubscribe(this.listener);
    }

    update() {
        this.setState({
            messages: MessageBag.all()
        });
    }

    render() {
        return (
            <div className={style.container}>
                {this.state.messages.map((message) => {
                    return <Message key={message.id} id={message.id} type={message.type}>{message.text}</Message>;
                })}
            </div>
        );
    }
}