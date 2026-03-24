import api from '@/api';
import { defineStore } from 'pinia';

interface DistState {
    offset: number;
    week: {
        data: number[];
        total: number;
        title: string;
    };
}

export const useGraphDist = defineStore('useGraphDist', {
    state: (): DistState => {
        return {
            offset: 0,
            week: {
                data: [],
                total: 0,
                title: '',
            },
        };
    },
    actions: {
        async prevWeek() {
            this.offset++;
            const response = await api.post(`/analytic/dist/prevweek`, {
                offsetweek: this.offset,
            });

            this.week = response.data;
        },
        async nextweek() {
            this.offset--;
            const response = await api.post(`/analytic/dist/nextweek`, {
                offsetweek: this.offset,
            });

            this.week = response.data;
        },
    },
});
