document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('a[href*="delete"]').forEach(link => {
        link.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });
});
