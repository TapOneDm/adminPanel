import $api from '../http';
import { AxiosResponse } from 'axios';

export default class ProductService {
    static async fetchProducts(): Promise<AxiosResponse<any>> {
        return $api.post('/product/list', {filter: [], limit: 30, offset: 0});
    }

    static async fetchProduct(id: number): Promise<AxiosResponse<any>> {
        return $api.get('/product/get', { params: { id: id}});
    }

    static async saveProduct(model: any): Promise<AxiosResponse<any>> {
        return $api.post('/product/save', {model: model});
    }

    static async deleteProduct(id: number): Promise<AxiosResponse<any>> {
        return $api.post('/product/delete', {id: id});
    }
}
