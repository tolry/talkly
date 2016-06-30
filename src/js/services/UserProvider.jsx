import Client from "../services/Client";

class UserProvider {
    getUser() {
        if (this.user) {
            return this.user;
        }

        Client.get('/user/current').then(function (response) {
            this.user = response.data;
        }.bind(this));

        return this.user;
    }

    clear() {
        this.user = null;
    }
}

export default new UserProvider();