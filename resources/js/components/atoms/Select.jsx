import React from 'react';

const Select = ({ 
  label, 
  name, 
  value, 
  onChange, 
  options = [], 
  placeholder = "Seleccione una opción", 
  error, 
  className = "",
  required = false
}) => {
  return (
    <div className={`flex flex-col gap-2 ${className}`}>
      {label && (
        <label htmlFor={name} className="text-[14px] font-bold text-[#1A1A1A]">
          {label} {required && <span className="text-red-500">*</span>}
        </label>
      )}
      <div className="relative">
        <select
          id={name}
          name={name}
          value={value}
          onChange={onChange}
          required={required}
          className={`w-full bg-[#F6F5F2] border-0 rounded-xl px-4 py-3.5 text-[15px] text-gray-800 focus:ring-2 focus:ring-[#AE802D]/30 transition-shadow outline-none appearance-none cursor-pointer ${error ? 'ring-2 ring-red-500/50' : ''}`}
        >
          <option value="" disabled>{placeholder}</option>
          {options.map((option) => (
            <option key={option.value} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
        <div className="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500 font-bold">
          ↓
        </div>
      </div>
      {error && <span className="text-[12px] text-red-500 font-medium">{error}</span>}
    </div>
  );
};

export default Select;
