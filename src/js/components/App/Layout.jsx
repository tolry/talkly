import React from "react";
import Fork from "../Fork";
import Navigation from "./Navigation";
import Header from "./Header";
import Footer from "./Footer";
import "../../../scss/app.scss";

export default ({children}) => {
    return (
        <div>
            <Fork/>
            <Navigation />
            <Header />

            <main>
                <div id="flash"></div>
                {children}
            </main>

            <Footer />
        </div>
    );
}