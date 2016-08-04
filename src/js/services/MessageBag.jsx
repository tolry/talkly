import {createUUID} from "./util";

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

export default new MessageBag();