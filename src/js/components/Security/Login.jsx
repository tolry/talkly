import React from "react";
import Client from "../../services/Client";
import AuthorizationStorage from "../../services/AuthorizationStorage";
import History from "../../services/History";
import LoginForm from "./LoginForm";

export default class Index extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      error: false
    };
  }

  submit(data) {
    Client.post('/api/login', {
      username: data.username,
      password: data.password
    }).then(response => {
      this.authenticate(response.data.token);
    }).catch(() => {
      this.setState({error: true});

      AuthorizationStorage.clear();
    });
  }

  authenticate(token) {
    AuthorizationStorage.setToken(token);

    Client.get('/api/user/current').then(function (response) {
      AuthorizationStorage.setUser(response.data);

      let redirect = localStorage.getItem('redirect');
      localStorage.removeItem('redirect');

      if (!response.data.name || !response.data.email) {
        History.push('/user/' + response.data.id + "/edit");
      } else if (redirect) {
        History.push(redirect);
      } else {
        History.push('/');
      }

    }).catch(() => {
      this.setState({error: true});

      AuthorizationStorage.clear();
    });
  }

  render() {
    return (
      <main>
        <div className="row">
          <div className="medium-6 medium-centered large-4 large-centered columns">
            {this.state.error ? <div className="alert-box alert">wrong credentials</div> : null}
            <LoginForm submit={this.submit.bind(this)}/>
          </div>
        </div>
      </main>
    );
  }
}
