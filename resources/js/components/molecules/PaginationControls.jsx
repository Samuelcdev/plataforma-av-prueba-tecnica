import React from 'react';

const PaginationControls = ({
  page = 1,
  total = 0,
  pageSize = 10,
  loading = false,
  onPrevPage,
  onNextPage,
  itemLabel = 'registros',
}) => {
  const safePage = Math.max(1, page);
  const safePageSize = Math.max(1, pageSize);
  const from = total === 0 ? 0 : (safePage - 1) * safePageSize + 1;
  const to = total === 0 ? 0 : Math.min(safePage * safePageSize, total);
  const hasPrev = safePage > 1;
  const hasNext = safePage * safePageSize < total;

  return (
    <div className="flex flex-col gap-3 text-sm text-base-content/70 sm:flex-row sm:items-center sm:justify-between">
      <span>
        Mostrando {from}-{to} de {total} {itemLabel}
      </span>
      <div className="join">
        <button
          type="button"
          className="btn btn-sm join-item"
          onClick={onPrevPage}
          disabled={!hasPrev || loading}
        >
          Anterior
        </button>
        <button type="button" className="btn btn-sm join-item btn-disabled">
          Página {safePage}
        </button>
        <button
          type="button"
          className="btn btn-sm join-item"
          onClick={onNextPage}
          disabled={!hasNext || loading}
        >
          Siguiente
        </button>
      </div>
    </div>
  );
};

export default PaginationControls;
