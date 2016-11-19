import React from "react";

export default class TopicListSearchTemplates extends React.Component {
    constructor(props) {
        super(props);
    }

    onChange(e) {
        const value = e.target.value;
        const template = this.template(value);

        e.preventDefault();

        if (template) {
            this.props.activate(template.parameters);
        }
    }

    render() {

        let templateList = this.templates().map((template) => {
            return (
                <option key={template.key} value={template.key}>{template.label}</option>
            );
        });

        return (
            <select onChange={ (e) => this.onChange(e) }>
                {templateList}
            </select>
        );
    }

    templates() {
        return [
            {key: 'empty', label: '', parameters: {}},
            {key: 'most_voted', label: 'Most Voted', parameters: {order: 'votes'}},
            {key: 'upcoming', label: 'Upcoming Talks', parameters: {order: 'schedule_date', scheduled: 'yes'}},
            {key: 'need_schedule', label: 'Ready for Schedule', parameters: {order: 'votes', scheduled: 'no', speaker_found: 'yes'}},
        ];
    }

    template(key) {
        const templates = this.templates();

        for (let i=0; i < templates.length; i++) {
            let template = templates[i];
            if (template.key == key) {
                return template;
            }
        }

        return false;
    }
}
