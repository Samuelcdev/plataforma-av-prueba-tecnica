import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import DashboardTemplate from '../components/templates/DashboardTemplate';
import HotelsTable from '../components/organisms/HotelsTable';
import HotelDetailModal from '../components/organisms/HotelDetailModal';
import HotelFormModal from '../components/organisms/HotelFormModal';
import Typography from '../components/atoms/Typography';

const Hotels = () => {
  const { token } = useAuth();
  const [hotels, setHotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Modal states
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [showFormModal, setShowFormModal] = useState(false);
  const [selectedHotel, setSelectedHotel] = useState(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isDeleting, setIsDeleting] = useState(false);

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  // Cargar hoteles
  const fetchHotels = async () => {
    try {
      setLoading(true);
      setError(null);
      const response = await axios.get('/api/v1/hotels', config);
      setHotels(response.data.data || response.data || []);
    } catch (err) {
      console.error('Error fetching hotels:', err);
      setError(err.response?.data?.message || 'Error al cargar los hoteles');
      setHotels([]);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchHotels();
  }, [token]);

  // Handlers
  const handleRowClick = (hotel) => {
    setSelectedHotel(hotel);
    setShowDetailModal(true);
  };

  const handleCreateClick = () => {
    setSelectedHotel(null);
    setShowFormModal(true);
  };

  const handleEditClick = (hotel) => {
    setSelectedHotel(hotel);
    setShowFormModal(true);
  };

  const handleDeleteClick = async (hotelId) => {
    try {
      setIsDeleting(true);
      await axios.delete(`/api/v1/hotels/${hotelId}`, config);
      setHotels(hotels.filter(h => h.id !== hotelId));
      setShowDetailModal(false);
      setSelectedHotel(null);
    } catch (err) {
      console.error('Error deleting hotel:', err);
      alert(err.response?.data?.message || 'Error al eliminar el hotel');
    } finally {
      setIsDeleting(false);
    }
  };

  const handleFormSubmit = async (formData, hotelId) => {
    try {
      setIsSubmitting(true);

      if (hotelId) {
        // Actualizar
        const response = await axios.put(`/api/v1/hotels/${hotelId}`, formData, config);
        const updatedHotel = response.data.data || response.data;
        setHotels(hotels.map(h => h.id === hotelId ? updatedHotel : h));
      } else {
        // Crear
        const response = await axios.post('/api/v1/hotels', formData, config);
        const newHotel = response.data.data || response.data;
        setHotels([...hotels, newHotel]);
      }

      setShowFormModal(false);
      setSelectedHotel(null);
    } catch (err) {
      console.error('Error submitting form:', err);
      alert(err.response?.data?.message || 'Error al guardar el hotel');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <DashboardTemplate activePath="/hotels">
      <div className="p-8 space-y-6">
        {/* Header */}
        <div>
          <Typography variant="h1">Gestión de Hoteles</Typography>
          <p className="text-gray-600 text-[14px] mt-2">
            Administra todos los hoteles registrados en la plataforma
          </p>
        </div>

        {/* Error message */}
        {error && (
          <div className="bg-red-50 border border-red-200 rounded-lg px-6 py-4">
            <p className="text-red-700 text-[14px]">{error}</p>
          </div>
        )}

        {/* Tabla de hoteles */}
        <HotelsTable
          hotels={hotels}
          loading={loading}
          onRowClick={handleRowClick}
          onDeleteClick={handleDeleteClick}
          onCreateClick={handleCreateClick}
        />
      </div>

      {/* Modales */}
      <HotelDetailModal
        isOpen={showDetailModal}
        hotel={selectedHotel}
        onClose={() => {
          setShowDetailModal(false);
          setSelectedHotel(null);
        }}
        onEdit={handleEditClick}
        onDelete={handleDeleteClick}
        isDeleting={isDeleting}
      />

      <HotelFormModal
        isOpen={showFormModal}
        hotel={selectedHotel}
        onClose={() => {
          setShowFormModal(false);
          setSelectedHotel(null);
        }}
        onSubmit={handleFormSubmit}
        isLoading={isSubmitting}
      />
    </DashboardTemplate>
  );
};

export default Hotels;
