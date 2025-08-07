// public/js/toast-notifications.js

function showToast(type, title) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: title,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
}
