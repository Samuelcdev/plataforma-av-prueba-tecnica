import React from 'react';
import Avatar from '../atoms/Avatar';
import { TrashIcon } from 'lucide-react';
import Swal from 'sweetalert2';

const UserActionMenu = ({ user, onLogout }) => {
  const handleLogout = async () => {
    const result = await Swal.fire({
      title: '¿Cerrar sesión?',
      text: 'Tu sesión actual se cerrará.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
      allowOutsideClick: false,
      allowEscapeKey: false,
      background: 'var(--color-base-100)',
      color: 'var(--color-base-content)',
      confirmButtonColor: 'var(--color-primary)',
      cancelButtonColor: 'var(--color-neutral)',
    });

    if (result.isConfirmed) {
      onLogout?.();
    }
  };

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
          className="dropdown-content menu z-[1] mt-2 w-52 rounded-box bg-base-100 p-2 shadow text-error"
        >
          <li>
            <button type="button" onClick={handleLogout}>
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
