import React from "react";

export default class Duration extends React.Component {
    render() {
        return (
            <span>
                {this.props.showIcon ? <i className="fa fa-clock-o"/> : null}
                &nbsp;
                {this.renderDuration()}
            </span>
        );
    }

    renderDuration() {
        if (!this.props.children) {
            return '--';
        }

        const hours = parseInt(this.props.children / 60, 10);

        if (hours < 2) {
            return this.props.children + ' min';
        }

        const minutes = this.props.children - hours * 60;
        const string = hours + ' hrs';

        if (!minutes) {
            return string;
        }

        return string + ' ' + minutes + ' min';
    }
}
