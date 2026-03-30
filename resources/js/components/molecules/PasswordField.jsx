import React, { useState } from 'react';
import { Label } from '../atoms/Label';
import { Input } from '../atoms/Input';
import { Eye, EyeOff } from 'lucide-react';

export function PasswordField({ label, icon, forgotPasswordUrl, ...props }) {
    const [showPassword, setShowPassword] = useState(false);

    return (
        <div className="space-y-2.5">
            <div className="flex justify-between items-center pl-1">
                <Label className="pl-0">{label}</Label>
            </div>
            <div className="relative group">
                {icon && (
                    <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        {icon}
                    </div>
                )}
                <Input
                    type={showPassword ? 'text' : 'password'}
                    hasIcon={!!icon}
                    hasRightIcon={true}
                    className="tracking-[0.2em] text-lg"
                    {...props}
                />
                <button
                    type="button"
                    className="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-none"
                    onClick={() => setShowPassword(!showPassword)}
                    tabIndex={-1}
                >
                    {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                </button>
            </div>
        </div>
    );
}