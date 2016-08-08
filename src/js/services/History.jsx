import {createHistory, useBasename} from "history";
import {useRouterHistory} from "react-router";
import {base} from "./Parameters";

export const history = useRouterHistory(createHistory)({
    basename: base + '/'
});

export default history;