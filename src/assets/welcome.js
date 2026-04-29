document.addEventListener('DOMContentLoaded', function() {
    const jenisSelect = document.getElementById('jenisKendaraan');
    const merkSelect = document.getElementById('merkKendaraan');
    const unitSelect = document.getElementById('pilihUnit');
    const tenorRadios = document.getElementsByName('tenor');

    // Helper untuk menangani fetch agar lebih aman
    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            // Pastikan response adalah JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new TypeError("Ups! Server tidak mengirimkan JSON. Cek file PHP Anda.");
            }
            
            return await response.json();
        } catch (e) {
            console.error("Gagal mengambil data:", e);
            return null;
        }
    }

    // 1. Fetch MERK berdasarkan Jenis
    jenisSelect.addEventListener('change', async function() {
        const idKategori = this.value;
        merkSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        unitSelect.innerHTML = '<option value="0">-- Pilih Unit --</option>';
        merkSelect.disabled = true;
        unitSelect.disabled = true;

        if (idKategori) {
            // TIPS DOCKER: Pastikan folder 'admin' sejajar dengan file index/js ini
            const dataMerk = await fetchData(`../admin/get_merk.php?kategori_id=${idKategori}`);

            merkSelect.innerHTML = '<option value="">-- Pilih Merk --</option>';
            if (dataMerk && dataMerk.length > 0) {
                merkSelect.disabled = false;
                dataMerk.forEach(merk => {
                    const opt = document.createElement('option');
                    opt.value = merk.id; 
                    opt.textContent = merk.nama_merk;
                    merkSelect.appendChild(opt);
                });
            } else {
                merkSelect.innerHTML = '<option value="">Merk tidak ditemukan</option>';
            }
        } else {
            merkSelect.innerHTML = '<option value="">-- Pilih Merk --</option>';
            resetHasil();
        }
    });

    // 2. Fetch UNIT berdasarkan Merk
    merkSelect.addEventListener('change', async function() {
        const idMerk = this.value;
        unitSelect.innerHTML = '<option value="0">-- Memuat... --</option>';
        unitSelect.disabled = true;

        if (idMerk) {
            const dataUnit = await fetchData(`../admin/get_unit.php?merk_id=${idMerk}`);

            unitSelect.innerHTML = '<option value="0">-- Pilih Unit --</option>';
            if (dataUnit && dataUnit.length > 0) {
                unitSelect.disabled = false;
                dataUnit.forEach(unit => {
                    const opt = document.createElement('option');
                    // KRUSIAL: Pastikan field 'harga_pasar' ada di query SQL Anda
                    opt.value = unit.harga_pasar || 0; 
                    opt.textContent = `${unit.nama_unit} ${unit.model || ''}`;
                    unitSelect.appendChild(opt);
                });
            } else {
                unitSelect.innerHTML = '<option value="0">Unit tidak ditemukan</option>';
            }
        } else {
            unitSelect.innerHTML = '<option value="0">-- Pilih Unit --</option>';
            resetHasil();
        }
    });

    // 3. LOGIKA HITUNG SIMULASI (Tetap sama, hanya penambahan validasi)
    function hitungSimulasi() {
        const hargaPasar = parseInt(unitSelect.value);
        let tenor = 12;
        
        tenorRadios.forEach(r => {
            if (r.checked) tenor = parseInt(r.value);
        });

        if (!isNaN(hargaPasar) && hargaPasar > 0) {
            const rasioCair = 0.95;
            const maksimalCair = hargaPasar * rasioCair;
            const bungaBulan = 0.009;
            const totalBunga = maksimalCair * bungaBulan * tenor;
            const totalHutang = maksimalCair + totalBunga;
            const cicilanPerBulan = totalHutang / tenor;

            animateValue("hargaPasarHasil", hargaPasar);
            animateValue("pinjamanHasil", maksimalCair);
            animateValue("cicilanHasil", cicilanPerBulan);
        } else {
            resetHasil();
        }
    }

    unitSelect.addEventListener('change', hitungSimulasi);
    tenorRadios.forEach(radio => radio.addEventListener('change', hitungSimulasi));

    function resetHasil() {
        ["hargaPasarHasil", "pinjamanHasil", "cicilanHasil"].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.innerText = "0";
        });
    }

    function animateValue(id, value) {
        const obj = document.getElementById(id);
        if(!obj) return;
        
        const start = parseInt(obj.innerText.replace(/\./g, '')) || 0;
        const duration = 600;
        let startTimestamp = null;

        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const current = Math.floor(progress * (value - start) + start);
            obj.innerText = current.toLocaleString('id-ID');
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }
});