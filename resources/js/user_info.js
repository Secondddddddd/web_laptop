document.addEventListener("DOMContentLoaded", function () {
    // Các component
    const userInfoComponent = document.querySelector(".user-info-component");
    const addressComponent = document.querySelector(".address-component");
    const changePasswordComponent = document.querySelector(".change-password");
    const transactionHistoryComponent = document.querySelector(".transaction-history");
    const active = document.querySelector('.activeComponent').innerText;
    // Menu items
    const menuItems = document.querySelectorAll("#profile-menu .menu-item");

    // Hàm ẩn tất cả các component
    function hideAllComponents() {
        userInfoComponent.classList.add("hidden");
        addressComponent.classList.add("hidden");
        changePasswordComponent.classList.add("hidden");
        transactionHistoryComponent.classList.add("hidden");
    }

    function removeTextPrimaryClass() {
        menuItems.forEach(item => {
            item.querySelector("p").classList.remove("text-primary");
        });
    }

    // Hàm hiển thị component theo key
    function showComponent(key) {
        hideAllComponents();
        removeTextPrimaryClass();

        switch (key) {
            case "user-info":
                userInfoComponent.classList.remove("hidden");
                menuItems[0].querySelector("p").classList.add("text-primary");
                break;
            case "address":
                addressComponent.classList.remove("hidden");
                menuItems[1].querySelector("p").classList.add("text-primary");
                break;
            case "change-password":
                changePasswordComponent.classList.remove("hidden");
                menuItems[2].querySelector("p").classList.add("text-primary");
                break;
            case "transaction-history":
                transactionHistoryComponent.classList.remove("hidden");
                menuItems[3].querySelector("p").classList.add("text-primary");
                break;
        }
        // Lưu lựa chọn vào localStorage để khi quay lại vẫn hiển thị đúng
        localStorage.setItem("activeComponent", key);
    }

    // Khi trang tải, kiểm tra localStorage để hiển thị component đã chọn (nếu có)
    // const activeComponent = localStorage.getItem("activeComponent") || ;
    showComponent(active);

    // Gán sự kiện cho từng menu item
    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            const text = this.textContent.trim();
            if (text.includes("Thông tin cá nhân")) {
                showComponent("user-info");
            } else if (text.includes("Địa chỉ")) {
                showComponent("address");
            } else if (text.includes("Đổi mật khẩu")) {
                // Chỉ hiển thị component đổi mật khẩu mà không chuyển hướng trang
                showComponent("change-password");
            } else if (text.includes("Lịch sử giao dịch")) {
                showComponent("transaction-history");
            }
        });
    });
});
