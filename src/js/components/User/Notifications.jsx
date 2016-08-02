import React from "react";
import Loading from "../Loading/Loading";
import Client from "../../services/Client";

export default class Notifications extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        Client.get('/api/user/notifications').then(function (response) {
            this.setState({
                loading: false,
                data: response.data
            });
        }.bind(this));
    }

    render() {
        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

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
                        <a href="/user/mark-all-notifications-read">
                            <i className="fa fa-check"/>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr className="row">
                    <td className="text-center" colSpan={3}>no unread notifications</td>
                </tr>
                </tbody>
            </table>
        );
    }
}