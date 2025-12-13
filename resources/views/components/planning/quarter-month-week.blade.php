@props([
    'year',
    'totalArea',
    'value' => [],
    'inputName' => 'plandivision_details',
])

<div
    class="planning-container"
    data-year="{{ $year }}"
    data-total-area="{{ $totalArea }}"
    data-input-name="{{ $inputName }}"
    data-existing='@json($value)'
>
    <!-- Stepper -->
    <div class="stepper mb-4">
        <div class="step active" data-step="1">
            <div class="step-icon"><i class="fas fa-calendar-quarter"></i></div>
            <div class="step-label">{{ __('Select Quarters') }}</div>
            <span class="step-badge" data-quarters-count>0</span>
        </div>
        <div class="step" data-step="2">
            <div class="step-icon"><i class="fas fa-calendar-week"></i></div>
            <div class="step-label">{{ __('Weekly Distribution') }}</div>
            <span class="step-badge" data-weeks-count>0</span>
        </div>
    </div>

    <!-- Dynamic content -->
    <div class="quarters-wrapper"></div>

    <!-- Hidden JSON output -->
    <input type="hidden" name="{{ $inputName }}" class="planning-output">
</div>
