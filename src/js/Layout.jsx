import React from "react";
import Fork from "./components/Fork";
import "../scss/app.scss";

export default ({children}) => {
    return (
        <div>
            <Fork/>

            <nav class="fixed" headroom>
                test
            </nav>

            <header>
                <div class="row">
                    <div class="large-12 columns text-center">
                        <h1><i class="fa fa-bullhorn"/> talkly</h1>

                        <h2>
                            <small>request, vote &amp; deliver the talks you like to hear</small>
                        </h2>
                    </div>
                </div>
            </header>

            <main>
                <div class="row">
                    <div class="small-12 columns">
                        <div id="flash"></div>

                        {children}
                    </div>
                </div>
            </main>

            <footer class="text-center">
                <div class="row">
                    <div class="large-12 columns">
                        Fork me on <a href="https://github.com/tolry/talkly">GitHub</a>
                    </div>
                </div>
            </footer>
        </div>
    );
}