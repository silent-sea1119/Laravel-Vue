import { createStore } from 'vuex'
import { api, setApiAuthenticationHeader } from '../../services/api'
import { getError } from "@/utils"

export default {
    namespaced: true,
    state: {
        credentials: {
            email: '',
            password: '',
        },
        login: localStorage.getItem('accessToken') ? true : false,
        accessToken: localStorage.getItem('accessToken') || '',
        refreshToken: localStorage.getItem('refreshToken') || '',
        user: JSON.parse(localStorage.getItem('user') || "{}") || {},
        meta: JSON.parse(localStorage.getItem('meta') || "{}") || {},
        msg: '',
        permissions: JSON.parse(localStorage.getItem('permissions') || "{}") || [],
        error: null,
    },
    mutations: {
        setCredentials(state: any, credentials: any) {
            state.credentials = credentials;
        },
        setAccessToken(state: any, accessToken: string) {
            state.accessToken = accessToken;
        },
        setRefreshToken(state: any, refreshToken: string) {
            state.refreshToken = refreshToken;
        },
        setLogin(state: any, login: boolean) {
            state.login = login;
        },
        setUser(state: any, user: any) {
            state.user = JSON.parse(user);
            localStorage.user = user;
        },
        setMeta(state: any, meta: any) {
            state.meta = JSON.parse(meta);
            localStorage.meta = meta;
        },
        setPermissions(state: any, permissions: any) {
            state.permissions = JSON.parse(permissions);
            localStorage.permissions = permissions;
        },
        setMsg(state: any, msg: string) {
            state.msg = msg;
        },
        setHeadertoken(state: any) {
            setApiAuthenticationHeader(state.accessToken);
        },
        setError(state: any, error: any) {
            state.error = null; //getError(error)
        }
    },
    getters: {
        credentials(state: any) {
            return state.credentials;
        },
        accessToken(state: any) {
            return state.accessToken;
        },
        refreshToken(state: any) {
            return state.refreshToken;
        },
        user(state: any) {
            return state.user;
        },
        meta(state: any) {
            return state.meta;
        },
        msg(state: any) {
            return state.msg;
        },
        login(state: any) {
            return state.login;
        },
        permissions(state: any) {
            return state.permissions;
        },
        error(state: any) {
            return state.error;
        },
    },
    actions: {
        setToken(context: any) {
            context.commit('setHeadertoken');
        },
        getMe(context: any) {
            return api.auth.me().then((response: any) => {
                context.commit('setUser', JSON.stringify(response.user));
                context.commit('setMeta', JSON.stringify(response.meta));
                context.commit('setPermissions', JSON.stringify(response.permissions));
            }
            ).catch((error: any) => {
                context.commit('setMsg', error.response.data.message);
            });
        },
        postRegister(context: any, data: any) {
            context.commit('setError', null);
            return api.auth.register(data).then((response: any) => {
                // do nothing.
            }).catch((error: any) => {
                context.commit('setError', error);
            }
            );
        },
        login(context: any, credentials: any) {
            context.commit('setCredentials', credentials);
            return api.auth.login(credentials).then((response: any) => {
                context.commit('setMsg', null);
                context.commit('setAccessToken', response.accessToken);
                setApiAuthenticationHeader(response.accessToken);
                context.commit('setRefreshToken', response.refreshToken);
                context.commit('setUser', JSON.stringify(response.user));
                localStorage.accessToken = response.accessToken;
                localStorage.refreshToken = response.refreshToken;
                context.commit('setLogin', true);
            }
            ).catch((error: any) => {
                if (error.response === undefined) {
                    context.commit('setMsg', error.message);
                }
                context.commit('setMsg', error.response.data.msg ?? error.response.data.message);
                context.commit('setLogin', false);
            }
            );
        },
        logout(context: any) {
            return api.auth.logout().then((response: any) => {
                context.commit('setAccessToken', null);
                context.commit('setRefreshToken', null);
                context.commit('setUser', {});
                context.commit('setMeta', {});
                context.commit('setPermissions', []);
                context.commit('setLogin', false);
            }
            ).catch((error: any) => {
                if (error.response != null) {
                    context.commit('setMsg', error.response.data.msg ?? error.response.data.message);
                }
                context.commit('setLogin', false);
            })
        },
        resetPassword(context: any, email: any) {
            return api.auth.reset(email).then((response: any) => {
                context.commit('setMsg', response.msg);
            }).catch((error: any) => {
                context.commit('setMsg', error.response.data.msg ?? error.response.data.message);
            });
        },
    },
}
