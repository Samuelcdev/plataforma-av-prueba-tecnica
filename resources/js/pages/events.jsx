import React, { useEffect, useState, useMemo } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { Navigate, useLocation } from 'react-router-dom';
import { Plus, List, AlertCircle } from 'lucide-react';
import axios from 'axios';

// Components
import DashboardTemplate from '../components/templates/DashboardTemplate';
import Typography from '../components/atoms/Typography';
import Button from '../components/atoms/Button';
import EventsTable from '../components/organisms/EventsTable';
import EventForm from '../components/organisms/EventForm';

export default function Events () {
  const { token, user, logout, isAuthenticated, isAdmin, isHotel } = useAuth();
  const location = useLocation();
  const [orders, setOrders] = useState([]);
  const [hotels, setHotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [view, setView] = useState('list'); // 'list' or 'create'
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  useEffect(() => {
    if (isAuthenticated) {
      fetchData();
    }
  }, [isAuthenticated]);

  useEffect(() => {
    if (location.state?.openForm && isHotel) {
      setView('create');
    }
  }, [location.state, isHotel]);


  const fetchData = async () => {
    setLoading(true);
    try {
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      
      const [ordersRes, hotelsRes] = await Promise.all([
        axios.get('/api/v1/orders', config),
        isAdmin ? axios.get('/api/v1/hotels', config) : Promise.resolve({ data: { data: [] } })
      ]);

      setOrders(ordersRes.data.data);
      setHotels(hotelsRes.data.data);
    } catch (error) {
      console.error('Error fetching events data:', error);
      if (error.response?.status === 401) {
        logout();
      }
      setError('Error al cargar los datos. Por favor, intente de nuevo.');
    } finally {
      setLoading(false);
    }
  };

  const handleCreateEvent = async (formData) => {
    setError(null);
    setSuccess(null);
    try {
      const config = {
        headers: { Authorization: `Bearer ${token}` }
      };
      
      const response = await axios.post('/api/v1/orders', formData, config);
      
      if (response.status === 201 || response.status === 200) {
        setSuccess('Evento creado exitosamente.');
        setView('list');
        fetchData();
      }
    } catch (error) {
      console.error('Error creating event:', error);
      setError(error.response?.data?.message || 'Error al crear el evento. Verifique los datos.');
    }
  };

  if (!isAuthenticated) {
    return <Navigate to="/" />;
  }

  return (
    <DashboardTemplate 
      user={user} 
      onLogout={logout} 
      activePath="/events"
      headerActions={
        view === 'list' && isHotel ? (
          <Button 
            onClick={() => setView('create')}
            className="bg-[#FFB800] hover:bg-[#F2AE00] text-[#1A1A1A] font-bold px-6 flex items-center gap-2"
          >
            <Plus size={18} /> Nuevo Evento
          </Button>
        ) : view === 'create' ? (
          <Button 
            variant="secondary"
            onClick={() => setView('list')}
            className="flex items-center gap-2"
          >
            <List size={18} /> Ver Listado
          </Button>
        ) : null
      }
    >
      {/* Page Header Slot */}
      <div className="mb-2">
        <Typography variant="h1">
          {view === 'list' ? 'Listado de Eventos' : 'Configurar Nuevo Evento'}
        </Typography>
        <Typography variant="body" className="mt-1">
          {view === 'list' 
            ? 'Visualiza y gestiona el estado de todas las producciones audiovisuales.' 
            : 'Define los detalles técnicos y recursos para tu próxima experiencia.'}
        </Typography>
      </div>

      {error && (
        <div className="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl flex items-center gap-3 animate-in slide-in-from-top duration-300">
          <AlertCircle className="text-red-500" size={20} />
          <p className="text-red-700 text-sm font-medium">{error}</p>
        </div>
      )}

      {success && (
        <div className="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl flex items-center gap-3 animate-in slide-in-from-top duration-300">
          <div className="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold italic">✓</div>
          <p className="text-green-700 text-sm font-medium">{success}</p>
        </div>
      )}

      {view === 'list' ? (
        loading ? (
          <div className="flex flex-col items-center justify-center py-20 gap-4">
            <div className="w-12 h-12 border-4 border-[#F5B505]/20 border-t-[#F5B505] rounded-full animate-spin"></div>
            <p className="text-gray-500 font-medium animate-pulse">Cargando eventos...</p>
          </div>
        ) : (
          <EventsTable orders={orders} hotels={hotels} />
        )
      ) : (
        <EventForm onSubmit={handleCreateEvent} onCancel={() => setView('list')} />
      )}
    </DashboardTemplate>
  );
};
