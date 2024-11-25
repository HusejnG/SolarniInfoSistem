// Potvrda prije brisanja proizvoda ili narudžbe
document.addEventListener("DOMContentLoaded", function () {
    const deleteLinks = document.querySelectorAll('a[href*="delete_product.php"], a[href*="remove"]');
    deleteLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            const confirmDelete = confirm("Jeste li sigurni da želite obrisati ovaj proizvod?");
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    });
});

// Dinamičko ažuriranje ukupne cijene u korpi
const quantityInputs = document.querySelectorAll('input[type="number"]');
quantityInputs.forEach(input => {
    input.addEventListener("input", function () {
        const price = parseFloat(this.closest('.product-card').querySelector('.product-price').textContent.replace("KM", "").trim());
        const quantity = parseInt(this.value, 10) || 0;
        const totalElement = this.closest('.product-card').querySelector('.product-total');
        if (totalElement) {
            totalElement.textContent = (price * quantity).toFixed(2) + " KM";
        }
    });
});

// Dodavanje animacija na dugmad
const buttons = document.querySelectorAll("button");
buttons.forEach(button => {
    button.addEventListener("click", function () {
        this.style.transform = "scale(0.95)";
        setTimeout(() => {
            this.style.transform = "scale(1)";
        }, 200);
    });
});

// Validacija forme za unos proizvoda
const productForm = document.querySelector("form");
if (productForm) {
    productForm.addEventListener("submit", function (event) {
        const nameInput = this.querySelector('input[name="name"]');
        const priceInput = this.querySelector('input[name="price"]');
        const descriptionInput = this.querySelector('textarea[name="description"]');

        if (!nameInput.value.trim() || priceInput.value <= 0 || !descriptionInput.value.trim()) {
            alert("Sva polja su obavezna i moraju biti ispravno popunjena.");
            event.preventDefault();
        }
    });
}
