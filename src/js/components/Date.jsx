import React from "react";
import moment from "moment";

export default class Date extends React.Component {
    constructor(props) {
        super(props);

        this.date = props.children ? moment(props.children) : null;

        this.state = {
            date: null
        };
    }

    componentDidMount() {
        this.tick();
        this.interval = setInterval(() => this.tick(), 1000);
    }

    componentWillUnmount() {
        clearInterval(this.interval);
    }

    tick() {
        if (!this.date) {
            return;
        }

        this.setState({
            date: this.date.fromNow()
        });
    }

    render() {
        return (
            <span title={this.props.children}>
                {this.props.showIcon ? <i className="fa fa-calendar"/> : null}
                {this.state.date ? this.state.date : '--'}
            </span>
        );
    }
}
