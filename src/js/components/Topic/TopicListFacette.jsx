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
            if (variation.value === this.props.activeValue) {
                return (
                    <li key={variation.value}><a className="label" onClick={e => this.filter(e, '')}>x {variation.label}</a></li>
                );
            }

            return (
                <li key={variation.value}><a onClick={e => this.filter(e, variation.value)}>{variation.label} [{variation.count}]</a></li>
            );
        });

        return (
            <div>
                <h6>{this.props.label}</h6>
                <ul className="no-bullet">
                    {facetteVariations}
                </ul>
            </div>
        );
    }
}
