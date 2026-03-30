import React from 'react';
import { Search } from 'lucide-react';
import Input from '../atoms/Input';

const SearchInput = ({
  value,
  onChange,
  placeholder = 'Buscar...',
  className = '',
  inputClassName = '',
  disabled = false,
  name = 'search',
}) => {
  return (
    <Input
      type="text"
      name={name}
      value={value}
      onChange={onChange}
      placeholder={placeholder}
      icon={Search}
      disabled={disabled}
      className={className}
      inputClassName={inputClassName}
    />
  );
};

export default SearchInput;
