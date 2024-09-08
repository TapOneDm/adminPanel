import { IUser } from '../models/IUser';
import { makeAutoObservable } from 'mobx';
import AuthService from '../services/AuthService';
import axios from 'axios';
import { AuthResponse } from '../models/response/AuthResponse';
import { BASE_URL } from '../http';
import CommonDataStore from './commonDataStore';

export default class UserStore {
    public _user = {} as IUser;
    public _isAuth = false;
    public _isLoading: boolean = false;

    constructor() {
        makeAutoObservable(this);
    }

    setAuth(bool: boolean) {
        this._isAuth = bool;
    }

    setUser(user: IUser) {
        this._user = user;
    }

    setLoading(bool: boolean) {
        this._isLoading = bool;
    }

    async login(email: string, password: string) {
        try {
            const response: any = await AuthService.login(email, password);
            localStorage.setItem('token', response.data.accessToken);
            this.setAuth(true);
            this.setUser(response.data.user);
        } catch (e) {
            console.log(e);
        }
    }

    async registration(email: string, password: string) {
        try {
            const response: any = await AuthService.registration(
                email,
                password,
            );

            localStorage.setItem('token', response.data.accessToken);
            this.setAuth(true);
            this.setUser(response.data.user);
        } catch (e) {
            console.log(e);
        }
    }

    async logout() {
        try {
            const response: any = await AuthService.logout();
            localStorage.removeItem('token');
            this.setAuth(false);
            this.setUser({} as IUser);
        } catch (e) {
            console.log(e);
        }
    }

    async checkAuth() {
        this.setLoading(true);
        try {
            const response = await axios.get<AuthResponse>(
                `${BASE_URL}/user/refresh`,
                { withCredentials: true },
            );

            localStorage.setItem('token', response.data.accessToken);
            this.setAuth(true);
            this.setUser(response.data.user);
        } catch (e) {
            console.log(e);
        } finally {
            this.setLoading(false);
        }
    }

    get user() {
        return this._user;
    }

    async getData() {
        return {
            email: this.user.email,
        }
    }

    get isAuth() {
        return this._isAuth;
    }

    get email() {
        return this._user.email;
    }
}
