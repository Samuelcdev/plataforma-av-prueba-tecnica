import React, { useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import { useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import EventsTemplate from '../components/templates/EventsTemplate';
import Modal from '../components/atoms/Modal';
import useDebouncedValue from '../hooks/useDebouncedValue';
import Swal from 'sweetalert2';

const PAGE_SIZE = 10;

const EMPTY_CREATE_FORM = {
  name: '',
  service_type: '',
  start_date: '',
  end_date: '',
  items: [{ item_id: '', quantity: 1 }],
};

const Events = () => {
  const { token, isAdmin, isHotel } = useAuth();
  const location = useLocation();

  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const [searchTerm, setSearchTerm] = useState('');
  const debouncedSearch = useDebouncedValue(searchTerm, 400);
  const [statusFilter, setStatusFilter] = useState('');
  const [dateFilter, setDateFilter] = useState('');
  const [page, setPage] = useState(1);
  const [total, setTotal] = useState(0);

  const [selectedOrder, setSelectedOrder] = useState(null);
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [loadingDetail, setLoadingDetail] = useState(false);

  const [showCreateModal, setShowCreateModal] = useState(false);
  const [createForm, setCreateForm] = useState(EMPTY_CREATE_FORM);
  const [availableItems, setAvailableItems] = useState([]);
  const [loadingItems, setLoadingItems] = useState(false);
  const [creating, setCreating] = useState(false);

  const [selectedOperativeIds, setSelectedOperativeIds] = useState([]);
  const [operativeSearch, setOperativeSearch] = useState('');
  const debouncedOperativeSearch = useDebouncedValue(operativeSearch, 400);
  const [operativeOptions, setOperativeOptions] = useState([]);
  const [loadingOperatives, setLoadingOperatives] = useState(false);
  const [savingOperatives, setSavingOperatives] = useState(false);

  const config = {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  };

  const operativeMap = useMemo(() => {
    const map = new Map();
    operativeOptions.forEach((operative) => {
      map.set(operative.id, operative.name);
    });
    return map;
  }, [operativeOptions]);

  const swalTheme = {
    background: 'var(--color-base-100)',
    color: 'var(--color-base-content)',
    confirmButtonColor: 'var(--color-primary)',
  };

  const fetchOrders = async (search, pageValue, status, date) => {
    try {
      setLoading(true);
      setError(null);

      const response = await axios.get('/api/v1/orders', {
        ...config,
        params: {
          search: search.trim() !== '' ? search.trim() : undefined,
          status: status !== '' ? status : undefined,
          date: date !== '' ? date : undefined,
          page: pageValue,
          total: PAGE_SIZE,
        },
      });

      const data = response.data.data || [];
      setOrders(Array.isArray(data) ? data : []);
      setTotal(Number(response.data.total || 0));
    } catch (err) {
      console.error('Error fetching orders:', err);
      setError(err.response?.data?.message || 'Error al cargar los eventos');
      setOrders([]);
      setTotal(0);
    } finally {
      setLoading(false);
    }
  };

  const fetchOrderDetail = async (orderId) => {
    try {
      setLoadingDetail(true);
      const response = await axios.get(`/api/v1/orders/${orderId}`, config);
      const order = response.data.data;
      setSelectedOrder(order);
      setSelectedOperativeIds(order?.assignments?.map((item) => item.operative_id) || []);
    } catch (err) {
      console.error('Error fetching order detail:', err);
      setError(err.response?.data?.message || 'No fue posible cargar el detalle del evento');
    } finally {
      setLoadingDetail(false);
    }
  };

  const fetchItems = async () => {
    try {
      setLoadingItems(true);
      const response = await axios.get('/api/v1/items', {
        ...config,
        params: {
          page: 1,
          total: 100,
          is_active: 1,
        },
      });

      const data = response.data.data || [];
      setAvailableItems(Array.isArray(data) ? data : []);
    } catch (err) {
      console.error('Error fetching items:', err);
      setError(err.response?.data?.message || 'No fue posible cargar los items');
      setAvailableItems([]);
    } finally {
      setLoadingItems(false);
    }
  };

  const fetchOperatives = async (search = '') => {
    try {
      setLoadingOperatives(true);
      const response = await axios.get('/api/v1/operatives', {
        ...config,
        params: {
          page: 1,
          total: 50,
          is_active: 1,
          search: search.trim() !== '' ? search.trim() : undefined,
        },
      });

      const data = response.data.data || [];
      setOperativeOptions(Array.isArray(data) ? data : []);
    } catch (err) {
      console.error('Error fetching operatives:', err);
      setError(err.response?.data?.message || 'No fue posible cargar el personal operativo');
      setOperativeOptions([]);
    } finally {
      setLoadingOperatives(false);
    }
  };

  useEffect(() => {
    if (!token) return;
    fetchOrders(debouncedSearch, page, statusFilter, dateFilter);
  }, [token, debouncedSearch, statusFilter, dateFilter, page]);

  useEffect(() => {
    if (!isHotel) return;
    if (location.state?.openForm) {
      setShowCreateModal(true);
      if (availableItems.length === 0) {
        fetchItems();
      }
    }
  }, [location.state, isHotel]);

  useEffect(() => {
    if (!token || !isAdmin || !showDetailModal) return;
    fetchOperatives(debouncedOperativeSearch);
  }, [token, isAdmin, showDetailModal, debouncedOperativeSearch]);

  const handleRowClick = async (order) => {
    setSuccess(null);
    setError(null);
    setSelectedOrder(order);
    setSelectedOperativeIds(order?.assignments?.map((item) => item.operative_id) || []);
    setOperativeSearch('');
    setShowDetailModal(true);
    await fetchOrderDetail(order.id);
  };

  const handleCreateClick = async () => {
    setSuccess(null);
    setError(null);
    setCreateForm(EMPTY_CREATE_FORM);
    setShowCreateModal(true);

    if (availableItems.length === 0) {
      await fetchItems();
    }
  };

  const handleCreateItemChange = (index, field, value) => {
    setCreateForm((prev) => ({
      ...prev,
      items: prev.items.map((item, itemIndex) => {
        if (itemIndex !== index) return item;
        return { ...item, [field]: value };
      }),
    }));
  };

  const handleAddCreateItem = () => {
    setCreateForm((prev) => ({
      ...prev,
      items: [...prev.items, { item_id: '', quantity: 1 }],
    }));
  };

  const handleRemoveCreateItem = (index) => {
    setCreateForm((prev) => ({
      ...prev,
      items: prev.items.filter((_, itemIndex) => itemIndex !== index),
    }));
  };

  const handleSubmitCreate = async (event) => {
    event.preventDefault();
    setError(null);
    setSuccess(null);

    const cleanedItems = createForm.items
      .map((item) => ({
        item_id: item.item_id,
        name: item.name,
        quantity: Number(item.quantity),
      }))
      .filter((item) => item.item_id && item.quantity > 0);

    if (!createForm.name || !createForm.service_type || !createForm.start_date || !createForm.end_date) {
      setError('Completa todos los campos requeridos del evento.');
      return;
    }

    if (cleanedItems.length === 0) {
      setError('Debes agregar al menos un item con cantidad válida.');
      return;
    }

    try {
      setCreating(true);
      await axios.post(
        '/api/v1/orders',
        {
          name: createForm.name,
          service_type: createForm.service_type,
          start_date: createForm.start_date,
          end_date: createForm.end_date,
          items: cleanedItems,
        },
        config
      );
      setShowCreateModal(false);
      setCreateForm(EMPTY_CREATE_FORM);
      setSuccess('Evento creado correctamente.');
      fetchOrders(debouncedSearch, page, statusFilter, dateFilter);
      await Swal.fire({
        title: 'Evento creado',
        text: 'El evento se registró correctamente.',
        icon: 'success',
        confirmButtonText: 'Continuar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...swalTheme,
      });
    } catch (err) {
      console.error('Error creating order:', err);
      setError(err.response?.data?.message || 'No fue posible crear el evento');
    } finally {
      setCreating(false);
    }
  };

  const handleToggleOperative = (operativeId) => {
    setSelectedOperativeIds((prev) => (
      prev.includes(operativeId)
        ? prev.filter((id) => id !== operativeId)
        : [...prev, operativeId]
    ));
  };

  const handleSaveOperatives = async () => {
    if (!selectedOrder) return;

    try {
      setSavingOperatives(true);
      setError(null);
      setSuccess(null);
      const response = await axios.post(
        `/api/v1/orders/${selectedOrder.id}/assign-operatives`,
        { operative_ids: selectedOperativeIds },
        config
      );

      const updatedOrder = response.data.data;
      setSelectedOrder(updatedOrder);
      setOrders((prev) => prev.map((order) => (order.id === updatedOrder.id ? updatedOrder : order)));
      setSuccess('Personal operativo actualizado correctamente.');
      await Swal.fire({
        title: 'Evento actualizado',
        text: 'La asignación de operativos se actualizó correctamente.',
        icon: 'success',
        confirmButtonText: 'Continuar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...swalTheme,
      });
    } catch (err) {
      console.error('Error assigning operatives:', err);
      await Swal.fire({
        title: 'Error',
        text: 'No fue posible asignar los operativos: ' + err.response?.data?.message,
        icon: 'error',
        confirmButtonText: 'Continuar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...swalTheme,
      });
    } finally {
      setSavingOperatives(false);
    }
  };

  const handleCancelEvent = async () => {
    if (!selectedOrder || !isHotel || selectedOrder.status === 'cancelled') return;

    const result = await Swal.fire({
      title: '¿Eliminar evento?',
      text: 'El evento se marcará como cancelado.',
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
      setError(null);
      setSuccess(null);
      const response = await axios.post(`/api/v1/orders/${selectedOrder.id}/cancel`, {}, config);
      const cancelledOrder = response.data.data;
      setSelectedOrder(cancelledOrder);
      setOrders((prev) => prev.map((order) => (order.id === cancelledOrder.id ? cancelledOrder : order)));
      setShowDetailModal(false);
      await Swal.fire({
        title: 'Evento eliminado',
        text: 'El evento fue cancelado correctamente.',
        icon: 'success',
        confirmButtonText: 'Continuar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        ...swalTheme,
      });
    } catch (err) {
      console.error('Error cancelling order:', err);
      setError(err.response?.data?.message || 'No fue posible eliminar el evento');
    }
  };

  return (
    <EventsTemplate
      orders={orders}
      loading={loading}
      error={error}
      onRowClick={handleRowClick}
      isHotel={isHotel}
      isAdmin={isAdmin}
      onCreateClick={handleCreateClick}
      searchValue={searchTerm}
      onSearchChange={(event) => {
        setSearchTerm(event.target.value);
        setPage(1);
      }}
      statusValue={statusFilter}
      onStatusChange={(event) => {
        setStatusFilter(event.target.value);
        setPage(1);
      }}
      dateValue={dateFilter}
      onDateChange={(event) => {
        setDateFilter(event.target.value);
        setPage(1);
      }}
      page={page}
      total={total}
      pageSize={PAGE_SIZE}
      onPrevPage={() => setPage((prev) => Math.max(1, prev - 1))}
      onNextPage={() => setPage((prev) => prev + 1)}
    >
      {success ? (
        <div className="alert alert-success">
          <span>{success}</span>
        </div>
      ) : null}

      <Modal
        isOpen={showDetailModal}
        onClose={() => {
          setShowDetailModal(false);
          setSelectedOrder(null);
          setSelectedOperativeIds([]);
        }}
        title="Detalle del evento"
        closeDisabled={savingOperatives}
        actions={
          <>
            <button
              type="button"
              className="btn"
              onClick={() => {
                setShowDetailModal(false);
                setSelectedOrder(null);
                setSelectedOperativeIds([]);
              }}
              disabled={savingOperatives}
            >
              Cerrar
            </button>
            {isAdmin ? (
              <button
                type="button"
                className="btn btn-primary"
                onClick={handleSaveOperatives}
                disabled={savingOperatives}
              >
                {savingOperatives ? <span className="loading loading-spinner loading-xs" /> : 'Guardar asignación'}
              </button>
            ) : null}
            {isHotel && selectedOrder?.status !== 'cancelled' ? (
              <button
                type="button"
                className="btn btn-error btn-outline"
                onClick={handleCancelEvent}
              >
                Eliminar evento
              </button>
            ) : null}
          </>
        }
      >
        {loadingDetail ? (
          <div className="py-8 text-center">
            <span className="loading loading-spinner loading-md" />
          </div>
        ) : selectedOrder ? (
          <div className="space-y-4 text-sm">
            <div>
              <p className="text-base-content/60">Evento</p>
              <p className="font-medium">{selectedOrder.name || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Servicio</p>
              <p className="font-medium">{selectedOrder.service_type || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Estado</p>
              <p className="font-medium">{selectedOrder.status || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Inicio</p>
              <p className="font-medium">{selectedOrder.start_date || '-'}</p>
            </div>
            <div>
              <p className="text-base-content/60">Fin</p>
              <p className="font-medium">{selectedOrder.end_date || '-'}</p>
            </div>

            <div>
              <p className="text-base-content/60">Items del evento</p>
              {selectedOrder.items?.length ? (
                <ul className="mt-2 space-y-1">
                  {selectedOrder.items.map((item) => (
                    <li key={item.id} className="flex items-center justify-between rounded border border-base-300 px-3 py-2">
                      <span className="text-xs text-base-content/70">{item.name}</span>
                      <span className="badge badge-outline">x{item.quantity}</span>
                    </li>
                  ))}
                </ul>
              ) : (
                <p className="font-medium">Sin items.</p>
              )}
            </div>

            <div>
              <p className="text-base-content/60">Personal operativo asignado</p>
              {(isAdmin ? selectedOperativeIds.length > 0 : selectedOrder.assignments?.length > 0) ? (
                <div className="mt-2 flex flex-wrap gap-2">
                  {isAdmin
                    ? selectedOperativeIds.map((operativeId) => (
                        <span key={operativeId} className="badge badge-outline badge-info">
                          {operativeMap.get(operativeId)
                            || selectedOrder.assignments?.find((assignment) => assignment.operative_id === operativeId)?.operative_name
                            || operativeId}
                        </span>
                      ))
                    : selectedOrder.assignments.map((assignment) => (
                        <span key={assignment.id} className="badge badge-outline badge-info">
                          {assignment.operative_name || assignment.name || assignment.operative_id}
                        </span>
                      ))}
                </div>
              ) : (
                <p className="font-medium">Sin personal asignado.</p>
              )}
            </div>

            {isAdmin ? (
              <div className="space-y-2 rounded border border-base-300 p-3">
                <label className="label px-0 py-0">
                  <span className="label-text font-medium">Modificar personal operativo</span>
                </label>
                <input
                  type="text"
                  value={operativeSearch}
                  onChange={(event) => setOperativeSearch(event.target.value)}
                  className="input input-bordered input-sm w-full"
                  placeholder="Buscar operativo..."
                />
                <div className="max-h-56 space-y-2 overflow-auto">
                  {loadingOperatives ? (
                    <div className="py-3 text-center">
                      <span className="loading loading-spinner loading-sm" />
                    </div>
                  ) : operativeOptions.length === 0 ? (
                    <p className="text-sm text-base-content/70">No hay operativos disponibles.</p>
                  ) : (
                    operativeOptions.map((operative) => (
                      <label key={operative.id} className="flex cursor-pointer items-center justify-between rounded border border-base-300 px-3 py-2">
                        <div>
                          <p className="font-medium">{operative.name}</p>
                          <p className="text-xs text-base-content/70">
                            {operative.document_type} - {operative.document}
                          </p>
                        </div>
                        <input
                          type="checkbox"
                          className="checkbox checkbox-sm"
                          checked={selectedOperativeIds.includes(operative.id)}
                          onChange={() => handleToggleOperative(operative.id)}
                        />
                      </label>
                    ))
                  )}
                </div>
              </div>
            ) : null}
          </div>
        ) : null}
      </Modal>

      <Modal
        isOpen={showCreateModal}
        onClose={() => {
          setShowCreateModal(false);
          setCreateForm(EMPTY_CREATE_FORM);
        }}
        title="Crear evento"
        closeDisabled={creating}
        actions={
          <>
            <button
              type="button"
              className="btn"
              onClick={() => {
                setShowCreateModal(false);
                setCreateForm(EMPTY_CREATE_FORM);
              }}
              disabled={creating}
            >
              Cancelar
            </button>
            <button
              type="submit"
              form="create-event-form"
              className="btn btn-primary"
              disabled={creating}
            >
              {creating ? <span className="loading loading-spinner loading-xs" /> : 'Crear evento'}
            </button>
          </>
        }
      >
        <form id="create-event-form" className="space-y-4" onSubmit={handleSubmitCreate}>
          <div>
            <label htmlFor="event-name" className="label px-0 py-0">
              <span className="label-text">Nombre del evento</span>
            </label>
            <input
              id="event-name"
              type="text"
              className="input input-bordered w-full"
              value={createForm.name}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, name: event.target.value }))}
              required
            />
          </div>

          <div>
            <label htmlFor="event-service" className="label px-0 py-0">
              <span className="label-text">Tipo de servicio</span>
            </label>
            <input
              id="event-service"
              type="text"
              className="input input-bordered w-full"
              value={createForm.service_type}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, service_type: event.target.value }))}
              required
            />
          </div>

          <div className="grid gap-3 sm:grid-cols-2">
            <div>
              <label htmlFor="event-start" className="label px-0 py-0">
                <span className="label-text">Inicio</span>
              </label>
              <input
                id="event-start"
                type="datetime-local"
                className="input input-bordered w-full"
                value={createForm.start_date}
                onChange={(event) => setCreateForm((prev) => ({ ...prev, start_date: event.target.value }))}
                required
              />
            </div>
            <div>
              <label htmlFor="event-end" className="label px-0 py-0">
                <span className="label-text">Fin</span>
              </label>
              <input
                id="event-end"
                type="datetime-local"
                className="input input-bordered w-full"
                value={createForm.end_date}
                onChange={(event) => setCreateForm((prev) => ({ ...prev, end_date: event.target.value }))}
                required
              />
            </div>
          </div>

          <div className="space-y-2 rounded border border-base-300 p-3">
            <div className="flex items-center justify-between">
              <p className="font-medium">Items</p>
              <button type="button" className="btn btn-outline btn-xs" onClick={handleAddCreateItem}>
                Agregar item
              </button>
            </div>
            {loadingItems ? (
              <div className="py-3 text-center">
                <span className="loading loading-spinner loading-sm" />
              </div>
            ) : (
              createForm.items.map((item, index) => (
                <div key={`create-item-${index}`} className="grid gap-2 sm:grid-cols-[1fr_120px_auto]">
                  <select
                    className="select select-bordered w-full"
                    value={item.item_id}
                    onChange={(event) => handleCreateItemChange(index, 'item_id', event.target.value)}
                  >
                    <option value="">Selecciona un item</option>
                    {availableItems.map((availableItem) => (
                      <option key={availableItem.id} value={availableItem.id}>
                        {availableItem.name}
                      </option>
                    ))}
                  </select>
                  <input
                    type="number"
                    min="1"
                    className="input input-bordered w-full"
                    value={item.quantity}
                    onChange={(event) => handleCreateItemChange(index, 'quantity', event.target.value)}
                  />
                  <button
                    type="button"
                    className="btn btn-error btn-outline"
                    onClick={() => handleRemoveCreateItem(index)}
                    disabled={createForm.items.length <= 1}
                  >
                    Quitar
                  </button>
                </div>
              ))
            )}
          </div>
        </form>
      </Modal>
    </EventsTemplate>
  );
};

export default Events;
