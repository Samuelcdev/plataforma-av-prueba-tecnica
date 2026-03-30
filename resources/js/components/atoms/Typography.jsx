import React from 'react';

export const Typography = ({ variant = 'body', children, className = '', ...props }) => {
  const variants = {
    h1: 'text-[28px] font-bold text-[#1A1A1A] tracking-tight',
    h2: 'text-[20px] font-bold text-gray-900 leading-snug',
    h3: 'text-[11px] font-bold text-gray-500 uppercase tracking-wider',
    metric: 'text-[36px] font-bold text-gray-900 leading-none',
    body: 'text-[14px] text-gray-600 leading-relaxed',
    caption: 'text-[12px] text-gray-500',
    small: 'text-[11px] font-bold uppercase tracking-widest',
  };

  const Component = variant.startsWith('h') ? variant : 'p';

  return (
    <Component className={`${variants[variant]} ${className}`} {...props}>
      {children}
    </Component>
  );
};

export default Typography;
