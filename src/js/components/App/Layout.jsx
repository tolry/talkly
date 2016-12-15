import React from "react";
import Fork from "../Fork";
import Navigation from "./Navigation";
import Header from "./Header";
import Footer from "./Footer";
import MessageList from "../Message/MessageList";
import InternalLinkCatcher from "./InternalLinkCatcher";
import "../../../scss/app.scss";

export default ({children}) => {
  return (
    <InternalLinkCatcher>
      <Fork/>
      <Navigation />
      <Header />

      <MessageList/>

      <main>
        <div id="flash"></div>
        {children}
      </main>

      <Footer />
    </InternalLinkCatcher>
  );
}