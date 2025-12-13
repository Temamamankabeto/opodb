export class CalendarManager {
    constructor(config) {
        this.config = {
            ecMonths: config.ecMonths || [],
            ecQuarters: config.ecQuarters || {},
            year: config.year,
            unit: config.unit || 'kg',
            total: config.total || 0,
            formId: config.formId,
            planningContainer: config.planningContainer,
            onQuarterChange: config.onQuarterChange,
            onMonthChange: config.onMonthChange,
            onInputChange: config.onInputChange,
            translations: config.translations || {}
        };

        this.selectedQuarters = new Set();
        this.selectedMonths = new Set();
        this.distributions = [];
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.renderPlanningContainer();
    }

    bindEvents() {
        $(document).on('change', '.quarter-checkbox', (e) => {
            const quarterNum = parseInt($(e.target).attr('id').replace('quarter', ''));
            const isChecked = $(e.target).is(':checked');
            this.handleQuarterChange(quarterNum, isChecked);
        });

        $(document).on('change', '.month-checkbox', (e) => {
            const monthNum = parseInt($(e.target).data('month'));
            const isChecked = $(e.target).is(':checked');
            this.handleMonthChange(monthNum, isChecked);
        });

        $(document).on('input', '.week-input', (e) => {
            this.handleInputChange(e.target);
        });
    }

    handleQuarterChange(quarterNum, isChecked) {
        if (isChecked) {
            this.selectedQuarters.add(quarterNum);
            this.expandQuarter(quarterNum);
        } else {
            this.selectedQuarters.delete(quarterNum);
            this.clearQuarterMonths(quarterNum);
        }

        if (this.config.onQuarterChange) {
            this.config.onQuarterChange(quarterNum, isChecked, this);
        }
    }

    handleMonthChange(monthNum, isChecked) {
        if (isChecked) {
            this.selectedMonths.add(monthNum);
            this.expandMonthDistribution(monthNum);
        } else {
            this.selectedMonths.delete(monthNum);
        }

        if (this.config.onMonthChange) {
            this.config.onMonthChange(monthNum, isChecked, this);
        }
    }

    handleInputChange(input) {
        const monthNum = parseInt($(input).data('month'));
        const weekNumber = parseInt($(input).data('week'));
        const value = parseFloat($(input).val()) || 0;

        this.updateDistribution(monthNum, weekNumber, value);

        if (this.config.onInputChange) {
            this.config.onInputChange(monthNum, weekNumber, value, this);
        }
    }

    renderPlanningContainer() {
        $(this.config.planningContainer).empty();

        Object.keys(this.config.ecQuarters).forEach(q => {
            const quarterNum = parseInt(q);
            const quarterData = this.calculateQuarterData(quarterNum);
            const isSelected = this.selectedQuarters.has(quarterNum);

            const quarterHtml = `
                <x-calendar.quarter-card
                    :quarterNum="$quarterNum"
                    :year="$this.config.year"
                    :isSelected="$isSelected"
                    :distributed="$quarterData.distributed"
                    :unit="$this.config.unit"
                    :percentage="$quarterData.percentage"
                />
            `;

            $(this.config.planningContainer).append(quarterHtml);
        });
    }

    calculateQuarterData(quarterNum) {
        const quarterName = `Q${quarterNum}`;
        const quarterDistributions = this.distributions.filter(dist => dist.quarter === quarterName);
        const distributed = quarterDistributions.reduce((total, dist) => total + dist.quantity, 0);
        const percentage = this.config.total > 0 ? (distributed / this.config.total * 100) : 0;

        return { distributed, percentage };
    }

    calculateMonthData(monthNum) {
        const monthName = this.config.ecMonths[monthNum - 1].name;
        const monthDistributions = this.distributions.filter(dist => dist.month === monthName);
        const distributed = monthDistributions.reduce((total, dist) => total + dist.quantity, 0);
        const percentage = this.config.total > 0 ? (distributed / this.config.total * 100) : 0;

        return { distributed, percentage };
    }

    updateDistribution(monthNum, weekNumber, quantity) {
        const month = this.config.ecMonths[monthNum - 1];
        const quarter = this.findQuarter(monthNum);

        let distribution = this.distributions.find(dist =>
            dist.month === month.name &&
            dist.week === `Week ${weekNumber}`
        );

        if (distribution) {
            distribution.quantity = quantity;
        } else {
            distribution = {
                year: this.config.year,
                quarter: `Q${quarter}`,
                month: month.name,
                week: `Week ${weekNumber}`,
                quantity: quantity,
                unit: this.config.unit
            };
            this.distributions.push(distribution);
        }

        // Remove if quantity is 0
        if (quantity === 0) {
            this.distributions = this.distributions.filter(dist => dist !== distribution);
        }
    }

    findQuarter(monthNum) {
        for (const [q, months] of Object.entries(this.config.ecQuarters)) {
            if (months.includes(monthNum)) {
                return q;
            }
        }
        return null;
    }

    expandQuarter(quarterNum) {
        // Implementation for expanding quarter
    }

    expandMonthDistribution(monthNum) {
        // Implementation for expanding month
    }

    clearQuarterMonths(quarterNum) {
        // Implementation for clearing quarter months
    }

    getDistributions() {
        return this.distributions;
    }

    setDistributions(distributions) {
        this.distributions = distributions;
    }
}