import React from 'react';
import Input from '../atoms/Input';
import Textarea from '../atoms/Textarea';
import { Label } from '../atoms/Label';

const HotelForm = ({
  formId = 'hotel-form',
  formData,
  errors = {},
  onChange,
  onSubmit,
  isLoading = false,
}) => {
  const handleInputChange = (event) => {
    const { name, value } = event.target;
    onChange?.(name, value);
  };

  return (
    <form id={formId} className="space-y-4" onSubmit={onSubmit}>
      <div>
        <Label htmlFor="hotel-username">Usuario *</Label>
        <Input
          id="hotel-username"
          type="text"
          name="username"
          inputClassName={errors.username ? 'ring-2 ring-red-500/40' : ''}
          value={formData.username}
          onChange={handleInputChange}
          maxLength={100}
          disabled={isLoading}
          placeholder="Ej: hotel_example"
        />
        {errors.username ? <p className="mt-1 text-[12px] text-red-600">{errors.username}</p> : null}
      </div>

      <div>
        <Label htmlFor="hotel-nit">NIT *</Label>
        <Input
          id="hotel-nit"
          type="text"
          name="nit"
          inputClassName={errors.nit ? 'ring-2 ring-red-500/40' : ''}
          value={formData.nit}
          onChange={handleInputChange}
          maxLength={20}
          disabled={isLoading}
          placeholder="Ej: 9000000000"
        />
        {errors.nit ? <p className="mt-1 text-[12px] text-red-600">{errors.nit}</p> : null}
      </div>

      <div>
        <Label htmlFor="hotel-document-type">Tipo de Documento *</Label>
        <select
          id="hotel-document-type"
          name="document_type"
          className={`w-full px-4 py-2.5 bg-[#F5F5F5] border-transparent rounded-xl text-sm text-gray-800 focus:bg-white focus:border-gray-200 focus:ring-2 focus:ring-[#F5B505]/20 focus:outline-none transition-all ${
            errors.document_type ? 'ring-2 ring-red-500/40' : ''
          }`}
          value={formData.document_type}
          onChange={handleInputChange}
          disabled={isLoading}
        >
          <option value="CC">CC - Cédula de Ciudadanía</option>
          <option value="NIT">NIT - Número de Identificación Tributaria</option>
          <option value="CE">CE - Cédula de Extranjería</option>
          <option value="PP">PP - Pasaporte</option>
        </select>
        {errors.document_type ? <p className="mt-1 text-[12px] text-red-600">{errors.document_type}</p> : null}
      </div>

      <div>
        <Label htmlFor="hotel-name">Nombre del Hotel *</Label>
        <Input
          id="hotel-name"
          type="text"
          name="name"
          inputClassName={errors.name ? 'ring-2 ring-red-500/40' : ''}
          value={formData.name}
          onChange={handleInputChange}
          maxLength={150}
          disabled={isLoading}
          placeholder="Ej: Hotel Premier"
        />
        {errors.name ? <p className="mt-1 text-[12px] text-red-600">{errors.name}</p> : null}
      </div>

      <div>
        <Label htmlFor="hotel-phone">Teléfono</Label>
        <Input
          id="hotel-phone"
          type="tel"
          name="phone"
          inputClassName={errors.phone ? 'ring-2 ring-red-500/40' : ''}
          value={formData.phone}
          onChange={handleInputChange}
          maxLength={20}
          disabled={isLoading}
          placeholder="Ej: +57 1 2345678"
        />
        {errors.phone ? <p className="mt-1 text-[12px] text-red-600">{errors.phone}</p> : null}
      </div>

      <div>
        <Label htmlFor="hotel-address">Dirección</Label>
        <Textarea
          id="hotel-address"
          name="address"
          textareaClassName={errors.address ? 'ring-2 ring-red-500/40' : ''}
          value={formData.address}
          onChange={handleInputChange}
          rows={3}
          maxLength={255}
          disabled={isLoading}
          placeholder="Ej: Calle 1 #23-45"
        />
        {errors.address ? <p className="mt-1 text-[12px] text-red-600">{errors.address}</p> : null}
      </div>

      <p className="text-[11px] text-gray-500 italic">* Campos obligatorios</p>
    </form>
  );
};

export default HotelForm;
