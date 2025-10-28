<x-layout>
    <h2 class="form-title">
        <i class="fas fa-edit"></i>
        Update Achievement for: {{ $plan->activity }} ({{ $plan->month }} - Week {{ $plan->week }})
    </h2>

    <form action="{{ route('weekly-plans.land.update', $plan->id) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label><i class="fas fa-bullseye"></i> Planned Value:</label>
            <div class="readonly-value">{{ $plan->planned_value }}</div>
        </div>

        <div class="form-group">
            <label for="achieved_value"><i class="fas fa-check-circle"></i> Achieved Value</label>
            <input 
                type="number" 
                name="achieved_value" 
                step="0.01" 
                class="form-control" 
                value="{{ old('achieved_value', $plan->achieved_value) }}" 
                required
            >
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
    </form>

    <style>
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #444;
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .readonly-value {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        @media (max-width: 600px) {
            .form-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>

    <!-- FontAwesome (only add if not included in your main layout) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-layout>
