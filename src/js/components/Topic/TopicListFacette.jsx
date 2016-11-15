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
        console.log('props', this.props);

        let variations = {};
        for (let i=0; i < this.props.topics.length; i++) {
            let tupel = this.props.callback(this.props.topics[i]);
            if (variations[tupel.value] === undefined) {
                variations[tupel.value] = tupel;
                variations[tupel.value].count = 0;
            }

            variations[tupel.value].count++;
        }
        variations = Object.keys(variations).map(key => variations[key]);
        console.log('variations', variations);

        let facetteVariations = variations.map((variation) => {
            if (variation.value === this.props.activeValue) {
                return (
                    <li><a key={variation.value} onClick={e => this.filter(e, '')}>x {variation.label}</a></li>
                );
            }

            return (
                <li><a key={variation.value} onClick={e => this.filter(e, variation.value)}>{variation.label} [{variation.count}]</a></li>
            );
        });

        console.log('facetteVariations', facetteVariations);

        return (
            <div>
                <h4>{this.props.label}</h4>
                <ul>
                    {facetteVariations}
                </ul>
            </div>
        );
    }
}
