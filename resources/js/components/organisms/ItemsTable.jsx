import React, { useMemo, useState } from 'react';
import SearchInput from '../molecules/SearchInput';

const ItemsTable = ({ items = [], loading = false, onRowClick }) => {
  const [searchTerm, setSearchTerm] = useState('');

  const filteredItems = useMemo(() => {
    if (!searchTerm.trim()) return items;

    const term = searchTerm.toLowerCase();
    return items.filter(
      (item) =>
        (item.name || '').toLowerCase().includes(term) ||
        (item.description || '').toLowerCase().includes(term),
    );
  }, [items, searchTerm]);

  return (
    <div className="card border border-base-300 bg-base-100 shadow-sm">
      <div className="card-body gap-4 p-4 sm:p-6">
        <div className="space-y-2">
          <SearchInput
            value={searchTerm}
            onChange={(event) => setSearchTerm(event.target.value)}
            placeholder="Buscar por nombre o descripción..."
            className="w-full max-w-xl"
            inputClassName="input input-bordered bg-base-100 focus:bg-base-100"
          />
          <p className="text-sm text-base-content/70">Haz clic en un item para ver sus detalles.</p>
        </div>

        <div className="overflow-x-auto rounded-box border border-base-300">
          <table className="table table-zebra table-sm sm:table-md">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td colSpan="4" className="py-8 text-center">
                    <span className="loading loading-spinner loading-md" />
                  </td>
                </tr>
              ) : filteredItems.length === 0 ? (
                <tr>
                  <td colSpan="4" className="py-8 text-center text-base-content/70">
                    No hay items disponibles
                  </td>
                </tr>
              ) : (
                filteredItems.map((item) => (
                  <tr
                    key={item.id}
                    className="cursor-pointer"
                    onClick={() => onRowClick?.(item)}
                  >
                    <td className="font-medium">{item.name || '-'}</td>
                    <td className="max-w-96 truncate">{item.description || '-'}</td>
                    <td>{item.priceFormatted || '-'}</td>
                    <td>
                      <span
                        className={`badge badge-outline ${
                          item.is_active ? 'badge-success' : 'badge-error'
                        }`}
                      >
                        {item.is_active ? 'Activo' : 'Inactivo'}
                      </span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        <div className="text-sm text-base-content/70">
          Mostrando {filteredItems.length} de {items.length} items
        </div>
      </div>
    </div>
  );
};

export default ItemsTable;
