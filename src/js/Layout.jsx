import React from "react";
import Fork from "./components/Fork";
import Navigation from "./partials/Navigation";
import Header from "./partials/Header";
import Footer from "./partials/Footer";
import "../scss/app.scss";

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