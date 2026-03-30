import React from 'react';

export const Input = ({ className = '', inputClassName = '', icon: Icon, hasIcon, ...props }) => {
  return (
    <label className="input focus-within:outline-0 focus-within:border-primary">
       {Icon && (
           <Icon size={18} className="text-gray-400" />
       )}
      <input type="text" className="grow" {...props} />
    </label>
  );
};

export default Input;
