import React from 'react';

export const Button = ({ 
  children, 
  variant = 'primary', 
  className = '', 
  icon: Icon, 
  ...props 
}) => {
  const baseStyles = 'flex items-center justify-center gap-2 rounded-xl transition-all shadow-sm font-semibold transition-colors duration-200';
  
  const variants = {
    primary: 'bg-[#F5B505] hover:bg-[#E5A500] text-[#4A3500] py-3.5 px-4',
    secondary: 'bg-white hover:bg-gray-50 text-gray-800 border border-gray-100 py-2 px-5 text-[13px]',
    ghost: 'text-gray-500 hover:text-gray-900 transition-colors font-medium py-3 px-6 border-l-4 border-transparent hover:bg-gray-50',
    active: 'bg-[#FFF9F0] border-l-4 border-[#E5A500] text-[#E5A500] font-semibold py-3 px-6 transition-colors',
    icon: 'text-gray-300 hover:text-gray-600 p-1',
  };

  return (
    <button 
      className={`${baseStyles} ${variants[variant]} ${className}`} 
      {...props}
    >
      {Icon && <Icon size={18} />}
      {children}
    </button>
  );
};

export default Button;
