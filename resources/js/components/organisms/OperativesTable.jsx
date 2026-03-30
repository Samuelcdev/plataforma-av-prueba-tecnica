import React from 'react';
import SearchInput from '../molecules/SearchInput';

const OperativesTable = ({
  operatives = [],
  loading = false,
  onRowClick,
  searchValue = '',
  onSearchChange,
}) => {
  return (
    <div className="card border border-base-300 bg-base-100 shadow-sm">
      <div className="card-body gap-4 p-4 sm:p-6">
        <div className="space-y-2">
          <SearchInput
            value={searchValue}
            onChange={onSearchChange}
            placeholder="Buscar por nombre o documento..."
            className="w-full max-w-xl"
            inputClassName="input input-bordered bg-base-100 focus:bg-base-100"
          />
          <p className="text-sm text-base-content/70">Haz clic en una fila para ver los detalles.</p>
        </div>

        <div className="overflow-x-auto rounded-box border border-base-300">
          <table className="table table-zebra table-sm sm:table-md">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Tipo Documento</th>
                <th>Documento</th>
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
              ) : operatives.length === 0 ? (
                <tr>
                  <td colSpan="4" className="py-8 text-center text-base-content/70">
                    No hay personal disponible
                  </td>
                </tr>
              ) : (
                operatives.map((operative) => (
                  <tr
                    key={operative.id}
                    className="cursor-pointer hover:bg-black/10 duration-100"
                    onClick={() => onRowClick?.(operative)}
                  >
                    <td className="font-medium">{operative.name || '-'}</td>
                    <td>{operative.document_type || '-'}</td>
                    <td>{operative.document || '-'}</td>
                    <td>
                      <span
                        className={`badge badge-outline ${
                          operative.is_active ? 'badge-success' : 'badge-error'
                        }`}
                      >
                        {operative.is_active ? 'Activo' : 'Inactivo'}
                      </span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        <div className="text-sm text-base-content/70">
          Mostrando {operatives.length} colaboradores
        </div>
      </div>
    </div>
  );
};

export default OperativesTable;
