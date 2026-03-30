import React from 'react';
import Typography from '../atoms/Typography';
import { TrendingUp } from 'lucide-react';

const MetricCard = ({ title, value, trend, icon: Icon, variant = 'default', color = 'text-green-600' }) => {
  if (variant === 'highlight') {
    return (
      <div className="bg-gradient-to-br from-[#F5B505] to-[#E59500] rounded-[20px] p-6 shadow-lg relative overflow-hidden text-white">
        <div className="absolute -right-4 -bottom-4 opacity-20">
          <div className="w-24 h-24 border-8 border-white rounded-[20px] rotate-12"></div>
          <div className="absolute top-4 left-4 w-24 h-24 border-8 border-white rounded-[20px] rotate-12"></div>
        </div>
        <div className="relative z-10">
          <Typography variant="h3" className="text-[#6D4C00] mb-2">{title}</Typography>
          <Typography variant="metric" className="text-[#3B2600] mb-3">{value}</Typography>
          {trend && (
            <div className="flex items-center gap-1.5 text-[#5A3800]">
               {Icon && <Icon size={16} className="fill-[#5A3800] text-[#F5B505]" />}
               <span className="text-xs font-bold">{trend}</span>
            </div>
          )}
        </div>
      </div>
    );
  }

  return (
    <div className="bg-white rounded-[20px] p-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50">
      <Typography variant="h3" className={`mb-2 ${color.replace('text-', 'text-opacity-70 text-')}`}>{title}</Typography>
      <Typography variant="metric" className="mb-3">{value}</Typography>
      {trend && (
        <div className={`flex items-center gap-1.5 ${color}`}>
          {Icon ? <Icon size={14} strokeWidth={2.5} /> : <TrendingUp size={14} strokeWidth={2.5} />}
          <span className="text-xs font-semibold">{trend}</span>
        </div>
      )}
    </div>
  );
};

export default MetricCard;
