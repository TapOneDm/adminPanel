import axios, { AxiosRequestConfig } from 'axios';
import { AuthResponse } from '../models/response/AuthResponse';

export const BASE_URL = 'http://localhost:8080';

const $api = axios.create({
    withCredentials: true,
    baseURL: BASE_URL,
});

$api.interceptors.request.use((config: AxiosRequestConfig | any) => {
    config.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
    return config;
});

$api.interceptors.response.use(
    (config) => {
        return config;
    },
    async (error) => {
        const originalResponse = error.config;
        if (
            error.response.status == 401 &&
            error.config &&
            !error.config._isRetry
        ) {
            try {
                error.config._isRetry = true;
                const response = await axios.get<AuthResponse>(
                    `${BASE_URL}/user/refresh`,
                    { withCredentials: true },
                );
                localStorage.setItem('token', response.data.accessToken);
                return $api.request(originalResponse);
            } catch (e) {
                console.log('НЕ АВТОРИЗОВАН');
            }
        }
        
        throw error;
    },
);

export default $api;
