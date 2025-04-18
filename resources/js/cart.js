// const checkbox = require("daisyui/components/checkbox/index.js");
document.addEventListener("DOMContentLoaded", function() {
    const checkboxes = document.querySelectorAll(".product-checkbox");
    const totalPriceElement = document.querySelector(".total_price");
    const productCheckBoxAll = document.querySelector('.product-checkbox-all');
    const paymentBtn = document.getElementById('payment-btn');
    const toast = document.getElementById('toast');
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

        const unitPrice = parseFloat(priceElement.dataset.price);
        const quantity = parseInt(quantityInput.value);
        const newTotal = unitPrice * quantity;

        const productId = row.getAttribute("data-id"); // Lấy ID sản phẩm từ <tr data-id="...">

        totalElement.textContent = new Intl.NumberFormat('vi-VN').format(newTotal) + " VND";
        checkbox.dataset.price = newTotal; // Cập nhật giá trị checkbox

        updateTotal(); // Cập nhật tổng giá trị giỏ hàng

        // Gửi request cập nhật session
        fetch(`/cart/update/${productId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({ quantity: quantity })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Cập nhật giỏ hàng thành công!");
                } else {
                    console.error("Cập nhật thất bại:", data.message);
                }
            })
            .catch(error => console.error("Lỗi:", error));
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

    paymentBtn.addEventListener("click", () => {
        let selectedProducts = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedProducts.push(checkbox.closest("tr").dataset.id);
            }
        });

        if (selectedProducts.length === 0) {
            toast.classList.remove("hidden");
            setTimeout(() => toast.classList.add("hidden"), 3000);
            return;
        }

        let form = document.createElement("form");
        form.method = "POST";
        form.action = "/cart/checkout";

        let csrf = document.createElement("input");
        csrf.type = "hidden";
        csrf.name = "_token";
        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        form.appendChild(csrf);

        selectedProducts.forEach(id => {
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "products[]";
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    });


});
