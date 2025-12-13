class QuarterMonthWeekPlanner {
    constructor(container) {
        this.container = container;
        this.year = parseInt(container.dataset.year);
        this.totalArea = parseFloat(container.dataset.totalArea);
        this.existing = JSON.parse(container.dataset.existing || '{}');
        this.output = container.querySelector('.planning-output');

        this.selectedQuarters = new Set();
        this.selectedMonths = new Set();
        this.weekly = [];

        this.init();
    }

    init() {
        this.loadExisting();
        this.renderQuarters();
        this.syncOutput();
    }

    loadExisting() {
        if (this.existing.weekly_distribution) {
            this.weekly = this.existing.weekly_distribution;
            this.weekly.forEach(w => {
                this.selectedQuarters.add(parseInt(w.quarter.replace('Q', '')));
            });
        }
    }

    renderQuarters() {
        const wrapper = this.container.querySelector('.quarters-wrapper');
        wrapper.innerHTML = '';

        [1,2,3,4].forEach(q => {
            wrapper.innerHTML += `
                <div class="card quarter-card mb-3">
                    <div class="card-header">
                        <input type="checkbox" class="quarter-toggle" data-quarter="${q}">
                        <strong>Q${q} ({{ __('Year') }} ${this.year})</strong>
                    </div>
                    <div class="card-body months" data-quarter="${q}" style="display:none"></div>
                </div>
            `;
        });

        wrapper.querySelectorAll('.quarter-toggle').forEach(cb => {
            cb.addEventListener('change', e => this.toggleQuarter(e));
        });
    }

    toggleQuarter(e) {
        const q = parseInt(e.target.dataset.quarter);
        const months = this.container.querySelector(`.months[data-quarter="${q}"]`);

        if (e.target.checked) {
            this.selectedQuarters.add(q);
            months.style.display = 'block';
            this.renderMonths(q, months);
        } else {
            this.selectedQuarters.delete(q);
            months.style.display = 'none';
        }

        this.updateBadges();
        this.syncOutput();
    }

    renderMonths(q, container) {
        container.innerHTML = '';
        const months = this.getMonthsForQuarter(q);

        months.forEach(m => {
            container.innerHTML += `
                <div class="month-card mb-2">
                    <strong>${m.name}</strong>
                    <div class="weeks" data-month="${m.id}"></div>
                </div>
            `;
            this.renderWeeks(m, container.querySelector(`[data-month="${m.id}"]`));
        });
    }

    renderWeeks(month, container) {
        for (let w = 1; w <= Math.ceil(month.days / 7); w++) {
            container.innerHTML += `
                <div class="d-flex mb-1">
                    <span class="mr-2">W${w}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm week-input"
                        data-month="${month.id}" data-week="${w}">
                </div>
            `;
        }

        container.querySelectorAll('.week-input').forEach(input => {
            input.addEventListener('input', () => this.collectWeeks());
        });
    }

    collectWeeks() {
        this.weekly = [];

        this.container.querySelectorAll('.week-input').forEach(i => {
            const val = parseFloat(i.value || 0);
            if (val > 0) {
                this.weekly.push({
                    year: this.year,
                    quarter: 'Q' + this.findQuarter(i.dataset.month),
                    month: i.dataset.month,
                    week: 'Week ' + i.dataset.week,
                    planned_area: val
                });
            }
        });

        this.updateBadges();
        this.syncOutput();
    }

    syncOutput() {
        this.output.value = JSON.stringify({
            year: this.year,
            weekly_distribution: this.weekly
        });
    }

    updateBadges() {
        this.container.querySelector('[data-quarters-count]').innerText = this.selectedQuarters.size;
        this.container.querySelector('[data-weeks-count]').innerText = this.weekly.length;
    }

    getMonthsForQuarter(q) {
        const map = {
            1: [{id:1,name:'Meskerem',days:30},{id:2,name:'Tikimt',days:30},{id:3,name:'Hidar',days:30}],
            2: [{id:4,name:'Tahsas',days:30},{id:5,name:'Tir',days:30},{id:6,name:'Yekatit',days:30}],
            3: [{id:7,name:'Megabit',days:30},{id:8,name:'Miyazia',days:30},{id:9,name:'Ginbot',days:30}],
            4: [{id:10,name:'Sene',days:30},{id:11,name:'Hamle',days:30},{id:12,name:'Nehase',days:30},{id:13,name:'Pagumen',days:5}],
        };
        return map[q];
    }

    findQuarter(monthId) {
        return Object.keys(this.getMonthsForQuarter).find(q =>
            this.getMonthsForQuarter(q).some(m => m.id == monthId)
        );
    }
}

// Auto-init
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.planning-container').forEach(el => {
        new QuarterMonthWeekPlanner(el);
    });
});
