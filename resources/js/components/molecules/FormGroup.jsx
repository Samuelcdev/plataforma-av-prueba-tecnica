import React from 'react';
import { Label } from '../atoms/Label';
import { Input } from '../atoms/Input';

export function FormGroup({ label, icon, ...props }) {
    return (
        <div className="space-y-2.5">
            <Label>{label}</Label>
            <div className="relative group">
                {icon && (
                    <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#E5A500]">
                        {icon}
                    </div>
                )}
                <Input hasIcon={!!icon} {...props} />
            </div>
        </div>
    );
}