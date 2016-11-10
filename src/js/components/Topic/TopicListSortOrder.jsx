import React from "react";

export default class Index extends React.Component {
    constructor(props) {
        super(props);
    }

    changeSortOrder(key) {
        console.log('changeSortOrder');
        console.log(key);

        this.props.location.query.order = key;
        History.push(this.props.location);
    }

    render() {
        return (
            <ul>
                <li key="foo"><a href="" onClick={() => { this.changeSortOrder("foo"); } }>foo</a></li>
                <li key="bar"><a href="" onClick={() => { this.changeSortOrder("bar"); } }>bar</a></li>
                <li key="baz"><a href="" onClick={() => { this.changeSortOrder("baz"); } }>baz</a></li>
            </ul>
        );
    }
}
