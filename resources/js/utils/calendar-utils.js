export class CalendarUtils {
    static getEthiopianMonths() {
        return [
            { id: 1, name: "Meskerem", days: 30 },
            { id: 2, name: "Tikimt", days: 30 },
            // ... all months
        ];
    }

    static getEthiopianQuarters() {
        return {
            1: [1, 2, 3],
            2: [4, 5, 6],
            3: [7, 8, 9],
            4: [10, 11, 12, 13]
        };
    }

    static getWeeksForMonth(monthId) {
        const month = this.getEthiopianMonths().find(m => m.id === monthId);
        if (!month) return [];

        const weeks = [];
        let start = 1;
        while (start <= month.days) {
            let end = Math.min(start + 6, month.days);
            weeks.push({
                weekNum: weeks.length + 1,
                start: start,
                end: end
            });
            start += 7;
        }
        return weeks;
    }

    static calculateStatus(value) {
        return value > 0 ? __('Planned') : __('Not Planned');
    }

    static calculatePercentage(distributed, total) {
        return total > 0 ? (distributed / total * 100).toFixed(1) : 0;
    }
}