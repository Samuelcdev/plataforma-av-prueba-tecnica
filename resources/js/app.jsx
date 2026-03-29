import React, { StrictMode } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

export default function app() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<Home />} />
            </Routes>
        </BrowserRouter>
    );
}
