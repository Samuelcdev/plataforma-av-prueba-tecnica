import React from 'react';
import DashboardTemplate from './DashboardTemplate';
import Typography from '../atoms/Typography';
import EventsTable from '../organisms/EventsTable';

const EventsTemplate = ({
  orders,
  loading,
  error,
  onRowClick,
  isHotel = false,
  isAdmin = false,
  onCreateClick,
  searchValue,
  onSearchChange,
  page,
  total,
  pageSize,
  onPrevPage,
  onNextPage,
  children,
}) => {
  return (
    <DashboardTemplate activePath="/events">
      <div>
        <Typography variant="h1">Gestión de Eventos</Typography>
        <p className="mt-2 text-sm text-base-content/70">
          {isHotel
            ? 'Crea eventos y consulta sus detalles.'
            : 'Consulta eventos y administra el personal operativo asignado.'}
        </p>
      </div>

      <div className="w-full space-y-4">
        {error ? (
          <div className="alert alert-error">
            <span>{error}</span>
          </div>
        ) : null}

        <EventsTable
          orders={orders}
          loading={loading}
          onRowClick={onRowClick}
          isHotel={isHotel}
          isAdmin={isAdmin}
          onCreateClick={onCreateClick}
          searchValue={searchValue}
          onSearchChange={onSearchChange}
          page={page}
          total={total}
          pageSize={pageSize}
          onPrevPage={onPrevPage}
          onNextPage={onNextPage}
        />
      </div>

      {children}
    </DashboardTemplate>
  );
};

export default EventsTemplate;
