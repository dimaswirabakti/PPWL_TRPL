// Konfirmasi sebelum menghapus data.
// Event delegation: berlaku untuk semua form dengan atribut [data-confirm-delete],
// baik di halaman index (banyak) maupun di halaman show (satu).
document.addEventListener("submit", (event) => {
    const form = event.target.closest("[data-confirm-delete]");
    if (!form) return;

    const pesan =
        form.dataset.confirmDelete || "Yakin ingin menghapus data ini?";
    if (!window.confirm(pesan)) {
        event.preventDefault();
    }
});
