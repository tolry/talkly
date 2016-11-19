import React from "react";
import Client from "../../services/Client";
import Topic from "./Topic";
import TopicListSortOrder from "./TopicListSortOrder";
import TopicListFacette from "./TopicListFacette";
import AddTopic from "./AddTopic";
import Loading from "../Loading/Loading";
import History from "../../services/History";
import _ from "lodash";

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

    render() {
        console.log('render', this.state);

        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        let filterCriteria = {
            search: this.props.location.query.search,
            speaker: this.props.location.query.speaker,
            order: this.props.location.query.order || 'newest'
        };

        let data = this.state.data;
        data = this.filterTopics(data, filterCriteria);
        data = this.sortTopics(data, filterCriteria.order);

        let topics = data.map((topic) => {
            return (
                <Topic key={topic.id} data={topic}/>
            );
        });

        let facettes = [];
        _.forOwn(this.getFacettes(), (value, key) => {
            facettes.push((
                <TopicListFacette
                    key={key}
                    topics={data}
                    name={key}
                    label={value.label}
                    filter={(key, value) => this.setFilter(key, value)}
                    activeValue={filterCriteria[key]}
                    callback={value.callback}
                />
            ));
        });

        return (
            <div>
                <AddTopic />
                <div className="row">
                    <div className="small-3 columns">
                        <h4>Filter</h4><hr/>
                        <label>
                            <input
                                type="text"
                                defaultValue={filterCriteria.search}
                                onChange={(e) => { this.search(e); }}
                                placeholder="Search"/>
                        </label>
                        {facettes}
                    </div>
                    <div className="small-9 columns">
                        <h4>{data.length} topic(s)</h4><hr/>

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
            const search = criteria.search;
            const facettes = this.getFacettes();

            if (search != undefined && search.length > 0 && ! topic.title.toLowerCase().includes(search.toLowerCase())) {
                return false;
            }

            for (let key in facettes) {
                if (! facettes.hasOwnProperty(key)) {
                    continue;
                }

                if (! criteria[key]) {
                    continue;
                }

                let tupel = facettes[key].callback(topic);
                if (tupel.value !== criteria[key]) {
                    return false;
                }
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

    getFacettes() {
        return {
            speaker: {
                label: "Speaker",
                callback: (topic) => {
                    if (topic.speakers.length > 0) {
                        return { value: 'yes', label: 'speaker found' };
                    }

                    return { value: 'no', label: 'looking for speaker' };
                }
            }
        };
    }
}
