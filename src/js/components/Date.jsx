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
        return (
            <span title={this.props.children}>{this.state.date}</span>
        );
    }
}