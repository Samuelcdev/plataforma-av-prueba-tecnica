import React from 'react';
import Typography from '../atoms/Typography';
import ItemsTable from '../organisms/ItemsTable';
import DashboardTemplate from './DashboardTemplate';

const ItemsTemplate = ({ items, loading, error, onRowClick, children }) => {
  return (
    <DashboardTemplate activePath="/items">
      <div>
        <Typography variant="h1">Catálogo de Items</Typography>
        <p className="mt-2 text-sm text-base-content/70">
          Consulta los recursos disponibles. Vista de solo lectura.
        </p>
      </div>

      <div className="w-full space-y-4">
        {error ? (
          <div className="alert alert-error">
            <span>{error}</span>
          </div>
        ) : null}

        <ItemsTable items={items} loading={loading} onRowClick={onRowClick} />
      </div>

      {children}
    </DashboardTemplate>
  );
};

export default ItemsTemplate;
