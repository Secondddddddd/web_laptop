document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.querySelector('select[name="province_code"]');
    const districtSelect = document.querySelector('select[name="district_code"]');
    const wardSelect = document.querySelector('select[name="ward_code"]');

    // Khi chọn tỉnh/thành phố
    provinceSelect.addEventListener('change', function () {
        const provinceCode = this.value;
        districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>'; // Reset
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>'; // Reset

        if (provinceCode) {
            fetch(`/api/districts/${provinceCode}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.full_name;
                        districtSelect.appendChild(option);
                    });
                });
        }
    });

    // Khi chọn quận/huyện
    districtSelect.addEventListener('change', function () {
        const districtCode = this.value;
        wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>'; // Reset

        if (districtCode) {
            fetch(`/api/wards/${districtCode}`)
                .then(response => response.json()

                )
                .then(data => {
                    data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.full_name;
                        wardSelect.appendChild(option);
                    });
                });
        }
    });

    // Mở / Đóng form modal
    window.toggleAddressForm = function () {
        const modal = document.getElementById('addressModal');
        modal.classList.toggle('hidden');
    };
});


