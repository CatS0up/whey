import Swal from 'sweetalert2';

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

const emitModalEvent = event => {
    const { method, params } = event;

    window.livewire.emit(method, params);
};

window.addEventListener('swal:modal', e => {
    const modalData = e.detail;

    Swal.fire({
        title: modalData.title,
        text: modalData.text,
        icon: modalData.icon,
        showDenyButton: modalData.show_deny_button,
        showCancelButton: modalData.show_cancel_button,
        confirmButtonText: modalData.confirm_button_text,
        denyButtonText: modalData.deny_button_text,
    }).then(result => {
        if (result.isConfirmed && modalData.cofirmEvent) {
            emitModalEvent(modalData.cofirmEvent);
        }

        if (!result.isConfirmed && modalData.denyEvent) {
            emitModalEvent(modalData.denyEvent);
        }
    })
});


export { Swal, Toast };
