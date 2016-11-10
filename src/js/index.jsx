import "babel-polyfill";
import React from "react";
import {render} from "react-dom";
import App from "./components/App/Router";

const rootEl = document.getElementById('app');

render(
  <App/>,
  rootEl
);