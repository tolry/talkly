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

        if (!token) {
            return null;
        }

        try {
            var decodedToken = jwtDecode(token);

            return decodedToken.user;
        } catch (e) {
            this.clear();

            return null;
        }
    }

    clear() {
        localStorage.removeItem("token");
    }
}

export default new UserProvider();