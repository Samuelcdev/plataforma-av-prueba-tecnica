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
  required = false,
  disabled = false
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
          disabled={disabled}
          className={`select focus:outline-0 focus:border-primary ${error ? 'ring-2 ring-red-500/50' : ''}`}
        >
          <option value="" disabled>{placeholder}</option>
          {options.map((option) => (
            <option key={option.value} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>
      </div>
      {error && <span className="text-[12px] text-red-500 font-medium">{error}</span>}
    </div>
  );
};

export default Select;
