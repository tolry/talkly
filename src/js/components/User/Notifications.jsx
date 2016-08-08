import React from "react";
import Date from "../Date";
import Markdown from "../Markdown/Markdown";
import Notification from "../../services/Notification";

export default class Notifications extends React.Component {
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

    done(notification) {
        Notification.done(notification.id);
    }

    doneAll() {
        for (let notification of this.state.data) {
            this.done(notification);
        }
    }

    render() {
        return (
            <table>
                <thead>
                <tr className="row">
                    <th className="small-2 text-center">
                        <i className="fa fa-clock-o"/>
                    </th>
                    <th className="small-9 text-center">
                        &nbsp;
                    </th>
                    <th className="small-1 text-center">
                        <a onClick={() => this.doneAll()}>
                            <i className="fa fa-check"/>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                {this.state.data.length
                    ? this.state.data.map(this.renderEntry.bind(this))
                    : this.renderEmpty()
                }
                </tbody>
            </table>
        );
    }

    renderEntry(notification) {
        return (
            <tr className="row" key={notification.id}>
                <td><Date>{notification.createdAt}</Date></td>
                <td><Markdown>{notification.message}</Markdown></td>
                <td><a onClick={() => this.done(notification)}><i className="fa fa-check"/></a></td>
            </tr>
        );
    }

    renderEmpty() {
        return (
            <tr className="row">
                <td className="text-center" colSpan={3}>no unread notifications</td>
            </tr>
        );
    }
}