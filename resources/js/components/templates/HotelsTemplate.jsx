import React from 'react';
import DashboardTemplate from './DashboardTemplate';
import HotelsTable from '../organisms/HotelsTable';
import Typography from '../atoms/Typography';

const HotelsTemplate = ({
  hotels,
  loading,
  error,
  onRowClick,
  onCreateClick,
  children,
}) => {
  return (
    <DashboardTemplate activePath="/hotels">
      <div>
        <Typography variant="h1">Gestión de Hoteles</Typography>
        <p className="mt-2 text-sm text-base-content/70">
          Administra todos los hoteles registrados en la plataforma
        </p>
      </div>

      <div className="w-full space-y-4">
        {error ? (
          <div className="alert alert-error">
            <span>{error}</span>
          </div>
        ) : null}

        <HotelsTable
          hotels={hotels}
          loading={loading}
          onRowClick={onRowClick}
          onCreateClick={onCreateClick}
        />
      </div>

      {children}
    </DashboardTemplate>
  );
};

export default HotelsTemplate;
