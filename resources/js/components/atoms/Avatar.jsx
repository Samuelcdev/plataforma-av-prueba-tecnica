import React from 'react';

export const Avatar = ({ name, src, size = 'md', className = '' }) => {
  const sizes = {
    sm: 'w-7 h-7',
    md: 'w-9 h-9',
    lg: 'w-10 h-10',
  };

  const defaultSrc = `https://ui-avatars.com/api/?name=${encodeURIComponent(name || 'User')}&background=F5B505&color=fff&size=100`;

  return (
    <div className={`${sizes[size]} rounded-full overflow-hidden border border-gray-200 ${className}`}>
      <img src={src || defaultSrc} alt={name} className="w-full h-full object-cover" />
    </div>
  );
};

export default Avatar;
