import React from 'react';
import Badge from '../atoms/Badge';
import Typography from '../atoms/Typography';
import { Video, MoreVertical, MapPin, ChevronLeft, ChevronRight } from 'lucide-react';
import Button from '../atoms/Button';

const EventsTable = ({ orders, hotels = [] }) => {
  const getHotelName = (hotelId) => {
    const hotel = hotels.find(h => h.id === hotelId);
    return hotel ? hotel.name : 'Unknown Hotel';
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
  };

  const formatTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
  };

  return (
    <div className="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50 overflow-hidden">
      <table className="w-full">
        <thead>
          <tr className="bg-gray-50/50 border-b border-gray-100">
            <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[35%]">Nombre del Evento</th>
            <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[15%]">Fecha & Hora</th>
            <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[15%]">Tipo</th>
            <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[15%]">Ubicación</th>
            <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[15%]">Estado</th>
            <th className="text-right px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider w-[5%]">Acciones</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-gray-100">
          {orders.map((order) => (
            <tr key={order.id} className="hover:bg-gray-50/50 transition-colors group">
              <td className="px-6 py-4 flex items-center gap-4">
                <div className="w-10 h-10 rounded-xl bg-[#FFF6E0] text-[#E5A500] flex items-center justify-center flex-shrink-0">
                  <Video size={18} />
                </div>
                <div>
                  <div className="font-bold text-gray-900 text-[14px]">{order.name}</div>
                  <div className="text-[12px] text-gray-500 mt-0.5">ID: {order.id.slice(0, 8).toUpperCase()}</div>
                </div>
              </td>
              <td className="px-6 py-4 align-middle">
                <div className="text-[13px] font-medium text-gray-900">{formatDate(order.start_date)}</div>
                <div className="text-[12px] text-gray-500 mt-0.5">{formatTime(order.start_date)} - {formatTime(order.end_date)}</div>
              </td>
              <td className="px-6 py-4 align-middle">
                <span className="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-[12px] font-medium">
                  {order.service_type}
                </span>
              </td>
              <td className="px-6 py-4 align-middle">
                <div className="flex items-center gap-1.5 text-gray-600 text-[13px]">
                  <MapPin size={14} className="text-gray-400" />
                  <span>{getHotelName(order.hotel_id)}</span>
                </div>
              </td>
              <td className="px-6 py-4 align-middle">
                <Badge variant={order.status === 'active' ? 'success' : order.status === 'live' ? 'live' : 'warning'}>
                  {order.status === 'active' ? 'Confirmado' : order.status === 'live' ? 'LIVE' : 'Pendiente'}
                </Badge>
              </td>
              <td className="px-6 py-4 align-middle text-right">
                <Button variant="icon" icon={MoreVertical} />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      
      {/* Pagination Footer */}
      <div className="px-6 py-4 border-t border-gray-100 bg-[#FAFAFA] flex items-center justify-between">
        <p className="text-[12px] text-gray-500 font-medium">
          Mostrando <span className="font-bold text-gray-700">1 - {orders.length}</span> de {orders.length} eventos
        </p>
        <div className="flex items-center gap-2">
          <button className="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 bg-white hover:bg-gray-50 transition-colors">
            <ChevronLeft size={16} />
          </button>
          <div className="flex items-center gap-1.5 mx-2 pb-0.5">
            <button className="w-7 h-7 flex items-center justify-center rounded-lg bg-[#F5B505] text-[#332200] font-bold text-[13px] shadow-sm">1</button>
          </div>
          <button className="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 transition-colors">
            <ChevronRight size={16} />
          </button>
        </div>
      </div>
    </div>
  );
};

export default EventsTable;
