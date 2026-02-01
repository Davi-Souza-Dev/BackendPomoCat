import api from '@/api';
import { CardType } from '@/types/types';
import { defineStore } from 'pinia';

interface CardState {
    cards: CardType[];
    card: CardType;
    file: File | null;
}
export const useCardStore = defineStore('CardStore', {
    state: (): CardState => {
        return {
            cards: [],
            card: {
                id: 0,
                image_url: '',
                rarity: '',
                title: '',
            },
            file: null,
        };
    },
    actions: {
        async setCard() {
            try {
                const verificacao = await this.verificacao();
                if (verificacao !== true) return verificacao;

                const formData = new FormData();
                formData.append('card', JSON.stringify(this.card));
                if (this.file != null) {
                    formData.append('imagem', this.file);
                }

                console.log(this.card);
                const response = await api.post('/card/set', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                if (response.data.success) {
                    this.cards.push(response.data.card);
                }
                return response.data;
            } catch ($error) {
                console.log($error);
            }
        },
        async delete(cardId: number) {
            try {
                console.log(this.card);
                const response = await api.post('/card/delete', {
                    id: cardId,
                });
                if (response.data.success) {
                  console.log('deletado')
                    this.cards = this.cards.filter(
                        (card: CardType) => card.id != cardId,
                    );
                }
                return response.data;
            } catch ($error) {
                console.log($error);
            }
        },
        clear() {
            this.card = {
                id: 0,
                image_url: '',
                rarity: '',
                title: '',
            };
            this.file = null;
        },
        async verificacao() {
            const error = (mensagem: string) => ({
                error: {
                    titulo: mensagem,
                    code: 422,
                },
            });

            if (!this.card.title || this.card.title.trim() === '') {
                return error('O titulo é obrigatório.');
            }

            if (!this.card.rarity || this.card.rarity.trim() === '') {
                return error('Escolha a raridade.');
            }

            if (this.file == null && this.card.rarity.trim() === '') {
                return error('Precisa de uma imagem!');
            }
            return true;
        },
    },
});
