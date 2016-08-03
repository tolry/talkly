import React from "react";
import Notification from "../../services/Notification";

export default class NotificationCounter extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            data: Notification.all()
        };

        this.listener = () => this.update();
    }

    componentDidMount() {
        Notification.subscribe(this.listener);
    }

    componentWillUnmount() {
        Notification.unsubscribe(this.listener);
    }

    update() {
        this.setState({
            data: Notification.all()
        });
    }

    render() {
        if (this.state.data.length === 0) {
            return null;
        }

        return <span className=" round radius label">{this.state.data.length}</span>;
    }
}