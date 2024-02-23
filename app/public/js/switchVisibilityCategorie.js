const inputs = document.querySelectorAll('input[data-switch-categorie-id]');

inputs.forEach((item) => {
    item.addEventListener('change', async (e) => {
        const id = e.currentTarget.dataset.switchCategorieId
        console.error(id);

        const response = await fetch(`/admin/categories/${id}/switch`);

        if (response.ok) {
            const data = await response.json();

            const card = e.target.closest('.card');

            if (data.visibility) {
                card.classList.remove('border-danger');
                card.classList.add('border-success');
            } else {
                card.classList.remove('border-success');
                card.classList.add('border-danger');
            }
        }
    });
});
