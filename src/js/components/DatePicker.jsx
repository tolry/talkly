import React from "react";
import flatpickr from "flatpickr";

export default class DatePicker extends React.Component {
    constructor(props) {
        super(props);

    }

    componentDidMount() {
        let options = {
            enabletime: this.props.enableTime || false
        };

        flatpickr(this.input, options);
    }

    componentWillUnmount() {

    }

    render() {
        return (
            <input
                ref={(node) => this.input = node}
                name={this.props.name}
                placeholder={this.props.placeholder}
                type="text"
            />
        );
    }
}
