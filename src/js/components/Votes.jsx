import React from "react";

export default ({children}) => {
    return (
        <span><i className="fa fa-thumbs-o-up" /> {children.length}</span>
    );
}