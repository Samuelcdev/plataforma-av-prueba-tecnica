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
  const [page, setPage] = useState(1);
  const PAGE_SIZE = 10;
  const [totalCount, setTotalCount] = useState(0);

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
          total: PAGE_SIZE,
          search: search.trim() !== '' ? search.trim() : undefined,
        },
      });

      const data = response.data.data || response.data || [];
      setOperatives(Array.isArray(data) ? data.map(normalizeOperative) : []);
      setTotalCount(Number(response.data.total || 0));
    } catch (err) {
      console.error('Error fetching operatives:', err);
      setError(err.response?.data?.message || 'Error al cargar el personal');
      setOperatives([]);
      setTotalCount(0);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!token) return;
    fetchOperatives(debouncedSearch);
  }, [token, debouncedSearch, page]);

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
      onSearchChange={(event) => {
        setSearchTerm(event.target.value);
        setPage(1);
      }}
      page={page}
      total={totalCount}
      pageSize={PAGE_SIZE}
      onPrevPage={() => setPage((prev) => Math.max(1, prev - 1))}
      onNextPage={() => setPage((prev) => prev + 1)}
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
