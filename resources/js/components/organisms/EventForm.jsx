import React, { useState } from 'react';
import Typography from '../atoms/Typography';
import Input from '../atoms/Input';
import Select from '../atoms/Select';
import Button from '../atoms/Button';
import OrderItemField from '../molecules/OrderItemField';
import { Tag, Network, CalendarDays, MapPin, Save, Plus, X } from 'lucide-react';

const EventForm = ({ onSubmit, onCancel }) => {
  const [formData, setFormData] = useState({
    name: '',
    service_type: '',
    start_date: '',
    end_date: '',
    items: [{ item_id: '', quantity: 1 }]
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleAddItem = () => {
    setFormData(prev => ({
      ...prev,
      items: [...prev.items, { item_id: '', quantity: 1 }]
    }));
  };

  const handleRemoveItem = (index) => {
    setFormData(prev => ({
      ...prev,
      items: prev.items.filter((_, i) => i !== index)
    }));
  };

  const handleItemChange = (index, itemId) => {
    const newItems = [...formData.items];
    newItems[index].item_id = itemId;
    setFormData(prev => ({ ...prev, items: newItems }));
  };

  const handleQuantityChange = (index, quantity) => {
    const newItems = [...formData.items];
    newItems[index].quantity = quantity;
    setFormData(prev => ({ ...prev, items: newItems }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-8 animate-in fade-in duration-500">
      <div className="bg-white rounded-3xl p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-50">
        <Typography variant="h3" className="mb-6 flex items-center gap-2">
          <Tag size={20} className="text-[#8C610F]" />
          Detalles Generales
        </Typography>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
          <Input 
            label="Nombre del Evento"
            name="name"
            value={formData.name}
            onChange={handleChange}
            placeholder="Ej. Keynote Anual 2024"
            required
            icon={Tag}
          />

          <Select 
            label="Tipo de Evento"
            name="service_type"
            value={formData.service_type}
            onChange={handleChange}
            options={[
              { value: 'Conferencia', label: 'Conferencia' },
              { value: 'Taller', label: 'Taller' },
              { value: 'Concierto', label: 'Concierto' },
              { value: 'Streaming', label: 'Streaming' },
              { value: 'Otro', label: 'Otro' },
            ]}
            placeholder="Seleccione el tipo..."
            required
          />

          <Input 
            label="Fecha y Hora de Inicio"
            name="start_date"
            type="datetime-local"
            value={formData.start_date}
            onChange={handleChange}
            required
            icon={CalendarDays}
          />

          <Input 
            label="Fecha y Hora de Finalización"
            name="end_date"
            type="datetime-local"
            value={formData.end_date}
            onChange={handleChange}
            required
            icon={CalendarDays}
          />
        </div>
      </div>

      <div className="bg-white rounded-3xl p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-gray-50">
        <div className="flex justify-between items-center mb-6">
          <Typography variant="h3" className="flex items-center gap-2">
            <Network size={20} className="text-[#8C610F]" />
            Recursos Audiovisuales
          </Typography>
          <Button 
            type="button" 
            variant="secondary" 
            onClick={handleAddItem}
            className="flex items-center gap-2 text-sm"
          >
            <Plus size={16} /> Añadir Item
          </Button>
        </div>

        <div className="space-y-4">
          {formData.items.map((item, index) => (
            <OrderItemField 
              key={index}
              items={ITEMS}
              selectedItemId={item.item_id}
              quantity={item.quantity}
              onItemChange={(id) => handleItemChange(index, id)}
              onQuantityChange={(qty) => handleQuantityChange(index, qty)}
              onRemove={() => handleRemoveItem(index)}
              isLast={formData.items.length === 1}
            />
          ))}
        </div>
      </div>

      <div className="flex items-center justify-end gap-4 pb-10">
        <Button 
          type="button" 
          variant="secondary" 
          onClick={onCancel}
          className="px-8"
        >
          Cancelar
        </Button>
        <Button 
          type="submit" 
          className="bg-[#FFB800] hover:bg-[#F2AE00] text-[#1A1A1A] px-10 shadow-[0_4px_12px_rgba(255,184,0,0.3)] flex items-center gap-2"
        >
          <Save size={18} strokeWidth={2.5}/>
          Guardar Evento
        </Button>
      </div>
    </form>
  );
};

export default EventForm;
