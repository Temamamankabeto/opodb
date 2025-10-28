<x-layout>
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <form method="POST" action="{{ route('construction_maintenance.store') }}">
            @csrf
            
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Karoora Waggaa</h4>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Vertical Tabs Navigation -->
                        <div class="col-md-2">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-start" id="v-pills-overview-tab" data-bs-toggle="pill" 
                                        data-bs-target="#v-pills-overview" type="button" role="tab">
                                    <i class="fas fa-chart-pie me-2"></i> Midhaanii
                                </button>
                                <button class="nav-link text-start" id="v-pills-edit-tab" data-bs-toggle="pill" 
                                        data-bs-target="#v-pills-edit" type="button" role="tab">
                                    <i class="fas fa-edit me-2"></i> Beeyladaa
                                </button>
                                <button class="nav-link text-start" id="v-pills-settings-tab" data-bs-toggle="pill" 
                                        data-bs-target="#v-pills-settings" type="button" role="tab">
                                    <i class="fas fa-cog me-2"></i>Bu'uuraalee Misoomaa
                                </button>
                                <button class="nav-link text-start" id="v-pills-community-tab" data-bs-toggle="pill" 
                                        data-bs-target="#v-pills-community" type="button" role="tab">
                                    <i class="fas fa-users me-2"></i>Misooma Hawaasummaa
                                </button>
                            </div>
                        </div>
                        
                        <!-- Tab Content -->
                        <div class="col-md-10">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="v-pills-overview" role="tabpanel">
                              
 
                                <!-- <title>Production Report - {{ Auth::user()->kebele->name ?? 'Unknown Kebele' }}</title> -->


  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    input[type="text"] {
      margin-bottom: 15px;
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }
    thead {
  background-color: #004080; /* for example, dark blue */
  color: white;
}

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
    }
    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    th {
  background-color: #004080;
  color: white;
}

  </style>
</head>
<body>

<h2 class="text-center"> Karooraa Fi Raawwii Misooma Midhaanii  {{ Auth::user()->kebele->name ?? 'Unknown Kebele' }}</h2>


<input type="text" id="searchInput" placeholder="Search in table...">

`@php
    $groupedPlans = $plans->groupBy('plan_year');
@endphp

@php
    $groupedPlans = $plans->groupBy('plan_year');
@endphp

<table class="table table-bordered table-striped" id="productionTable">
<thead>
                    <tr>
                        <th>Kebele</th>
                        <th>Crop Type</th>
                        <th>Crop</th>
                        <th>Year</th>
                        <th class="rotate">Year Plan</th>
                        <th class="rotate">Month</th>
                        <th class="rotate">Week</th>
                        <th class="rotate">Planned</th>
                        <th class="rotate">Achieved</th>
                        <th class="rotate">%Achieved</th>
                        <th class="rotate">Upto This Week Plan</th>
                        <th class="rotate">Upto This Week Achievement</th>
                        <th class="rotate">%Upto This Week</th>
                        <th class="rotate">%Yearly Achievement</th>
                    </tr>
                </thead>
                <tbody>
@foreach($plans as $groupKey => $group)
    @php
        [$croptype_id, $year] = explode('-', $groupKey);
        $cropTypeName = $group->first()->cropType->name ?? 'Unknown';
    @endphp

    <tr style="background-color:#e8f6f3; font-weight: bold;">
        <td colspan="14">Crop Type: {{ $cropTypeName }} | Year: {{ $year }}</td>
    </tr>

    @foreach($group as $plan)
        <tr>
            <td>{{ $plan->kebele->name ?? '-' }}</td>
            <td>{{ $plan->cropType->name ?? '-' }}</td>
            <td>{{ $plan->crop->name ?? '-' }}</td>
            <td>{{ $plan->plan_year }}</td>
            <td>{{ $plan->land_area_plan_idaama}}</td>
            <td>{{ $plan->month }}</td>
            <td>Week {{ $plan->week }}</td>
            <td>{{ $plan->planned_value }}</td>
            <td>
                @if($plan->achieved_value)
                    <span class="badge badge-success">{{ $plan->achieved_value }}</span>
                @else
                    <span class="badge badge-warning">Pending</span>
                @endif
            </td>
            <td>
    <div class="progress-bar-wrapper">
        <div class="progress-bar-fill" style="width: {{ $plan->weekly_achievement_percentage }}%">
            {{ $plan->weekly_achievement_percentage }}%
        </div>
    </div>
</td>
            <td>{{ $plan->upto_this_week_plan }}</td>
            <td>{{ $plan->upto_this_week_achievement }}</td>
            <td>
    <div class="progress-bar-wrapper">
        <div class="progress-bar-fill" style="width: {{ $plan->upto_this_week_achieved_percentage }}%">
            {{ $plan->upto_this_week_achieved_percentage }}%
        </div>
    </div>
</td>
<td>
    <div class="progress-bar-wrapper">
        <div class="progress-bar-fill" style="width: {{ $plan->yearly_achieved_percentage }}%">
            {{ $plan->yearly_achieved_percentage }}%
        </div>
    </div>
</td>
            
        </tr>
    @endforeach
@endforeach
</tbody>
</table>



<script>
  const searchInput = document.getElementById("searchInput");
  const table = document.getElementById("productionTable");
  const rows = table.getElementsByTagName("tr");

  searchInput.addEventListener("keyup", function () {
    const filter = searchInput.value.toLowerCase();

    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName("td");
      let match = false;

      for (let j = 0; j < cells.length; j++) {
        const cellText = cells[j].textContent || cells[j].innerText;
        if (cellText.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }

      rows[i].style.display = match ? "" : "none";
    }
  });
</script>

</body>
</html>
<hr>
<hr>

 
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      font-family: Arial, sans-serif;
    }
    th, td {
      border: 1px solid #0074c2;
      padding: 8px;
    }
    th {
      background-color: #dfeefc;
      color: #004080;
    }
    .rotate {
      writing-mode: vertical-rl;
      transform: rotate(180deg);
      padding: 20px 5px;
    }
    .major {
      text-align: left;
      padding-left: 10px;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
 
 



                                </div>
                                
                                <!-- Edit Tab -->
                                <div class="tab-pane fade" id="v-pills-edit" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="card mb-4 border-primary">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title text-primary">
                                                        <i class="fas fa-paw me-2"></i>Beeyladaa Fi Bu'aa Beeyladaa
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <a href="{{url('livestock/supply/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-truck me-1"></i> Dhiyeessiwwan Misooma Beeyladaa
                                                    </a>
                                                    <a href="{{url('livestock-production/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-egg me-1"></i> Oomisha Beeyladaa
                                                    </a>
                                                    <a href="{{url('watershed-resources/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-tree me-1"></i> Hojii Qabeenya Uumamaa
                                                    </a>
                                                    <a href="{{url('forest-management/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-mountain me-1"></i> Eeggumsa Naannoo
                                                    </a>
                                                    <a href="{{url('cooperative-activities/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-handshake me-1"></i> Babal'ina Waldaalee
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Settings Tab -->
                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="card mb-4 border-primary">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title text-primary">
                                                        <i class="fas fa-hammer me-2"></i>Bu'uuralee Misoomaa
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <a href="{{url('construction-plans/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-building me-1"></i> Ijaarsa Dhaabbilee
                                                    </a>
                                                    <a href="{{url('upgrade/construction-plans/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-tools me-1"></i> Haaromsa Dhaabbilee
                                                    </a>
                                                    <a href="{{url('construction-maintenance')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-clipboard-check me-1"></i> Ijaarsa Faasilitiiwwan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Community Tab -->
                                <div class="tab-pane fade" id="v-pills-community" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="card mb-4 border-primary">
                                                <div class="card-header bg-light">
                                                    <h5 class="card-title text-primary">
                                                        <i class="fas fa-graduation-cap me-2"></i>Misooma Hawaasummaa
                                                    </h5>
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>Barnoota
                                                    </div> 
                                                </div>
                                                <div class="card-body">
                                                    <a href="{{url('socialdevelopment-plans/create')}}" class="btn btn-outline-primary btn-sm mb-2">
                                                        <i class="fas fa-people-carry me-1"></i> Hirmaannaa fi Ittifufiinsa
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Vertical Tabs Styling */
        .nav-pills .nav-link {
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            padding: 0.75rem 1rem;
            color: #495057;
            background-color: #f8f9fa;
            text-align: left;
            transition: all 0.3s;
        }
        
        .nav-pills .nav-link:hover {
            background-color: #e9ecef;
        }
        
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        
        /* Card Styling */
        .card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .nav-pills {
                flex-direction: row !important;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 10px;
            }
            
            .nav-pills .nav-link {
                white-space: nowrap;
                margin-right: 0.5rem;
                margin-bottom: 0;
            }
            
            .col-md-3, .col-md-9 {
                width: 100%;
                padding: 0;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
</x-layout>