import { createStore } from 'vuex'
import { api } from '../../services/api'
import { getError } from "@/utils"

export default {
    namespaced: true,
    state: {
        card: {},
        id: null,
        error: null,
        cards: [],
    },
    mutations: {
        setCards(state: any, cards: any) {
            state.cards = cards;
        },
        setCard(state: any, card: any) {
            state.card = card;
        },
        setError(state: any, error: any) {
            state.error = getError(error);
        },
        setId(state: any, id: any) {
            state.id = id;
        },
    },
    getters: {
        card(state: any) {
            return state.card;
        },
        error(state: any) {
            return state.error;
        },
        id(state: any) {
            return state.id;
        },
        cards(state: any) {
            return state.cards;
        },
    },
    actions: {
        postCardD(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.defaultCard.post(data).then((response: any) => {
                context.commit('setCard', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        postCard(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.card.post(data).then((response: any) => {
                context.commit('setCard', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        updateCard(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.card.put(data.id, data.form).then((response: any) => {
                context.commit('setCard', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getCard(context: any, id: number) {
            context.commit('setError', null);
            return api.admin.card.get(id).then((response: any) => {
                context.commit('setCard', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getCards(context: any, page: number) {
            context.commit('setError', null);
            return api.admin.card.index(page).then((response: any) => {
                context.commit('setCards', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getCardsD(context: any, page: number) {
            context.commit('setError', null);
            return api.admin.defaultCard.index(page).then((response: any) => {
                context.commit('setCards', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        deleteCard(context: any, id: number) {
            context.commit('setError', null);
            return api.admin.card.delete(id).then((response: any) => {
                context.dispatch('setCard', 1);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        importCards(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.card.import(data).then((response: any) => {
                context.commit('setCards', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        importCardsD(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.defaultCard.import(data).then((response: any) => {
                context.commit('setCards', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
    },
}
