import React from 'react';
import Select from '../atoms/Select';
import Input from '../atoms/Input';
import { Minus, Plus, Trash2 } from 'lucide-react';

const OrderItemField = ({ 
  items = [], 
  selectedItemId, 
  quantity, 
  onItemChange, 
  onQuantityChange, 
  onRemove,
  isLast = false
}) => {
  const itemOptions = items.map(item => ({
    value: item.id,
    label: `${item.name} ($${parseFloat(item.price).toLocaleString()})`
  }));

  return (
    <div className="flex flex-col md:flex-row items-end gap-4 p-4 bg-[#FDFBF7] rounded-2xl border border-gray-100 group transition-all hover:border-[#AE802D]/30">
      <div className="flex-1 w-full">
        <Select
          label="Recurso / Item"
          value={selectedItemId}
          onChange={(e) => onItemChange(e.target.value)}
          options={itemOptions}
          placeholder="Seleccione un recurso..."
          className="w-full"
        />
      </div>
      
      <div className="w-full md:w-32">
        <label className="text-[14px] font-bold text-[#1A1A1A] mb-2 block">
          Cantidad
        </label>
        <div className="flex items-center gap-2">
          <input
            type="number"
            min="1"
            value={quantity}
            onChange={(e) => onQuantityChange(parseInt(e.target.value) || 1)}
            className="w-full bg-[#F6F5F2] border-0 rounded-xl px-4 py-3.5 text-[15px] text-center text-gray-800 focus:ring-2 focus:ring-[#AE802D]/30 outline-none"
          />
        </div>
      </div>

      <button
        type="button"
        onClick={onRemove}
        disabled={isLast}
        className={`p-3.5 rounded-xl text-red-500 hover:bg-red-50 transition-colors ${isLast ? 'opacity-30 cursor-not-allowed' : ''}`}
      >
        <Trash2 size={20} />
      </button>
    </div>
  );
};

export default OrderItemField;
