document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll(".product-checkbox");
    const totalPriceElement = document.querySelector(".total_price");
    const productCheckBoxAll = document.querySelector('.product-checkbox-all');

    // Hàm cập nhật tổng giá giỏ hàng
    function updateTotal() {
        let total = 0;
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                total += parseFloat(checkbox.dataset.price);
            }
        });
        totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + " VND";
    }

    // Hàm cập nhật tổng tiền của từng sản phẩm khi số lượng thay đổi
    function updateProductTotal(row) {
        const quantityInput = row.querySelector('input[name="quantity"]');
        const priceElement = row.querySelector(".price-product");
        const totalElement = row.querySelector("td:nth-child(5)");
        const checkbox = row.querySelector(".product-checkbox");

        const unitPrice = parseFloat(priceElement.dataset.price); // Lấy giá sản phẩm gốc
        const quantity = parseInt(quantityInput.value);
        const newTotal = unitPrice * quantity;

        totalElement.textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + " VND"; // Cập nhật tổng tiền
        checkbox.dataset.price = newTotal; // Cập nhật giá trị checkbox để tổng giỏ hàng chính xác

        updateTotal(); // Cập nhật tổng giỏ hàng
    }

    // Xử lý khi nhấn nút tăng/giảm số lượng
    document.querySelectorAll("tr").forEach(row => {
        const decreaseBtn = row.querySelector("#btn-decrease");
        const increaseBtn = row.querySelector("#btn-increase");
        const quantityInput = row.querySelector('input[name="quantity"]');

        if (decreaseBtn && increaseBtn && quantityInput) {
            const maxValue = parseInt(quantityInput.max);

            decreaseBtn.addEventListener("click", function() {
                if (quantityInput.value > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    updateProductTotal(row);
                }
            });

            increaseBtn.addEventListener("click", function() {
                if (quantityInput.value < maxValue) {
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updateProductTotal(row);
                }
            });

            quantityInput.addEventListener("change", function() {
                if (quantityInput.value < 1) {
                    quantityInput.value = 1;
                }
                if (quantityInput.value > maxValue) {
                    quantityInput.value = maxValue;
                }
                updateProductTotal(row);
            });
        }
    });

    // Gán sự kiện cho checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", updateTotal);
    });

    // Gán sự kiện cho checkbox "Chọn tất cả"
    productCheckBoxAll.addEventListener("change", function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateTotal();
    });

    updateTotal(); // Cập nhật tổng khi trang tải
});
