document.addEventListener('DOMContentLoaded', function () {
    const editPhoneBtn = document.querySelector('.edit-phone');
    const inputUserPhone = document.querySelector('.input-user-phone');

    const provinceSelect = document.querySelector('select[name="province_code"]');
    const districtSelect = document.querySelector('select[name="district_code"]');
    const wardSelect = document.querySelector('select[name="ward_code"]');
    const addressDetail = document.querySelector('.address_detail');
    const btnAddAddress = document.querySelector('.btn-add-address');
    const inputAddress = document.querySelector('.input-address');


    const saveAddressBtn = document.querySelector('.save-address-btn'); // Nút Lưu trong modal Chọn địa chỉ
    const addressSelect = document.querySelector('.address-select'); // Dropdown chọn địa chỉ
    const addressSpan = document.querySelector('.address'); // Span hiển thị địa chỉ
    const btnNewAddAddress = document.querySelector('.save-new-address-btn');
    if(addressSpan.textContent.trim().replace(/\s+/g, ' ') !== 'Không có'){
        inputAddress.value = addressSpan.textContent.trim().replace(/\s+/g, ' ');
    }else {
        inputAddress.value = '';
    }
    btnAddAddress.addEventListener('click', function () {
        fetch(`/api/province`)
            .then(response => response.json())
            .then(data => {
                data.forEach(province => { // Sửa "district" thành "province"
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.full_name;
                    provinceSelect.appendChild(option);
                });
            });
    });

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
                .then(response => response.json())
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

        saveAddressBtn.addEventListener('click', function () {
            const selectedOption = addressSelect.options[addressSelect.selectedIndex];
            if (selectedOption) {
                const fullAddress = selectedOption.getAttribute('data-full-address');
                if (fullAddress) {
                    addressSpan.textContent = fullAddress;
                    inputAddress.value = fullAddress;
                }
            }
        });

        btnNewAddAddress.addEventListener('click', ()=>{
            const selectedProvince = provinceSelect.options[provinceSelect.selectedIndex]?.textContent;
            const selectedDistrict = districtSelect.options[districtSelect.selectedIndex]?.textContent;
            const selectedWard = wardSelect.options[wardSelect.selectedIndex]?.textContent;
            const address = addressDetail.value;
            addressSpan.textContent = `${address},  ${selectedWard},${selectedDistrict}, ${selectedProvince}`;
            inputAddress.value = addressSpan.textContent;
        });

    editPhoneBtn.addEventListener('click', () => {
        if (editPhoneBtn.innerText === 'Chỉnh sửa') {
            inputUserPhone.removeAttribute('readonly');
            inputUserPhone.focus();
            editPhoneBtn.innerText = 'Lưu';
        } else if (editPhoneBtn.innerText === 'Lưu') {
            const phone = inputUserPhone.value.trim();

            if (phone !== '' && phone.length === 10 && phone.charAt(0) === '0') {
                inputUserPhone.setAttribute('readonly', true);
                editPhoneBtn.innerText = 'Chỉnh sửa';
            } else {
                alert('Số điện thoại không hợp lệ');
            }
        }
    });
});
