class TokenStorage {
    setToken(token) {
        localStorage.setItem("token", token);
    }

    getToken() {
        return localStorage.getItem("token");
    }

    clear() {
        localStorage.removeItem("token");
    }
}

export default new TokenStorage();