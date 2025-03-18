document.addEventListener("DOMContentLoaded", () => {
    const increaseBtn = document.getElementById('btn-increase');
    const decreaseBtn = document.getElementById('btn-decrease');
    const quantityInput = document.getElementById('quantity-product');
    const buyNowQuantity = document.getElementById('buy-now-quantity');
    const buyNowForm = document.getElementById('buy-now-form');
    const maxQuantity = Number(quantityInput.max) || 1; // Nếu không có max thì mặc định là 1

    const updateQuantity = (change) => {
        let currentValue = Number(quantityInput.value) || 1;
        let newValue = currentValue + change;

        if (newValue < 1) newValue = 1;
        if (newValue > maxQuantity) newValue = maxQuantity;

        quantityInput.value = newValue;
        buyNowQuantity.value = quantityInput.value;
    };

    increaseBtn.addEventListener('click', () => updateQuantity(1));
    decreaseBtn.addEventListener('click', () => updateQuantity(-1));

    quantityInput.addEventListener('input', () => {
        let currentValue = Number(quantityInput.value);
        if (currentValue > maxQuantity) {
            quantityInput.value = maxQuantity;
        } else if (currentValue < 1 || isNaN(currentValue)) {
            quantityInput.value = 1;
        }
    });
});
