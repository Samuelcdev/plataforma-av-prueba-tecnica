import React, { useEffect, useRef } from 'react';
import { X } from 'lucide-react';

const sizeClasses = {
  sm: 'max-w-lg',
  md: 'max-w-2xl',
  lg: 'max-w-4xl',
  full: 'max-w-7xl',
};

const Modal = ({
  isOpen,
  onClose,
  title,
  children,
  actions,
  size = 'md',
  closeDisabled = false,
}) => {
  const dialogRef = useRef(null);

  useEffect(() => {
    const dialog = dialogRef.current;
    if (!dialog) return;

    if (isOpen && !dialog.open) {
      dialog.show();
    }

    if (!isOpen && dialog.open) {
      dialog.close();
    }
  }, [isOpen]);

  useEffect(() => {
    const dialog = dialogRef.current;
    if (!dialog) return;

    const handleCancel = (event) => {
      if (closeDisabled) {
        event.preventDefault();
        return;
      }

      event.preventDefault();
      onClose?.();
    };

    dialog.addEventListener('cancel', handleCancel);
    return () => {
      dialog.removeEventListener('cancel', handleCancel);
    };
  }, [closeDisabled, onClose]);

  const handleBackdropClose = () => {
    if (closeDisabled) return;
    onClose?.();
  };

  return (
    <dialog
      ref={dialogRef}
      className="modal"
      onClose={() => {
        if (isOpen) onClose?.();
      }}
    >
      <div className={`modal-box z-50 w-11/12 ${sizeClasses[size] || sizeClasses.md}`}>
        <div className="mb-4 flex items-start justify-between gap-3">
          {title ? <h3 className="text-lg font-semibold">{title}</h3> : <span />}
          <button
            type="button"
            className="btn btn-ghost btn-sm btn-circle"
            onClick={handleBackdropClose}
            disabled={closeDisabled}
            aria-label="Cerrar modal"
          >
            <X size={18} />
          </button>
        </div>

        <div>{children}</div>

        {actions ? <div className="modal-action mt-6">{actions}</div> : null}
      </div>

      <form method="dialog" className="modal-backdrop modal-background">
        <button type="button" onClick={handleBackdropClose} aria-label="Cerrar modal" />
      </form>
    </dialog>
  );
};

export default Modal;
