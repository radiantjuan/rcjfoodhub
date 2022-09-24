import axios from 'axios';

const auth_token = document.getElementById('auth_token');
const instance = axios.create({
    baseURL: '',
    headers: {
        get: {
            Authorization: 'Bearer '+auth_token.value
        },
        post: {
            Authorization: 'Bearer '+auth_token.value
        }
    }
});

export default instance;