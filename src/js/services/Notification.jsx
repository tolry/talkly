import Client from "./Client";
import AuthStorage from "./AuthorizationStorage";

class Notification {
    constructor() {
        this.notifications = new Map();
        this.listeners = [];

        this.update();

        let updater = () => {
            if (AuthStorage.getUser()) {
                this.interval = setInterval(() => this.update(), 5000);
            } else {
                clearInterval(this.interval);
            }
        };

        AuthStorage.subscribe(updater);

        updater();
    }

    all() {
        return Array.from(this.notifications.values());
    }

    done(id) {
        Client.get('/api/notification/' + id + '/mark-read').then(function (response) {
            this.notifications.delete(id);
            this.notify();
        }.bind(this));
    }

    update() {
        Client.get('/api/notification/').then(function (response) {
            this.notifications.clear();

            for (let notification of response.data) {
                this.notifications.set(notification.id, notification);
            }

            this.notify();
        }.bind(this));
    }

    subscribe(listener) {
        this.listeners.push(listener);
    }

    unsubscribe(listener) {
        this.listeners = this.listeners.filter((l) => {
            return listener != l
        });
    }

    notify() {
        for (let listener of this.listeners) {
            listener();
        }
    }
}

export default new Notification();