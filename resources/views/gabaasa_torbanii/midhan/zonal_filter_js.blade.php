<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const woreda = document.getElementById('woreda');
    const kebele = document.getElementById('kebele');
    const year = document.getElementById('year');
    const season = document.getElementById('season');
    const croptype = document.getElementById('croptype');
    const crop = document.getElementById('crop');

    function reset(select, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        select.disabled = true;
    }

    async function fetchData(url, params) {
        const query = new URLSearchParams(params).toString();
        try {
            const res = await fetch(`${url}?${query}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Network error');
            return await res.json();
        } catch (e) {
            console.error(e);
            return [];
        }
    }

    // WOREDA → KEBELE
    if (woreda) {
        woreda.addEventListener('change', async () => {
            reset(kebele, 'All Kebeles');
            reset(season, 'All Seasons');
            reset(croptype, 'All Crop Types');
            reset(crop, 'All Crops');

            if (!woreda.value) return;

            const data = await fetchData('/zones/filter/kebeles', { woreda_id: woreda.value });
            if (data.length) {
                kebele.disabled = false;
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = k.name;
                    kebele.appendChild(opt);
                });
            }
        });
    }

    // KELELE → YEAR
    if (kebele) {
        kebele.addEventListener('change', async () => {
            reset(year, 'Select Year');
            reset(season, 'All Seasons');
            reset(croptype, 'All Crop Types');
            reset(crop, 'All Crops');

            const params = { woreda_id: woreda.value || '', kebele_id: kebele.value || '' };
            const data = await fetchData('/zones/filter/seasons', params);
            
            if (data.length) {
                year.disabled = false;
                // Extract unique years from season objects if needed
                const years = [...new Set(data.map(s => s.year || s))];
                years.forEach(y => {
                    const opt = document.createElement('option');
                    opt.value = y;
                    opt.textContent = y;
                    year.appendChild(opt);
                });
            }
        });
    }

    // YEAR → SEASON
    if (year) {
        year.addEventListener('change', async () => {
            reset(season, 'All Seasons');
            reset(croptype, 'All Crop Types');
            reset(crop, 'All Crops');

            if (!year.value) return;

            const data = await fetchData('/zones/filter/seasons', {
                year: year.value,
                woreda_id: woreda.value || '',
                kebele_id: kebele.value || ''
            });

            if (data.length) {
                season.disabled = false;
                data.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s;
                    opt.textContent = s;
                    season.appendChild(opt);
                });
            }
        });
    }

    // SEASON → CROP TYPE
    if (season) {
        season.addEventListener('change', async () => {
            reset(croptype, 'All Crop Types');
            reset(crop, 'All Crops');

            if (!season.value || !year.value) return;

            const data = await fetchData('/zones/filter/crop-types', {
                year: year.value,
                season: season.value,
                woreda_id: woreda.value || '',
                kebele_id: kebele.value || ''
            });

            if (data.length) {
                croptype.disabled = false;
                data.forEach(ct => {
                    const opt = document.createElement('option');
                    opt.value = ct.id;
                    opt.textContent = ct.name;
                    croptype.appendChild(opt);
                });
            }
        });
    }

    // CROP TYPE → CROP
    if (croptype) {
        croptype.addEventListener('change', async () => {
            reset(crop, 'All Crops');

            if (!croptype.value || !season.value || !year.value) return;

            const data = await fetchData('/zones/filter/crops', {
                year: year.value,
                season: season.value,
                croptype_id: croptype.value,
                woreda_id: woreda.value || '',
                kebele_id: kebele.value || ''
            });

            if (data.length) {
                crop.disabled = false;
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name;
                    crop.appendChild(opt);
                });
            }
        });
    }

    // Initialize form on page load (keep previous selections)
    function initializeForm() {
        const selectedWoreda = "{{ request('woreda_id', '') }}";
        const selectedKebele = "{{ request('kebele_id', '') }}";
        const selectedYear = "{{ request('year', '') }}";
        const selectedSeason = "{{ request('season', '') }}";
        const selectedCropType = "{{ request('croptype_id', '') }}";
        const selectedCrop = "{{ request('crop_id', '') }}";

        if (selectedWoreda && woreda) woreda.value = selectedWoreda;
        if (selectedKebele && kebele) kebele.value = selectedKebele;
        if (selectedYear && year) year.value = selectedYear;
        if (selectedSeason && season) season.value = selectedSeason;
        if (selectedCropType && croptype) croptype.value = selectedCropType;
        if (selectedCrop && crop) crop.value = selectedCrop;
    }

    setTimeout(initializeForm, 500);
});
</script>
