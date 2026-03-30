import React from 'react';
import Avatar from '../atoms/Avatar';
import { TrashIcon } from 'lucide-react';

const UserActionMenu = ({ user, onLogout }) => {
  return (
    <div className="flex items-center">
      <div className="dropdown dropdown-end">
        <div
          tabIndex={0}
          role="button"
          className="btn btn-ghost px-2"
        >
          <Avatar name={user?.username} />
          <span className="hidden text-sm font-medium text-base-content sm:inline">
            {user?.username || 'Usuario'}
          </span>
        </div>
        <ul
          tabIndex={0}
          className="dropdown-content menu z-1 mt-2 w-52 rounded-box bg-base-100 p-2 shadow text-error"
        >
          <li>
            <button type="button" onClick={onLogout}>
              <TrashIcon size={14} />
              Cerrar sesión
            </button>
          </li>
        </ul>
      </div>
    </div>
  );
};

export default UserActionMenu;
