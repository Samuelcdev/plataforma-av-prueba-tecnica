import React from 'react';
import DashboardTemplate from './DashboardTemplate';
import Typography from '../atoms/Typography';
import OperativesTable from '../organisms/OperativesTable';

const PersonalTemplate = ({
  operatives,
  loading,
  error,
  onRowClick,
  searchValue,
  onSearchChange,
  children,
}) => {
  return (
    <DashboardTemplate activePath="/personal">
      <div>
        <Typography variant="h1">Gestión de Personal</Typography>
        <p className="mt-2 text-sm text-base-content/70">
          Consulta el listado de operativos disponibles en la plataforma.
        </p>
      </div>

      <div className="w-full space-y-4">
        {error ? (
          <div className="alert alert-error">
            <span>{error}</span>
          </div>
        ) : null}

        <OperativesTable
          operatives={operatives}
          loading={loading}
          onRowClick={onRowClick}
          searchValue={searchValue}
          onSearchChange={onSearchChange}
        />
      </div>

      {children}
    </DashboardTemplate>
  );
};

export default PersonalTemplate;
