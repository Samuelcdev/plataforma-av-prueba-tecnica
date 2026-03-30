import React from 'react';

const Textarea = ({ className = '', textareaClassName = '', ...props }) => {
  return (
    <div className={`relative w-full ${className}`}>
      <textarea
        className={`w-full px-4 py-2.5 bg-[#F5F5F5] border-transparent rounded-xl text-sm text-gray-800 focus:bg-white focus:border-gray-200 focus:ring-2 focus:ring-[#F5B505]/20 focus:outline-none transition-all placeholder:text-gray-400 resize-none ${textareaClassName}`}
        {...props}
      />
    </div>
  );
};

export default Textarea;
