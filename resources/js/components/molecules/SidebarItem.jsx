import React from 'react';
import { Link } from 'react-router-dom';
import Button from '../atoms/Button';

const SidebarItem = ({ icon, label, to, active }) => {
  return (
    <Link to={to} className="block w-full">
      <Button 
        variant={active ? 'active' : 'ghost'} 
        className="w-full justify-start rounded-none"
        icon={icon}
      >
        <span className="text-[14px]">{label}</span>
      </Button>
    </Link>
  );
};

export default SidebarItem;
