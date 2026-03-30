import React from 'react';
import Input from '../atoms/Input';
import Textarea from '../atoms/Textarea';
import { Label } from '../atoms/Label';
import Select from '../atoms/Select';

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

      <Select
        label="Tipo de Documento"
        name="document_type"
        value={formData.document_type}
        onChange={handleInputChange}
        required={true}
        disabled={isLoading}
        error={errors.document_type}
        options={[
          { value: 'CC', label: 'CC - Cédula de Ciudadanía' },
          { value: 'NIT', label: 'NIT - Número de Identificación Tributaria' },
          { value: 'CE', label: 'CE - Cédula de Extranjería' },
          { value: 'PP', label: 'PP - Pasaporte' },
        ]}
      />

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
