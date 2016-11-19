import React from "react";

export default class TopicListFacette extends React.Component {
    constructor(props) {
        super(props);
    }

    filter(e, value) {
        e.preventDefault();
        this.props.filter(this.props.name, value);
    }

    render() {
        let variations = {};
        for (let i=0; i < this.props.topics.length; i++) {
            const tupel = this.props.callback(this.props.topics[i]);
            if (variations[tupel.value] === undefined) {
                variations[tupel.value] = tupel;
                variations[tupel.value].count = 0;
            }

            variations[tupel.value].count++;
        }
        variations = Object.keys(variations).map(key => variations[key]);

        let facetteVariations = variations.map((variation) => {
            let active = (variation.value === this.props.activeValue);

            return (
                <li className={ active ? "active" : ""} key={variation.value}><a onClick={e => this.filter(e, active ? '' : variation.value)}>{variation.label} [{variation.count}]</a></li>
            );
        });

        return (
            <div className="search-facette">
                <div className="search-facette-title">{this.props.label}</div>
                <ul className="no-bullet">
                    {facetteVariations}
                </ul>
            </div>
        );
    }
}
