import React from "react";
import Client from "../../services/Client";
import Topic from "./Topic";
import TopicListSortOrder from "./TopicListSortOrder";
import TopicListSearchTemplates from "./TopicListSearchTemplates";
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

    activateTemplate(queryParameters) {
        let location = this.props.location;

        location.query = queryParameters;
        History.push(location);
    }

    render() {
        console.log('render', this.state);
        console.log('render:query', this.props.location.query);

        if (this.state.loading) {
            return <Loading size="0.5"/>;
        }

        // @todo generate partially automatic using this.getFacettes()
        let filterCriteria = {
            search: this.props.location.query.search,
            speaker_found: this.props.location.query.speaker_found,
            author: this.props.location.query.author,
            scheduled: this.props.location.query.scheduled,
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
                        <h4>Filter <small>{data.length} topic(s)</small></h4>

                        <TopicListSearchTemplates
                            activate={(queryParameters) => this.activateTemplate(queryParameters) }
                        />

                        <hr />

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
                        <TopicListSortOrder
                            filter={(key, value) => this.setFilter(key, value)}
                            activeSortOrder={filterCriteria.order} />

                        <div className="float-right"></div>

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
            case 'schedule_date':
                column = 'lectureDate';
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
            speaker_found: {
                label: "Speaker?",
                callback: (topic) => {
                    if (topic.speakers.length > 0) {
                        return { value: 'yes', label: 'speaker found' };
                    }

                    return { value: 'no', label: 'looking for speaker' };
                }
            },
            scheduled: {
                label: "Scheduled?",
                callback: (topic) => {
                    if (topic.lectureDate) {
                        return { value: 'yes', label: 'already scheduled' };
                    }

                    return { value: 'no', label: 'not yet scheduled' };
                }
            },
            author: {
                label: "Author",
                callback: (topic) => {
                    const author = topic.createdBy;
                    if (author) {
                        return { value: author.id.toString(), label: author.name };
                    }

                    return { value: '-1', label: 'unknown' };
                }
            }
        };
    }
}
