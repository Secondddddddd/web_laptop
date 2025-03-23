@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 alert">
        <p>{{ session('error') }}</p>
    </div>
@endif



<script>
    function closeAlert(alertId) {
        document.getElementById(alertId).style.display = 'none';
    }

    // Tự động ẩn sau 5 giây
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => alert.style.display = 'none');
    }, 5000);
</script>
