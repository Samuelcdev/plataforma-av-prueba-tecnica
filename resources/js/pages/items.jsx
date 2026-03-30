import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import ItemsTemplate from '../components/templates/ItemsTemplate';
import Modal from '../components/atoms/Modal';
import useDebouncedValue from '../hooks/useDebouncedValue';

const Items = () => {
  const { token } = useAuth();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedItem, setSelectedItem] = useState(null);
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const debouncedSearch = useDebouncedValue(searchTerm, 400);
  const page = 1;
  const total = 10;

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  const normalizeItem = (item) => ({
    ...item,
    is_active: Boolean(item?.is_active),
    priceFormatted:
      item?.price != null
        ? new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            maximumFractionDigits: 0,
          }).format(Number(item.price))
        : null,
  });

  const fetchItems = async (search = '') => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get('/api/v1/items', {
        ...config,
        params: {
          page,
          total,
          search: search.trim() !== '' ? search.trim() : undefined,
        },
      });
      const data = response.data.data || response.data || [];
      setItems(Array.isArray(data) ? data.map(normalizeItem) : []);
    } catch (err) {
      console.error('Error fetching items:', err);
      setError(err.response?.data?.message || 'Error al cargar los items');
      setItems([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!token) return;
    fetchItems(debouncedSearch);
  }, [token, debouncedSearch, page, total]);

  const handleRowClick = (item) => {
    setSelectedItem(item);
    setShowDetailModal(true);
  };

  return (
    <ItemsTemplate
      items={items}
      loading={loading}
      error={error}
      onRowClick={handleRowClick}
      searchValue={searchTerm}
      onSearchChange={(event) => setSearchTerm(event.target.value)}
    >
      <Modal
        isOpen={showDetailModal}
        onClose={() => {
          setShowDetailModal(false);
          setSelectedItem(null);
        }}
        title="Detalles del Item"
        actions={
          <button
            type="button"
            className="btn"
            onClick={() => {
              setShowDetailModal(false);
              setSelectedItem(null);
            }}
          >
            Cerrar
          </button>
        }
      >
        {selectedItem ? (
          <div className="space-y-3 text-sm">
            <div>
              <p className="text-base-content/60">Nombre</p>
              <p className="font-medium">{selectedItem.name || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Descripción</p>
              <p className="font-medium">{selectedItem.description || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Precio</p>
              <p className="font-medium">{selectedItem.priceFormatted || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Estado</p>
              <p className="font-medium">{selectedItem.is_active ? 'Activo' : 'Inactivo'}</p>
            </div>
            {selectedItem.created_at ? (
              <div>
                <p className="text-base-content/60">Registrado el</p>
                <p className="font-medium">
                  {new Date(selectedItem.created_at).toLocaleDateString('es-ES')}
                </p>
              </div>
            ) : null}
          </div>
        ) : null}
      </Modal>
    </ItemsTemplate>
  );
};

export default Items;
