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
                <div className="row">
                    <div className="small-12 columns">
                        <div id="flash"></div>
                        {children}
                    </div>
                </div>
            </main>

            <Footer />
        </div>
    );
}