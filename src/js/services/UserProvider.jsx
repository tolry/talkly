import jwtDecode from "jwt-decode";

class UserProvider {
    setToken(token) {
        localStorage.setItem("token", token);
    }

    getToken() {
        return localStorage.getItem("token");
    }

    getUser() {
        var token = this.getToken();
        var decodedToken = jwtDecode(token);

        return decodedToken.user;
    }

    clear() {
        localStorage.removeItem("token");
    }
}

export default new UserProvider();