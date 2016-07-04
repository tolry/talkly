class AuthorizationStorage {
    setToken(token) {
        this.token = token;
        localStorage.setItem("token", token);
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
    }
}

export default new AuthorizationStorage();