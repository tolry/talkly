const ERROR = 'error';
const INFO = 'info';
const SUCCESS = 'success';

class MessageBag {
    constructor() {
        this.messages = new Map();
        this.listeners = [];
    }

    add(type, text) {
        let id = createUUID();

        let message = {
            id: id,
            type: type,
            text: text
        };

        this.messages.set(id, message);

        this.notify();

        setTimeout(() => {
            this.remove(id);
        }, 5000);

        return message;
    }

    error(text) {
        this.add(ERROR, text);
    }

    info(text) {
        this.add(INFO, text);
    }

    success(text) {
        this.add(SUCCESS, text);
    }

    all() {
        return Array.from(this.messages.values());
    }

    remove(id) {
        this.messages.delete(id);

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

function createUUID() {
    // http://www.ietf.org/rfc/rfc4122.txt
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    return s.join("");
}

export default new MessageBag();