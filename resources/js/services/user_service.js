import {http, httpFile} from './http_service';

export function userScope() {
    return http().get('/user/user-scope');
}
