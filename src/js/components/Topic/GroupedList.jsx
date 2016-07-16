import React from "react";
import Topic from "./Topic";

export default class GroupedList extends React.Component {

    render() {
        return (
            <div className="row">
                {this.renderLeftSide()}
                {this.renderRightSide()}
            </div>
        );
    }

    renderLeftSide() {
        let list = [];

        for (let month in this.props.data) {
            if(!this.props.data.hasOwnProperty(month)) {
                continue;
            }

            let href = '#month-' + month;

            list.push(
                <li key={month}>
                    <a href={href}>
                        {month}
                    </a>
                </li>
            );
        }

        return (
            <div className="large-4 small-12 columns">
                <h3><i className="fa fa-calendar"/> Months</h3>
                <hr/>

                <ul>
                    {list}
                </ul>
            </div>
        );
    }

    renderRightSide() {
        let output = [];

        for (let month in this.props.data) {
            if(!this.props.data.hasOwnProperty(month)) {
                continue;
            }

            let list = this.props.data[month];
            let id = '#month-' + month;

            output.push(
                <h4 className="highlight-target" id={id} key={month}>{month}</h4>
            );

            for (let topic of list) {
                output.push(
                    <Topic key={topic.id} data={topic}/>
                );
            }
        }

        return (
            <div className="large-8 small-12 columns">
                <h3>Next Topics</h3>
                <hr />

                {output}
            </div>
        );
    }
}