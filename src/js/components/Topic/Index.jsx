import React from "react";
import Client from "../../services/Client";
import Topic from "./Topic";
import AddTopic from "./AddTopic";
import Loading from "../Loading/Loading";
import History from "../../services/History";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

        var search = this.props.location.query.search;

        this.state = {
            loading: true,
            data: []
        };
    }

    componentDidMount() {
        this.load();
    }

    load() {
        Client.get('/api/topic/').then((response) => {
            this.setState({
                loading: false,
                data: response.data
            });
        });
    }

    onSearch(event) {
        let value = event.target.value;
        clearTimeout(this.timer);

        this.timer = setTimeout(() => {
            this.props.location.query.search = value;
            History.push(this.props.location);
        }, 250);
    }

    //componentWillReceiveProps(nextProps) {
        //this.setState({
            //search: nextProps.location.query.search
        //});
    //}

    render() {
        console.log('render', this.state);

        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        let data = this.state.data.filter((topic) => {
            var search = this.props.location.query.search;

            if (search == undefined) {
                return true;
            }

            return topic.title.toLowerCase().includes(search.toLowerCase());
        });

        let topics = data.map((topic) => {
            return (
                <Topic key={topic.id} data={topic}/>
            );
        });

        return (
            <div>
                <AddTopic/>
                <div className="row">
                    <div className="small-12 columns">
                        <h3>Open Topics ({data.length})</h3>
                        <form>
                            <div className="row">
                                <div className="large-6 columns">
                                    <label>
                                        <input type="text" value={this.props.location.query.search} onChange={this.onSearch.bind(this)} placeholder="Search"/>
                                    </label>
                                </div>
                            </div>
                        </form>
                        {topics}
                    </div>
                </div>
            </div>
        );
    }
}
