import React, { forwardRef } from 'react';

export const Input = forwardRef(
    ({ className = '', hasIcon = false, hasRightIcon = false, ...props }, ref) => {
        return (
            <input
                ref={ref}
                className={`w-full py-4 bg-[#F5F5F5]/60 hover:bg-[#F5F5F5] border border-transparent focus:border-[#F5B505]/30 focus:bg-white rounded-xl text-[#1A1A1A] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#F5B505]/10 transition-all font-medium text-[15px] ${hasIcon ? 'pl-11' : 'pl-4'} ${hasRightIcon ? 'pr-12' : 'pr-4'} ${className}`}
                {...props}
            />
        );
    }
);
Input.displayName = 'Input';