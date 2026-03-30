import React from 'react';
import SidebarItem from '../molecules/SidebarItem';
import Button from '../atoms/Button';
import { Calendar, Users, FileText, Plus } from 'lucide-react';

const Sidebar = ({ activePath }) => {
  return (
    <aside className="w-64 bg-white border-r border-gray-100 flex flex-col pt-6 flex-shrink-0 z-20">
      <div className="px-6 mb-10">
        <h1 className="text-xl font-bold text-[#1A1A1A] tracking-tight">Plataforma AV</h1>
        <p className="text-xs text-gray-400 mt-0.5">Event Management</p>
      </div>

      <nav className="flex-1 space-y-2">
        <SidebarItem 
          icon={Calendar} 
          label="Eventos" 
          to="/dashboard" 
          active={activePath === '/dashboard'} 
        />
        <SidebarItem 
          icon={Users} 
          label="Personal" 
          to="/personal" 
          active={activePath === '/personal'} 
        />
        <SidebarItem 
          icon={FileText} 
          label="Reportes" 
          to="/reportes" 
          active={activePath === '/reportes'} 
        />
      </nav>

      <div className="p-6">
        <Button variant="primary" className="w-full" icon={Plus}>
          <span className="text-[14px]">Crear Evento</span>
        </Button>
      </div>
    </aside>
  );
};

export default Sidebar;
