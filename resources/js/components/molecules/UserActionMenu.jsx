import React from 'react';
import Avatar from '../atoms/Avatar';
import Button from '../atoms/Button';
import { Bell, Settings } from 'lucide-react';

const UserActionMenu = ({ user, onLogout }) => {
  return (
    <div className="flex items-center gap-8">
      <nav className="flex space-x-6 h-full">
        <a href="#" className="text-[#D97706] font-semibold text-sm border-b-2 border-[#D97706] py-7">
          Dashboard
        </a>
        <a href="#" className="text-gray-500 hover:text-gray-900 font-medium text-sm py-7 transition-colors">
          Calendario
        </a>
      </nav>
      
      <div className="flex items-center gap-4 border-l border-gray-200 pl-8">
        <button className="relative text-gray-500 hover:text-gray-900 transition-colors">
          <Bell size={20} />
          <span className="absolute top-0 right-0 w-2 h-2 bg-red-500 border border-white rounded-full"></span>
        </button>
        <button className="text-gray-500 hover:text-gray-900 transition-colors">
          <Settings size={20} />
        </button>
        <button 
          onClick={onLogout}
          className="flex items-center gap-2 ml-2 group"
        >
          <Avatar name={user?.username} />
        </button>
      </div>
    </div>
  );
};

export default UserActionMenu;
