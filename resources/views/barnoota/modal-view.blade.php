@php
    $hirmaannaaData = json_decode($barnoota->Hirmaannaa_itti_fufiinsa, true) ?? [];
    $buaaData = json_decode($barnoota->Bu_aa_qabeessummaa, true) ?? [];
    $nyaataData = json_decode($barnoota->Dhiyeesii_nyaata_barattootaa, true) ?? [];
    $totalCost = array_sum(array_column($nyaataData, 'cost'));
@endphp

<div class="modal fade" id="viewModal{{ $barnoota->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-info">
                <h5 class="modal-title">Barnoota Report Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- Basic Information --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th width="40%">Report ID:</th>
                                <td>{{ $barnoota->id }}</td>
                            </tr>
                            <tr>
                                <th>Year:</th>
                                <td>{{ $barnoota->year }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $barnoota->status == 'approved' ? 'success' :
                                        ($barnoota->status == 'rejected' ? 'danger' : 'warning')
                                    }}">
                                        {{ ucfirst($barnoota->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $barnoota->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Region:</th>
                                <td>{{ $barnoota->region->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Zone:</th>
                                <td>{{ $barnoota->zone->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Woreda:</th>
                                <td>{{ $barnoota->woreda->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kebele:</th>
                                <td>{{ $barnoota->kebele->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Sector:</th>
                                <td>{{ $barnoota->sector->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Hirmaannaa --}}
                <div class="card card-info mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Hirmaannaa fi Ittifufiinsa</h6>
                    </div>

                    <div class="card-body p-0">
                        @if(count($hirmaannaaData))
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Student Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($hirmaannaaData as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? 'N/A' }}</td>
                                        <td>{{ $item['type'] ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($item['student_type'] ?? 'N/A') }}</td>
                                        <td>{{ number_format($item['amount'] ?? 0, 2) }} Lakk</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="p-3 mb-0 text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                {{-- Bu'aa --}}
                <div class="card card-warning mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Bu'aa Qabeessummaa Barnootaa</h6>
                    </div>

                    <div class="card-body p-0">
                        @if(count($buaaData))
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Student Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($buaaData as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? 'N/A' }}</td>
                                        <td>{{ $item['type'] ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($item['student_type'] ?? 'N/A') }}</td>
                                        <td>{{ number_format($item['amount'] ?? 0, 2) }} Lakk</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="p-3 mb-0 text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                {{-- Nyaata --}}
                <div class="card card-success">
                    <div class="card-header">
                        <h6 class="mb-0">Dhiyeesii Nyaata Barattootaa</h6>
                    </div>

                    <div class="card-body p-0">
                        @if(count($nyaataData))
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Amount</th>
                                        <th>Source</th>
                                        <th>Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($nyaataData as $item)
                                    <tr>
                                        <td>{{ number_format($item['amount'] ?? 0, 2) }} Kunt</td>
                                        <td>{{ $item['source'] ?? 'N/A' }}</td>
                                        <td>{{ number_format($item['cost'] ?? 0, 2) }} Birr</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                {{-- Total Cost --}}
                                <tfoot>
                                    <tr>
                                        <th>Total:</th>
                                        <th></th>
                                        <th>{{ number_format($totalCost, 2) }} Birr</th>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <p class="p-3 mb-0 text-muted">No data available</p>
                        @endif
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <a href="{{ route('sector.barnoota.edit', $barnoota->id) }}"
                   class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Report
                </a>
            </div>

        </div>
    </div>
</div>
