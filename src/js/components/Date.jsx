import React from "react";
import moment from "moment";

export default class Date extends React.Component {
    constructor(props) {
        super(props);

        this.date = moment(props.children);

        this.state = {
            date: this.date.fromNow()
        };
    }

    componentDidMount() {
        this.interval = setInterval(function() {
            this.setState({
                date: this.date.fromNow()
            });
        }.bind(this), 1000);
    }

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    render() {
        if (! this.props.children) {
            if (this.props.showIcon) {
                return (<span title={this.props.children}><i className="fa fa-calendar"/> --</span>);
            }
            return (<span title={this.props.children}>--</span>);
        }

        if (this.props.showIcon) {
            return (
                <span title={this.props.children}><i className="fa fa-calendar"/> {this.state.date}</span>
            );
        }

        return (
            <span title={this.props.children}>{this.state.date}</span>
        );
    }
}
