import React, { useState } from 'react';
import { ArrowRight, Lock, User } from 'lucide-react';
import { Button } from '../atoms/Button';
import { FormGroup } from '../molecules/FormGroup';
import { PasswordField } from '../molecules/PasswordField';
import { useAuth } from '../../contexts/AuthContext';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

export function LoginForm() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [processing, setProcessing] = useState(false);
    const [error, setError] = useState(null);
    const { login } = useAuth();
    const navigate = useNavigate();

    const submit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setError(null);
        console.log('que carajobich');

        try {
            let response = await axios.post('/api/v1/auth/login', {
                username,
                password,
                device_name: 'web'
            });

            response = response.data;

            console.log(response);
            if (!response.success) return;

            login(response.data.token, response.data.user);
            navigate('/dashboard');
        } catch (err) {
            setError(err.response?.data?.message || 'Error de autenticación. Verifica tus credenciales.');
        } finally {
            setProcessing(false);
        }
    };

    return (
        <form onSubmit={submit} className="space-y-6">
            {error && (
                <div className="p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-200">
                    {error}
                </div>
            )}
            
            <FormGroup
                label="USUARIO"
                type="text"
                placeholder="Ingresa tu usuario"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                required
                icon={<User size={18} className="text-gray-400 group-focus-within:text-[#D19B00] transition-colors" />}
            />

            <PasswordField
                label="CONTRASEÑA"
                placeholder="••••••••"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                forgotPasswordUrl="#"
                icon={<Lock size={18} className="text-gray-400 group-focus-within:text-[#D19B00] transition-colors" />}
            />

            <div className="pt-2">
                <Button type="submit" disabled={processing} className='w-full'>
                    Entrar
                    <ArrowRight size={18} strokeWidth={2.5} className="group-hover:translate-x-1 transition-transform" />
                </Button>
            </div>
        </form>
    );
}