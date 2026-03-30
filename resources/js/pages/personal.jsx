import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import PersonalTemplate from '../components/templates/PersonalTemplate';
import Modal from '../components/atoms/Modal';
import useDebouncedValue from '../hooks/useDebouncedValue';

const PersonalPage = () => {
  const { token } = useAuth();
  const [operatives, setOperatives] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [selectedOperative, setSelectedOperative] = useState(null);
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

  const normalizeOperative = (operative) => ({
    ...operative,
    is_active: Boolean(operative?.is_active),
  });

  const fetchOperatives = async (search = '') => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get('/api/v1/operatives', {
        ...config,
        params: {
          page,
          total,
          search: search.trim() !== '' ? search.trim() : undefined,
        },
      });

      const data = response.data.data || response.data || [];
      setOperatives(Array.isArray(data) ? data.map(normalizeOperative) : []);
    } catch (err) {
      console.error('Error fetching operatives:', err);
      setError(err.response?.data?.message || 'Error al cargar el personal');
      setOperatives([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!token) return;
    fetchOperatives(debouncedSearch);
  }, [token, debouncedSearch, page, total]);

  const handleRowClick = (operative) => {
    setSelectedOperative(operative);
    setShowDetailModal(true);
  };

  return (
    <PersonalTemplate
      operatives={operatives}
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
          setSelectedOperative(null);
        }}
        title="Detalles del Operativo"
        actions={
          <button
            type="button"
            className="btn"
            onClick={() => {
              setShowDetailModal(false);
              setSelectedOperative(null);
            }}
          >
            Cerrar
          </button>
        }
      >
        {selectedOperative ? (
          <div className="space-y-3 text-sm">
            <div>
              <p className="text-base-content/60">Nombre</p>
              <p className="font-medium">{selectedOperative.name || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Tipo de documento</p>
              <p className="font-medium">{selectedOperative.document_type || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Documento</p>
              <p className="font-medium">{selectedOperative.document || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Estado</p>
              <p className="font-medium">{selectedOperative.is_active ? 'Activo' : 'Inactivo'}</p>
            </div>
          </div>
        ) : null}
      </Modal>
    </PersonalTemplate>
  );
};

export default PersonalPage;
