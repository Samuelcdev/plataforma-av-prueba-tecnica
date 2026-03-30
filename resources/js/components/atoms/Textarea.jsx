import React from 'react';

const Textarea = ({ className = '', textareaClassName = '', ...props }) => {
  return (
    <div className={`relative w-full ${className}`}>
      <textarea
        className={`textarea focus:outline-0 focus:border-primary resize-none h-20 ${textareaClassName}`}
        {...props}
      />
    </div>
  );
};

export default Textarea;
