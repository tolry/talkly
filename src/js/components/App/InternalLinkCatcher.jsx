import React from "react";
import History from "../../services/History";
import {base} from "../../services/Parameters";

export default class InternalLinkCatcher extends React.Component {
  click(e) {
    if (e.button !== 0) {
      return;
    }

    const href = e.target.getAttribute('href');

    if (!href) {
      return;
    }

    const url = location.origin + base;

    if (href.indexOf(url) !== 0) {
      return;
    }

    const relativeUrl = href.replace(url, '');

    History.push(relativeUrl);
    e.preventDefault();
  }

  render() {
    return (
      <div onClick={this.click.bind(this)}>{this.props.children}</div>
    );
  }
}
