import {createHistory, useBasename} from "history";
import {useRouterHistory} from "react-router";

export const base = document.getElementById('app').getAttribute('data-base');

export const history = useRouterHistory(createHistory)({
    basename: base + '/'
});

export default history;