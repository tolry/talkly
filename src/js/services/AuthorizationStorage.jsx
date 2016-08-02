class AuthorizationStorage {

    constructor() {
        this.listeners = [];
    }

    setToken(token) {
        this.token = token;
        localStorage.setItem("token", token);
        this.notify();
    }

    getToken() {
        if (!this.token) {
            this.token = localStorage.getItem("token");
        }

        return this.token;
    }

    setUser(user) {
        this.user = user;
        localStorage.setItem("user", JSON.stringify(user));
        this.notify();
    }

    getUser() {
        if (!this.user) {
            let json = localStorage.getItem("user");

            if (json) {
                this.user = JSON.parse(json);
            }
        }

        return this.user;
    }

    clear() {
        this.token = null;
        this.user = null;
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        this.notify();
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

export default new AuthorizationStorage();