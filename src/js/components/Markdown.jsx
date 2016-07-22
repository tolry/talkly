import React from "react";
import Client from '../services/Client';

export default class Markdown extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            html: null
        };
    }

    componentDidMount() {
        this.parse(this.props.children);
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.children != this.props.children) {
            this.parse(nextProps.children);
        }
    }

    parse(markdown) {
        Client.post('/api/markdown', {markdown: markdown}).then((response) => {
            console.log(response.data.html);

            this.setState({
                html: response.data.html
            });
        });
    }

    render() {
        return <div dangerouslySetInnerHTML={{ __html: this.state.html}} />;
    }
}
