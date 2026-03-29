import React from 'react';

export function Label({ children, className = '', ...props }) {
    return (
        <label 
            className={`block text-[11px] font-bold text-gray-500 uppercase tracking-widest pl-1 ${className}`}
            {...props}
        >
            {children}
        </label>
    );
}