import React from "react";
import moment from "moment";

export default class DateDiff extends React.Component {
    render() {
        let from = moment(this.props.from);
        let until = moment(this.props.until);

        let diff = until.diff(from, 'minutes');

        let hours = parseInt(diff / 60, 10);
        let minutes = diff - hours * 60;

        let text = '';

        if (hours == 1) {
            text = hours + ' hour'
        } else if(hours > 1) {
            text = hours + ' hours'
        }

        if (hours && minutes) {
            text += ' and ';
        }

        if (minutes == 1) {
            text += minutes + ' minute'
        } else if(minutes > 1) {
            text += minutes + ' minutes'
        }

        return (
            <span title={this.props.until}>
                {this.props.showIcon ? <i className="fa fa-clock-o"/> : null}
                &nbsp;
                {text ? text : '--'}
            </span>
        );
    }
}
