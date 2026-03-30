import React from 'react';

export const Input = ({ className = '', icon: Icon, hasIcon, ...props }) => {
  return (
    <div className={`relative ${className} w-full`}>
      {Icon && (
        <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
          <Icon size={18} className="text-gray-400" />
        </div>
      )}
      <input
        className={`w-full ${hasIcon || Icon ? 'pl-10' : 'px-4'} pr-4 py-2.5 bg-[#F5F5F5] border-transparent rounded-xl text-sm text-gray-800 focus:bg-white focus:border-gray-200 focus:ring-2 focus:ring-[#F5B505]/20 focus:outline-none transition-all placeholder:text-gray-400`}
        {...props}
      />
    </div>
  );
};

export default Input;
