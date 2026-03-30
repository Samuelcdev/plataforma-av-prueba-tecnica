import React from 'react';

export const Badge = ({ children, variant = 'default', className = '' }) => {
  const baseStyles = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-bold border';
  
  const variants = {
    default: 'bg-gray-100 text-gray-600 border-transparent',
    success: 'bg-green-50 text-green-700 border-green-100/50',
    warning: 'bg-[#FFF4DC] text-[#AE7E15] border-[#FDF2D7]',
    danger: 'bg-red-50 text-red-600 border-red-100',
    live: 'bg-red-50 text-red-600 border-red-100 uppercase',
  };

  const dotColor = variant === 'live' ? 'bg-red-500' : '';

  return (
    <span className={`${baseStyles} ${variants[variant]} ${className}`}>
      {variant === 'live' && <span className={`w-1.5 h-1.5 rounded-full ${dotColor} flex-shrink-0 animate-pulse`}></span>}
      {children}
    </span>
  );
};

export default Badge;
