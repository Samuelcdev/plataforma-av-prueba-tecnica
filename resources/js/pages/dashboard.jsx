import React, { useEffect, useState, useMemo } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { Navigate } from 'react-router-dom';
import axios from 'axios';
import DashboardTemplate from '../components/templates/DashboardTemplate';
import Typography from '../components/atoms/Typography';
import FilterPills from '../components/molecules/FilterPills';
import StatsGrid from '../components/organisms/StatsGrid';
import EventsTable from '../components/organisms/EventsTable';
import { Sparkles } from 'lucide-react';

const DashboardPage = () => {
  const { token, user, logout, isAuthenticated, isAdmin, isHotel } = useAuth();
  const [orders, setOrders] = useState([]);
  const [hotels, setHotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('all');
  const [search, setSearch] = useState('');

  useEffect(() => {
    if (isAuthenticated) {
      fetchData();
    }
  }, [isAuthenticated]);

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
      console.error('Error fetching dashboard data:', error);
      if (error.response?.status === 401) {
        logout();
      }
    } finally {
      setLoading(false);
    }
  };

  const filteredOrders = useMemo(() => {
    return orders.filter(order => {
      const matchesFilter = filter === 'all' || order.status === filter;
      const matchesSearch = order.name.toLowerCase().includes(search.toLowerCase()) || 
                            order.service_type.toLowerCase().includes(search.toLowerCase());
      return matchesFilter && matchesSearch;
    });
  }, [orders, filter, search]);

  const stats = useMemo(() => {
    return {
      total: orders.length,
      confirmed: orders.filter(o => o.status === 'active').length,
      pending: orders.filter(o => o.status === 'pending').length,
    };
  }, [orders]);

  if (!isAuthenticated) {
    return <Navigate to="/" />;
  }

  const filterOptions = [
    { label: 'Todos', value: 'all' },
    { label: 'Confirmado', value: 'active' },
    { label: 'Pendiente', value: 'pending' },
    { label: 'Live', value: 'live' },
  ];

  return (
    <DashboardTemplate 
      user={user} 
      onLogout={logout} 
      onSearch={setSearch}
      activePath="/dashboard"
      headerActions={
        <FilterPills 
          options={filterOptions} 
          activeOption={filter} 
          onSelect={setFilter} 
        />
      }
    >
      {/* Page Header Slot */}
      <div>
        <Typography variant="h1">Gestión de Eventos</Typography>
        <Typography variant="body" className="mt-1">
          {isAdmin ? 'Supervisa y coordina la agenda de producción audiovisual global.' : `Gestiona los eventos de ${user?.username}.`}
        </Typography>
      </div>

      <StatsGrid stats={stats} />

      <EventsTable orders={filteredOrders} hotels={hotels} />

      {/* Bottom Banner Section */}
      <div className="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-6 pb-8">
          <div className="relative rounded-[24px] overflow-hidden group min-h-[220px]">
              <img src="/stadium.png" alt="Innovation Summit 2024" className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
              <div className="absolute inset-0 bg-gradient-to-t from-[#0A1116]/90 via-[#0D161C]/50 to-transparent"></div>
              
              <div className="absolute inset-x-0 bottom-0 p-8 flex flex-col items-start justify-end h-full">
                  <span className="bg-[#B47C1C]/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded shadow-sm mb-3">
                      Próximo Gran Evento
                  </span>
                  <h3 className="text-[28px] font-bold text-white mb-2 leading-tight">Innovation Summit 2024</h3>
                  <p className="text-white/80 text-[14px]">Capacidad: 2,500 personas | 12 Canales AV Activos</p>
              </div>
          </div>

          <div className="bg-[#EBE9E2] rounded-[24px] p-8 relative overflow-hidden flex flex-col shadow-inner">
              <div className="text-[#875D0D] mb-5 relative z-10">
                  <Sparkles size={32} />
              </div>
              <h3 className="text-[20px] font-bold text-gray-900 mb-3 relative z-10 leading-snug">
                  Optimización con AI en curso...
              </h3>
              <p className="text-gray-600 text-[14px] leading-relaxed mb-6 font-medium relative z-10 flex-1">
                  Estamos recalculando la distribución de ancho de banda para los streaming del fin de semana.
              </p>
              <div className="mt-auto relative z-10">
                  <a href="#" className="font-bold text-[#8C610F] hover:text-[#5E410A] text-[14px] flex items-center gap-1.5 transition-colors group">
                      Ver Detalles <span className="group-hover:translate-x-1 transition-transform">→</span>
                  </a>
              </div>
          </div>
      </div>
    </DashboardTemplate>
  );
};

export default DashboardPage;
