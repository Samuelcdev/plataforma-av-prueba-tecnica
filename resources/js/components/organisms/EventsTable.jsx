import React from 'react';
import SearchInput from '../molecules/SearchInput';
import PaginationControls from '../molecules/PaginationControls';

const formatDateTime = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '-';
  return date.toLocaleString('es-CO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const EventsTable = ({
  orders = [],
  loading = false,
  onRowClick,
  isHotel = false,
  isAdmin = false,
  onCreateClick,
  searchValue = '',
  onSearchChange,
  page = 1,
  total = 0,
  pageSize = 10,
  onPrevPage,
  onNextPage,
}) => {
  return (
    <div className="card border border-base-300 bg-base-100 shadow-sm">
      <div className="card-body gap-4 p-4 sm:p-6">
        <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <SearchInput
            value={searchValue}
            onChange={onSearchChange}
            placeholder="Buscar por nombre o tipo de servicio..."
            className="w-full max-w-xl"
            inputClassName="input input-bordered bg-base-100 focus:bg-base-100"
          />
          {isHotel ? (
            <button type="button" className="btn btn-primary" onClick={onCreateClick}>
              Crear evento
            </button>
          ) : null}
        </div>

        <div className="overflow-x-auto rounded-box border border-base-300">
          <table className="table table-zebra table-sm sm:table-md">
            <thead>
              <tr>
                <th>Evento</th>
                <th>Servicio</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                {isAdmin ? <th>Operativos</th> : null}
              </tr>
            </thead>
            <tbody>
              {loading ? (
                <tr>
                  <td colSpan={isAdmin ? 6 : 5} className="py-8 text-center">
                    <span className="loading loading-spinner loading-md" />
                  </td>
                </tr>
              ) : orders.length === 0 ? (
                <tr>
                  <td colSpan={isAdmin ? 6 : 5} className="py-8 text-center text-base-content/70">
                    No hay eventos disponibles
                  </td>
                </tr>
              ) : (
                orders.map((order) => (
                  <tr
                    key={order.id}
                    className="cursor-pointer hover:bg-black/10 duration-100"
                    onClick={() => onRowClick?.(order)}
                  >
                    <td className="font-medium">{order.name || '-'}</td>
                    <td>{order.service_type || '-'}</td>
                    <td>{formatDateTime(order.start_date)}</td>
                    <td>{formatDateTime(order.end_date)}</td>
                    <td>
                      <span
                        className={`badge badge-outline ${
                          order.status === 'cancelled' ? 'badge-error' : 'badge-success'
                        }`}
                      >
                        {order.status || '-'}
                      </span>
                    </td>
                    {isAdmin ? <td>{order.assignments?.length || 0}</td> : null}
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        <PaginationControls
          page={page}
          total={total}
          pageSize={pageSize}
          loading={loading}
          onPrevPage={onPrevPage}
          onNextPage={onNextPage}
          itemLabel="eventos"
        />
      </div>
    </div>
  );
};

export default EventsTable;
