import { makeAutoObservable } from 'mobx';
import axios from 'axios';
import CommonDataService from '../services/CommonDataService';
import { log } from 'node:console';

export default class CommonDataStore {
    public _tags = [];

    constructor() {
        makeAutoObservable(this);
    }

    async getData() {
        try {
            let response = await CommonDataService.fetchCommonData();
            this.setTags(response.data.tags);
        } catch (e) {
            console.log(e);
        }
    }

    setTags(tags: Array<any>) {
        this._tags = tags;
    }

    get tags() {
        return this._tags;
    }
}