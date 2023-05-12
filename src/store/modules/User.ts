import { createStore } from 'vuex'
import { api } from '../../services/api'
import { getError } from "@/utils"

export default {
    namespaced: true,
    state: {
        users: [],
        user: {},
        id: null,
        error: null,
        cards: [],
        transactions: [],
        items: [],
        redirectedURL: null,
        requests: [],
    },
    mutations: {
        setUsers(state: any, users: any) {
            state.users = users;
        },
        setUser(state: any, user: any) {
            state.user = user;
        },
        setError(state: any, error: any) {
            state.error = getError(error);
        },
        setId(state: any, id: any) {
            state.id = id;
        },
        setCards(state: any, cards: any) {
            state.cards = cards;
        },
        setTransactions(state: any, transactions: any) {
            state.transactions = transactions;
        },
        setItems(state: any, items: any) {
            state.items = items
        },
        setRedirectedURL(state: any, url: any) {
            state.redirectedURL = url;
        }, 
        setRequests(state: any, requests: any) {
            state.requests = requests;
        },  
    },
    getters: {
        users(state: any) {
            return state.users;
        },
        user(state: any) {
            return state.user;
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
        transactions(state: any) {
            return state.transactions;
        },
        items(state: any) {
            return state.items;
        },
        redirectedURL(state: any) {
            return state.redirectedURL;
        },
        requests(state: any) {
            return state.requests;
        }
    },
    actions: {
        getCardRequests(context: any, query: string) {
            context.commit('setError', null);
            return api.user.cardRequests(query).then((response: any) => {
                context.commit('setRequests', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getCards(context: any, query: string) {
            context.commit('setError', null);
            return api.user.cards(query).then((response: any) => {
                context.commit('setCards', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getTransactions(context: any, query: string) {
            context.commit('setError', null);
            return api.user.transactions(query).then((response: any) => {
                context.commit('setTransactions', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        updatePassword(context: any, data: any) {
            context.commit('setError', null);
            return api.user.changePassword(data).then((response: any) => {
                //context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        updateProfile(context: any, data: any) {
            context.commit('setError', null);
            return api.user.updateProfile(data).then((response: any) => {
                context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getDashboard(context: any, id: number) {
            context.commit('setError', null);
            return api.user.dashboard().then((response: any) => {
                context.commit('setItems', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        addFundToUser(context: any, data: any) {
            context.commit('setError', null);
            return api.user.addFunds(data).then((response: any) => {
                context.commit('setRedirectedURL', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        requestCard(context: any, data: any) {
            context.commit('setError', null);
            return api.user.requestCard(data).then((response: any) => {
                //context.commit('', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        updateUser(context: any, data: any) {
            context.commit('setError', null);
            return api.user.update(data).then((response: any) => {
                context.commit('Auth/setUser', JSON.stringify(response), {root:true});
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
    },
}
