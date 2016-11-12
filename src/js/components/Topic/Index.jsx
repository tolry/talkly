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

        clearTimeout(this.timer);

        this.timer = setTimeout(() => {
            location.query.search = value;
            History.push(location);
        }, 500);
    }

    setFilter(key, value) {
        let location = this.props.location;

        location.query[key] = value;
        History.push(location);
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

        let filterCriteria = {
            search: this.props.location.query.search,
            order: this.props.location.query.order
                ? this.props.location.query.order
                : 'newest'
        };

        let data = this.state.data;
        data = this.filterTopics(data, filterCriteria);
        data = this.sortTopics(data, filterCriteria.order);

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
                    <label>
                        <input
                            type="text"
                            defaultValue={filterCriteria.search}
                            onChange={(e) => { this.search(e); }}
                            placeholder="Search"/>
                    </label>
                </div>
                <div className="small-9 columns">
                <h4>{data.length} topic(s)</h4>
                <hr/>

                <TopicListSortOrder
                    filter={(key, value) => this.setFilter(key, value)}
                    activeSortOrder={filterCriteria.order} />

                {topics}
                </div>
            </div>
        </div>
        );
    }

    filterTopics(topics, criteria) {
        return topics.filter((topic) => {
            var search = criteria.search;

            if (search != undefined && search.length > 0 && ! topic.title.toLowerCase().includes(search.toLowerCase())) {
                return false;
            }

            return true;
        });
    }

    sortTopics(topics, sortOrder) {
        let column = 'title';
        let direction = 'asc';
        let length = false;

        switch (sortOrder) {
            case 'title_asc':
                column = 'title';
                break;
            case 'title_desc':
                column = 'title';
                direction = 'desc';
                break;
            case 'votes':
                column = 'votes';
                direction = 'desc';
                length = true;
                break;
            case 'newest':
                column = 'createdAt';
                direction = 'desc';
                break;
            case 'oldest':
                column = 'createdAt';
                direction = 'asc';
                break;
        }

        console.log('topic', topics[0]);
        return topics.sort((topicA, topicB) => {
            let valueA = topicA[column];
            let valueB = topicB[column];

            if (length) {
                valueA = valueA.length;
                valueB = valueB.length;
            }

            if (valueA > valueB) {
                return direction == 'asc'
                    ? 1
                    : -1;
            }

            if (valueA < valueB) {
                return direction == 'asc'
                    ? -1
                    : 1;
            }

            return 0;
        });
    }
}
