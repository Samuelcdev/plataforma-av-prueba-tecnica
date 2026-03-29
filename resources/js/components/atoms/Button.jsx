import React from 'react';

export function Button({ children, className = '', disabled, ...props }) {
    return (
        <button
            disabled={disabled}
            className={`w-full bg-[#E5A500] hover:bg-[#CC9300] active:scale-[0.98] text-[#332200] font-bold text-[15px] py-4 rounded-xl shadow-[0_6px_20px_rgba(229,165,0,0.3)] hover:shadow-[0_8px_25px_rgba(229,165,0,0.4)] transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed ${className}`}
            {...props}
        >
            {children}
        </button>
    );
}