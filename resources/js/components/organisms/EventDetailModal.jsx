import React from 'react';
import Modal from '../atoms/Modal';

const formatDateTime = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '-';
  return date.toLocaleString('es-CO', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const EventDetailModal = ({ isOpen, onClose, loading, event, hotel, isAdmin }) => (
  <Modal
    isOpen={isOpen}
    onClose={onClose}
    title="Detalle del evento"
    actions={(
      <button type="button" className="btn" onClick={onClose}>
        Cerrar
      </button>
    )}
  >
    {loading ? (
      <div className="py-8 text-center">
        <span className="loading loading-spinner loading-md" />
      </div>
    ) : !event ? (
      <p className="text-sm text-base-content/70">No fue posible cargar el detalle del evento.</p>
    ) : (
      <div className="space-y-4 text-sm">
        <div>
          <p className="text-base-content/60">Evento</p>
          <p className="font-medium">{event.name || '-'}</p>
        </div>
        <div>
          <p className="text-base-content/60">Servicio</p>
          <p className="font-medium">{event.service_type || '-'}</p>
        </div>
        <div>
          <p className="text-base-content/60">Estado</p>
          <p className="font-medium">{event.status || '-'}</p>
        </div>
        <div>
          <p className="text-base-content/60">Inicio</p>
          <p className="font-medium">{formatDateTime(event.start_date)}</p>
        </div>
        <div>
          <p className="text-base-content/60">Fin</p>
          <p className="font-medium">{formatDateTime(event.end_date)}</p>
        </div>

        <div>
          <p className="text-base-content/60">Items del evento</p>
          {Array.isArray(event.items) && event.items.length > 0 ? (
            <ul className="mt-2 space-y-1">
              {event.items.map((item) => (
                <li key={item.id} className="flex items-center justify-between rounded border border-base-300 px-3 py-2">
                  <span className="text-xs text-base-content/70">{item.name || item.item_id || '-'}</span>
                  <span className="badge badge-outline">x{item.quantity}</span>
                </li>
              ))}
            </ul>
          ) : (
            <p className="font-medium">Sin items.</p>
          )}
        </div>

        <div>
          <p className="text-base-content/60">Hotel</p>
          {hotel ? (
            <div className="mt-2 space-y-1 rounded border border-base-300 p-3">
              <p><span className="text-base-content/60">Nombre:</span> <span className="font-medium">{hotel.name || '-'}</span></p>
              <p><span className="text-base-content/60">NIT:</span> <span className="font-medium">{hotel.nit || '-'}</span></p>
              <p><span className="text-base-content/60">Teléfono:</span> <span className="font-medium">{hotel.phone || '-'}</span></p>
              <p><span className="text-base-content/60">Dirección:</span> <span className="font-medium">{hotel.address || '-'}</span></p>
            </div>
          ) : (
            <p className="font-medium">No disponible.</p>
          )}
        </div>

        {isAdmin ? (
          <div>
            <p className="text-base-content/60">Personal operativo asignado</p>
            <p className="font-medium">{Array.isArray(event.assignments) ? event.assignments.length : 0}</p>
          </div>
        ) : null}
      </div>
    )}
  </Modal>
);

export default EventDetailModal;
