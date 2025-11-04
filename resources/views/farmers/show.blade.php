<x-layout>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card modern-card shadow-lg border-0">
                    <div class="card-header modern-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h1 class="h3 mb-0 text-white">{{ $farmer->farmer_name }}</h1>
                                <p class="mb-0 text-light opacity-75">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $farmer->region ?? '—' }}, {{ optional($farmer->zone)->name ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-modern" id="showAllParcelsBtn">
                                <i class="bi bi-map me-1"></i> View All Parcels
                            </button>
                            <a href="{{ route('farmers.index') }}" class="btn btn-outline-light btn-modern">
                                <i class="bi bi-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Personal Information Card -->
            <div class="col-lg-4">
                <div class="card modern-card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-success">
                            <i class="bi bi-person-vcard me-2"></i>Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Farmer Name</span>
                                <span class="info-value">{{ $farmer->farmer_name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Gender</span>
                                <span class="info-value">{{ ucfirst($farmer->gender) }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Phone</span>
                                <span class="info-value">{{ $farmer->phone_number ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Region</span>
                                <span class="info-value">{{ $farmer->region ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Zone</span>
                                <span class="info-value">{{ optional($farmer->zone)->name ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Woreda</span>
                                <span class="info-value">{{ optional($farmer->woreda)->name ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Kebele</span>
                                <span class="info-value">{{ optional($farmer->kebele)->name ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Union</span>
                                <span class="info-value">{{ optional($farmer->union)->name ?? '—' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Association</span>
                                <span class="info-value">{{ optional($farmer->association)->name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Land Details Card -->
            <div class="col-lg-8">
                <div class="card modern-card shadow-sm border-0 h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-success">
                            <i class="bi bi-map me-2"></i>Land Details
                        </h5>
                         
                    </div>
                    <div class="card-body">
                        @php
                            $landDetails = json_decode($farmer->land_details, true);
                        @endphp

                        @if (!empty($landDetails))
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Land Type</th>
                                            <th>Size (ha)</th>
                                            <th>GPS Coordinates</th>
                                            <th>Soil Type</th>
                                            <th>Irrigation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($landDetails as $index => $land)
                                            <tr>
                                                <td>
                                                    <span class="index-badge">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <span class="land-type-tag">
                                                        {{ $landTypes[$land['land_type_id']] ?? '—' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="size-value">{{ $land['size'] ?? '—' }}</span>
                                                </td>
                                                <td>
                                                    @if ($land['gps_x'] && $land['gps_y'])
                                                        <span class="gps-coords">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            {{ $land['gps_x'] }}, {{ $land['gps_y'] }}
                                                        </span>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="soil-badge">
                                                        {{ $soilTypes[$land['soil_type']] ?? '—' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="irrigation-badge">
                                                        {{ $land['irrigation_type'] ?? '—' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-0">No land details recorded</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Land Type Details with Maps -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card modern-card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-success">
                            <i class="bi bi-layers me-2"></i>Land Type Details
                        </h5>
                        <a href="{{ route('farmers.land.edit', $farmer->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Update Land Details
                        </a>
                    </div>
                    <div class="card-body">
                        @php
                            $landTypeDetails = json_decode($farmer->land_type_details, true);
                        @endphp

                        @if (!empty($landTypeDetails))
                            <div class="accordion" id="landTypeAccordion">
                                @foreach ($landTypeDetails as $index => $type)
                                    <div class="accordion-item border-0 mb-3">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button collapsed" type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#collapse{{ $index }}" 
                                                    aria-expanded="false" 
                                                    aria-controls="collapse{{ $index }}">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <div>
                                                        <span class="land-type-title">
                                                            {{ $landTypes[$type['land_type_id']] ?? 'Unknown Land Type' }}
                                                        </span>
                                                        <span class="badge bg-primary ms-2">
                                                            {{ count($type['parcels'] ?? []) }} parcels
                                                        </span>
                                                    </div>
                                                    <div class="text-muted">
                                                        <strong>{{ $type['total_size'] ?? 0 }} ha</strong>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" 
                                             aria-labelledby="heading{{ $index }}" 
                                             data-bs-parent="#landTypeAccordion">
                                            <div class="accordion-body p-0">
                                                @if (!empty($type['parcels']))
                                                    <div class="row g-3 p-3">
                                                        @foreach ($type['parcels'] as $parcelIndex => $parcel)
                                                            <div class="col-md-6 col-lg-4">
                                                                <div class="parcel-card card border h-100">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">
                                                                            <i class="bi bi-geo me-1"></i>
                                                                            Parcel #{{ $parcelIndex + 1 }}
                                                                        </h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="parcel-details">
                                                                            <div class="detail-item">
                                                                                <span class="label">Size:</span>
                                                                                <span class="value">{{ $parcel['size'] ?? '—' }} ha</span>
                                                                            </div>
                                                                            <div class="detail-item">
                                                                                <span class="label">GPS:</span>
                                                                                <span class="value">
                                                                                    {{ $parcel['gps_x'] ?? '—' }}, {{ $parcel['gps_y'] ?? '—' }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="detail-item">
                                                                                <span class="label">Soil:</span>
                                                                                <span class="value">{{ $soilTypes[$parcel['soil_type']] ?? '—' }}</span>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        @if (!empty($parcel['gps_x']) && !empty($parcel['gps_y']))
                                                                            <div class="mt-3">
                                                                                <div id="map-{{ $index }}-{{ $parcelIndex }}" 
                                                                                     class="parcel-map" 
                                                                                     data-lat="{{ $parcel['gps_y'] }}" 
                                                                                     data-lng="{{ $parcel['gps_x'] }}" 
                                                                                     data-title="Parcel #{{ $parcelIndex + 1 }} ({{ $parcel['size'] }} ha)">
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="no-gps-data text-center py-3">
                                                                                <i class="bi bi-map text-muted"></i>
                                                                                <p class="mb-0 text-muted small">No GPS data available</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="bi bi-inbox display-6 text-muted"></i>
                                                        <p class="text-muted mt-2">No parcels for this land type</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-0">No land type details recorded</p>
                                <a href="{{ route('farmers.land.edit', $farmer->id) }}" class="btn btn-success mt-3">
                                    <i class="bi bi-plus-circle me-1"></i> Add Land Details
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for All Parcels Map -->
    <div class="modal fade" id="allParcelsModal" tabindex="-1" aria-labelledby="allParcelsLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header modern-header">
                    <h5 class="modal-title text-white" id="allParcelsLabel">
                        <i class="bi bi-map me-2"></i>All Farmer Parcels - {{ $farmer->farmer_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="allParcelsMap"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS Styles -->
    <style>
        :root {
            --primary-color: #198754;
            --primary-light: #e8f5e9;
            --secondary-color: #6c757d;
            --success-light: #d1eddd;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .modern-card {
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: var(--transition);
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .modern-header {
            background: linear-gradient(135deg, var(--primary-color), #2e7d32) !important;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .btn-modern {
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .info-value {
            font-weight: 600;
            color: #333;
        }

        .index-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: 50%;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .land-type-tag {
            background-color: #e3f2fd;
            color: #1565c0;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .size-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .gps-coords {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--secondary-color);
        }

        .soil-badge, .irrigation-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            background-color: #f5f5f5;
            color: #555;
        }

        .total-land-badge .badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .accordion-button {
            background-color: white;
            border-radius: var(--border-radius) !important;
            padding: 1.25rem;
            font-weight: 500;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--primary-light);
            color: var(--primary-color);
            box-shadow: none;
        }

        .land-type-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .parcel-card {
            border-radius: 10px;
            transition: var(--transition);
        }

        .parcel-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .parcel-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
        }

        .detail-item .label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .detail-item .value {
            font-weight: 600;
        }

        .parcel-map {
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .no-gps-data {
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        #allParcelsMap {
            height: 100%;
            min-height: 70vh;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modern-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .info-item {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .parcel-map {
                height: 150px;
            }
        }
    </style>

    <!-- Leaflet & Bootstrap JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Map Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize individual parcel maps when they come into view
            const mapContainers = document.querySelectorAll(".parcel-map");
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const container = entry.target;
                        const lat = parseFloat(container.dataset.lat);
                        const lng = parseFloat(container.dataset.lng);
                        const title = container.dataset.title;

                        if (!isNaN(lat) && !isNaN(lng)) {
                            // Clear loading text
                            container.innerHTML = '';
                            
                            const map = L.map(container).setView([lat, lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '&copy; OpenStreetMap'
                            }).addTo(map);
                            
                            L.marker([lat, lng])
                                .addTo(map)
                                .bindPopup(`<b>${title}</b>`)
                                .openPopup();
                        } else {
                            container.innerHTML = '<div class="text-center text-muted"><i class="bi bi-exclamation-circle"></i><p class="mb-0 mt-1">Invalid GPS data</p></div>';
                        }
                        obs.unobserve(container);
                    }
                });
            }, { threshold: 0.1 });
            
            mapContainers.forEach(container => observer.observe(container));

            // Show all parcels in modal
            const showAllBtn = document.getElementById('showAllParcelsBtn');
            const allParcelsModal = new bootstrap.Modal(document.getElementById('allParcelsModal'));
            const allParcelsMapContainer = document.getElementById('allParcelsMap');
            let allParcelsMap = null;

            showAllBtn.addEventListener('click', () => {
                allParcelsModal.show();
                
                // Initialize map after modal is shown
                setTimeout(() => {
                    if (allParcelsMap) {
                        allParcelsMap.remove();
                    }
                    
                    allParcelsMap = L.map(allParcelsMapContainer).setView([9.145, 40.4897], 6);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(allParcelsMap);

                    const parcels = @json(collect($landTypeDetails)->flatMap(fn($type) => $type['parcels'] ?? []));
                    const bounds = [];
                    let validParcels = 0;

                    parcels.forEach((p, i) => {
                        if (p.gps_y && p.gps_x) {
                            const lat = parseFloat(p.gps_y);
                            const lng = parseFloat(p.gps_x);
                            if (!isNaN(lat) && !isNaN(lng)) {
                                L.marker([lat, lng])
                                    .addTo(allParcelsMap)
                                    .bindPopup(`
                                        <b>Parcel #${i+1}</b><br>
                                        Size: ${p.size ?? '—'} ha<br>
                                        Soil: ${p.soil_type ? (@json($soilTypes)[p.soil_type] ?? '—') : '—'}
                                    `);
                                bounds.push([lat, lng]);
                                validParcels++;
                            }
                        }
                    });

                    if (bounds.length > 0) {
                        allParcelsMap.fitBounds(bounds, { padding: [20, 20] });
                    }
                    
                    // If no valid parcels, show message
                    if (validParcels === 0) {
                        allParcelsMapContainer.innerHTML = `
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="bi bi-map display-1 text-muted"></i>
                                    <h4 class="mt-3 text-muted">No parcels with GPS data available</h4>
                                    <p class="text-muted">Update land details to add GPS coordinates</p>
                                </div>
                            </div>
                        `;
                    }
                }, 100);
            });

            // Clean up map when modal is closed
            document.getElementById('allParcelsModal').addEventListener('hidden.bs.modal', function() {
                if (allParcelsMap) {
                    allParcelsMap.remove();
                    allParcelsMap = null;
                }
            });
        });
    </script>
</x-layout>