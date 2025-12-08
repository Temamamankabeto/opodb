<x-layout>
    <div class="content-header">
        <section class="content">
            <div class="container-fluid py-3">
                <h2 class="mb-3 text-primary">
                    <i class="fas fa-briefcase mr-2"></i>Job Opportunity Plans
                </h2>

                {{-- Flash message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

              

                {{-- Create Button --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <a href="{{ route('sector.employment-opportunity-plans.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle mr-2"></i>Create New Employment Plan
                        </a>
                    </div>
                </div>

                {{-- Main Table --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Employment Opportunity Plans</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Year (EC)</th>
                                        <th>Job Opportunity</th>
                                        <th>Enterprise Work</th>
                                        <th>Employment Statistics</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                        $groupedPlans = $plans->groupBy('year');
                                    @endphp

                                    @forelse ($groupedPlans as $year => $yearPlans)
                                        @foreach ($yearPlans as $index => $plan)
                                            @php
                                                $data = $plan->opportunity_data;
                                                $male = $data['male_members'] ?? 0;
                                                $female = $data['female_members'] ?? 0;
                                                $total = $male + $female;
                                            @endphp
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                
                                                {{-- Year with grouping visual --}}
                                                @if ($index === 0)
                                                    <td class="text-center" rowspan="{{ $yearPlans->count() }}">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="font-weight-bold text-primary" style="font-size: 1.2em;">{{ $year }}</span>
                                                            <span class="badge badge-secondary mt-1">EC</span>
                                                            <small class="text-muted mt-1">{{ $yearPlans->count() }} plan{{ $yearPlans->count() > 1 ? 's' : '' }}</small>
                                                        </div>
                                                    </td>
                                                @endif
                                                
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-2">
                                                            <i class="fas fa-briefcase text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $plan->jobOpportunity->name ?? 'N/A' }}</strong>
                                                            @if($plan->jobOpportunity->description ?? false)
                                                                <br>
                                                                <small class="text-muted">{{ Str::limit($plan->jobOpportunity->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-2">
                                                            <i class="fas fa-industry text-success"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $plan->enterpriseWork->name ?? 'N/A' }}</strong>
                                                            @if($plan->enterpriseWork->description ?? false)
                                                                <br>
                                                                <small class="text-muted">{{ Str::limit($plan->enterpriseWork->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <div class="employment-stats">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="mr-3 mb-1">
                                                                <span class="font-weight-bold text-info">Male:</span>
                                                                <span class="ml-1">{{ number_format($male) }}</span>
                                                            </div>
                                                            <div class="mr-3 mb-1">
                                                                <span class="font-weight-bold text-pink">Female:</span>
                                                                <span class="ml-1">{{ number_format($female) }}</span>
                                                            </div>
                                                            <div class="mb-1">
                                                                <span class="font-weight-bold text-success">Total:</span>
                                                                <span class="ml-1">{{ number_format($total) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    @if($plan->status === 'approved')
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check-circle mr-1"></i>Approved
                                                        </span>
                                                        @if($plan->approved_at)
                                                            <br>
                                                            <small class="text-muted">{{ $plan->approved_at->format('M d, Y') }}</small>
                                                        @endif
                                                    @elseif($plan->status === 'rejected')
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-times-circle mr-1"></i>Rejected
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                
                                               <td class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-cog"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('sector.employment-opportunity-plans.edit', $plan->id) }}">
                <i class="fas fa-edit mr-2 text-warning"></i>Edit
            </a>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#detailsModal{{ $plan->id }}">
                <i class="fas fa-eye mr-2 text-info"></i>View Details
            </a>
        </div>
    </div>
    
    {{-- Separate Delete Button --}}
    @if($plan->status !== 'approved')
    <form action="{{ route('sector.employment-opportunity-plans.destroy', $plan->id) }}" method="POST" class="d-inline ml-1">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
    @endif
</td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                                    <h4 class="text-muted">No Employment Plans Found</h4>
                                                    <p class="text-muted mb-3">You haven't created any employment opportunity plans yet.</p>
                                                    <a href="{{ route('sector.employment-opportunity-plans.create') }}" class="btn btn-primary">
                                                        <i class="fas fa-plus-circle mr-2"></i>Create First Plan
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($plans->count() > 0)
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Showing {{ $plans->count() }} employment plans
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $plans->groupBy('year')->count() }} years
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Modals for each plan --}}
                @foreach($plans as $plan)
                    <div class="modal fade" id="detailsModal{{ $plan->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">
                                        <i class="fas fa-file-alt mr-2"></i>
                                        Plan Details: {{ $plan->jobOpportunity->name ?? 'N/A' }}
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Year</label>
                                                <div class="form-control-plaintext border-bottom pb-2">{{ $plan->year }} EC</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Status</label>
                                                <div>
                                                    @if($plan->status === 'approved')
                                                        <span class="badge badge-success">Approved</span>
                                                    @elseif($plan->status === 'rejected')
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-warning">Pending</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Job Opportunity</label>
                                                <div class="border p-3 rounded bg-light">
                                                    <h5>{{ $plan->jobOpportunity->name ?? 'N/A' }}</h5>
                                                    <p class="mb-0 text-muted">{{ $plan->jobOpportunity->description ?? 'No description available' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Enterprise Work</label>
                                                <div class="border p-3 rounded bg-light">
                                                    <h5>{{ $plan->enterpriseWork->name ?? 'N/A' }}</h5>
                                                    <p class="mb-0 text-muted">{{ $plan->enterpriseWork->description ?? 'No description available' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $data = $plan->opportunity_data;
                                        $male = $data['male_members'] ?? 0;
                                        $female = $data['female_members'] ?? 0;
                                        $total = $male + $female;
                                    @endphp
                                    
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="font-weight-bold">Employment Statistics</label>
                                            <div class="border p-3 rounded">
                                                <div class="row text-center">
                                                    <div class="col-md-4 col-12 mb-3">
                                                        <div class="border p-3 rounded bg-info text-white">
                                                            <div class="h2 mb-1">{{ number_format($male) }}</div>
                                                            <div class="h5">Male Members</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12 mb-3">
                                                        <div class="border p-3 rounded bg-pink text-white">
                                                            <div class="h2 mb-1">{{ number_format($female) }}</div>
                                                            <div class="h5">Female Members</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <div class="border p-3 rounded bg-success text-white">
                                                            <div class="h2 mb-1">{{ number_format($total) }}</div>
                                                            <div class="h5">Total Members</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($plan->status === 'approved' && $plan->approved_by)
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="border p-3 rounded bg-light">
                                                    <h5 class="mb-3">Approval Information</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Approved By:</strong> {{ $plan->approvedBy->name ?? 'N/A' }}</p>
                                                            <p><strong>Approval Date:</strong> {{ $plan->approved_at->format('M d, Y H:i') }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            @if($plan->approval_description)
                                                                <p><strong>Approval Notes:</strong></p>
                                                                <p class="text-muted">{{ $plan->approval_description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    @if(auth()->user()->role_id === 5)
                                        <a href="{{ route('sector.employment-opportunity-plans.edit', $plan->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit mr-1"></i>Edit Plan
                                        </a>
                                    @endif
                                    @if(auth()->user()->role_id === 4 && $plan->status !== 'approved')
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal{{ $plan->id }}">
                                            <i class="fas fa-check-circle mr-1"></i>Approve
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Approve Modal --}}
                    @if(auth()->user()->role_id === 4 && $plan->status !== 'approved')
                    <div class="modal fade" id="approveModal{{ $plan->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title text-white">
                                        <i class="fas fa-check-circle mr-2"></i>Approve Employment Plan
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('employment-opportunity-plans.approve', $plan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <p>Are you sure you want to approve this employment opportunity plan?</p>
                                        <div class="form-group">
                                            <label>Approval Notes (Optional)</label>
                                            <textarea class="form-control" name="approval_description" rows="3" placeholder="Add any comments..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check-circle mr-1"></i>Approve Plan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
    </div>

    <style>
        .employment-stats {
            font-size: 0.9em;
        }
        
        .text-pink {
            color: #e64980;
        }
        
        .bg-pink {
            background-color: #e64980;
        }
        
        .empty-state {
            padding: 2rem 0;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }
        
        /* Subtle alternating background for year groups */
        tbody tr:nth-child(odd) {
            background-color: rgba(0, 0, 0, 0.01);
        }
        
        tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }
        
        /* Year column styling */
        td[rowspan] {
            background-color: #f8f9fa;
            border-right: 2px solid #dee2e6;
        }
        
        /* Employment stats styling */
        .employment-stats .font-weight-bold {
            min-width: 60px;
            display: inline-block;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .employment-stats {
                font-size: 0.85em;
            }
            
            .employment-stats .mr-3 {
                margin-right: 0.5rem !important;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 576px) {
            .employment-stats .d-flex {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .employment-stats .mb-1 {
                margin-bottom: 0.25rem !important;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</x-layout>