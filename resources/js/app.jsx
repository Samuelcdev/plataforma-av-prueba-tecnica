import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/login';
import Dashboard from './pages/dashboard';
import { AuthProvider, useAuth } from './contexts/AuthContext';

const ProtectedRoute = ({ children, allowedRoles }) => {
    const { isAuthenticated, user } = useAuth();

    if (!isAuthenticated) {
        return <Navigate to="/" />;
    }

    if (allowedRoles && !allowedRoles.includes(user?.role_id)) {
        return <Navigate to="/dashboard" />;
    }

    return children;
};

const AppRoutes = () => {
    const { isAuthenticated } = useAuth();

    return (
        <Routes>
            <Route 
                path="/" 
                element={isAuthenticated ? <Navigate to="/dashboard" /> : <Login />} 
            />
            <Route 
                path="/dashboard" 
                element={
                    <ProtectedRoute allowedRoles={[1, 2]}>
                        <Dashboard />
                    </ProtectedRoute>
                } 
            />
            <Route path="*" element={<Navigate to="/" />} />
        </Routes>
    );
};

export default function App() {
    return (
        <AuthProvider>
            <BrowserRouter>
                <AppRoutes />
            </BrowserRouter>
        </AuthProvider>
    );
}
