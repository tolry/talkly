import React from "react";
import Client from '../../services/Client';
import Loading from "../Loading/Loading";

export default class Markdown extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
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
        this.setState({
            html: '',
            loading: true
        });

        Client.post('/api/markdown', {markdown: markdown}).then((response) => {
            console.log(response.data.html);

            this.setState({
                html: response.data.html,
                loading: false
            });
        });
    }

    render() {
        if (this.state.loading) {
            return <Loading size={0.5}/>
        }

        return <div dangerouslySetInnerHTML={{ __html: this.state.html}} />;
    }
}
