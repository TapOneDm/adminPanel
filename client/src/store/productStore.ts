import { makeAutoObservable } from 'mobx';
import axios from 'axios';
import ProductService from '../services/ProductService';

export default class ProductStore {
    public _product = {} as any;
    public _products = [];
    public _isLoading: boolean = false;

    constructor() {
        makeAutoObservable(this);
    }

    async getProducts() {
        try {
            this.setLoading(true);
            let response: any = await ProductService.fetchProducts();
            this.setProducts(response.data.result)
            this.setLoading(false);
        } catch (e) {
            console.log(e);
        }
    }

    async getProduct(id: number) {
        try {
            this.setLoading(true);
            let response: any = await ProductService.fetchProduct(id);
            this.setProduct(response.data.result);
            this.setLoading(false);
            return this.product
        } catch (e) {
            console.log(e);
        }
    }

    async saveProduct(model: any) {
        try {
            this.setLoading(true);
            let response = await ProductService.saveProduct(model);
            this.setProduct(response.data.result);
            this.setLoading(false);
        } catch (e) {
            console.log(e);
        }
    }

    async deleteProduct(id: number) {
        this.setLoading(true);
        await ProductService.deleteProduct(id)
        const products = this.products.filter((product) => product.id !== id);
        this.setProducts(products);
        this.setLoading(false);
    }

    setProducts(products) {
        this._products = products;
    }

    setProduct(product: any) {
        this._product = product;
    }

    setLoading(bool: boolean) {
        this._isLoading = bool;
    }

    get products() {
        return this._products;
    }

    get product() {
        return this._product;
    }

    get loading() {
        return this._isLoading;
    }
}
