import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import HotelsTemplate from '../components/templates/HotelsTemplate';
import Modal from '../components/atoms/Modal';
import HotelForm from '../components/organisms/HotelForm';
import useDebouncedValue from '../hooks/useDebouncedValue';
import Swal from 'sweetalert2';

const EMPTY_FORM = {
  username: '',
  nit: '',
  document_type: 'CC',
  name: '',
  phone: '',
  address: '',
};

const Hotels = () => {
  const { token } = useAuth();
  const [hotels, setHotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [showDetailModal, setShowDetailModal] = useState(false);
  const [showFormModal, setShowFormModal] = useState(false);
  const [selectedHotel, setSelectedHotel] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);
  const [formData, setFormData] = useState(EMPTY_FORM);
  const [formErrors, setFormErrors] = useState({});
  const [searchTerm, setSearchTerm] = useState('');
  const debouncedSearch = useDebouncedValue(searchTerm, 400);

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  const normalizeHotel = (hotel) => ({
    ...hotel,
    username: hotel?.username || hotel?.user?.username || '',
  });

  const toFormData = (hotel) => ({
    username: hotel?.username || hotel?.user?.username || '',
    nit: hotel?.nit || '',
    document_type: hotel?.document_type || 'CC',
    name: hotel?.name || '',
    phone: hotel?.phone || '',
    address: hotel?.address || '',
  });

  const fetchHotels = async (search = '') => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get('/api/v1/hotels', {
        ...config,
        params: {
          search: search.trim() !== '' ? search.trim() : undefined,
        },
      });
      const data = response.data.data || response.data || [];
      setHotels(Array.isArray(data) ? data.map(normalizeHotel) : []);
    } catch (err) {
      console.error('Error fetching hotels:', err);
      setError(err.response?.data?.message || 'Error al cargar los hoteles');
      setHotels([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!token) return;
    fetchHotels(debouncedSearch);
  }, [token, debouncedSearch]);

  const handleRowClick = (hotel) => {
    setSelectedHotel(hotel);
    setShowDetailModal(true);
  };

  const handleCreateClick = () => {
    setFormData(EMPTY_FORM);
    setFormErrors({});
    setShowFormModal(true);
  };

  const handleEditClick = (hotel) => {
    setSelectedHotel(hotel);
    setFormData(toFormData(hotel));
    setFormErrors({});
    setShowDetailModal(false);
    setShowFormModal(true);
  };

  const handleFormChange = (name, value) => {
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));

    if (formErrors[name]) {
      setFormErrors((prev) => ({
        ...prev,
        [name]: '',
      }));
    }
  };

  const validateForm = () => {
    const errors = {};

    if (!formData.username.trim()) {
      errors.username = 'El usuario es requerido';
    } else if (formData.username.length > 100) {
      errors.username = 'El usuario no puede exceder 100 caracteres';
    }

    if (!formData.nit.trim()) {
      errors.nit = 'El NIT es requerido';
    } else if (formData.nit.length > 20) {
      errors.nit = 'El NIT no puede exceder 20 caracteres';
    }

    if (!formData.name.trim()) {
      errors.name = 'El nombre es requerido';
    } else if (formData.name.length > 150) {
      errors.name = 'El nombre no puede exceder 150 caracteres';
    }

    if (!formData.document_type.trim()) {
      errors.document_type = 'El tipo de documento es requerido';
    } else if (formData.document_type.length > 10) {
      errors.document_type = 'El tipo de documento no puede exceder 10 caracteres';
    }

    if (formData.phone && formData.phone.length > 20) {
      errors.phone = 'El teléfono no puede exceder 20 caracteres';
    }

    if (formData.address && formData.address.length > 255) {
      errors.address = 'La dirección no puede exceder 255 caracteres';
    }

    setFormErrors(errors);
    return Object.keys(errors).length === 0;
  };

  const swalTheme = {
    background: 'var(--color-base-100)',
    color: 'var(--color-base-content)',
    confirmButtonColor: 'var(--color-primary)',
  };

  const handleDeleteClick = async (hotelId) => {
    const result = await Swal.fire({
      title: '¿Eliminar hotel?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
      allowOutsideClick: false,
      allowEscapeKey: false,
      ...swalTheme,
      cancelButtonColor: 'var(--color-neutral)',
    });

    if (!result.isConfirmed) return;

    try {
      setIsDeleting(true);
      setError(null);
      await axios.delete(`/api/v1/hotels/${hotelId}`, config);
      setHotels((prev) => prev.filter((hotel) => hotel.id !== hotelId));
      setShowDetailModal(false);
      setSelectedHotel(null);
      await Swal.fire({
        title: 'Hotel eliminado',
        text: 'El hotel se eliminó correctamente.',
        icon: 'success',
        confirmButtonText: 'Continuar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...swalTheme,
      });
    } catch (err) {
      console.error('Error deleting hotel:', err);
      setError(err.response?.data?.message || 'Error al eliminar el hotel');
    } finally {
      setIsDeleting(false);
    }
  };

  const handleFormSubmit = async (event) => {
    event.preventDefault();
    if (!validateForm()) return;

    try {
      setIsSubmitting(true);
      setError(null);
      const hotelId = selectedHotel?.id;

      if (hotelId) {
        const response = await axios.put(`/api/v1/hotels/${hotelId}`, formData, config);
        const updatedHotel = normalizeHotel(response.data.data || response.data);
        setHotels((prev) => prev.map((hotel) => (hotel.id === hotelId ? updatedHotel : hotel)));
        await Swal.fire({
          title: 'Hotel actualizado',
          text: 'Los cambios se guardaron correctamente.',
          icon: 'success',
          confirmButtonText: 'Continuar',
          allowOutsideClick: false,
          allowEscapeKey: false,
          ...swalTheme,
        });
      } else {
        const response = await axios.post('/api/v1/hotels', formData, config);
        const newHotel = normalizeHotel(response.data.data || response.data);
        setHotels((prev) => [...prev, newHotel]);
        await Swal.fire({
          title: 'Hotel creado',
          text: 'El hotel se registró correctamente.',
          icon: 'success',
          confirmButtonText: 'Continuar',
          allowOutsideClick: false,
          allowEscapeKey: false,
          ...swalTheme,
        });
      }

      setShowFormModal(false);
      setSelectedHotel(null);
      setFormData(EMPTY_FORM);
      setFormErrors({});
    } catch (err) {
      console.error('Error submitting form:', err);
      setError(err.response?.data?.message || 'Error al guardar el hotel');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <HotelsTemplate
      hotels={hotels}
      loading={loading}
      error={error}
      onRowClick={handleRowClick}
      onCreateClick={handleCreateClick}
      searchValue={searchTerm}
      onSearchChange={(event) => setSearchTerm(event.target.value)}
    >
      <Modal
        isOpen={showDetailModal}
        onClose={() => {
          setShowDetailModal(false);
          setSelectedHotel(null);
        }}
        title="Detalles del Hotel"
        actions={
          selectedHotel ? (
            <>
              <button
                type="button"
                className="btn"
                onClick={() => {
                  setShowDetailModal(false);
                  setSelectedHotel(null);
                }}
                disabled={isDeleting}
              >
                Cerrar
              </button>
              <button
                type="button"
                className="btn btn-primary"
                onClick={() => handleEditClick(selectedHotel)}
                disabled={isDeleting}
              >
                Editar
              </button>
              <button
                type="button"
                className="btn btn-error btn-outline"
                onClick={() => handleDeleteClick(selectedHotel.id)}
                disabled={isDeleting}
              >
                {isDeleting ? <span className="loading loading-spinner loading-xs" /> : 'Eliminar'}
              </button>
            </>
          ) : null
        }
      >
        {selectedHotel ? (
          <div className="space-y-3 text-sm">
            <div>
              <p className="text-base-content/60">Usuario</p>
              <p className="font-medium">{selectedHotel.user?.username || selectedHotel.username || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">NIT</p>
              <p className="font-medium">{selectedHotel.nit || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Tipo de documento</p>
              <p className="font-medium">{selectedHotel.document_type || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Nombre</p>
              <p className="font-medium">{selectedHotel.name || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Teléfono</p>
              <p className="font-medium">{selectedHotel.phone || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Dirección</p>
              <p className="font-medium">{selectedHotel.address || '-'}</p>
            </div>
            {selectedHotel.created_at ? (
              <div>
                <p className="text-base-content/60">Registrado el</p>
                <p className="font-medium">
                  {new Date(selectedHotel.created_at).toLocaleDateString('es-ES')}
                </p>
              </div>
            ) : null}
          </div>
        ) : null}
      </Modal>

      <Modal
        isOpen={showFormModal}
        onClose={() => {
          setShowFormModal(false);
          setSelectedHotel(null);
          setFormData(EMPTY_FORM);
          setFormErrors({});
        }}
        title={selectedHotel ? 'Editar hotel' : 'Crear hotel'}
        closeDisabled={isSubmitting}
        actions={
          <>
            <button
              type="button"
              className="btn"
              onClick={() => {
                setShowFormModal(false);
                setSelectedHotel(null);
                setFormData(EMPTY_FORM);
                setFormErrors({});
              }}
              disabled={isSubmitting}
            >
              Cancelar
            </button>
            <button
              type="submit"
              form="hotel-form"
              className="btn btn-primary"
              disabled={isSubmitting}
            >
              {isSubmitting ? <span className="loading loading-spinner loading-xs" /> : 'Guardar'}
            </button>
          </>
        }
      >
        <HotelForm
          formId="hotel-form"
          formData={formData}
          errors={formErrors}
          onChange={handleFormChange}
          onSubmit={handleFormSubmit}
          isLoading={isSubmitting}
        />
      </Modal>
    </HotelsTemplate>
  );
};

export default Hotels;
