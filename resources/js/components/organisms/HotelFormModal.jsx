import React, { useState, useEffect } from 'react';
import { X } from 'lucide-react';
import Button from '../atoms/Button';

const HotelFormModal = ({ isOpen, hotel = null, onClose, onSubmit, isLoading = false }) => {
  const [formData, setFormData] = useState({
    username: '',
    nit: '',
    document_type: 'CC',
    name: '',
    phone: '',
    address: '',
  });

  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (hotel) {
      setFormData({
        username: hotel.username || '',
        nit: hotel.nit || '',
        document_type: hotel.document_type || 'CC',
        name: hotel.name || '',
        phone: hotel.phone || '',
        address: hotel.address || '',
      });
    } else {
      setFormData({
        username: '',
        nit: '',
        document_type: 'CC',
        name: '',
        phone: '',
        address: '',
      });
    }
    setErrors({});
  }, [hotel, isOpen]);

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

  const validateForm = () => {
    const newErrors = {};
    
    if (!formData.username.trim()) {
      newErrors.username = 'El usuario es requerido';
    } else if (formData.username.length > 100) {
      newErrors.username = 'El usuario no puede exceder 100 caracteres';
    }

    if (!formData.nit.trim()) {
      newErrors.nit = 'El NIT es requerido';
    } else if (formData.nit.length > 20) {
      newErrors.nit = 'El NIT no puede exceder 20 caracteres';
    }

    if (!formData.name.trim()) {
      newErrors.name = 'El nombre es requerido';
    } else if (formData.name.length > 150) {
      newErrors.name = 'El nombre no puede exceder 150 caracteres';
    }

    if (!formData.document_type.trim()) {
      newErrors.document_type = 'El tipo de documento es requerido';
    } else if (formData.document_type.length > 10) {
      newErrors.document_type = 'El tipo de documento no puede exceder 10 caracteres';
    }

    if (formData.phone && formData.phone.length > 20) {
      newErrors.phone = 'El teléfono no puede exceder 20 caracteres';
    }

    if (formData.address && formData.address.length > 255) {
      newErrors.address = 'La dirección no puede exceder 255 caracteres';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!validateForm()) return;

    onSubmit(formData, hotel?.id);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value,
    }));
    // Limpiar error del campo cuando el usuario comienza a escribir
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: '',
      }));
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-[20px] shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
          <h2 className="text-[18px] font-bold text-gray-900">
            {hotel ? 'Editar Hotel' : 'Crear Nuevo Hotel'}
          </h2>
          <button
            onClick={onClose}
            disabled={isLoading}
            className="text-gray-400 hover:text-gray-600 transition-colors disabled:opacity-50"
          >
            <X size={24} />
          </button>
        </div>

        {/* Form */}
        <form onSubmit={handleSubmit} className="px-6 py-6 space-y-4">
          {/* Usuario */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              Usuario *
            </label>
            <input
              type="text"
              name="username"
              value={formData.username}
              onChange={handleChange}
              placeholder="Ej: hotel_example"
              disabled={isLoading}
              maxLength={100}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 ${
                errors.username ? 'border-red-500' : 'border-gray-200'
              }`}
            />
            {errors.username && <p className="text-[12px] text-red-600 mt-1">{errors.username}</p>}
          </div>

          {/* NIT */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              NIT *
            </label>
            <input
              type="text"
              name="nit"
              value={formData.nit}
              onChange={handleChange}
              placeholder="Ej: 9000000000"
              disabled={isLoading}
              maxLength={20}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 ${
                errors.nit ? 'border-red-500' : 'border-gray-200'
              }`}
            />
            {errors.nit && <p className="text-[12px] text-red-600 mt-1">{errors.nit}</p>}
          </div>

          {/* Tipo de Documento */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              Tipo de Documento *
            </label>
            <select
              name="document_type"
              value={formData.document_type}
              onChange={handleChange}
              disabled={isLoading}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 ${
                errors.document_type ? 'border-red-500' : 'border-gray-200'
              }`}
            >
              <option value="CC">CC - Cédula de Ciudadanía</option>
              <option value="NIT">NIT - Número de Identificación Tributaria</option>
              <option value="CE">CE - Cédula de Extranjería</option>
              <option value="PP">PP - Pasaporte</option>
            </select>
            {errors.document_type && <p className="text-[12px] text-red-600 mt-1">{errors.document_type}</p>}
          </div>

          {/* Nombre */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              Nombre del Hotel *
            </label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
              placeholder="Ej: Hotel Premier"
              disabled={isLoading}
              maxLength={150}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 ${
                errors.name ? 'border-red-500' : 'border-gray-200'
              }`}
            />
            {errors.name && <p className="text-[12px] text-red-600 mt-1">{errors.name}</p>}
          </div>

          {/* Teléfono */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              Teléfono
            </label>
            <input
              type="tel"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              placeholder="Ej: +57 1 2345678"
              disabled={isLoading}
              maxLength={20}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 ${
                errors.phone ? 'border-red-500' : 'border-gray-200'
              }`}
            />
            {errors.phone && <p className="text-[12px] text-red-600 mt-1">{errors.phone}</p>}
          </div>

          {/* Dirección */}
          <div>
            <label className="text-[12px] font-bold text-gray-700 uppercase tracking-wider block mb-2">
              Dirección
            </label>
            <textarea
              name="address"
              value={formData.address}
              onChange={handleChange}
              placeholder="Ej: Calle 1 #23-45"
              disabled={isLoading}
              maxLength={255}
              rows={3}
              className={`w-full px-4 py-2.5 border rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-[#F5B505] focus:border-transparent transition-colors disabled:bg-gray-50 disabled:opacity-50 resize-none ${
                errors.address ? 'border-red-500' : 'border-gray-200'
              }`}
            />
            {errors.address && <p className="text-[12px] text-red-600 mt-1">{errors.address}</p>}
          </div>

          {/* Nota de campos obligatorios */}
          <p className="text-[11px] text-gray-500 italic">* Campos obligatorios</p>
        </form>

        {/* Footer con acciones */}
        <div className="border-t border-gray-100 px-6 py-4 bg-gray-50/50 flex gap-3 sticky bottom-0">
          <Button
            variant="secondary"
            className="flex-1"
            onClick={onClose}
            disabled={isLoading}
          >
            Cancelar
          </Button>
          <Button
            variant="primary"
            className="flex-1"
            onClick={handleSubmit}
            disabled={isLoading}
          >
            {isLoading ? (
              <div className="flex items-center gap-2">
                <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                Guardando...
              </div>
            ) : (
              'Guardar'
            )}
          </Button>
        </div>
      </div>
    </div>
  );
};

export default HotelFormModal;
