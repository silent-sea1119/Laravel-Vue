import axios from 'axios';
import store from '../store';
import Swal from 'sweetalert2';

const setApiAuthenticationHeader = (accessToken = null) => {
    if (accessToken) {
        axiosInstance.defaults.headers.common.authorization = `Bearer ${accessToken}`;
    } else {
        delete axiosInstance.defaults.headers.common.authorization;
    }
};

const API_SERVER = "http://127.0.0.1:8000/api/v1"
// const API_SERVER = "https://upwork33534882.alphasofthub.com/api/v1"


// basic configurations
axios.defaults.baseURL = API_SERVER;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

// create instance 
const axiosInstance = axios.create();

// Axios wrapper to handle error
const axiosWrapper = apiCall => apiCall.then(res => res.data).catch(err => Promise.reject(err));

// axios interceptors to handle token expiration
axiosInstance.interceptors.response.use(
    response => {
        return response;
    },
    error => {

        if (error.config
            && error.response
            && error.response.status === 402 || error.response.status === 500) {
                Swal.fire({
                    title: 'Oops...',
                    text: error.response.status === 402 ? 
                        "You do not have premission to perform this action" : "Something wrong in server",
                    type: 'error',
                    confirmButtonText: 'OK'
                });
                return Promise.reject(error);
            }
        if (error.config
            && error.response
            && error.response.status === 401) {
            const oldRefreshToken = store.getters['Auth/refreshToken'];
            if (oldRefreshToken) {
                return axios.post('/auth/refresh', { refreshToken: oldRefreshToken })
                .then(res => {
                    const {accessToken, refreshToken} = res.data;
                    if (accessToken && refreshToken) {
                        error.config.headers.authorization = `Bearer ${accessToken}`;
                        setApiAuthenticationHeader(accessToken);
                        localStorage.setItem('accessToken', accessToken);
                        localStorage.setItem('refreshToken', refreshToken);

                        // dispatch event that login successful and update the tokens.
                        // TODO.
                        return axiosInstance.request(error.config);
                    } else {
                        return Promise.reject("Can not get the access token");
                    }
                })
            }
        }
        return Promise.reject(error);
    }
);

// API calls.

let api = {
    auth: {
        register: (data) => axiosWrapper(axiosInstance.post('/auth/register', data)),
        login: (credentials) => axiosWrapper(axiosInstance.post('/auth/login', credentials)),
        logout: () => axiosWrapper(axiosInstance.post('/auth/logout')),
        me: () => axiosWrapper(axiosInstance.get('/auth/me')),
        reset: (email) => axiosWrapper(axiosInstance.post('/auth/password', {email: email})),
    },
    user: {
        // change password.
        changePassword: (data) => axiosWrapper(axiosInstance.post('/user/change-password', data)),
        // update profile.
        updateProfile: (data) => axiosWrapper(axiosInstance.post('/user/update-profile', data)),
        // get total cards, funds.
        dashboard: () => axiosWrapper(axiosInstance.get('/user/total-cards-funds')),
        // get all cards.
        cards: (query) => axiosWrapper(axiosInstance.get(`/user/cards${query}`)),
        // get all transactions.
        transactions: (query) => axiosWrapper(axiosInstance.get(`/user/transactions${query}`)),
        addFunds: (data) => axiosWrapper(axiosInstance.post('/user/add-funds', data)),
        requestCard: (data) => axiosWrapper(axiosInstance.post('/user/request-card', data)),
        update: (data) => axiosWrapper(axiosInstance.post('/user/update-user', data)),
        cardRequests: (query) => axiosWrapper(axiosInstance.get(`/user/card-requests${query}`)),
    },

    admin: {
        dashboard: () => axiosWrapper(axiosInstance.get('/admin/dashboard')),

        user: {
            index: (query) => axiosWrapper(axiosInstance.get(`/admin/users?${query}`)),
            delete: (id) => axiosWrapper(axiosInstance.delete(`/admin/users/${id}`)),
            get: (id) => axiosWrapper(axiosInstance.get(`/admin/users/${id}`)),
            put: (id, data) => axiosWrapper(axiosInstance.post(`/admin/users/put/${id}`, data)),
            post: (data) => axiosWrapper(axiosInstance.post('/admin/users', data)),
            mark: (id, data) => axiosWrapper(axiosInstance.post(`/admin/users/mark/${id}`, data)),
            addFunds: (data) => axiosWrapper(axiosInstance.post(`/admin/users/add-funds/${data.id}`, data)),
        },
        transactions: (query) => axiosWrapper(axiosInstance.get(`/admin/transactions?${query}`)),

        card: {
            index: (query) => axiosWrapper(axiosInstance.get(`/admin/cards?${query}`)),
            delete: (id) => axiosWrapper(axiosInstance.delete(`/admin/cards/${id}`)),
            get: (id) => axiosWrapper(axiosInstance.get(`/admin/cards/${id}`)),
            put: (id, data) => axiosWrapper(axiosInstance.post(`/admin/cards/put/${id}`, data)),
            post: (data) => axiosWrapper(axiosInstance.post('/admin/cards', data)),
            import: (data) => axiosWrapper(axiosInstance.post('/admin/cards/import', data)),
        },
        defaultCard: {
            index: (query) => axiosWrapper(axiosInstance.get(`/admin/default-cards?${query}`)),
            post: (data) => axiosWrapper(axiosInstance.post('/admin/default-cards', data)),
            import: (data) => axiosWrapper(axiosInstance.post('/admin/default-cards/import', data)),
        },
        CardRequest: {
            index: (query) => axiosWrapper(axiosInstance.get(`/admin/card-requests?${query}`)),
            get: (id) => axiosWrapper(axiosInstance.get(`/admin/card-requests/${id}`)),
            delete: (id) => axiosWrapper(axiosInstance.delete(`/admin/card-requests/${id}`)),
            updateStatus: (id, data) => axiosWrapper(axiosInstance.post(`/admin/card-requests/update-status/${id}`, data)),
            deny: (data) => axiosWrapper(axiosInstance.post(`/admin/card-requests/deny/${data.id}`, data)),
        },
    }
};

export { api, axiosInstance, setApiAuthenticationHeader };

export default api;
