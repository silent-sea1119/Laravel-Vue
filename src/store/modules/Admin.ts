import { createStore } from 'vuex'
import { api } from '../../services/api'
import { getError } from "@/utils"

export default {
    namespaced: true,
    state: {
        items: [],
        transactions: [],
        error: null,
    },
    mutations: {
        setItems(state: any, items: any) {
            state.items = items
        },
        setTransactions(state: any, transactions: any) {
            state.transactions = transactions
        },
        setError(state: any, error: any) {
            state.error = getError(error)
        },
    },
    getters: {
        items(state: any) {
            return state.items;
        },
        transactions(state: any) {
            return state.transactions;
        },
        error(state: any) {
            return state.error;
        },
    },
    actions: {
        getDashboard(context: any, id: number) {
            context.commit('setError', null);
            return api.admin.dashboard().then((response: any) => {
                context.commit('setItems', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getTransactions(context: any, query: any) {
            context.commit('setError', null);
            return api.admin.transactions(query).then((response: any) => {
                context.commit('setTransactions', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        }
    },
}
