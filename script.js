document.addEventListener('DOMContentLoaded', function() {
    // 1. Suntikkan Progress Bar ke atas form secara otomatis
    const form = document.querySelector('form');
    const progressBarHTML = `
        <div class="progress mb-3" style="height: 10px;">
            <div id="form-progress" class="progress-bar bg-primary" style="width: 0%; transition: 0.4s;"></div>
        </div>
    `;
    form.insertAdjacentHTML('beforebegin', progressBarHTML);

    // 2. Ambil elemen yang sudah ada di kodingan PHP lo
    const inputs = form.querySelectorAll('input, select, textarea');
    const progressBar = document.getElementById('form-progress');
    const submitBtn = form.querySelector('button[name="simpan"]');

    // 3. Validasi Tanggal (Menangkap event submit asli)
    form.addEventListener('submit', function(e) {
        const tglMulai = new Date(document.getElementById('tanggal_mulai').value);
        const tglSelesai = new Date(document.getElementById('tanggal_selesai').value);

        if (tglSelesai < tglMulai) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Oops!', text: 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!' });
            return false;
        }
        
        // Tombol loading
        submitBtn.innerHTML = 'Menyimpan...';
        submitBtn.disabled = true;
    });

    // 4. Progress Bar real-time
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            let filled = 0;
            inputs.forEach(i => { if(i.value !== "") filled++; });
            let percent = (filled / inputs.length) * 100;
            progressBar.style.width = percent + "%";
            progressBar.className = (percent >= 80) ? 'progress-bar bg-success' : 'progress-bar bg-primary';
        });
    });
});