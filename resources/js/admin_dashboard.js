document.addEventListener("DOMContentLoaded", ()=>{
    const product = document.querySelector('.products-manage');
    const category = document.querySelector('.categories-manage');
    const account = document.querySelector('.accounts-manage');
    const supplier = document.querySelector('.suppliers-manage');
    const order = document.querySelector('.orders-manage');
    const addProductBtn = document.querySelector('.add-product-btn');
    product.addEventListener("click", ()=>{
        window.location.href = "/admin/product";
    });

    category.addEventListener("click", ()=>{
        window.location.href = "/admin/category";
    });

    account.addEventListener("click", ()=>{
        window.location.href = "/admin/users";
    });

    if (addProductBtn){
        addProductBtn.addEventListener("click", ()=>{
            window.location.href = "/admin/product/add";
        });
    }

    const editButtons = document.querySelectorAll(".edit-product-btn");
    if (editButtons.length > 0){
        // Lặp qua từng nút và thêm sự kiện click
        editButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // Ngăn chặn load lại trang nếu có link

                const productId = this.getAttribute("data-id");
                if (productId) {
                    window.location.href = `/admin/products/${productId}/edit`;
                }
            });
        });
    }
});

function closeAlert(alertId) {
    document.getElementById(alertId).style.display = 'none';
}

// Tự động ẩn sau 5 giây
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => alert.style.display = 'none');
}, 5000);
