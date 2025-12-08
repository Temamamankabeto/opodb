<x-layout>
    <div class="content-header">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Kebele Asset Plans</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('sector.kebele-asset-plans.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add New Plan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Kebele Asset Plans Overview</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Year</th>
                                                <th>Properties</th>
                                                <th>Total Quantity</th>
                                                <th>Total Cost</th>
                                                <th>Status</th>
                                                <th width="150">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Group plans by year
                                                $groupedPlans = $plans->groupBy('year')->sortDesc();
                                            @endphp
                                            
                                            @foreach($groupedPlans as $year => $yearPlans)
                                                @php
                                                    $yearTotalQuantity = 0;
                                                    $yearTotalCost = 0;
                                                    $yearProperties = [];
                                                    $yearStatus = 'pending';
                                                    
                                                    // Process each plan for this year
                                                    foreach($yearPlans as $plan) {
                                                        if($plan->kebele_asset_plan) {
                                                            foreach($plan->kebele_asset_plan as $propertyId => $property) {
                                                                // Calculate quantity based on property type
                                                                $quantity = 0;
                                                                $cost = 0;
                                                                
                                                                // Different fields for different property types
                                                                if($property['name'] == 'Galii Mootummaa' || $property['name'] == 'Hojiiwwan Tajaajila Lammummaa') {
                                                                    $quantity = $property['amount'] ?? 0;
                                                                    $cost = $property['amount'] ?? 0;
                                                                } elseif($property['name'] == 'Buusaa Gonofaa') {
                                                                    $quantity = $property['members'] ?? 0;
                                                                    $cost = ($property['membership_fee'] ?? 0) + ($property['monetary_contribution'] ?? 0);
                                                                } elseif($property['name'] == 'Gaachena Sirnaa') {
                                                                    $quantity = ($property['transferred_persons'] ?? 0) + ($property['daily_served_persons'] ?? 0);
                                                                } elseif($property['name'] == 'Fudhannaa Liqi') {
                                                                    $quantity = $property['received_this_year'] ?? 0;
                                                                    $cost = ($property['returned_this_year'] ?? 0) + ($property['not_returned'] ?? 0);
                                                                } elseif($property['name'] == 'Qusannaa Baankii Siinqee') {
                                                                    $quantity = 1;
                                                                    $cost = ($property['family_amount'] ?? 0) + ($property['association_amount'] ?? 0);
                                                                } else {
                                                                    $quantity = $property['quantity'] ?? 0;
                                                                    $cost = $property['cost'] ?? 0;
                                                                }
                                                                
                                                                $yearTotalQuantity += $quantity;
                                                                $yearTotalCost += $cost;
                                                                
                                                                // Store property details
                                                                if(!isset($yearProperties[$propertyId])) {
                                                                    $yearProperties[$propertyId] = [
                                                                        'name' => $property['name'] ?? 'Unknown',
                                                                        'unit' => $property['unit'] ?? '',
                                                                        'quantity' => 0,
                                                                        'cost' => 0
                                                                    ];
                                                                }
                                                                $yearProperties[$propertyId]['quantity'] += $quantity;
                                                                $yearProperties[$propertyId]['cost'] += $cost;
                                                            }
                                                        }
                                                        
                                                        // Check status
                                                        if($plan->status === 'approved') {
                                                            $yearStatus = 'approved';
                                                        }
                                                    }
                                                @endphp
                                                
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        <i class="fas fa-calendar-alt text-primary mr-2"></i>{{ $year }} EC
                                                    </td>
                                                    <td>
                                                        <button type="button" 
                                                                class="btn btn-info btn-sm" 
                                                                data-toggle="modal" 
                                                                data-target="#propertiesModal{{ $year }}">
                                                            <i class="fas fa-eye"></i> View Properties ({{ count($yearProperties) }})
                                                        </button>
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        {{ number_format($yearTotalQuantity, 2) }}
                                                    </td>
                                                    <td class="font-weight-bold text-success">
                                                        ETB {{ number_format($yearTotalCost, 2) }} Birr
                                                    </td>
                                                    <td>
                                                        @if($yearStatus === 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            @if($yearPlans->first())
                                                                <a href="{{ route('sector.kebele-asset-plans.edit', $yearPlans->first()->id) }}" 
                                                                   class="btn btn-warning btn-sm"
                                                                   title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('sector.kebele-asset-plans.delete', $yearPlans->first()->id) }}" 
                                                                      method="POST" 
                                                                      class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="btn btn-danger btn-sm" 
                                                                            onclick="return confirm('Are you sure you want to delete this kebele asset plan?')"
                                                                            title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                            @if($plans->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                                            <p>No kebele asset plans found.</p>
                                                            <a href="{{ route('sector.kebele-asset-plans.create') }}" class="btn btn-success">
                                                                <i class="fas fa-plus"></i> Create Your First Plan
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Properties Detail Modals -->
    @foreach($groupedPlans as $year => $yearPlans)
        @php
            $yearProperties = [];
            $yearTotalQuantity = 0;
            $yearTotalCost = 0;
            
            foreach($yearPlans as $plan) {
                if($plan->kebele_asset_plan) {
                    foreach($plan->kebele_asset_plan as $propertyId => $property) {
                        // Calculate quantity based on property type
                        $quantity = 0;
                        $cost = 0;
                        
                        // Different fields for different property types
                        if($property['name'] == 'Galii Mootummaa' || $property['name'] == 'Hojiiwwan Tajaajila Lammummaa') {
                            $quantity = $property['amount'] ?? 0;
                            $cost = $property['amount'] ?? 0;
                        } elseif($property['name'] == 'Buusaa Gonofaa') {
                            $quantity = $property['members'] ?? 0;
                            $cost = ($property['membership_fee'] ?? 0) + ($property['monetary_contribution'] ?? 0);
                        } elseif($property['name'] == 'Gaachena Sirnaa') {
                            $quantity = ($property['transferred_persons'] ?? 0) + ($property['daily_served_persons'] ?? 0);
                        } elseif($property['name'] == 'Fudhannaa Liqi') {
                            $quantity = $property['received_this_year'] ?? 0;
                            $cost = ($property['returned_this_year'] ?? 0) + ($property['not_returned'] ?? 0);
                        } elseif($property['name'] == 'Qusannaa Baankii Siinqee') {
                            $quantity = 1;
                            $cost = ($property['family_amount'] ?? 0) + ($property['association_amount'] ?? 0);
                        } else {
                            $quantity = $property['quantity'] ?? 0;
                            $cost = $property['cost'] ?? 0;
                        }
                        
                        $yearTotalQuantity += $quantity;
                        $yearTotalCost += $cost;
                        
                        if(!isset($yearProperties[$propertyId])) {
                            $yearProperties[$propertyId] = [
                                'name' => $property['name'] ?? 'Unknown',
                                'unit' => $property['unit'] ?? '',
                                'quantity' => 0,
                                'cost' => 0
                            ];
                        }
                        $yearProperties[$propertyId]['quantity'] += $quantity;
                        $yearProperties[$propertyId]['cost'] += $cost;
                    }
                }
            }
        @endphp
        
        <div class="modal fade" id="propertiesModal{{ $year }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-alt mr-2"></i>Kebele Asset Plan Properties for {{ $year }} EC
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-tasks"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Properties</span>
                                        <span class="info-box-number">{{ count($yearProperties) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Quantity</span>
                                        <span class="info-box-number">{{ number_format($yearTotalQuantity, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Cost</span>
                                        <span class="info-box-number">ETB {{ number_format($yearTotalCost, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Properties Details</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Property Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($yearProperties as $property)
                                                <tr>
                                                    <td>
                                                        <i class="fas fa-chevron-right text-primary mr-2"></i>
                                                        {{ $property['name'] }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $property['unit'] }}</span>
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        {{ number_format($property['quantity'], 2) }}
                                                    </td>
                                                    <td class="font-weight-bold text-success">
                                                        ETB {{ number_format($property['cost'], 2) }} Birr
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <td class="font-weight-bold text-right">Total:</td>
                                                <td></td>
                                                <td class="font-weight-bold">
                                                    {{ number_format($yearTotalQuantity, 2) }}
                                                </td>
                                                <td class="font-weight-bold text-success">
                                                    ETB {{ number_format($yearTotalCost, 2) }} Birr
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($yearPlans->first())
                            <a href="{{ route('sector.kebele-asset-plans.edit', $yearPlans->first()->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit mr-2"></i>Edit Plan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>

 
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }
    .info-box .info-box-icon {
        border-radius: .25rem;
        align-items: center;
        display: flex;
        font-size: 1.875rem;
        justify-content: center;
        text-align: center;
        width: 70px;
    }
    .info-box .info-box-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.8;
        flex: 1;
        padding: 0 10px;
    }
    .info-box .info-box-text {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-transform: uppercase;
        font-size: .875rem;
    }
    .info-box .info-box-number {
        display: block;
        margin-top: .25rem;
        font-weight: 700;
        font-size: 1.5rem;
    }
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .btn-group form {
        margin: 0;
    }
    .modal-lg {
        max-width: 800px;
    }
</style>
 

 
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[title]').tooltip();
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
 