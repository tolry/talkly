import React from "react";

export default class Index extends React.Component {
    constructor(props) {
        super(props);
    }

    sort(e, value) {
        e.preventDefault();
        this.props.filter('order', value);
    }

    render() {
        const columns = [
            {key: 'title_asc', label: 'A-Z'},
            {key: 'title_desc', label: 'Z-A'},
            {key: 'votes', label: 'Votes'},
            {key: 'newest', label: 'Newest'},
            {key: 'oldest', label: 'Oldest'}
        ];

        let columnMarkup = columns.map((column) => {
            if (this.props.activeSortOrder == column.key) {
                return (
                    <dd className="active"> <a key={column.key} onClick={e => this.sort(e, column.key)}>{column.label}</a></dd>
                );
            }

            return (
                <dd> <a key={column.key} onClick={e => this.sort(e, column.key)}>{column.label}</a></dd>
            );
        });

        return (
            <dl className="sub-nav">
                <dt>Sort</dt>
                {columnMarkup}
            </dl>
        );
    }
}
