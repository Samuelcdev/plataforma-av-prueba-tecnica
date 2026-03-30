import React, { useState, useMemo } from 'react';
import { Search, Edit2, Trash2 } from 'lucide-react';
import Badge from '../atoms/Badge';
import Typography from '../atoms/Typography';
import Button from '../atoms/Button';

const HotelsTable = ({ hotels = [], loading = false, onRowClick, onDeleteClick, onCreateClick }) => {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredHotels = useMemo(() => {
    if (!searchTerm.trim()) return hotels;
    
    const term = searchTerm.toLowerCase();
    return hotels.filter(hotel => 
      hotel.name.toLowerCase().includes(term) || 
      hotel.nit.toLowerCase().includes(term)
    );
  }, [hotels, searchTerm]);

  const getStatusBadge = (hotel) => {
    return <Badge variant="success">Activo</Badge>;
  };

  return (
    <div className="bg-white rounded-[20px] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-50 overflow-hidden">
      {/* Header con búsqueda y botón crear */}
      <div className="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div className="flex-1 max-w-md">
          <div className="relative">
            <Search size={18} className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              placeholder="Buscar por nombre o NIT..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent"
            />
          </div>
        </div>
        <Button 
          variant="primary" 
          className="ml-4"
          onClick={onCreateClick}
        >
          + Crear Hotel
        </Button>
      </div>

      {/* Tabla */}
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead>
            <tr className="bg-gray-50/50 border-b border-gray-100">
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">NIT</th>
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nombre</th>
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Teléfono</th>
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Dirección</th>
              <th className="text-left px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Estado</th>
              <th className="text-right px-6 py-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {loading ? (
              <tr>
                <td colSpan="7" className="px-6 py-8 text-center text-gray-500">
                  <div className="flex justify-center items-center">
                    <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-[#F5B505]"></div>
                  </div>
                </td>
              </tr>
            ) : filteredHotels.length === 0 ? (
              <tr>
                <td colSpan="7" className="px-6 py-8 text-center text-gray-500">
                  No hay hoteles disponibles
                </td>
              </tr>
            ) : (
              filteredHotels.map((hotel) => (
                <tr 
                  key={hotel.id} 
                  className="hover:bg-gray-50/50 transition-colors cursor-pointer group"
                  onDoubleClick={() => onRowClick(hotel)}
                >
                  <td className="px-6 py-4">
                    <div className="text-[13px] font-medium text-gray-900">{hotel.username}</div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="text-[13px] font-medium text-gray-900">{hotel.nit}</div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="text-[13px] font-medium text-gray-900">{hotel.name}</div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="text-[13px] text-gray-600">{hotel.phone || '-'}</div>
                  </td>
                  <td className="px-6 py-4">
                    <div className="text-[13px] text-gray-600 max-w-xs truncate">{hotel.address || '-'}</div>
                  </td>
                  <td className="px-6 py-4">
                    {getStatusBadge(hotel)}
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button
                        onClick={(e) => {
                          e.stopPropagation();
                          onRowClick(hotel);
                        }}
                        className="p-2 text-gray-600 hover:text-[#F5B505] hover:bg-gray-50 rounded-lg transition-colors"
                        title="Ver detalles (doble clic en fila)"
                      >
                        <Edit2 size={16} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Footer con info */}
      <div className="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[12px] text-gray-600">
        Mostrando {filteredHotels.length} de {hotels.length} hoteles
      </div>
    </div>
  );
};

export default HotelsTable;
