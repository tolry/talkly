import React from "react";
import TopicForm from "./TopicForm";
import Client from '../../services/Client';
import History from "../../services/History";
import MessageBag from "../../services/MessageBag";

export default class AddTopic extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            open: false
        };
    }

    toggle() {
        this.setState({
            open: !this.state.open
        });
    }

    submit(data) {
        Client.post('/api/topic/create', data).then((response) => {
            MessageBag.success('Success');
            History.push('/topic/' + response.data.id);
        });
    }

    render() {
        return (
            <div>
                <div className="row">
                    <div className="small-12 medium-8 medium-centered large-6 large-centered columns text-center">
                        <a className="button radius" onClick={() => this.toggle()}>
                            {this.renderButton()}
                        </a>
                    </div>
                </div>

                {this.renderForm()}
            </div>
        );
    }

    renderButton() {
        if (this.state.open) {
            return (
                <div>
                    <i className="fa fa-angle-double-up"/>
                    &nbsp;
                    cancel suggestion
                    &nbsp;
                    <i className="fa fa-angle-double-up"/>
                </div>
            );
        }

        return (
            <div>
                <i className="fa fa-angle-double-down"/>
                &nbsp;
                add a suggestion
                &nbsp;
                <i className="fa fa-angle-double-down"/>
            </div>
        );
    }

    renderForm() {
        if (!this.state.open) {
            return null;
        }

        return (
            <div className="bg-secondary" id="create-topic-form">
                <div className="row">
                    <div className="small-12 medium-8 medium-centered large-8 large-centered columns">
                        <TopicForm submit={this.submit.bind(this)}/>
                    </div>
                </div>
            </div>
        );
    }
}