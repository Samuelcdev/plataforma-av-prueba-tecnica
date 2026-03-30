import React, { useEffect } from 'react';
import { X, Trash2 } from 'lucide-react';
import Button from '../atoms/Button';

const HotelDetailModal = ({ isOpen, hotel, onClose, onEdit, onDelete, isDeleting = false }) => {
  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'auto';
    }
    return () => {
      document.body.style.overflow = 'auto';
    };
  }, [isOpen]);

  if (!isOpen || !hotel) return null;

  const handleDelete = () => {
    if (window.confirm(`¿Estás seguro de que deseas eliminar el hotel "${hotel.name}"?`)) {
      onDelete(hotel.id);
    }
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-[20px] shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
          <h2 className="text-[18px] font-bold text-gray-900">Detalles del Hotel</h2>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <X size={24} />
          </button>
        </div>

        {/* Content */}
        <div className="px-6 py-6 space-y-5">
          {/* Usuario */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              Usuario
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.username}</p>
          </div>

          {/* NIT */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              NIT
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.nit}</p>
          </div>

          {/* Tipo de Documento */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              Tipo de Documento
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.document_type || '-'}</p>
          </div>

          {/* Nombre */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              Nombre
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.name}</p>
          </div>

          {/* Teléfono */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              Teléfono
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.phone || '-'}</p>
          </div>

          {/* Dirección */}
          <div>
            <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
              Dirección
            </label>
            <p className="text-[14px] font-medium text-gray-900">{hotel.address || '-'}</p>
          </div>

          {/* Fecha de Creación */}
          {hotel.created_at && (
            <div>
              <label className="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">
                Registrado el
              </label>
              <p className="text-[14px] font-medium text-gray-900">
                {new Date(hotel.created_at).toLocaleDateString('es-ES')}
              </p>
            </div>
          )}
        </div>

        {/* Footer con acciones */}
        <div className="border-t border-gray-100 px-6 py-4 bg-gray-50/50 flex gap-3 sticky bottom-0">
          <Button
            variant="secondary"
            className="flex-1"
            onClick={onClose}
          >
            Cerrar
          </Button>
          <Button
            variant="primary"
            className="flex-1"
            onClick={() => {
              onEdit(hotel);
              onClose();
            }}
          >
            Editar
          </Button>
          <button
            onClick={handleDelete}
            disabled={isDeleting}
            className="p-2.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            title="Eliminar hotel"
          >
            {isDeleting ? (
              <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-red-600"></div>
            ) : (
              <Trash2 size={18} />
            )}
          </button>
        </div>
      </div>
    </div>
  );
};

export default HotelDetailModal;
