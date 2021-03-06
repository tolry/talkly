import React from "react";
import Client from "../../services/Client";
import prismjs from "prismjs";
import $ from "jquery";

export default class Markdown extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            loading: true,
            html: null
        };

        this.cache = {};
    }

    componentDidMount() {
        this.parse(this.props.children);
    }

    componentDidUpdate() {
        $(this.el).find('code').each(function() {
            prismjs.highlightElement(this);
        });
    }

    componentWillReceiveProps(nextProps) {
        if (nextProps.children != this.props.children) {
            this.parse(nextProps.children);
        }
    }

    parse(markdown) {
        markdown = markdown.trim();

        if (!markdown) {
            this.setState({
                html: this.props.fallback || '',
                loading: false
            });

            return;
        }

        if (this.cache[markdown]) {
            this.setState({
                html: this.cache[markdown],
                loading: false
            });

            return;
        }

        this.setState({
            html: '',
            loading: true
        });

        if (markdown) {
            Client.post('/api/markdown', {markdown: markdown}).then((response) => {
                this.cache[markdown] = response.data.html;

                this.setState({
                    html: this.cache[markdown],
                    loading: false
                });
            });
        }
    }

    render() {
        if (this.state.loading) {
            return <span>loading...</span>
        }

        return <div ref={el => this.el = el} dangerouslySetInnerHTML={{__html: this.state.html}}/>;
    }
}
