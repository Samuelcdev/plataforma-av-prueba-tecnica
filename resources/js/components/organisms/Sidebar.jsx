import React from 'react';
import SidebarItem from '../molecules/SidebarItem';
import Button from '../atoms/Button';
import { Calendar, Users, Plus, LayoutDashboard, Building2, Boxes } from 'lucide-react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';

const Sidebar = ({ activePath }) => {
  const { isHotel, isAdmin } = useAuth();
  const navigate = useNavigate();

  return (
    <aside className="w-64 bg-white border-r border-gray-100 flex flex-col pt-6 shrink-0 z-20">
      <div className="px-6 mb-10">
        <h1 className="text-xl font-bold text-[#1A1A1A] tracking-tight">Plataforma AV</h1>
        <p className="text-xs text-gray-400 mt-0.5">Event Management</p>
      </div>

      <nav className="flex-1 space-y-2">
        {/* Administrative, hotel */}
        <SidebarItem 
          icon={LayoutDashboard} 
          label="Dashboard" 
          to="/dashboard" 
          active={activePath === '/dashboard'} 
        />
        {/* Administrative, hotel */}
        <SidebarItem 
          icon={Calendar} 
          label="Eventos" 
          to="/events" 
          active={activePath === '/events'} 
        />
        {/* Administrative */}
        {isAdmin && (
          <SidebarItem
            icon={Users}
            label="Personal"
            to="/personal"
            active={activePath === '/personal'}
          />
        )}
        {/* Administrative */}
        {isAdmin && (
          <SidebarItem 
            icon={Building2} 
            label="Hoteles" 
            to="/hotels" 
            active={activePath === '/hotels'} 
          />
        )}
        {/* Administrative */}
        <SidebarItem
          icon={Boxes}
          label="Items"
          to="/items"
          active={activePath === '/items'}
        />
      </nav>

      {isHotel && (
        <div className="p-6">
          <Button 
            variant="primary" 
            className="w-full" 
            icon={Plus}
            onClick={() => navigate('/events', { state: { openForm: true } })}
          >
            <span className="text-[14px]">Crear Evento</span>
          </Button>
        </div>
      )}
    </aside>
  );
};

export default Sidebar;
