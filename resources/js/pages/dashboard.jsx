import React, { useEffect, useMemo, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import axios from 'axios';
import DashboardTemplate from '../components/templates/DashboardTemplate';
import Typography from '../components/atoms/Typography';
import EventDetailModal from '../components/organisms/EventDetailModal';
import { useAuth } from '../contexts/AuthContext';

const UPCOMING_LIMIT = 3;
const KPI_PAGE_SIZE = 100;

const formatDateTime = (value) => {
  if (!value) return '-';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '-';
  return date.toLocaleString('es-CO', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const WelcomeCard = ({ user, isAdmin }) => {
  const label = isAdmin ? 'Panel Administrativo' : 'Panel del Hotel';
  const subtitle = isAdmin
    ? 'Monitorea los próximos eventos y la capacidad operativa asignada.'
    : 'Revisa tus próximos eventos y mantén el flujo operativo al día.';

  return (
    <div className="card bg-base-100 border border-base-300 shadow-sm">
      <div className="card-body">
        <p className="text-xs uppercase tracking-wide text-base-content/60">{label}</p>
        <h2 className="card-title text-2xl">
          Bienvenido, {user?.username || 'usuario'}
        </h2>
        <p className="text-base-content/70">{subtitle}</p>
      </div>
    </div>
  );
};

const KpiCard = ({ title, value, tone = 'primary' }) => {
  const toneClass = tone === 'error' ? 'text-error' : tone === 'success' ? 'text-success' : 'text-primary';

  return (
    <div className="card bg-base-100 border border-base-300 shadow-sm">
      <div className="card-body p-5">
        <p className="text-sm text-base-content/70">{title}</p>
        <p className={`text-3xl font-semibold ${toneClass}`}>{value}</p>
      </div>
    </div>
  );
};

const UpcomingCards = ({ events, loading, isAdmin, onViewEvent }) => {
  if (loading) {
    return (
      <div className="card bg-base-100 border border-base-300 shadow-sm">
        <div className="card-body py-10 text-center">
          <span className="loading loading-spinner loading-md" />
        </div>
      </div>
    );
  }

  if (events.length === 0) {
    return (
      <div className="card bg-base-100 border border-base-300 shadow-sm">
        <div className="card-body">
          <h3 className="card-title text-lg">Próximos eventos</h3>
          <p className="text-base-content/70">No tienes eventos próximos por ahora.</p>
          <div className="card-actions justify-end">
            <Link className="btn btn-primary btn-sm" to="/events">
              Ir a eventos
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
      {events.map((event) => (
        <div key={event.id} className="card bg-base-100 border border-base-300 shadow-sm">
          <div className="card-body">
            <div className="flex items-center justify-between gap-2">
              <h3 className="card-title text-lg">{event.name || 'Evento sin nombre'}</h3>
              <span className="badge badge-outline">{event.status || '-'}</span>
            </div>
            <p className="text-sm text-base-content/70">{event.service_type || '-'}</p>
            <div className="space-y-1 text-sm">
              <p>
                <span className="text-base-content/60">Inicio:</span> {formatDateTime(event.start_date)}
              </p>
              <p>
                <span className="text-base-content/60">Fin:</span> {formatDateTime(event.end_date)}
              </p>
              {isAdmin ? (
                <p>
                  <span className="text-base-content/60">Operativos:</span> {event.assignments?.length || 0}
                </p>
              ) : null}
            </div>
            <div className="card-actions justify-end">
              <button
                type="button"
                className="btn btn-ghost btn-sm"
                onClick={() => onViewEvent(event.id)}
              >
                Ver evento
              </button>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
};

const DashboardPage = () => {
  const { token, user, logout, isAuthenticated, isAdmin, isHotel } = useAuth();
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [upcomingEvents, setUpcomingEvents] = useState([]);
  const [showEventDetailModal, setShowEventDetailModal] = useState(false);
  const [loadingEventDetail, setLoadingEventDetail] = useState(false);
  const [selectedEvent, setSelectedEvent] = useState(null);
  const [selectedHotel, setSelectedHotel] = useState(null);
  const [kpis, setKpis] = useState({
    totalEvents: 0,
    pendingEvents: 0,
    upcomingEvents: 0,
    assignedOperatives: 0,
  });

  const config = useMemo(() => ({
    headers: { Authorization: `Bearer ${token}` },
  }), [token]);

  const fetchAdminKpis = async () => {
    const firstResponse = await axios.get('/api/v1/orders', {
      ...config,
      params: {
        page: 1,
        total: KPI_PAGE_SIZE,
        sort: 'created_at',
        order: 'desc',
      },
    });

    const totalEvents = Number(firstResponse.data.total || 0);
    const totalPages = Math.max(1, Math.ceil(totalEvents / KPI_PAGE_SIZE));
    let pendingEvents = Array.isArray(firstResponse.data.data)
      ? firstResponse.data.data.filter((order) => order.status === 'pending').length
      : 0;

    if (totalPages > 1) {
      for (let currentPage = 2; currentPage <= totalPages; currentPage += 1) {
        const response = await axios.get('/api/v1/orders', {
          ...config,
          params: {
            page: currentPage,
            total: KPI_PAGE_SIZE,
            sort: 'created_at',
            order: 'desc',
          },
        });

        const orders = Array.isArray(response.data.data) ? response.data.data : [];
        pendingEvents += orders.filter((order) => order.status === 'pending').length;
      }
    }

    return { totalEvents, pendingEvents };
  };

  const fetchDashboardData = async () => {
    setLoading(true);
    setError(null);

    try {
      const now = new Date().toISOString();
      const upcomingResponse = await axios.get('/api/v1/orders', {
        ...config,
        params: {
          page: 1,
          total: UPCOMING_LIMIT,
          sort: 'start_date',
          order: 'asc',
          start_from: now,
        },
      });

      const upcoming = Array.isArray(upcomingResponse.data.data) ? upcomingResponse.data.data : [];
      setUpcomingEvents(upcoming);

      const assignedOperatives = upcoming.reduce(
        (sum, event) => sum + (Array.isArray(event.assignments) ? event.assignments.length : 0),
        0
      );

      if (isAdmin) {
        const adminKpis = await fetchAdminKpis();
        setKpis({
          totalEvents: adminKpis.totalEvents,
          pendingEvents: adminKpis.pendingEvents,
          upcomingEvents: upcoming.length,
          assignedOperatives,
        });
      } else {
        setKpis({
          totalEvents: 0,
          pendingEvents: 0,
          upcomingEvents: upcoming.length,
          assignedOperatives: 0,
        });
      }
    } catch (err) {
      console.error('Error fetching dashboard data:', err);
      if (err.response?.status === 401) {
        logout();
      }
      setError(err.response?.data?.message || 'No fue posible cargar el dashboard');
    } finally {
      setLoading(false);
    }
  };

  const handleViewEvent = async (eventId) => {
    setShowEventDetailModal(true);
    setLoadingEventDetail(true);
    setSelectedEvent(null);
    setSelectedHotel(null);

    try {
      const orderResponse = await axios.get(`/api/v1/orders/${eventId}`, config);
      const order = orderResponse.data?.data || null;
      setSelectedEvent(order);

      if (order?.hotel_id) {
        try {
          const hotelResponse = await axios.get(`/api/v1/hotels/${order.hotel_id}`, config);
          setSelectedHotel(hotelResponse.data?.data || null);
        } catch (hotelError) {
          console.error('Error fetching hotel detail:', hotelError);
          setSelectedHotel(null);
        }
      }
    } catch (err) {
      console.error('Error fetching order detail:', err);
      if (err.response?.status === 401) {
        logout();
      }
      setError(err.response?.data?.message || 'No fue posible cargar el detalle del evento');
    } finally {
      setLoadingEventDetail(false);
    }
  };

  useEffect(() => {
    if (!isAuthenticated || !token) return;
    fetchDashboardData();
  }, [isAuthenticated, token, isAdmin, isHotel]);

  if (!isAuthenticated) {
    return <Navigate to="/" />;
  }

  return (
    <DashboardTemplate
      user={user}
      onLogout={logout}
      onSearch={() => {}}
      activePath="/dashboard"
    >
      <div>
        <Typography variant="h1">Dashboard</Typography>
        <Typography variant="body" className="mt-1">
          {isAdmin
            ? 'Resumen ejecutivo de operación y próximos eventos.'
            : 'Resumen de bienvenida y agenda de tus próximos eventos.'}
        </Typography>
      </div>

      {error ? (
        <div className="alert alert-error">
          <span>{error}</span>
        </div>
      ) : null}

      <WelcomeCard user={user} isAdmin={isAdmin} />

      {isAdmin ? (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <KpiCard title="Total eventos" value={kpis.totalEvents} />
          <KpiCard title="Eventos pendientes" value={kpis.pendingEvents} />
          <KpiCard title="Próximos eventos" value={kpis.upcomingEvents} />
          <KpiCard title="Operativos asignados (top 3)" value={kpis.assignedOperatives} />
        </div>
      ) : null}

      <div className="space-y-3">
        <h3 className="text-lg font-semibold text-base-content">Próximos eventos</h3>
        <UpcomingCards
          events={upcomingEvents}
          loading={loading}
          isAdmin={isAdmin}
          onViewEvent={handleViewEvent}
        />
      </div>

      <EventDetailModal
        isOpen={showEventDetailModal}
        onClose={() => {
          setShowEventDetailModal(false);
          setSelectedEvent(null);
          setSelectedHotel(null);
        }}
        loading={loadingEventDetail}
        event={selectedEvent}
        hotel={selectedHotel}
        isAdmin={isAdmin}
      />
    </DashboardTemplate>
  );
};

export default DashboardPage;
