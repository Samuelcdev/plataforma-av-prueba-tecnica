import React from 'react';
import Input from '../atoms/Input';
import UserActionMenu from '../molecules/UserActionMenu';
import { Search } from 'lucide-react';

const Topbar = ({ user, onLogout, onSearch }) => {
  return (
    <header className="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-8 flex-shrink-0">
      <div className="relative w-96">
        <Input 
          icon={Search} 
          placeholder="Buscar eventos..." 
          onChange={(e) => onSearch(e.target.value)}
        />
      </div>

      <UserActionMenu user={user} onLogout={onLogout} />
    </header>
  );
};

export default Topbar;
