import React from 'react';
import Avatar from '../atoms/Avatar';
import Button from '../atoms/Button';
import { Bell, Settings } from 'lucide-react';
import { Link } from 'react-router-dom';

const UserActionMenu = ({ user, onLogout }) => {
  return (
    <div className="flex items-center gap-8">
        <button 
          onClick={onLogout}
          className="flex items-center gap-2 ml-2 group"
        >
          <Avatar name={user?.username} />
        </button>
    </div>
  );
};

export default UserActionMenu;
