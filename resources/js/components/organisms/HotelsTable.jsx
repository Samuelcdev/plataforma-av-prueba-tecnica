import React, { useMemo, useState } from 'react';
import SearchInput from '../molecules/SearchInput';

const HotelsTable = ({ hotels = [], loading = false, onRowClick, onCreateClick }) => {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredHotels = useMemo(() => {
    if (!searchTerm.trim()) return hotels;
    
    const term = searchTerm.toLowerCase();
    return hotels.filter((hotel) =>
      (hotel.name || '').toLowerCase().includes(term) ||
      (hotel.nit || '').toLowerCase().includes(term)
    );
  }, [hotels, searchTerm]);

  return (
    <div className="card bg-base-100 border border-base-300 shadow-sm">
      <div className="card-body gap-4 p-4 sm:p-6">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <SearchInput
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder="Buscar por nombre o NIT..."
            className="w-full max-w-xl"
            inputClassName="input input-bordered bg-base-100 focus:bg-base-100"
          />
          <button type="button" className="btn btn-primary" onClick={onCreateClick}>
            Crear hotel
          </button>
        </div>

        <div className="overflow-x-auto rounded-box border border-base-300">
          <table className="table table-zebra table-sm sm:table-md">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>NIT</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td colSpan="6" className="py-8 text-center">
                    <span className="loading loading-spinner loading-md" />
                  </td>
                </tr>
              ) : filteredHotels.length === 0 ? (
                <tr>
                  <td colSpan="6" className="py-8 text-center text-base-content/70">
                    No hay hoteles disponibles
                  </td>
                </tr>
              ) : (
                filteredHotels.map((hotel) => (
                  <tr
                    key={hotel.id}
                    className="cursor-pointer hover:bg-black/10 duration-100"
                    onClick={() => onRowClick?.(hotel)}
                  >
                    <td>{hotel.user?.username || hotel.username || '-'}</td>
                    <td>{hotel.nit || '-'}</td>
                    <td>{hotel.name || '-'}</td>
                    <td>{hotel.phone || '-'}</td>
                    <td className="max-w-72 truncate">{hotel.address || '-'}</td>
                    <td>
                      <span className="badge badge-success badge-outline">Activo</span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        <div className="text-sm text-base-content/70">
          Mostrando {filteredHotels.length} de {hotels.length} hoteles
        </div>
      </div>
    </div>
  );
};

export default HotelsTable;
