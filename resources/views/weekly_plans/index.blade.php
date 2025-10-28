 <x-layout>
    <div class="weekly-plans-container">
        <div class="header-section">
            <h2 class="page-title">🌱 Weekly Plans Overview</h2>
            <a href="{{ route('weekly-plans.create') }}" class="add-new-btn">
                <i class="fas fa-plus-circle"></i> Add New Plan
            </a>
        </div>

        <div class="table-wrapper">
            <table class="plans-table">
                <thead>
                    <tr>
                        <th>Crop Type</th>
                        <th>Crop</th>
                        <th>Year</th>
                        <th>Year Plan</th>
                        <th>Month</th>
                        <th>Week</th>
                        <th>Planned</th>
                        <th>Achieved</th>
                        <th>%Achieved</th>
                        <th>Upto This Week Plan</th>
                        <th>Upto This Week Achievement</th>
                        <th>%Upto This Week</th>
                        <th>%Yearly Achievement</th>
                        <th>Actions</th>
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
            <td>{{ $plan->cropType->name ?? '-' }}</td>
            <td>{{ $plan->crop->name ?? '-' }}</td>
            <td>{{ $plan->plan_year }}</td>
            <td>{{ $plan->production_plan_idaama }}</td>
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
            <td>
                @unless($plan->achieved_value)
                    <a href="{{ route('weekly-plans.edit', $plan->id) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Update
                    </a>
                @endunless
            </td>
        </tr>
    @endforeach
@endforeach
</tbody>

            </table>
        </div>
    </div>

    <style>
        body {
            background: #f5f8fa;
        }

        .weekly-plans-container {
            width: 100%;
            padding: 40px 5%;
            box-sizing: border-box;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #34495e;
        }

        .add-new-btn {
            background: #1abc9c;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .add-new-btn:hover {
            background: #16a085;
            transform: scale(1.05);
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .plans-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1200px;
        }

        .plans-table thead {
            background: #2c3e50;
            color: white;
        }

        .plans-table th, .plans-table td {
            padding: 15px;
            text-align: center;
        }

        .plans-table tbody tr:hover {
            background: #ecf0f1;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
        }

        .badge-success {
            background: #2ecc71;
            color: white;
        }

        .badge-warning {
            background: #f39c12;
            color: white;
        }

        .btn-edit {
            background: #f1c40f;
            padding: 6px 14px;
            border-radius: 6px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit:hover {
            background: #f39c12;
            color: white;
        }

        @media screen and (max-width: 768px) {
            .plans-table th, .plans-table td {
                padding: 10px;
                font-size: 13px;
            }
        }

        .progress-bar-wrapper {
    background-color:rgb(116, 144, 245);
    border-radius: 20px;
    height: 20px;
    width: 100%;
    position: relative;
    overflow: hidden;
}

.progress-bar-fill {
    background-color: #3498db;
    height: 100%;
    border-radius: 20px;
    text-align: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
    line-height: 20px; /* matches height for vertical centering */
    transition: width 0.4s ease-in-out;
}

@media print {
    body * {
        visibility: hidden;
    }

    .table-wrapper, .table-wrapper * {
        visibility: visible;
    }

    .table-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}

    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-layout>
