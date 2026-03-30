import React from 'react';
import Button from '../atoms/Button';

const FilterPills = ({ options, activeOption, onSelect }) => {
  return (
    <div className="flex bg-white rounded-full p-1 border border-gray-100 shadow-sm">
      {options.map((option) => (
        <button
          key={option.value}
          onClick={() => onSelect(option.value)}
          className={`px-5 py-2 rounded-full text-[13px] transition-all duration-200 flex items-center gap-1.5 ${
            activeOption === option.value
              ? 'bg-white shadow-sm border border-gray-100 font-semibold text-gray-800'
              : 'font-medium text-gray-500 hover:text-gray-800'
          }`}
        >
          {option.label === 'Live' && <span className="w-2 h-2 bg-red-500 rounded-full"></span>}
          {option.label}
        </button>
      ))}
    </div>
  );
};

export default FilterPills;
