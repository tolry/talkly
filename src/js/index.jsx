import "babel-polyfill";
import {AppContainer} from "react-hot-loader";
import React from "react";
import {render} from "react-dom";
import App from "./components/App/Router";


const rootEl = document.getElementById('app');

render(
    <AppContainer>
        <App/>
    </AppContainer>,
    rootEl
);

if (module.hot) {
    module.hot.accept('./components/App/Router', () => {
        // If you use Webpack 2 in ES modules mode, you can
        // use <App /> here rather than require() a <NextApp />.
        const NextApp = require('./components/App/Router').default;

        render(
            <AppContainer>
                <NextApp/>
            </AppContainer>,
            rootEl
        );
    });
}