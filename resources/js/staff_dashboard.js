document.addEventListener("DOMContentLoaded", ()=>{
    const product = document.querySelector('.products-manage');
    const category = document.querySelector('.categories-manage');
    const supplier = document.querySelector('.suppliers-manage');
    const order = document.querySelector('.orders-manage');
    const addProductBtn = document.querySelector('.add-product-btn');
    const shipper = document.querySelector('.shipper-list');

    product.addEventListener("click", ()=>{
        window.location.href = "/staff/product";
    });

    category.addEventListener("click", ()=>{
        window.location.href = "/staff/category";
    });


    supplier.addEventListener("click", ()=>{
        window.location.href = "/staff/suppliers";
    });

    order.addEventListener("click", ()=>{
        window.location.href = "/staff/orders";
    });

    shipper.addEventListener('click', ()=>{
        window.location.href = "/staff/shipper-list"
    })

    if (addProductBtn){
        addProductBtn.addEventListener("click", ()=>{
            window.location.href = "/staff/product/add";
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
                    window.location.href = `/staff/products/${productId}/edit`;
                }
            });
        });
    }
});
