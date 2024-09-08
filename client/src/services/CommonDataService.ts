import $api from '../http';
import { AxiosResponse } from 'axios';

export default class CommonDataService {
    static async fetchCommonData(): Promise<AxiosResponse<any>> {
        return $api.post('/common-data/data', {filter: [], limit: 30, offset: 0});
    }
}