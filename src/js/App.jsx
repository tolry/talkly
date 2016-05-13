import 'babel-polyfill';
import React from 'react';
import ReactDOM from 'react-dom';
import Routes from './Routes';

// ID of the DOM element to mount app on
const DOM_APP_EL_ID = 'app';

// Render the router
ReactDOM.render((
    <div>
        {Routes}
    </div>
), document.getElementById(DOM_APP_EL_ID));
