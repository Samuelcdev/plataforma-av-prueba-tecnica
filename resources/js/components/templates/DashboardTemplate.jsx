import React from 'react';
import Sidebar from '../organisms/Sidebar';
import Topbar from '../organisms/Topbar';

const DashboardTemplate = ({ 
  user, 
  onLogout, 
  onSearch, 
  activePath, 
  children,
  headerActions
}) => {
  return (
    <div className="flex h-screen bg-[#FDFBF7] font-sans overflow-hidden">
      <Sidebar activePath={activePath} />

      <main className="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
        <Topbar user={user} onLogout={onLogout} onSearch={onSearch} />

        <div className="flex-1 overflow-auto bg-[#FDFBF7] p-8 scroller">
          <div className="max-w-[1280px] mx-auto w-full space-y-8">
            <div className="flex justify-between items-end">
              {children[0]} {/* Page Header */}
              {headerActions}
            </div>
            
            {children.slice(1)}
          </div>
        </div>
      </main>
    </div>
  );
};

export default DashboardTemplate;
