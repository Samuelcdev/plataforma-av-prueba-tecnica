import React from 'react';
import { AuthTemplate } from '../components/templates/AuthTemplate';
import { LoginForm } from '../components/organisms/LoginForm';

export default function LoginPage() {
    return (
        <AuthTemplate 
            title="Plataforma AV" 
            subtitle="Nos ponemos en tus zapatos"
        >
            <h2 className="text-[22px] font-bold text-[#1A1A1A] mb-2 tracking-tight">Bienvenido de nuevo</h2>
            <p className="text-gray-500 text-[14px] mb-8 leading-relaxed">Ingresa tus credenciales para acceder al panel de control.</p>

            <LoginForm />

            <div className="mt-10 pt-10 border-t border-gray-100/80 text-center">
                <p className="text-[13px] text-gray-500 font-medium tracking-wide">
                    ¿No tienes acceso? <a href="#" className="font-bold text-[#AE802D] hover:underline transition-colors">Contacta a Soporte</a>
                </p>
            </div>
        </AuthTemplate>
    );
}