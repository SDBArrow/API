import React, { Component } from 'react';
import Sign from './Sign';
import Register from './Register';
import Forget from './Forget';
import Error from './Error';
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";


class Body extends Component {
    state = {}
    render() {
        return (
            <Router>
                <Routes>
                    <Route path="/" element={<Sign />} />
                    <Route path="/Register" element={<Register />} />
                    <Route path="/Forget" element={<Forget />} />
                    <Route path="*" element={<Error />} />
                </Routes>
            </Router>
        );
    }
}

export default Body;



