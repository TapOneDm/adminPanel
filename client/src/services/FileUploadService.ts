import $api from '../http';
import { AxiosResponse } from 'axios';

export default class FileUploadService {
    static async uploadFile(fileData, config): Promise<AxiosResponse<any>> {
        return $api.post('/file/upload', fileData, config);
    }
}