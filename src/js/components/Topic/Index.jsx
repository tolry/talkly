import React from "react";
import Client from "../../services/Client";
import Topic from "./Topic";
import TopicListSortOrder from "./TopicListSortOrder";
import AddTopic from "./AddTopic";
import Loading from "../Loading/Loading";
import History from "../../services/History";

export default class Index extends React.Component {
    constructor(props) {
        super(props);

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

    search(event) {
        let value = event.target.value;
        let location = this.props.location;

        console.log('typing ' + value);
        clearTimeout(this.timer);

        this.timer = setTimeout(() => {
            console.log('executing search: ' + value);

            location.query.search = value;
            History.push(location);
        }, 500);
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
            <div className="small-3 columns">
            <h4>Filter</h4>
            <hr/>
            <TopicListSortOrder />
            <p>sort by: foo | bar | baz</p>

            <label>
                <input
                    type="text"
                    defaultValue={this.props.location.query.search}
                    onChange={this.search.bind(this)}
                    placeholder="Search"/>
            </label>
            </div>
            <div className="small-9 columns">
            <h4>{data.length} topic(s)</h4>
            <hr/>


            <form>
            <div className="row">
            <div className="large-6 columns">
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

function debounce(fn, delay) {
    var timer = null;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(context, args);
        }, delay);
    };
}

