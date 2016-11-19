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
        variations = variations.sort((first, second) => {
            return second.count - first.count;
        });

        let facetteVariations = variations.slice(0, 10).map((variation) => {
            let active = (variation.value === this.props.activeValue);

            return (
                <li className={ active ? "active" : ""} key={variation.value}>
                    <a className="one-line" onClick={e => this.filter(e, active ? '' : variation.value)}>
                        <i className={"fa " + (active ? "fa-check-circle-o" : "fa-circle-o")}></i>&nbsp;
                        {variation.label} {active ? "" : ("[" + variation.count + "]")}
                    </a>
                </li>
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
