import React from 'react';
import MetricCard from '../molecules/MetricCard';
import { Clock, CheckCircle2 } from 'lucide-react';

const StatsGrid = ({ stats }) => {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <MetricCard 
        title="TOTAL EVENTOS" 
        value={stats.total} 
        trend="+12% vs mes anterior" 
        color="text-green-600"
      />
      <MetricCard 
        title="CONFIRMADOS" 
        value={stats.confirmed} 
        trend="SLA Cumplido"
        icon={CheckCircle2}
        color="text-[#A67B27]"
      />
      <MetricCard 
        title="PENDIENTES" 
        value={stats.pending} 
        trend="Requieren aprobación"
        icon={Clock}
        color="text-gray-500"
      />
    </div>
  );
};

export default StatsGrid;
