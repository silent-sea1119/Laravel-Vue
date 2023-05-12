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
        }
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
    },
    actions: {
        markUser(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.user.mark(data.id, data).then((response: any) => {
                //context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        postUser(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.user.post(data).then((response: any) => {
                context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        updateUser(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.user.put(data.id, data.form).then((response: any) => {
                context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getUser(context: any, id: number) {
            context.commit('setError', null);
            return api.admin.user.get(id).then((response: any) => {
                context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        getUsers(context: any, page: number) {
            context.commit('setError', null);
            return api.admin.user.index(page).then((response: any) => {
                context.commit('setUsers', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        deleteUser(context: any, id: number) {
            context.commit('setError', null);
            return api.admin.user.delete(id).then((response: any) => {
                context.dispatch('getUsers', 1);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        },
        addFunds(context: any, data: any) {
            context.commit('setError', null);
            return api.admin.user.addFunds(data).then((response: any) => {
                context.commit('setUser', response);
            }).catch((error: any) => {
                context.commit('setError', error);
            });
        }
    },
}
